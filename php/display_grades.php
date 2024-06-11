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

@session_start();
$student_id=$_SESSION['student_id'];

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$exam_result = [];

//to fetch all the done exams
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $sql = "SELECT u.student_id, s.exam_number, s.exam_title, s.subject, s.grade_form, u.date_done, s.total_marks, u.student_marks
    FROM created_exams s
    INNER JOIN student_grades u ON s.exam_number = u.exam_number 
    WHERE u.student_id = '$student_id'";

    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $exam_result[] = $row;
        }
        // echo json_encode($exams);
    } else {
        echo "0 results";
    }
}

// var_dump($exam_result);

//set json as content type
header('Content-Type: application/json');
echo json_encode($exam_result);
?>