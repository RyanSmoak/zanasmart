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
    <title>Student's side</title>
    <link rel="stylesheet" href="../css/student.css" />
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
            <div href="#" class="nav_link submenu_item">
              <span class="navlink_icon">
                <i class="bx bx-home-alt"></i>
              </span>
              <span href="student_side.html" class="navlink">Home</span>
              <i class="bx bx-chevron-right arrow-left"></i>
            </div>
          </li>
        </ul>

        <ul class="menu_items">
          <div class="menu_title menu_editor"></div>
          <li class="item">
            <a href="student_my_exams.html" class="nav_link">
              <span class="navlink_icon">
                <img width="20" height="20" src="https://img.icons8.com/external-tulpahn-outline-color-tulpahn/20/external-paper-stack-printing-tulpahn-outline-color-tulpahn.png" alt="external-paper-stack-printing-tulpahn-outline-color-tulpahn"/>
              </span>
              <span class="navlink">My Exams</span>
            </a>
          </li>
          <li class="item">
            <a href="examination.html" class="nav_link">
              <span class="navlink_icon">
                <img width="20" height="20" src="https://img.icons8.com/pulsar-line/48/exam.png" alt="exam"/>
              </span>
              <span class="navlink">My Grades</span>
            </a>
          </li>
          <li class="item">
            <a href="#" class="nav_link">
              <span class="navlink_icon">
                <img width="20" height="20" src="https://img.icons8.com/ios/50/combo-chart--v1.png" alt="combo-chart--v1"/>
              </span>
              <span class="navlink">My Analysis</span>
            </a>
          </li>
        </ul>

        <!-- Sidebar Open / Close -->
        <div class="bottom_content">
          <div class="bottom expand_sidebar">
            <span> Expand</span>
            <i class='bx bx-log-in' ></i>
          </div>
          <div class="bottom collapse_sidebar">
            <span> Collapse</span>
            <i class='bx bx-log-out'></i>
          </div>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <!--Home page-->
    <div class="home-page">
        <div class="home-content">
            <div class="hello">
                <h1>Hello, <?php echo  $_SESSION['first_name']?></h1>
                <p>Lovely to see you again!</p>
            </div>
            
            <div class="overview-boxes">
            <div class="exams_box">
                <h1>Done Exams</h1><br>
                <p>3</p>
            </div>
            <div class="to_come">
                <h1>Upcoming Exams</h1><br>
            </div>
            <div class="not_done">
                <h1>Due Exams</h1><br>
            </div>
            <div class="grade_avg">
                <h1>Grade Average</h1><br>
                <p>80%</p>
            </div>
        </div>
    </div>
                        
    <!-- JavaScript -->
    <script src="js/exam_script.js"></script>
  </body>
</html>