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
    const fields = ['exam_number', 'school_name', 'exam_title', 'subject', 'grade_form', 'total_marks', 'duration', 'set_date', 'due_date', 'instructions'];

    // Create and append table data cells
    fields.forEach(field => {
        const td = document.createElement('td');
        td.innerHTML = exam[field];
        tr.appendChild(td);
    });

    // Append the table row to the table body
    document.getElementById('data').appendChild(tr);
}