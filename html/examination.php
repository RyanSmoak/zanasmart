<?php

session_start();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Boxicons CSS -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <title>Teacher's side</title>
    <link rel="stylesheet" href="../css/exam_style.css" />
  </head>
  <body>
    <!-- navbar -->
    <nav class="navbar">
      <div class="logo_item">
        <i class="bx bx-menu" id="sidebarOpen"></i>
        <img src="../images/logo.jpeg" alt=""></i>ZanaSmart
      </div>

      <div class="navbar_content">
        <i class="bi bi-grid"></i>
        <i class='bx bx-sun' id="darkLight"></i>
        <i class='bx bx-bell' ></i>
        <img src="../images/bm_profile.gif" alt="" class="profile" />
      </div>
    </nav>

    <!-- sidebar -->
    <nav class="sidebar">
      <div class="menu_content">
        <ul class="menu_items">
          <div class="menu_title menu_dahsboard"></div>
          <li class="item">
            <div href="teacher_side.php" class="nav_link submenu_item">
              <span class="navlink_icon">
                <i class="bx bx-home-alt"></i>
              </span>
              <span class="navlink">Home</span>
              <i class="bx bx-chevron-right arrow-left"></i>
            </div>

            <ul class="menu_items submenu">
              <a href="teacher_side.php" class="nav_link sublink">About Me</a>
            </ul>
          </li>
        </ul>
        <ul class="menu_items">
          <div class="menu_title menu_editor"></div>
          <li class="item">
            <a href="my_exams.html" class="nav_link">
              <span class="navlink_icon">
                <img width="20" height="20" src="https://img.icons8.com/external-tulpahn-outline-color-tulpahn/20/external-paper-stack-printing-tulpahn-outline-color-tulpahn.png" alt="external-paper-stack-printing-tulpahn-outline-color-tulpahn"/>
              </span>
              <span class="navlink">My Exams</span>
            </a>
          </li>
          <li class="item">
            <a href="examination.html" class="nav_link">
              <span class="navlink_icon">
                <i class="bx bx-cloud-upload"></i>
              </span>
              <span class="navlink">Create examination</span>
            </a>
          </li>
        </ul>

        <!-- Sidebar Open / Close -->
        <div class="bottom_content">
        <div class="logout">
            <span>Logout</span>
            <i class='bx bx-log-out' ></i>
          </div>
          <div class="bottom expand_sidebar">
            <span> Expand</span>
            <i class='bx bx-log-in' ></i>
          </div>
          <div class="bottom collapse_sidebar">
            <span> Collapse</span>
            <img width="20" height="20" src="https://img.icons8.com/color/48/collapse.png" alt="collapse"/>
          </div>
        </div>
      </div>
    </nav>

    <section class="content" id="content">
        <div class="container">
            <div class="examination-creation-content">
            <p>Create Examination</p>
            <form action="../php/connection.php" method="POST" enctype="multipart/form-data" id="examForm">
              <div class="form-block" id="exam-form">
                <div class="form-group">
                  <div class="flexbox_label">
                    <label for="exam_number" id="exam_label">Exam Number:</label>
                    <label for="teacher_id" id="license_label">License No:</label>
                  </div>
                  <div class="flexbox">
                    <input type="number" id="exam_no" name="exam_no" placeholder="Exam number" disabled> 
                    <input type="text" id="teacher_id" name="teacher_id" placeholder=<?php  echo $_SESSION['license'];?> required disabled>
                  </div>  
                  <label for="school_name">School:</label>
                  <input type="text" id="school_name" name="school_name" placeholder="School Name">
                  <label for="exam_title">Examination Title:</label>
                  <input type="text" id="exam_title" name="exam_title" placeholder="Examination Title">
                  <label for="subject">Subject:</label>
                  <input type="text" id="subject" name="subject" placeholder="Subject">
                  <label for="form">Grade/Form:</label>
                  <select id="grade_form" name="grade_form" required>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                  </select> 
                  <label for="total_marks">Total Marks:</label>
                  <input type="text" id="total_marks" name="total_marks" placeholder="Total Marks">
                  <label for="duration">Duration:</label>
                  <input type="number" id="duration" name="duration" placeholder="Exam Duration(hours)">
                  
                  <div class="flexbox_label" id="dates">
                    <label for="set_date">Set Date:</label>
                    <label for="due_date">Due Date:</label>
                  </div>
                  <div class="flexbox">
                    <input type="date" id="set_date" name="set_date">
                    <input type="date" id="due_date" name="due_date">
                  </div>
                  <label for="instructions">Instructions:</label>
                  <textarea name="instructions" id="instructions" cols="100" rows="5" placeholder="Enter instructions"></textarea>
                  <div class="sub_res">
                    <input type="submit" value="Submit">
                    <input type="reset" value="Clear">
                  </div>
                </div>
              </div>
            </form>            
                </div>
              </div>
            </form>
        </div>

        <div id="popup">
          <img width="100" height="100" src="https://img.icons8.com/pulsar-color/100/new-by-copy.png" alt="new-by-copy"/>
          <p>New Exam Created. Click 'Start Setting' to go to setting page</p>
          <button onclick="closePopup()">Start Setting</button>
        </div>
    </section>
    <!-- JavaScript -->
    <script src="../js/exam_script.js"></script>
    <script>
      window.onload=()=>{
        document.getElementById('examForm').addEventListener('submit', function(e) {
        e.preventDefault();  // Prevent the form from submitting normally
      
        var formData = new FormData(this);
      
        fetch('../php/connection.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          popup(data);
        })
        .catch(error => {
          console.error('Error:', error);
        });
      });
      }
      function popup(data)
      {
        let popup=document.getElementById('popup');
        popup.style.display='block';
        data=JSON.parse(data)
        popup.children[1].innerText=data['message'];
      }
      function closePopup()
      {
        let popup=document.getElementById('popup');
        popup.style.display='none';
        window.location.href="./setting.php";
      }
  
      </script>      
      <script>
      //function to end session after logout div in pressed
      document.querySelector('.logout').addEventListener('click', function(){
        window.location.href = 'login.html';
        fetch('../php/logout.php');
      });
    </script>
  </body>
</html>