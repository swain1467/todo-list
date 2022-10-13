<?php 
function checkLogIn(){
    if(!isset($_SESSION['user_name']))
    {
        header('location:/login.php');
        exit();
    }
}

function checkSession(){
    if(isset($_SESSION['user_name']))
    {
        header('location:/user_dashboard.php');
        exit();
    }
}
?>