<?php
         include 'core/init.php';

         if(!$userObj->isLoggedIn()){
            $userObj->redirect('index.php');
         }
         $userObj->logout();

?>