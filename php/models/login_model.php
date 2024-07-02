<?php
//Include the database class
$directory = dirname(__FILE__);
$database = "$directory/database.php";
require_once($database);


class LoginModel extends Database {
    // Constructor
    public function __construct() {
        parent::__construct();
    }

    // Method to verify user credentials
    public function emailExists($email)
    {
        $email=$this->escape($email);
        // Prepare a SQL statement to check both the student and teacher table
        $stmt_teacher = $this->query("SELECT * FROM teachers WHERE email = '$email'");
        $stmt_student = $this->query("SELECT * FROM students WHERE email = '$email'");
       // Get the result
        $result_teacher = $stmt_teacher;
        $result_student = $stmt_student;
        // Check if a user was found
        if ($result_student->num_rows > 0) {
            return true;
        }elseif ($result_teacher->num_rows > 0) {
                return true;
        } else {
            // No user was found with this email address
            return false;
        }
    }

    public function verifyTeacher($email, $password) {
        $email=$this->escape($email);
        $password=$this->escape($password);
        $wrong_email = 0;
        $wrong_password = 0;
        // Prepare a SQL statement
        $stmt_teacher = $this->query("SELECT * FROM teachers WHERE email = '$email'");
       // Get the result
        $result_teacher = $stmt_teacher;

        // Check if a user was found
        if ($result_teacher->num_rows > 0) {
            // Get the user data
            $user = $result_teacher->fetch_assoc();
            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Password is correct
                return true;
            } else {
                // Password is incorrect
                $wrong_password=1;
                return false;
            }
        }else {
            // No user was found with this email address
            $wrong_email=1;
            return false;
        }
    }

    public function verifyStudent($email, $password) {
        $email=$this->escape($email);
        $password=$this->escape($password);
        $wrong_email = 0;
        $wrong_password = 0;
        // Prepare a SQL statement
        $stmt_student = $this->query("SELECT * FROM students WHERE email = '$email'");
       // Get the result
        $result_student = $stmt_student;

        // Check if a user was found
        if ($result_student->num_rows > 0) {
            // Get the user data
            $user = $result_student->fetch_assoc();
            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Password is correct
                return true;
            } else {
                // Password is incorrect
                $wrong_password=1;
                return false;
            }
        } else {
            // No user was found with this email address
            $wrong_email=1;
            return false;
        }
    }
}
?>