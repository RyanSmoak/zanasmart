<?php
$response=[
    'status'=>0,
    'message'=>''
];
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "zana";

session_start();
$exam_id=$_SESSION['exam_id'];

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//To fetch all questions in the question_paper table
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $sql = "SELECT * FROM question_paper WHERE exam_id='$exam_id' ";
    $result = $conn->query($sql);
    $questions = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $questions[] = $row;
        }
        echo json_encode($questions);
    } else {
        echo "0 results";
    }
}
?>