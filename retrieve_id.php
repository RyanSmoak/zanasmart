<?php
$response=[
    'status'=>0,
    'message'=>''
];
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "zana";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the most recent number from the database
$sql = "SELECT exam_number FROM exams ORDER BY exam_number DESC LIMIT 1";
$result = $conn->query($sql);
$exam_number = '';

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $exam_number = $row['exam_number'];
}

$conn->close();
?>
