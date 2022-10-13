<?php
//start session on web page
session_start();
//Include Google Client Library for PHP autoload file
require_once("vendor/autoload.php");

//Make object of Google API Client for call Google API
$google_client = new Google_Client();

//Set the OAuth 2.0 Client ID
$google_client->setClientId('300152603364-u7dm3vaq9ifp4sk4ip94jpafb2ug6kr7.apps.googleusercontent.com');

//Set the OAuth 2.0 Client Secret key
$google_client->setClientSecret('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');

//Set the OAuth 2.0 Redirect URI
$google_client->setRedirectUri('http://subhendu-todo-list.com/user_dashboard.php');

// to get the email and profile 
$google_client->addScope('email');

$google_client->addScope('profile');

?>