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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $school_name = $_POST["school_name"];
    $exam_title = $_POST["exam_title"];
    $subject = $_POST["subject"];
    $grade_form = $_POST["grade_form"];
    $total_marks = $_POST["total_marks"];
    $duration = $_POST["duration"];
    $set_date = $_POST["set_date"];
    $due_date = $_POST["due_date"];
    $instructions = $_POST["instructions"];
    $completed = $_POST["completed"];

    $sql = "INSERT INTO exams (school_name, exam_title, subject, grade_form, total_marks, duration, set_date, due_date, instructions, completed)
    VALUES ('$school_name', '$exam_title', '$subject', '$grade_form', '$total_marks', '$duration', '$set_date', '$due_date', '$instructions', '$completed')";

    if ($conn->query($sql) === TRUE) {
        $response['status']=1;
        $response['message']='New Exam Created. Click Start Setting to go to setting page';
        echo json_encode($response);
    } else {
        //echo "Error: " . $sql . "<br>" . $conn->error;
        $response['status']=0;
        $response['message']='Error: ' . $sql . "<br>" . $conn->error;
        echo json_encode($response);
    }
}

$conn->close();
?>
