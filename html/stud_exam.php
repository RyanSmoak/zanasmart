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
    <div id="marksDiv" total="0"></div>

    </div>
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
    <h1 id="exam-title"></h1>
    <div class="exam_title">
        <p>Exam Title<br></p>
    </div>
    <div id="exam-container">
    </div>
    <button id="submit-exam">Submit Exam</button>

    <script>
        const examId = new URLSearchParams(window.location.search).get('exam_id');
        
        // Fetch the exam details
        fetch(`../php/fetch_exam.php?exam_number=${examId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('exam-title').textContent = data.exam.title;
                
                const examContainer = document.getElementById('exam-container');
                data.questions.forEach((question, index) => {
                    const questionDiv = document.createElement('div');
                    questionDiv.innerHTML = `
                        <p><strong>Question ${question.question_num}${question.question_alp}</strong>: ${question.question}</p>
                        <textarea id="answer_${question.question_num}" name="answer_${question.question_num}"  alp="${question.question_alp}" num=${question.question_num} rows="7" cols="100"></textarea>
                    `;
                    examContainer.appendChild(questionDiv);
                    
                });
            });

        // Handle exam submission
        document.getElementById('submit-exam').addEventListener('click', () => {
           mtumizi()
        });

        async function fetchAnswers(studAnswers,examId) {
              studAnswers=JSON.stringify(studAnswers);
              return fetch('./fetchAnswers.php', {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                  studAnswers:studAnswers,
                  examID:examId
                })
              })
              .then(response => response.text())
              .then(data => {
                 data=JSON.parse(data);
                 answers=data.data;
                 return answers;
              })
              .catch(error => console.error('Error:', error));
        }

        async function mtumizi()
        {
           const examId = new URLSearchParams(window.location.search).get('exam_id');
           let studAnswers=fetchStudentAnswers()
           let answers=await fetchAnswers(studAnswers,examId);
           answers.forEach(singleAns => {
              markAnswer(singleAns)
           });
        }
        function fetchStudentAnswers()
        {
          let studentAnswers=[];
          //get the exam_container
          const examContainer = document.getElementById('exam-container');
          //Get all the textareas
          let textAreas=examContainer.querySelectorAll('textarea');
          textAreas.forEach(element => {
              //get the element name
              let name=element.getAttribute('name')
              let alp=element.getAttribute('alp')
              let q_num=element.getAttribute('num')
              //Get the value
              let value=element.value;
              let obj={
                questionName:name,
                questionAlp:alp,
                questionNum:q_num,
                answer:value
              }
              studentAnswers.push(obj)
          });
          return studentAnswers;
        }

        //a synchronous XHR request function
        function markAnswer(answer)
        {
          let url='http://localhost:8000/api/analyse/'
          let obj={
            answer_scheme:answer.answer_scheme,
            student_answer:answer.answer
          }
          obj=JSON.stringify(obj);
          sendMarkingRequest(url,obj,answer.questionNum,answer.questionAlp)
        }

        function sendMarkingRequest(url, data,questionNum,alp) {
              const xhr = new XMLHttpRequest();
              xhr.open('POST', url, false); // Synchronous request

              // Set request headers (if needed)
              xhr.setRequestHeader('Content-Type', 'application/json');

              // Define callback functions
              xhr.onload = () => {
                  if (xhr.status >= 200 && xhr.status < 300) {
                      const response = JSON.parse(xhr.responseText);
                      manageMarks(response,questionNum,alp)
                      console.log(response)
                  } else {
                      console.error('Error:', xhr.statusText);
                  }
              };

              xhr.onerror = () => {
                  console.error('Network error occurred.');
              };

              // Send the request with data
              xhr.send(data);
        }

        //A function to manage the marks that come 
        function manageMarks(marks,qNum,alp)
        {
          marks=marks.marks;
          let target=document.getElementById('marksDiv')
          let total=target.getAttribute('total');
          total=parseInt(total);
          total+=marks;
          target.setAttribute('total',total)
          //Create a span to store marks for a single question
          let span=document.createElement('span')
          span.textContent=marks
          span.textContent+=" "+qNum+' '+alp
          target.appendChild(span)
        }

    </script>
    <!-- JavaScript -->
    <script src="../js/exam_script.js"></script>
  </body>
</html>