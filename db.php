<?php
$servername = "www.christianmeza.com";
$database = "christi5_otrabd";
$username = "christi5_prueba";
$password = "@L&-vbWzg]MW";

// Create connection

$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection

if (!$conn) {

    die("Connection failed: " . mysqli_connect_error());

}
echo "Connected successfully";
mysqli_close($conn);
?>

