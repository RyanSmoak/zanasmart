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
    $sql = "SELECT * FROM exams";
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
//sample record
/*
[{"exam_number":"1","school_name":"Sunshine Secondary","exam_title":"CRE Form 1 exam","subject":"CRE","grade_form":"1","total_marks":"100","duration":"00:00:02","set_date":"2024-05-21","due_date":"2024-05-22","instructions":"Some words","completed":"0"},
*/
echo json_encode($exams);
?>