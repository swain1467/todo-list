<?php 
require_once("GmailOAuth/gmail_config.php");
session_start();
$google_client->revokeToken();
session_destroy();
header("location:/login.php");
?>