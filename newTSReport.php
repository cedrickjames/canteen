<?php
    session_start();

    include("./connection.php");

    $fromDate = $_SESSION['totalFrom'];
    $toDate = $_SESSION['totalTo'];
    $arrayDate = $_SESSION['period'];
    $dateCount = $_SESSION['dateCount'];
    $Employers = array("GLORY", "MAXIM", "POWERLANE", "NIPPI", "SERVICE PROVIDER");

?>
<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Total Sales Report</title>
        <style>
        @media print {
            .page-break {
                page-break-before: always; /* Or page-break-after: always */
                /* You can use page-break-inside: avoid; for elements to avoid breaking inside */
            }
        }
    </style>
    </head>
    <body style="margin: 0; padding: 0; align-items: center; font-family: sans-serif;">
        <div style="page-break-after: always;">
            <!-- <header style="text-align: center; font-size: 12px;">
                <h3 style="text-align: center;margin: 0; padding: 0;">GLORY PHILIPPINES INC.</h3>
                <h3 style="text-align: center;margin: 0; padding: 0;">Total Sales Report</h3>
                <h3 style="text-align: center;margin: 0; padding: 0;">For the Period <?php echo date('M-d',strtotime($fromDate));?> to <?php echo date('M-d',strtotime($toDate))?></h3>
            </header> -->
            <table style="margin-top: 20px; width: 100%; text-align: center; border-collapse: collapse;">
<!-- GLORY -->
    <?php
        $Employee_list_comp_glory = "SELECT DISTINCT `emp_name` FROM emp_list WHERE employer = 'GLORY' UNION SELECT DISTINCT `emp_name` FROM `tbl_trans_logs` WHERE tran_date BETWEEN '$fromDate' AND '$toDate' AND employer = 'GLORY' UNION SELECT DISTINCT `lgbk_name` FROM `logbooksales` WHERE lgbk_date BETWEEN '$fromDate' AND '$toDate' AND lgbk_employer = 'GLORY'";
        $Employee_list_query_glory = mysqli_query($con, $Employee_list_comp_glory);
        $Employee_list_count_glory = mysqli_num_rows($Employee_list_query_glory);
        $Employee_list_array_glory = array();

    ?>  
        <!-- <thead>
            <tr>
               <td style="width: 6%; font-weight: bold; font-size: 11px;">Glory Philippines Inc.</td>
            </thead> -->
            <thead>
            <tr>
            <th colspan="<?php echo count($arrayDate) +1;?>">Glory Philippines Inc.</th></tr>
            <tr>
            <th colspan="<?php echo count($arrayDate) +1;?>">Total Sales Report</th></tr>
            <tr>
            <th colspan="<?php echo count($arrayDate) +1;?>">For the Period <?php echo date('M-d',strtotime($fromDate));?> to <?php echo date('M-d',strtotime($toDate))?></th></tr>
            <tr>
            <th >Glory Philippines Inc.</th></tr>
    
                <tr>
                    <th style="width: 6%; font-weight: bold; font-size: 11px;">Employee</th>
                <?php foreach ($arrayDate as $dates_glory){ ?>
                    <th style="width: 4%; font-weight: bold; font-size: 11px;"><?php echo date('M-d',strtotime($dates_glory))?></th> <?php } ?>
                </tr>
            </thead>
            <tbody>

            <?php
            
            $sql = "SELECT 
    emp_name,
    MAX(CASE WHEN tran_date = '2024-08-26' THEN '35' ELSE '0' END) AS 'Aug-26',
    MAX(CASE WHEN tran_date = '2024-08-27' THEN '35' ELSE '0' END) AS 'Aug-27',
    MAX(CASE WHEN tran_date = '2024-08-28' THEN '35' ELSE '0' END) AS 'Aug-28',
    MAX(CASE WHEN tran_date = '2024-08-29' THEN '35' ELSE '0' END) AS 'Aug-29',
    MAX(CASE WHEN tran_date = '2024-08-30' THEN '35' ELSE '0' END) AS 'Aug-30',
    MAX(CASE WHEN tran_date = '2024-08-31' THEN '35' ELSE '0' END) AS 'Aug-31',
    MAX(CASE WHEN tran_date = '2024-09-01' THEN '35' ELSE '0' END) AS 'Sep-01'
FROM tbl_trans_logs WHERE employer = 'GLORY'
GROUP BY emp_name
ORDER BY emp_name;
";


                            $result = mysqli_query($con, $sql);



                            while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                    <tr>
                                        <td><?php echo $row['emp_name']; ?></td>
                                        <td><?php echo $row['Aug-26']; ?></td>
                                        <td><?php echo $row['Aug-27']; ?></td>
                                        <td><?php echo $row['Aug-28']; ?></td>
                                        <td><?php echo $row['Aug-29']; ?></td>
                                        <td><?php echo $row['Aug-30']; ?></td>
                                        <td><?php echo $row['Aug-31']; ?></td>
                                        <td><?php echo $row['Sep-01']; ?></td>

                                    </tr>

<?php

                            }



            ?>
            <?php
            
            $sql = "SELECT 
    lgbk_name,
    MAX(CASE WHEN lgbk_date = '2024-08-26' THEN '35' ELSE '0' END) AS 'Aug-26',
    MAX(CASE WHEN lgbk_date = '2024-08-27' THEN '35' ELSE '0' END) AS 'Aug-27',
    MAX(CASE WHEN lgbk_date = '2024-08-28' THEN '35' ELSE '0' END) AS 'Aug-28',
    MAX(CASE WHEN lgbk_date = '2024-08-29' THEN '35' ELSE '0' END) AS 'Aug-29',
    MAX(CASE WHEN lgbk_date = '2024-08-30' THEN '35' ELSE '0' END) AS 'Aug-30',
    MAX(CASE WHEN lgbk_date = '2024-08-31' THEN '35' ELSE '0' END) AS 'Aug-31',
    MAX(CASE WHEN lgbk_date = '2024-09-01' THEN '35' ELSE '0' END) AS 'Sep-01'
FROM logbooksales WHERE lgbk_employer = 'GLORY'
GROUP BY lgbk_name
ORDER BY lgbk_name;
";


                            $result = mysqli_query($con, $sql);



                            while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                    <tr>
                                        <td><?php echo $row['lgbk_name']; ?></td>
                                        <td><?php echo $row['Aug-26']; ?></td>
                                        <td><?php echo $row['Aug-27']; ?></td>
                                        <td><?php echo $row['Aug-28']; ?></td>
                                        <td><?php echo $row['Aug-29']; ?></td>
                                        <td><?php echo $row['Aug-30']; ?></td>
                                        <td><?php echo $row['Aug-31']; ?></td>
                                        <td><?php echo $row['Sep-01']; ?></td>

                                    </tr>

<?php

                            }



            ?>
                </tbody>
                </table>

                <div class="page-break"></div>

                
    </body>
</html>
