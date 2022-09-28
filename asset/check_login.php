<?php 
session_start();
function checkLogIn(){
    if(!isset($_SESSION['user_name']))
    {
        header('location:/user_auth/login.php');
        exit();
    }
}

function checkSession(){
    if(isset($_SESSION['user_name']))
    {
        header('location:/dashboard/user_dashboard.php');
        exit();
    }
}
?>