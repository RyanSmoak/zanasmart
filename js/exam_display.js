window.onload = () => {
  getExams();
}

// Function to load data from the PHP backend
function getExams() {
  fetch('../php/fetch_exams.php', {
      method: 'GET'
  })
  .then(response => response.json()) // Directly parse JSON from response
  .then(data => {
      data.forEach(exam => {
          if (exam.completed == 0) { // Only display rows where completed is 0
              createRow(exam);
          }
      });
  })
  .catch(error => console.error('Error fetching exams:', error));
}

// Function to create a table row with exam data
function createRow(exam) {
  const tr = document.createElement('tr');

  // List of fields to be included in the table
  const fields = ['exam_number', 'school_name', 'exam_title', 'subject', 'grade_form', 'total_marks', 'duration', 'set_date', 'due_date', 'instructions', 'completed'];

  // Create and append table data cells
  fields.forEach(field => {
      const td = document.createElement('td');
      td.innerHTML = exam[field];
      tr.appendChild(td);
  });

  // Add click event listener to the row
  tr.addEventListener('click', () => {
      // Navigate to the page displaying the exam details
      // Assuming exam details page URL format is 'exam_details.php?exam_number=<exam_number>'
      window.location.href = `../html/stud_exam.php?exam_id=${exam.exam_number}`;
  });

  // Append the table row to the table body
  document.getElementById('data').appendChild(tr);
}
