<?php
session_start(); // Ensure session is started to access session variables

// Retrieve failed data from the session
$failedData = isset($_SESSION['failedData']) ? $_SESSION['failedData'] : [];

// Set headers to prompt file download as CSV
header("Content-Type: text/csv; charset=utf-8");
header("Content-Disposition: attachment; filename=Failed_Data_from_Importing_Canteen.csv");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private", false);

// Open PHP output stream for CSV
$output = fopen('php://output', 'w');

// Add CSV headers



fputcsv($output, [
    'Employee Id Number','Full Name','Card Number','Employer','Department','Section','GPI 8 (Yes or No)'
]);

// Write the failed data rows to the CSV
if (!empty($failedData)) {
    foreach ($failedData as $row) {
        // Ensure $row is an array; fputcsv will write it directly
        if (is_array($row)) {
            fputcsv($output, $row);
        }
    }
}

// Close the output stream and exit to complete file download
fclose($output);


exit();
// header("Location: index.php");
?>
