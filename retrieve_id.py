#!"C:\Users\Administrator\AppData\Local\Programs\Python\Python312\python.exe"
import cgi
import cgitb
import os

import mysql.connector
from mysql.connector import Error

def get_latest_number():
    try:
        connection = mysql.connector.connect(
            host='localhost',
            database='zana',
            user='root',
            password=''
        )
        if connection.is_connected():
            cursor = connection.cursor()
            cursor.execute("SELECT exam_number FROM exams ORDER BY exam_number DESC LIMIT 1")
            row = cursor.fetchone()
            return row[0] if row else None

    except Error as e:
        print(f"Error: {e}")
        return None

    finally:
        if connection.is_connected():
            cursor.close()
            connection.close()

# Enable debugging
cgitb.enable()

print("Content-Type: text/html")
print()

# Get the latest number from the database
latest_number = get_latest_number()

# Read the HTML template
with open('../templates/second_form.html', 'r') as file:
    template = file.read()

# Replace the placeholder with the actual number
html_content = template.replace('{{ number }}', str(latest_number))

# Print the final HTML content
print(html_content)
