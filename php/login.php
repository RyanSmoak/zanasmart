<?php
// Include the LoginModel class
require_once '../php/models/login_model.php';

$response=[
    "status"=>null,
    "message"=>null,
    "email"=>null,
    "password"=>null,
    "category"=>null //1 for teacher, 0 for student 
];

$conn = new mysqli("localhost", "root", "", "zana");

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Start a new session
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect the data from the form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Create a new LoginModel object
    $loginModel = new LoginModel();
    // Check if the email exists
    $emailExists = $loginModel->emailExists($email);
    if (!$emailExists) {
        // Email does not exist, show an error message
        $error_message = "Email does not exist. Please try again.";
        $response["status"]=0;
        $response['email']=0;
        $response["message"]=$error_message;
        header("Content-Type: application/json");
        //set a 404 (not found) response code
        http_response_code(404);
        echo  json_encode($response);
        exit;
    }

    // Verify the user credentials
    $isTeacher = $loginModel->verifyTeacher($email, $password);
    $isStudent = $loginModel->verifyStudent($email, $password);
    if ($isTeacher) {
        // User is verified, store user info in session
        $_SESSION['email'] = $email;
        // Redirect the user to the dashboard or another protected page
        $response["status"]=1;
        $response["message"]="Login successful";
        $response["category"]=1;
        header("Content-Type: application/json");
        http_response_code(200);
        echo  json_encode($response);

        //create a query to fetch user data for a session
        $query = "SELECT * FROM teachers WHERE email = '$email'";
        $result = $conn->query($query);
        $row = $result->fetch_assoc();
        $_SESSION['license'] = $row['license_no'];
        $_SESSION['email'] = $row['email'];
        $_SESSION['title'] = $row['title'];
        $_SESSION['first_name'] = $row['first_name'];
        $_SESSION['last_name'] = $row['last_name'];

    } elseif($isStudent){
        // User is verified, store user info in session
        $_SESSION['email'] = $email;
        // Redirect the user to the dashboard or another protected page
        $response["status"]=1;
        $response["message"]="Login successful";
        $response["category"]=0;
        header("Content-Type: application/json");
        http_response_code(200);
        echo  json_encode($response);

        //create a query to fetch user data for a session
        $query = "SELECT * FROM students WHERE email = '$email'";
        $result = $conn->query($query);
        $row = $result->fetch_assoc();
        $_SESSION['email'] = $row['email'];
        $_SESSION['student_id'] = $row['student_id'];
        $_SESSION['first_name'] = $row['first_name'];
        $_SESSION['last_name'] = $row['last_name'];
    }else{
        // User is not verified, show an error message
        $error_message = "Incorrect password. Please try again.";
        $response["status"]=0;
        $response["message"]=$error_message;
        $response['password']=0;
        header("Content-Type: application/json");
        //set a 401 (unauthorized) response code
        http_response_code(401);
        echo  json_encode($response);
        }
    }