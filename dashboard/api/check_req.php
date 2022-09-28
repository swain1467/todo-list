<?php
session_start();
if(!isset($_SESSION['user_name']))
{
    header('location:/asset/access_denied.php');
    exit();
}
?>