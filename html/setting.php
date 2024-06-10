<?php

session_start()
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
            <img src="../images/logo.jpeg" alt="">ZanaSmart
        </div>

        <div class="navbar_content">
            <i class="bi bi-grid"></i>
            <i class='bx bx-sun' id="darkLight"></i>
            <i class='bx bx-bell' ></i>
            <img src="../images/bw_profile.gif" alt="" class="profile" />
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
                        <span class="navlink">Home</span>
                        <i class="bx bx-chevron-right arrow-left"></i>
                    </div>
                    <ul class="menu_items submenu">
                        <a href="#" class="nav_link sublink">Nav Sub Link</a>
                    </ul>
                </li>
                <!-- end -->
                <li class="item">
                    <div href="#" class="nav_link submenu_item">
                        <span class="navlink_icon">
                            <i class="bx bx-grid-alt"></i>
                        </span>
                        <span class="navlink">Overview</span>
                        <i class="bx bx-chevron-right arrow-left"></i>
                    </div>
                    <ul class="menu_items submenu">
                        <a href="#" class="nav_link sublink">Nav Sub Link</a>
                    </ul>
                </li>
                <!-- end -->
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
            <ul class="menu_items">
                <div class="menu_title menu_setting"></div>
                <li class="item">
                    <a href="#" class="nav_link">
                        <span class="navlink_icon">
                            <img width="20" height="20" src="https://img.icons8.com/pulsar-line/48/exam.png" alt="exam"/>
                        </span>
                        <span class="navlink">Results</span>
                    </a>
                </li>
                <li class="item">
                    <a href="#" class="nav_link">
                        <span class="navlink_icon">
                            <img width="20" height="20" src="https://img.icons8.com/ios/50/combo-chart--v1.png" alt="combo-chart--v1"/>
                        </span>
                        <span class="navlink">Analysis</span>
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

    <div class="exam_title">
        <p><?php echo $_SESSION['exam_title'];?> <span class="tab"></span> Total Marks: <?php echo $_SESSION['total_marks'];?></p>
    </div>

    <div class="float-container">
        <section class="q_box" id="set_content">
            <div class="set_questions" id="set_questions">
                <p>Questions</p><br>
                <div id="set_q">
                    <div class="single_q" id="single_q"></div>
                </div>
            </div>
        </section>

        <section class="box" id="content">
            <div class="container">
                <p>Setting</p>
                <form action="" method="POST" enctype="multipart/form-data" id="examSetting">
                    <div class="question-block">
                        <div class="form-group" id="question_paper">
                            <label for="exam_id">Exam No.</label>
                            <input type="number" name="exam_id" id="exam_id" value=<?php echo $_SESSION['exam_id'];?> placeholder=<?php echo $_SESSION['exam_id'];?> required>
                            <label for="question_num">No.</label>
                            <input type="number" name="question_num" id="question_num" placeholder="Number">
                            <label for="question_alp">Sub</label>
                            <select id="question_alp" name="question_alp">
                                <option value="none"> </option>
                                <option value="a">a</option>
                                <option value="b">b</option>
                                <option value="c">c</option>
                            </select>
                            <label for="question_type">Type</label>
                            <select id="question_type" name="question_type">
                                <option value="Points">Points</option>
                                <option value="Narrative">Narrative</option>
                                <option value="Application">Application</option>
                                <option value="Comprehension">Comprehension</option>
                                <option value="Analysis">Analysis</option>
                                <option value="Calculation">Calculation</option>
                            </select>
                            <label for="marks">Marks</label>
                            <input type="number" name="marks" id="marks" placeholder="Marks"><br>
                            <label for="question">Question</label>
                            <input type="text" name="question" id="question" placeholder="Question">
                            <label for="answer_scheme">Answer (Add each answer on a new line)</label>
                            <textarea name="answer_scheme" id="answer_scheme" cols="100" rows="10" placeholder="Answer scheme"></textarea>
                            <div class="gen_new">
                                <button type="button" id="generate_answer">Generate Answer</button><br>
                                <div class="control">
                                    <button type="button" id="previous" onclick="previousQuestion()">Previous</button>
                                    <button type="button" id="next" onclick="nextQuestion()">Next</button>
                                    <button type="button" id="add_question" onclick="addQuestion()">Add Question</button>
                                    <button type="submit" id="submit">Submit Exam</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>
    <!-- JavaScript -->
    <script src="../js/exam_script.js"></script>
    <script src="../js/edit.js"></script>
</body>
</html>