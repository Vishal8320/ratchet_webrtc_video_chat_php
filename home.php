<?php


   include 'core/init.php';
   if(!$userObj->isLoggedIn()){
    $userObj->redirect('index.php');
    }

    $user = $userObj->userData();
    $userObj->updateSession();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Live Video Chat PHP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Tailwind -->
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <!-- Font-awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700&display=swap" rel="stylesheet"> <!-- Jquery -->
    <!-- Jquery -->
    <script
          src="https://code.jquery.com/jquery-3.6.0.min.js"
          integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
          crossorigin="anonymous"></script>

          <script type="text/javascript">
        const conn = new WebSocket('ws://localhost:8080/?token=<?php echo $userObj->sessionID;?>');
    </script>  
</head>
<body>
<!-- AlertPopup -->

<div id="alertBox" class="hidden z-10 transition absolute w-full h-full flex items-center justify-center">
    <div class="pop-up flex justify-between w-96 bg-white rounded overflow-hidden">
          <div class="pl-6 border-green-600 px-2 py-2 flex items-center">
            <div class="w-16 h-16 mx-1 rounded-full border overflow-hidden">
                <img id="alertImage" class="w-full h-auto" src="">
            </div>
            <div class="flex flex-col">
                <div id="alertName" class="mx-2 font-500">
                </div>
                <div class="animate-pulse mx-2 text-xs font-200 relative flex">
                    <span id="alertMessage" class="flex"></span> 
                </div>
            </div>
        </div>
    </div>
</div>


<!-- CallPopup -->

<div id="callBox" class="hidden z-10 transition absolute w-full h-full flex items-center justify-center">
    <div class="pop-up flex justify-between w-96 bg-white rounded overflow-hidden">
          <div class="pl-6 border-green-600 px-2 py-2 flex items-center">
            <div class="w-16 h-16 mx-1 rounded-full border overflow-hidden">
                <img id="profileImage" class="w-full h-auto" src="">
            </div>
            <div class="flex flex-col">

                <div id="username" class="mx-2 font-500">
                   username
                </div>
                <div class="animate-pulse mx-2 text-xs font-200 relative flex">
                    <span class="flex">Video calling</span> 
                    <span class="ml-2 text-lg text-green-600 top-0 flex">
                        <i class=" fas fa-video"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="flex items-center justify-center mx-4">
            <ul class="flex pr-2">
                <li>
                    <button id="declineBtn" class="hover:text-red-700 transition text-red-600 px-4 py-1 text-xl "><i class="fas fa-times"></i></button>
                </li>
                <li>
                    <button id="answerBtn" class="hover:text-green-700 transition text-green-600 px-4 py-1 text-xl "><i class="fas fa-phone"></i></button>
                </li>
            </ul>
        </div>
    </div>
</div>


<!--WRAPPER-->
<div class="wrapper h-screen items-center justify-center flex">

<div class="inner flex bg-white w-4/5 border" style="height:70%; margin-bottom:10%;">
    <!--LEFT_SIDE-->
    <div class="w-2/5 border-r">
        <div class="h-full overflow-hidden">
            <div class="flex justify-between items-center border-b">
                <div class="mx-2 flex items-center justify-center">
                    <div class="flex-shrink-0 mx-2 my-4 rounded-full overflow-hidden h-12 w-12  cursor-pointer">
                        <img  class="w-full h-auto rounded-full select-none" src="<?php echo BASE_URL.'/'.$user->profileImage; ?>">
                    </div>
                    <div>
                        <span class="font-medium select-none"><?php echo $user->name; ?></span>
                    </div>
                </div>
                <div class="flex items-center mx-4">
                    <span class="select-none transition mx-3 text-green-400 cursor-pointer"><i class="fas fa-circle"></i></span>
                    <span class="select-none transition hover:text-gray-500 mx-3 text-gray-600 cursor-pointer"><i class="fas fa-comment-alt"></i></span>
                    <span class="menu-logout relative select-none transition hover:text-gray-500 mx-3 text-gray-600 cursor-pointer"><i class="fas fa-ellipsis-v"></i>
                        <div class="logout flex items-center justify-center rounded absolute right-0 top-2 bg-white border hover:bg-gray-200" style="width: 100px;height: 60px;">
                            <a href="logout.php" class="p-2 px-2 text-lg text-gray-600">
                                Logout
                            </a>
                        </div>
                    </span>
                </div>
            </div>
            <div class="px-6 py-5 select-none">
                <input class="p-2 w-full rounded border" type="text" name="usersearch" placeholder="Search users">
            </div>
            <div class="select-none overflow-hidden overflow-y-auto h-2/4">
                <h2 class="font-bold text-lg my-4  px-6 select-none">Users</h2>
                <ul class="select-none">
                    <!-- USER-LIST -->
                    <?php
                    $userObj->getUsers();
                    ?>
                </ul>
            </div>
        </div>
    </div>
    <!--LEFT_SIDE_END-->  
    <!--RIGHT_SIDE-->
    <div class="flex-2 flex rounded-xl w-full h-full ">
        <!--HOME_PAGE_DIV-->
        <div class=" flex flex-1 justify-center items-center">
            <div class="select-none flex flex-col flex-1 h-full overflow-hidden overflow-y-auto items-center justify-center">
                <div class="w-60 h-60 right-img select-none">
                    <img class="h-auto w-full select-none" src="assets/images/cam.png">
                </div>
                <div class="right-heading">
                    <h2 class="text-center select-none">Keep your webcam connected</h2>
                    <p class="select-none">This is app allow users to video chat with other users.</p>
                </div>
                
            </div>
        </div><!--HOME_PAGE_DIV_ENDS-->
    </div>
    <!--RIGHT_SIDE_END-->
</div><!--INNER_ENDS-->
</div><!--WRAPPER ENDS-->
<!-- JavaScript Includes -->

<script type="text/javascript" src="<?php echo BASE_URL;?>/assets/js/main.js"></script>
<script src="https://webrtc.github.io/adapter/adapter-latest.js"></script>
</body>
</html>
 

