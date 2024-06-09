<?php

$ini = @parse_ini_file(".env");

$dbhost;
$dbuser;
$dbpass;
$dbdatabase;

if ($ini) {
    //load local .env file
    $dbhost = $ini["host"];
    $dbuser = $ini["user"];
    $dbpass = $ini["pass"];
    $dbdatabase = $ini["db"];
}
else {
    //load from heroku env variables
    $dbhost = getenv("host");
    $dbuser = getenv("user");
    $dbpass = getenv("pass");
    $dbdatabase = getenv("db");
}

?>
