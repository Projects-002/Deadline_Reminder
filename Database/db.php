<?php

$db_server = "localhost";
$db_user = "root";
$db_pass = "22092209";
$db_name = "deadline_db";

$conn = new mysqli($db_server, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
