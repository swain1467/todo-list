<?php
require_once("config.php");
require_once(UTILITY_DIR."check_login.php");
session_start();
checkLogIn();
checkSession();
?>