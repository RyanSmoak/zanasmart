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

$questions_json = $_POST['questions'];
$questions = json_decode($questions_json, true);

// Iterate over the array of questions
foreach ($questions as $question_each) {
    // Extract question details
    $exam_id = $question_each['exam_id'];
    $question_num = $question_each['question_num'];
    $question_alp = $question_each['question_alp'];
    $question_type = $question_each['question_type'];
    $question = $question_each['question'];
    $answer_scheme = $question_each['answer_scheme'];
    $marks = $question_each['marks'];

    if($question !== NULL){
        $sql = "INSERT INTO question_paper (exam_id,question_num, question_alp, question, answer_scheme, marks)
        VALUES ('$exam_id','$question_num', '$question_alp', '$question', '$answer_scheme', '$marks')";

        if ($conn->query($sql) === TRUE) {
            $response['status']=1;
            $response['message']='New Exam Created.';
            echo json_encode($response);
        } else {
            //echo "Error: " . $sql . "<br>" . $conn->error;
            $response['status']=0;
            $response['message']='Error: ' . $sql . "<br>" . $conn->error;
            echo json_encode($response);
        }
    }
}

$conn->close();
?>
