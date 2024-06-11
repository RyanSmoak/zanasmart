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
    $sql = "SELECT exam_number,school_name,exam_title,subject, grade_form, total_marks, duration, set_date,due_date,instructions FROM created_exams";
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
@session_start();
$student_id=$_SESSION['student_id'];

$processedExams=[];
//For each exam fetched, check if the student has completed it
foreach($exams as $exam)
{
    $exam_id=$exam['exam_number'];
    $exam['completed']=checkStudentCompletion($conn,$student_id,$exam_id);
    array_push($processedExams,$exam);
}

function checkStudentCompletion($conn,$student_id,$exam_id)
{
    $sql = "SELECT * FROM student_grades WHERE student_id='$student_id' AND exam_number='$exam_id'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        return 1;
    } else {
        return 0;
    }
}
$conn->close();

//set json as content type
header('Content-Type: application/json');
echo json_encode($processedExams);
?>