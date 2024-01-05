<?php

   include 'core/init.php';
    if($userObj->isLoggedIn()){
        $userObj->redirect('home.php');
    }
    if($_SERVER['REQUEST_METHOD'] === "POST"){
        if(isset($_POST)){
            $email     = trim(stripcslashes(htmlentities($_POST['email'])));
            $password  = $_POST['password'];

            if(!empty($email) && !empty($password)){
                //validate
                if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                    $error = "Invaild Email format!";
                }else{
                    if($user = $userObj->emailExist($email)){
                        if(password_verify($password,$user->password)){
                            // login user in
                            session_regenerate_id();
                            // login the user
                            $_SESSION['userID'] = $user->userID;
                            // redirect the user
                            $userObj->redirect('home.php');
                        }else{
                            $error = "Incorrect email or password";
                        }
                    }
                }
            }else{
                //display error
                $error = "Please enter your email and password to login";
            }
        }
    }
?>


<html>
<head>
    <title>Live Video Chat Using PHP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700&display=swap" rel="stylesheet"> 
</head>
<body>
<!--WRAPPER-->
<div class=" wrapper h-screen items-center justify-center flex">

<div class="inner rounded flex bg-white w-4/5 border" style="height:70%; margin-bottom:10%;">
    <!--LEFT_SIDE-->
    <div class="w-2/5 border-r">
        <div class="select-none flex h-full items-center justify-center">
            <img class="select-none w-4/5" src="assets/images/login-left-bg.png" style="height: 200px; width:200px">
        </div>
    </div><!--LEFT_SIDE_END-->  

    <!--RIGHT_SIDE-->
    <div class="flex-2 flex rounded-xl w-full h-full ">
        <!--PROFILE_SECTION-->
        <div class=" flex flex-1 justify-center items-center">
            <div class="flex flex-col flex-1 h-full overflow-hidden overflow-y-auto items-center justify-start">
            
                <div class="mt-10 w-60 h-60 right-img rounded-full overflow-hidden">
                    <img class="h-auto w-full" src="assets/images/user.png">
                </div>
            
                <div class="right-heading w-full flex flex-col items-center">
                    <div>
                        <h2 class="text-center" style="padding-top:0px;">Wellcome!</h2>
                        <p>Sign-in into your account.</p>
                    </div>
                <form method="post" class="w-full">
                 <div class="w-full flex flex-col items-center">
                    <div class="flex w-2/4 flex-col my-2 items-center">
                        <input class="w-4/5 my-2 border border-gray-200 rounded px-4 py-2" type="email" name="email" placeholder="Email">

                        <input class="w-4/5 my-2 border border-gray-200 rounded px-4 py-2" type="password" name="password" placeholder="Password">
                         <div class="select-none  error text-red-500 text-xs p-2 px-2 w-auto self-start ml-20">
                            <!-- ERROR -->
                            <?php
                            if(isset($error)){
                                echo $error;
                            }
                            ?>
                        </div>
                    </div>
                    <div>
                    <button class="active:-top-2 relative transition border border-gray-400 shadow-md my-4 bg-green-400 hover:bg-green-500 p-2 px-20 rounded-full text-white text-xl">Login</button>
                    </div>
                 </div>
                </form>
                </div>
                
            </div>
        </div>
        <!--PROFILE_SECTION_END-->
    </div>
    <!--RIGHT_SIDE_END-->
</div><!--INNER_ENDS-->
</div><!--WRAPPER ENDS-->
</body>
</html>