#!"C:\Users\Administrator\AppData\Local\Programs\Python\Python312\python.exe"
import cgi
import mysql.connector
import cgitb
cgitb.enable()
from mysql.connector import Error

print("Content-type: text/html\n")
def make_connection():
    try:
        conn = mysql.connector.connect(host="localhost", user="root", password="", database="zana")
        return conn
    except Error as e:
        print("Error:", e)
        return None
    
def send_data(exam_number, school_name, exam_title, subject, grade_form, total_marks, duration, set_date, 
              due_date, instructions, completed):
    conn = make_connection()
    if conn is not None:
        try:
            cursor = conn.cursor()
            sql = '''INSERT INTO exams (exam_number, school_name, exam_title, subject, grade_form, total_marks, duration, set_date, due_date, instructions, completed) 
            VALUES (%s, %s, %s, %s, %s, %s, %s)'''
            val = (exam_number, school_name, exam_title, subject, grade_form, total_marks, duration, set_date, 
              due_date, instructions, completed)
            cursor.execute(sql, val)
            conn.commit()
            print("Record inserted successfully.")
        except Error as e:
            print("Error:", e)
            

# Retrieve form data
form = cgi.FieldStorage()

# Get form values
exam_number = form.getvalue('exam_number')
school_name = form.getvalue('school_name')
exam_title = form.getvalue('exam_title')
subject = form.getvalue('subject')
grade_form = form.getvalue('grade_form')
total_marks = form.getvalue('total_marks')
duration = form.getvalue('duration')
set_date = form.getvalue('set_date')
due_date = form.getvalue('due_date')
instructions = form.getvalue('instructions')
completed = form.getvalue('completed')


# Send data to database
send_data(exam_number, school_name, exam_title, subject, grade_form, total_marks, duration, set_date, 
              due_date, instructions, completed)

print("Content-type: text/html\n")
print("<html><head><title>Form Submission</title></head><body>")
print("<h1>Form submitted successfully!</h1>")
print("</body></html>")