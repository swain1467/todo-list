<?php
function pdo_connect ()
{
    $pdo = new PDO("mysql:host=localhost;dbname=".DB_NAME, DB_USER_NAME, DB_PASSWORD); 
    return $pdo;
}
?>