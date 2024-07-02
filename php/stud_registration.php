<?php
$response=[
    'status'=>0,
    'message'=>''
];
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "zana";

session_start();

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//send data to database
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if($_POST["password"] === $_POST["confirm_password"]){
        $student_id = $_POST["student_id"];
        $first_name = $_POST["first_name"];
        $last_name = $_POST["last_name"];
        $level_edu = $_POST["level_edu"];
        $month_finish = $_POST["month_finish"];
        $year_finish = $_POST["year_finish"];
        $email = $_POST["email"];
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    }elseif(isValidPassword($password)){
        $response['status']=0;
        $response['message']='Password must be at least 8 characters long and contain at least one special character';
        echo json_encode($response);
        exit();
    }else{
        $response['status']=0;
        $response['message']='Passwords do not match';
        echo json_encode($response);
        exit();
    }

    $sql = "INSERT INTO students(student_id, first_name, last_name, level_edu, month_finish, year_finish, email, password)
    VALUES ('$student_id','$first_name', '$last_name', '$level_edu', '$month_finish', '$year_finish', '$email', '$password')";
    if ($conn->query($sql) === TRUE) {
        $response['status']=1;
        $response['message']='New Account Created. Click Login to go to login page';
        echo json_encode($response);
    } else {
        //echo "Error: " . $sql . "<br>" . $conn->error;
        $response['status']=0;
        $response['message']='Error: ' .$conn->error;
        echo json_encode($response);
    }
}

