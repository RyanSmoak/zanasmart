<?php
$response=[
    'message'=>'',
    'data'=>[],
    'error'=>false
];
//if any fields are empty
if(!isset($_POST['exam_number']) || !isset($_POST['student_marks']))
{
    $response['message']="Please fill all fields";
    $response['error']=true;
    echo json_encode($_POST);
    echo json_encode($response);
    http_response_code(400);
    exit();
}

$exam_number=$_POST['exam_number'];
$total_marks=$_POST['student_marks'];

$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "zana";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
@session_start();
$student_id=$_SESSION['student_id'];


//check if the student has completed the exam before
$sql = "SELECT * FROM student_grades WHERE student_id='$student_id' AND exam_number='$exam_number'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $response['message']="You have already completed this exam";
    $response['error']=true;
    echo json_encode($response);
    http_response_code(400);
    exit();
}

$data=new DateTime(); //the datetime
$exam_date=$data->format('y-m-h');
//To enter the marks into the student_grades table
$sql = "INSERT INTO student_grades (exam_number,date_done,student_marks,student_id) VALUES('$exam_number','$exam_date','$total_marks','$student_id')";
if ($conn->query($sql) === TRUE) {
    $response['message']="Marks saved successfully";
    echo json_encode($response);
} else {
    $response['message']="Error: " . $sql . "<br>" . $conn->error;
    $response['error']=true;
    echo json_encode($response);
}