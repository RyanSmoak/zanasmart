#!/usr/bin/env python3
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

print("Content-Type: text/html")
print()
latest_number = get_latest_number()

print(f'''
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Second Form</title>
</head>
<body>
    <form action="/cgi-bin/second_form.py" method="POST">
        <label for="number">Number:</label>
        <input type="number" id="number" name="number" value="{latest_number}" required>
        <button type="submit">Submit</button>
    </form>
</body>
</html>
''')
