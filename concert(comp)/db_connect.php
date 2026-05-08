<?php

$host = "localhost:8889";
$username = "root";
$password = "root";
$db = "concert_db";

$conn = mysqli_connect($host , $username, $password, $db);

if (!$conn) {
    die("Connection failed". mysqli_connect_error());
}

?>