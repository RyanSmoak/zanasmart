<?php
$response = [
    'status' => 0,
    'message' => '',
    'exam' => [],
    'questions' => []
];

$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "zana";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['exam_number'])) {
    $examId = $_GET['exam_number'];
    
    // Fetch the exam details
    $sqlExam = "SELECT * FROM created_exams WHERE exam_number = ?";
    $stmtExam = $conn->prepare($sqlExam);
    $stmtExam->bind_param("i", $examId);
    $stmtExam->execute();
    $resultExam = $stmtExam->get_result();

    if ($resultExam->num_rows > 0) {
        $response['exam'] = $resultExam->fetch_assoc();
        
        // Fetch the questions for the exam
        $sqlQuestions = "SELECT * FROM question_paper WHERE exam_id = ? ORDER BY question_num ASC, question_alp ASC";
        $stmtQuestions = $conn->prepare($sqlQuestions);
        $stmtQuestions->bind_param("i", $examId);
        $stmtQuestions->execute();
        $resultQuestions = $stmtQuestions->get_result();

        while ($row = $resultQuestions->fetch_assoc()) {
            $response['questions'][] = $row;
        }
        
        $response['status'] = 1;
        $response['message'] = 'Exam and questions fetched successfully';
    } else {
        $response['message'] = 'Exam not found';
    }
} else {
    $response['message'] = 'Invalid request';
}

echo json_encode($response);
