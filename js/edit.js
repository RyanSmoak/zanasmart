 // Array to hold the questions
 let questions = [];
 let currentQuestionIndex = -1;

 // Function to show a specific question in the form
 function showQuestion(index) {
     const question = questions[index];
     document.getElementById('exam_id').value = question.exam_id;
     document.getElementById('question_num').value = question.question_num;
     document.getElementById('question_alp').value = question.question_alp;
     document.getElementById('question_type').value = question.question_type;
     document.getElementById('question').value = question.question;
     document.getElementById('answer_scheme').value = question.answer_scheme;
     document.getElementById('marks').value= question.marks;
 }

 //check if form is empty
 function isFormEmpty() {
    return (
        document.getElementById('question_num').value === '' &&
        document.getElementById('question').value === '' &&
        document.getElementById('answer_scheme').value === ''
    );
}

 // Function to save the current question
 function saveCurrentQuestion() {
     const question = {
         exam_id: document.getElementById('exam_id').value,
         question_num: document.getElementById('question_num').value,
         question_alp: document.getElementById('question_alp').value,
         question_type: document.getElementById('question_type').value,
         question: document.getElementById('question').value,
         answer_scheme: document.getElementById('answer_scheme').value,
         marks: document.getElementById('marks').value
     };

     // Only save if the form is not empty
    if (!isFormEmpty()) {
        questions[currentQuestionIndex] = question;
        updateQuestionsList();
    }
 }

 // Function to add a new question
 function addQuestion() {
    if (!isFormEmpty()) {
        saveCurrentQuestion();
    }
     clearForm();
     currentQuestionIndex = questions.length;
     questions.push({});
 }

 // Function to update the displayed list of questions
 function updateQuestionsList() {
     const setQuestions = document.getElementById('set_q');
     setQuestions.innerHTML = '';
     questions.forEach((question, index) => {
         const questionDiv = document.createElement('div');
         questionDiv.className = 'single_q';
         questionDiv.innerHTML = `<div style="font-weight: bold;">Question: ${question.question_num}${question.question_alp}</div><div>${question.question}</div>`;
        
         questionDiv.addEventListener('click', () => {
             saveCurrentQuestion();
             currentQuestionIndex = index;
             showQuestion(index);
         });
         setQuestions.appendChild(questionDiv);
     });
 }

 // Function to clear the form
 function clearForm() {
     document.getElementById('question_num').value = '';
     document.getElementById('question_alp').value = 'a';
     document.getElementById('question_type').value = 'Points';
     document.getElementById('question').value = '';
     document.getElementById('answer_scheme').value = '';
     document.getElementById('marks').value='';
 }

 // Function to handle the form submission
 document.getElementById('examSetting').addEventListener('submit', function(event) {
     event.preventDefault();
     saveCurrentQuestion();

     const formData = new FormData();
     formData.append('questions', JSON.stringify(questions));

     fetch('../php/questions_conn.php', {
         method: 'POST',
         body: formData
     })
     .then(response => response.json())
     .then(data => {
         alert('Exam submitted successfully');
         console.log(data);
     })
     .catch(error => console.error('Error:', error));
 });

 // Function to navigate to the next question
 function nextQuestion() {
     if (currentQuestionIndex < questions.length - 1) {
         saveCurrentQuestion();
         currentQuestionIndex++;
         showQuestion(currentQuestionIndex);
     }
 }

 // Function to navigate to the previous question
 function previousQuestion() {
     if (currentQuestionIndex > 0) {
         saveCurrentQuestion();
         currentQuestionIndex--;
         showQuestion(currentQuestionIndex);
     }
 }

 // Initialize with one empty question block
 addQuestion();