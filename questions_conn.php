<?php
$response=[
    'status'=>0,
    'message'=>''
];
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "zana";

// print_r($_POST);

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $exam_id=$_POST['exam_id'];
    $question_num = $_POST["question_num"];
    $question_alp = $_POST["question_alp"];
    $question = $_POST["question"];
    $answer_scheme = $_POST["answer_scheme"];

    $sql = "INSERT INTO question_paper (exam_id,question_num, question_alp, question, answer_scheme)
    VALUES ('$exam_id','$question_num', '$question_alp', '$question', '$answer_scheme')";

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
