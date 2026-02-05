<?php

$servername = $env->servername;
$username = $env->username;
$password = $env->password;
$dbname = $env->dbname;

$db = mysqli_connect($servername, $username, $password, $dbname);

if (!$db) {
    exit("Connection with database failed.");

}