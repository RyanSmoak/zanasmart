<?php
session_start();

// Initialize the exam questions array if not already set
if (!isset($_SESSION['exam'])) {
    $_SESSION['exam'] = [];
}

// Add a new question
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['question'])) {
    $_SESSION['exam'][] = [
        'question' => $_POST['question'],
        'answer' => $_POST['answer']
    ];
}

// Update an existing question
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $index = $_POST['index'];
    $_SESSION['exam'][$index] = [
        'question' => $_POST['question'],
        'answer' => $_POST['answer']
    ];
}

// Fetch all questions
$questions = $_SESSION['exam'];
?>
