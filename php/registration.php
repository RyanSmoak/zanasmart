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
        $license_no = $_POST["license_no"];
        $title = $_POST["title"];
        $first_name = $_POST["first_name"];
        $last_name = $_POST["last_name"];
        $teaching_level = $_POST["teaching_level"];
        $email = $_POST["email"];
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    }else{
        $response['status']=0;
        $response['message']='Passwords do not match';
        echo json_encode($response);
        exit();
    }

    $sql = "INSERT INTO teachers(license_no, title, first_name, last_name, teaching_level, email, password)
    VALUES ('$license_no', '$title', '$first_name', '$last_name', '$teaching_level', '$email', '$password')";
    if ($conn->query($sql) === TRUE) {
        $response['status']=1;
        $response['message']='New Account Created. Click Login to go to login page';
        echo json_encode($response);
    } else {
        //echo "Error: " . $sql . "<br>" . $conn->error;
        $response['status']=0;
        $response['message']='Error: ' . $sql . "<br>" . $conn->error;
        echo json_encode($response);
    }
}

