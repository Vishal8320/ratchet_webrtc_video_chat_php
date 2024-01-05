'use strict';


//buttons
let callBtn     = $('#callBtn');
let callBox     = $('#callBox');
let answerBtn   = $('#answerBtn');
let declineBtn  = $('#declineBtn');

let alertBox    = $('#alertBox');

let pc;
let sendTo  = callBtn.data('user');
let localStream;
let mediaStream;

//video elements 
const localVideo   = document.querySelector("#localVideo");
const remoteVideo  = document.querySelector("#remoteVideo");

//mediaInfo
const mediaConst = {
	video:true,
	audio:true
};

//info about stun servers
const config = {
	iceServers:[
		{urls:'stun:stun1.l.google.com:19302'},
	]
}


//what to receive from other client
const options = {
	offerToReceiveVideo: 1,
	offerToReceiveAudio: 1
};

async function createOffer(sendTo){
	await sendIceCandidate(sendTo);
	await pc.createOffer(options);
	await pc.setLocalDescription(pc.localDescription);
	send('client-offer', pc.localDescription, sendTo);
}

async function createAnswer(sendTo, data){
	if(!pc){
		await getConn();
	}

	if(!localStream){
		await getCam();
	}

	await sendIceCandidate(sendTo);
	await pc.setRemoteDescription(data);
	await pc.createAnswer();
	await pc.setLocalDescription(pc.localDescription);
	send('client-answer', pc.localDescription, sendTo);
}

function sendIceCandidate(sendTo){
	pc.onicecandidate = e =>{
		if(e.candidate !== null){
			//send ice candidate to other client
			send('client-candidate', e.candidate, sendTo);
		}
	}

    pc.ontrack = e =>{
        $('#video').removeClass('hidden');
        $('#profile').addClass('hidden');
        remoteVideo.srcObject = e.streams[0];
    }
}

function hangup(){
	send('client-hangup', null, sendTo);
	pc.close();
	pc = null
}

$('#hangupBtn').on('click', () =>{
	hangup();
	location.reload(true);
});


function getConn(){
	if(!pc){
		pc = new RTCPeerConnection(config);
	}
}

// ask for media input

 async function getCam(){
    try{
         if(!pc){
            await getConn();
         }
         mediaStream = await navigator.mediaDevices.getUserMedia(mediaConst);
         localVideo.srcObject = mediaStream;
         localStream = mediaStream;
         localStream.getTracks().forEach( track => pc.addTrack(track,localStream));

    }catch(error){
        console.log(error);
    }
 }



 $('#callBtn').on('click', () =>{
    getCam();
    send('is-client-ready',null,sendTo);
 });

 conn.onopen = e =>{
	console.log('connected to websocket');
}


conn.onmessage = async e =>{
	let message      = JSON.parse(e.data);
	let by           = message.by;
	let data         = message.data;
	let type         = message.type;
	let profileImage = message.profileImage;
	let username     = message.username;

    $('#username').text(username);
    console.log(username);
    $('#profileImage').attr('src',profileImage);

	switch(type){
        case 'client-candidate':
            if(pc.localDescription){
                await pc.addIceCandidate(new RTCIceCandidate(data));
            }
            break;
		case 'is-client-ready':
			if(!pc){
				await getConn();
			}

			if(pc.iceConnectionState === "connected"){
				send('client-already-oncall');
			}else{
				//display call popup

				displayCall();
                if(window.location.href.indexOf(username) > -1){
                    answerBtn.on('click', () =>{
                        callBox.addClass('hidden');
                        $('.wrapper').removeClass('glass');
                        send('client-is-ready', null, sendTo);
                    });
                }else{
                    answerBtn.on('click', () =>{
                        callBox.addClass('hidden');
                        redirectToCall(username, by);
                    });
                }


                answerBtn.on('click', () =>{
					callBox.addClass('hidden');
					$('.wrapper').removeClass('glass');
					send('client-is-ready', null, sendTo);
				});

                declineBtn.on('click',() =>{
                    send('client-rejected',null,sendTo);
                    location.reload(true);
                })
			}
		break;

        case 'client-answer':
			if(pc.localDescription){
				await pc.setRemoteDescription(data);
			}
		break;

        case 'client-offer':
			createAnswer(sendTo, data);
		break;

		case 'client-is-ready':
			createOffer(sendTo);
		break;

		case 'client-already-oncall':
			//display popup right here
            displayAlert(username,profileImage,'is on Another Call');
			setTimeout('window.location.reload(true)', 2000);
		break;

        case 'client-rejected':
            displayAlert(username,profileImage,'is Busy');
            setTimeout('window.location.reload(true)',2000);
		break;

        case 'client-answer':
        if(pc.localDescription){
            await pc.setRemoteDescription(data);
            $('#callTimer').timer({ format: '%m:%s' });
        }
        break;

        case 'client-offer':
            createAnswer(sendTo, data);
            $('#callTimer').timer({format: '%m:%s'});

        break;
        case 'client-hangup':
            // call hangup
            displayAlert(username,profileImage,'Disconnected the Call');
            setTimeout('window.location.reload(true)',2000);
	}
}


// function send(type, data, sendTo ){
// 	conn.send(JSON.stringify({
// 		sendTo:sendTo,
// 		type:type,
// 		data:data
// 	}));
// }

function send(type, data, sendTo){
    // Check if localStream is defined and has audio tracks
    if (localStream && localStream.getAudioTracks().length > 0) {
        // Mute local audio track before sending
        localStream.getAudioTracks()[0].enabled = type !== 'client-is-ready';
    }

    conn.send(JSON.stringify({
        sendTo: sendTo,
        type: type,
        data: data
    }));

    // Unmute local audio track after sending
    if (localStream && localStream.getAudioTracks().length > 0) {
        localStream.getAudioTracks()[0].enabled = true;
    }
}



function displayCall(){
    callBox.removeClass('hidden');
    $('.wrapper').addClass('glass');
}

function displayAlert(username, profileImage, message){
	alertBox.find('#alertName').text(username);
	alertBox.find('#alertImage').attr('src',profileImage);
	alertBox.find('#alertMessage').text(message);

	alertBox.removeClass('hidden');
	$('.wrapper').addClass('glass');
	$('#video').addClass('hidden');
	$('#profile').removeClass('hidden');
}

function redirectToCall(username, sendTo){
	if(window.location.href.indexOf(username) == -1){
		sessionStorage.setItem('redirect', true);
		sessionStorage.setItem('sendTo',sendTo);
		window.location.href = '/webtest/'+username;
	}
}

if(sessionStorage.getItem('redirect')){
	sendTo = sessionStorage.getItem('sendTo');
	let waitForWs = setInterval(() => {
		if(conn.readyState === 1){
			send('client-is-ready', null, sendTo);
			clearInterval(waitForWs);
		}
	}, 500);
	sessionStorage.removeItem('redirect');
	sessionStorage.removeItem('sendTo');
}

///make sure to include this file in connect.php file                  