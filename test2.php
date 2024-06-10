<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semantic Analysis</title>
</head>
<body>
    <h1>Semantic Analysis</h1>
    <textarea id="passage1" placeholder="Enter Passage 1"></textarea><br>
    <textarea id="passage2" placeholder="Enter Passage 2"></textarea><br>
    <button onclick="performAnalysis()">Analyze</button>
    <h2>Results</h2>
    <pre id="result"></pre>

    <script>
        async function performAnalysis() {
            const passage1 = document.getElementById('passage1').value;
            const passage2 = document.getElementById('passage2').value;

            const response = await fetch('ai-models/sem_search/marker/api/marker/', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ passage1, passage2 })
            });

            const result = await response.json();
            document.getElementById('result').innerText = JSON.stringify(result, null, 2);
        }
    </script>
</body>
</html>
