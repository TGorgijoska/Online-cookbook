<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "usbw";
$database = "onlinerecipes";

$conn = mysqli_connect($servername, $username, $password);
if ($conn == FALSE) die(mysqli_connect_error());
if (mysqli_select_db($conn, $database) === FALSE) die(mysqli_connect_error());
$conn->set_charset("utf8");

?>