<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Downloading...</title>
      <script src="src/cdn_tailwindcss.js"></script>
    <script src="node_modules/flowbite/dist/flowbite.min.js"></script>
  
    <script>
        let countdown = 3;

        function updateCountdown() {
            const countdownElement = document.getElementById('countdown');
            countdownElement.textContent = countdown;
            updateProgressBar();

            if (countdown === 0) {
                window.location.href = 'employees.php';
            } else {
                countdown--;
                setTimeout(updateCountdown, 1000);
            }
        }

        function updateProgressBar() {
            const progressBar = document.getElementById('progressBar');
            const percentage = ((3 - countdown) / 3) * 100;
            progressBar.style.width = percentage + '%';
        }

        window.onload = function() {
            // Start the download
            window.location.href = 'failedDataFromImporting.php';

            // Start countdown
            updateCountdown();
        };
    </script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg text-center w-full max-w-md">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Preparing Your Download</h1>
        <p class="text-gray-600 text-lg mb-4">
            Your download will begin shortly.<br>
            You will be redirected in <span id="countdown" class="font-bold text-blue-600">3</span> seconds...
        </p>
        <div class="w-full bg-gray-200 rounded-full h-2.5">
            <div id="progressBar" class="bg-blue-500 h-2.5 rounded-full transition-all duration-1000" style="width: 0%;"></div>
        </div>
    </div>
</body>
</html>
