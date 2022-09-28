<?php
function pdo_connect ()
{
    $pdo = new PDO("mysql:host=localhost;dbname=assignment12092022", "mindfire", "mindfire"); 
    return $pdo;
}
?>