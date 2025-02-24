<?php

include("./connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tableData = json_decode($_POST['tableData'], true); // Convert JSON to PHP array

    if (!empty($tableData)) {
        foreach ($tableData as $row) {
            $lgbkDate = $row['date'];
            $lgbkName = $row['name'];
            $lgbkEmp = strtoupper($row['employer']);
            $lgbkEmpId = strtoupper($row['empId']);
            $lgbkCard = strtoupper($row['card']);
            $lgbkEmpDept = strtoupper($row['department']);
            $lgbkEmpSection = strtoupper($row['section']);
            $lgbkGPI8 = strtoupper($row['gpi8']);

            // Check if employee is already in logs
            $queryLgbkSales = "SELECT * FROM `tbl_trans_logs` WHERE `tran_date` = '$lgbkDate' AND `emp_id` = '$lgbkEmpId'";
            $resultLgbkSales = mysqli_query($con, $queryLgbkSales);

            if (mysqli_num_rows($resultLgbkSales) > 0) {
                echo "Employee $lgbkName (ID: $lgbkEmpId) is already in logs!";
                continue; // Skip this entry and move to the next one
            }

            // Insert into database
            $tran_insert = "INSERT INTO `tbl_trans_logs`
                (`transaction_id`, `emp_id`, `emp_name`, `emp_cardNum`, `employer`, `tran_date`, `department`, `section`, `gpi8`, `logbook`)
                VALUES (NULL, '$lgbkEmpId', '$lgbkName', '$lgbkCard', '$lgbkEmp', '$lgbkDate', '$lgbkEmpDept', '$lgbkEmpSection', '$lgbkGPI8', '1')";

            $insertResult = mysqli_query($con, $tran_insert);

            if ($insertResult) {
                // echo "Employee $lgbkName successfully added!<br>";
            } else {
                echo "Error inserting $lgbkName: " . mysqli_error($con) . "<br>";
            }
        }
    } else {
        echo "No data received!";
    }
}
?>
