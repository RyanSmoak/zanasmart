<?php
$response=[
    'message'=>'',
    'data'=>[],
    'error'=>false
];
if(!isset($_POST['studAnswers']))
{
    http_response_code(400);
}
if(!isset($_POST['examID']))
{
    echo json_encode("No exam ID found");
    http_response_code(400);
    exit();
}
$studAnswers=$_POST['studAnswers'];
$studAnswers=json_decode($studAnswers);
$decodedAnswers=[];
foreach($studAnswers as $answer)
{
    $answerObj=[
        'questionNum'=>$answer->questionNum,
        'questionAlp'=>$answer->questionAlp,
        'answer'=>$answer->answer
    ];
    array_push($decodedAnswers,$answerObj);
}
// var_dump($decodedAnswers);
//Fetch the exam answers from the database
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "zana";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$answers=[];
//To fetch all questions in the question_paper table
function fetchAnswer($answereObj,$conn,$exam_id){
$num=$answereObj['questionNum'];   
$alp=$answereObj['questionAlp'];
$sql = "SELECT answer_scheme FROM question_paper WHERE exam_id='$exam_id' AND question_num='$num' AND question_alp='$alp'";
$result = $conn->query($sql);
$answer='';
//fetch one row
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $answer=$row['answer_scheme'];
    }
} else {
    echo "0 results";
}
return $answer;
}
foreach($decodedAnswers as $answer)
{
    $answer_scheme=fetchAnswer($answer,$conn,$_POST['examID']);
    $answerObj=[
        'questionNum'=>$answer['questionNum'],
        'questionAlp'=>$answer['questionAlp'],
        'answer'=>$answer['answer'],
        'answer_scheme'=>$answer_scheme
    ];
    array_push($answers,$answerObj);
}

$response['data']=$answers;
echo json_encode($response);