<?php
$servername = "localhost";
$database = "u742109558_LearnEasy0";
$username = "u742109558_LearnEasy0";
$password = "L34rnE5yPr0";
// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
echo "Connected successfully";
mysqli_close($conn);
?>