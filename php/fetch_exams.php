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
$exams = [];
//To fetch all questions in the question_paper table
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $sql = "SELECT * FROM created_exams";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $exams[] = $row;
        }
        // echo json_encode($exams);
    } else {
        echo "0 results";
    }
}
echo json_encode($exams);
?>