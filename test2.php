<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Exam</title>
    <style>
        .question-block { display: none; }
        .question-block.active { display: block; }
    </style>
</head>
<body>
    <form action="" method="POST" enctype="multipart/form-data" id="myForm">
        <div id="questionsContainer">
            <!-- Initial question block will be added by JavaScript -->
        </div>
        <div class="control">
            <button type="button" id="previous" onclick="previousQuestion()">Previous</button>
            <button type="button" id="next" onclick="nextQuestion()">Next</button>
            <button type="button" id="add_question" onclick="addQuestion()">Add Question</button>
            <button type="submit" id="submit">Submit Exam</button>
        </div>
    </form>

    <script>
        let currentQuestion = -1;
        const questionsContainer = document.getElementById('questionsContainer');
        const questions = [];

        function showQuestion(index) {
            questions.forEach((div, i) => {
                div.classList.remove('active');
                if (i === index) div.classList.add('active');
            });
        }

        function nextQuestion() {
            if (currentQuestion < questions.length - 1) {
                currentQuestion++;
                showQuestion(currentQuestion);
            }
        }

        function previousQuestion() {
            if (currentQuestion > 0) {
                currentQuestion--;
                showQuestion(currentQuestion);
            }
        }

        function addQuestion() {
            const questionBlock = document.createElement('div');
            questionBlock.className = 'question-block';

            questionBlock.innerHTML = `
                <div class="form-group" id="question_paper">
                    <label for="exam_id">Exam No.</label>
                    <input type="number" name="exam_id" id="exam_id" value="1">
                    <label for="question_num">No.</label>
                    <input type="number" name="question_num" id="question_num" placeholder="Number">
                    <label for="question_alp">Sub</label>
                    <select id="question_alp" name="question_alp">
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
                    </select><br>
                    <label for="question">Question</label>
                    <input type="text" name="question" id="question" placeholder="Question">
                    <label for="answer_scheme">Answer (Add each answer on a new line)</label>
                    <textarea name="answer_scheme" id="answer_scheme" cols="100" rows="10" placeholder="Answer scheme"></textarea>
                </div>
            `;

            questionsContainer.appendChild(questionBlock);
            questions.push(questionBlock);

            // Set the new question as the current question and display it
            currentQuestion = questions.length - 1;
            showQuestion(currentQuestion);
        }

        document.getElementById('myForm').addEventListener('submit', function(event) {
            event.preventDefault();
            // Collect all questions and submit to the server
            const formData = new FormData(this);
            fetch('submit_exam.php', {
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

        // Initialize with one question
        addQuestion();
    </script>
</body>
</html>
