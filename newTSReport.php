<?php
    session_start();

    include("./connection.php");

    $fromDate = $_SESSION['totalFrom'];
    $toDate = $_SESSION['totalTo'];
    $arrayDate = $_SESSION['period'];
    $dateCount = $_SESSION['dateCount'];
    $Employers = array("GLORY", "MAXIM", "POWERLANE", "NIPPI", "SERVICE PROVIDER");


    $start = new DateTime($fromDate);
    $end = new DateTime($toDate);
    $end->modify('+1 day'); // Include the end date in the range
    
    // Create a DatePeriod with a 1-day interval
    $interval = new DateInterval('P1D'); // 1-day interval
    $period = new DatePeriod($start, $interval, $end);
    
    // Loop through the dates
    $dates = [];
    $datesArray = [];

    foreach ($period as $date) {
        $datesArray[] = $date->format('Y-m-d');
        // echo $date->format('Y-m-d'), '<br>';

    }

    $monday= $datesArray[0] ;
    $tuesday=  $datesArray[1];
    $wednesday=  $datesArray[2];
    $thursday=  $datesArray[3];
    $friday=  $datesArray[4];
    $saturday=  $datesArray[5];
    $sunday=  $datesArray[6];


//     echo $monday , '<br>';
// echo $tuesday, '<br>';
// echo $wednesday, '<br>';
// echo $thursday, '<br>';
// echo $friday, '<br>';
// echo $saturday, '<br>';
// echo $sunday, '<br>';


$overAlltotalCol1=0;
$overAlltotalCol2=0;
$overAlltotalCol3=0;
$overAlltotalCol4=0;
$overAlltotalCol5=0;
$overAlltotalCol6=0;
$overAlltotalCol7=0;




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
            <tbody style="font-size: 10px;">

            <?php
             $totalCol1=0;
             $totalCol2=0;
             $totalCol3=0;
             $totalCol4=0;
             $totalCol5=0;
             $totalCol6=0;
             $totalCol7=0;

            $sql = "SELECT 
    emp_name,
    MAX(CASE WHEN tran_date = '$monday' THEN '35' ELSE '0' END) AS 'monday',
    MAX(CASE WHEN tran_date = '$tuesday' THEN '35' ELSE '0' END) AS 'tuesday',
    MAX(CASE WHEN tran_date = '$wednesday' THEN '35' ELSE '0' END) AS 'wednesday',
    MAX(CASE WHEN tran_date = '$thursday' THEN '35' ELSE '0' END) AS 'thursday',
    MAX(CASE WHEN tran_date = '$friday' THEN '35' ELSE '0' END) AS 'friday',
    MAX(CASE WHEN tran_date = '$saturday' THEN '35' ELSE '0' END) AS 'saturday',
    MAX(CASE WHEN tran_date = '$sunday' THEN '35' ELSE '0' END) AS 'sunday'
FROM tbl_trans_logs WHERE employer = 'GLORY'
GROUP BY emp_name
ORDER BY emp_name;
";


                            $result = mysqli_query($con, $sql);



                            while ($row = mysqli_fetch_assoc($result)) {
                                $totalCol1 += (int)$row['monday'];
                                $totalCol2 += (int)$row['tuesday'];
                                $totalCol3 += (int)$row['wednesday'];
                                $totalCol4 += (int)$row['thursday'];
                                $totalCol5 += (int)$row['friday'];
                                $totalCol6 += (int)$row['saturday'];
                                $totalCol7 += (int)$row['sunday'];


                                $overAlltotalCol1 += (int)$row['monday'];
                                $overAlltotalCol2 += (int)$row['tuesday'];
                                $overAlltotalCol3 += (int)$row['wednesday'];
                                $overAlltotalCol4 += (int)$row['thursday'];
                                $overAlltotalCol5 += (int)$row['friday'];
                                $overAlltotalCol6 += (int)$row['saturday'];
                                $overAlltotalCol7 += (int)$row['sunday'];

                   


                                    ?>
                                    <tr>
                                        <td><?php echo $row['emp_name']; ?></td>
                                        <td><?php echo $row['monday']; ?></td>
                                        <td><?php echo $row['tuesday']; ?></td>
                                        <td><?php echo $row['wednesday']; ?></td>
                                        <td><?php echo $row['thursday']; ?></td>
                                        <td><?php echo $row['friday']; ?></td>
                                        <td><?php echo $row['saturday']; ?></td>
                                        <td><?php echo $row['sunday']; ?></td>

                                    </tr>

<?php

                            }



            ?>

<tr style=" font-weight: bold; font-size: 15px;">
                                        <td>TOTAL</td>
                                        <td><?php echo $totalCol1; ?></td>
                                        <td><?php echo $totalCol2; ?></td>
                                        <td><?php echo $totalCol3; ?></td>
                                        <td><?php echo $totalCol4; ?></td>
                                        <td><?php echo $totalCol5; ?></td>
                                        <td><?php echo $totalCol6; ?></td>
                                        <td><?php echo $totalCol7; ?></td>

                                    </tr>

        
                </tbody>
                </table>



                <div class="page-break"></div>


                <table style="margin-top: 20px; width: 100%; text-align: center; border-collapse: collapse;">
<!-- GLORY -->
    <?php
        $Employee_list_comp_maxim = "SELECT DISTINCT `emp_name` FROM emp_list WHERE employer = 'MAXIM' UNION SELECT DISTINCT `emp_name` FROM `tbl_trans_logs` WHERE tran_date BETWEEN '$fromDate' AND '$toDate' AND employer = 'MAXIM' UNION SELECT DISTINCT `lgbk_name` FROM `logbooksales` WHERE lgbk_date BETWEEN '$fromDate' AND '$toDate' AND lgbk_employer = 'MAXIM'";
        $Employee_list_query_maxim = mysqli_query($con, $Employee_list_comp_maxim);
        $Employee_list_count_maxim = mysqli_num_rows($Employee_list_query_maxim);
        $Employee_list_array_maxim = array();

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
            <th >Maxim De Humana International Inc</th></tr>
    
                <tr>
                    <th style="width: 6%; font-weight: bold; font-size: 11px;">Employee</th>
                <?php foreach ($arrayDate as $dates_glory){ ?>
                    <th style="width: 4%; font-weight: bold; font-size: 11px;"><?php echo date('M-d',strtotime($dates_glory))?></th> <?php } ?>
                </tr>
            </thead>
            <tbody style="font-size: 10px;">

            <?php
            $totalCol1=0;
            $totalCol2=0;
            $totalCol3=0;
            $totalCol4=0;
            $totalCol5=0;
            $totalCol6=0;
            $totalCol7=0;
            $sql = "SELECT 
    emp_name,
    MAX(CASE WHEN tran_date = '$monday' THEN '35' ELSE '0' END) AS 'col1',
    MAX(CASE WHEN tran_date = '$tuesday' THEN '35' ELSE '0' END) AS 'col2',
    MAX(CASE WHEN tran_date = '$wednesday' THEN '35' ELSE '0' END) AS 'col3',
    MAX(CASE WHEN tran_date = '$thursday' THEN '35' ELSE '0' END) AS 'col4',
    MAX(CASE WHEN tran_date = '$friday' THEN '35' ELSE '0' END) AS 'col5',
    MAX(CASE WHEN tran_date = '$saturday' THEN '35' ELSE '0' END) AS 'col6',
    MAX(CASE WHEN tran_date = '$sunday' THEN '35' ELSE '0' END) AS 'col7'
FROM tbl_trans_logs WHERE employer = 'MAXIM'
GROUP BY emp_name
ORDER BY emp_name;
";


                            $result = mysqli_query($con, $sql);



                            while ($row = mysqli_fetch_assoc($result)) {
                                $totalCol1 += (int)$row['col1'];
                                $totalCol2 += (int)$row['col2'];
                                $totalCol3 += (int)$row['col3'];
                                $totalCol4 += (int)$row['col4'];
                                $totalCol5 += (int)$row['col5'];
                                $totalCol6 += (int)$row['col6'];
                                $totalCol7 += (int)$row['col7'];
                                

                                $overAlltotalCol1 += (int)$row['col1'];
                                $overAlltotalCol2 += (int)$row['col2'];
                                $overAlltotalCol3 += (int)$row['col3'];
                                $overAlltotalCol4 += (int)$row['col4'];
                                $overAlltotalCol5 += (int)$row['col5'];
                                $overAlltotalCol6 += (int)$row['col6'];
                                $overAlltotalCol7 += (int)$row['col7']

                                    ?>
                                    <tr>
                                        <td><?php echo $row['emp_name']; ?></td>
                                        <td><?php echo $row['col1']; ?></td>
                                        <td><?php echo $row['col2']; ?></td>
                                        <td><?php echo $row['col3']; ?></td>
                                        <td><?php echo $row['col4']; ?></td>
                                        <td><?php echo $row['col5']; ?></td>
                                        <td><?php echo $row['col6']; ?></td>
                                        <td><?php echo $row['col7']; ?></td>

                                    </tr>

                                        <?php
                            }
            ?>
         
                                    <tr style=" font-weight: bold; font-size: 15px;">
                                        <td>TOTAL</td>
                                        <td><?php echo $totalCol1; ?></td>
                                        <td><?php echo $totalCol2; ?></td>
                                        <td><?php echo $totalCol3; ?></td>
                                        <td><?php echo $totalCol4; ?></td>
                                        <td><?php echo $totalCol5; ?></td>
                                        <td><?php echo $totalCol6; ?></td>
                                        <td><?php echo $totalCol7; ?></td>

                                    </tr>

                </tbody>
                </table>




                <table style="margin-top: 20px; width: 100%; text-align: center; border-collapse: collapse;">
<!-- GLORY -->
    <?php
        $Employee_list_comp_glory = "SELECT DISTINCT `emp_name` FROM emp_list WHERE employer = 'POWERLANE' UNION SELECT DISTINCT `emp_name` FROM `tbl_trans_logs` WHERE tran_date BETWEEN '$fromDate' AND '$toDate' AND employer = 'POWERLANE' UNION SELECT DISTINCT `lgbk_name` FROM `logbooksales` WHERE lgbk_date BETWEEN '$fromDate' AND '$toDate' AND lgbk_employer = 'POWERLANE'";
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
            <th >POWERLANE</th></tr>
    
                <tr>
                    <th style="width: 6%; font-weight: bold; font-size: 11px;">Employee</th>
                <?php foreach ($arrayDate as $dates_glory){ ?>
                    <th style="width: 4%; font-weight: bold; font-size: 11px;"><?php echo date('M-d',strtotime($dates_glory))?></th> <?php } ?>
                </tr>
            </thead>
            <tbody style="font-size: 10px;">

            <?php
             $totalCol1=0;
             $totalCol2=0;
             $totalCol3=0;
             $totalCol4=0;
             $totalCol5=0;
             $totalCol6=0;
             $totalCol7=0;

            $sql = "SELECT 
    emp_name,
    MAX(CASE WHEN tran_date = '$monday' THEN '35' ELSE '0' END) AS 'monday',
    MAX(CASE WHEN tran_date = '$tuesday' THEN '35' ELSE '0' END) AS 'tuesday',
    MAX(CASE WHEN tran_date = '$wednesday' THEN '35' ELSE '0' END) AS 'wednesday',
    MAX(CASE WHEN tran_date = '$thursday' THEN '35' ELSE '0' END) AS 'thursday',
    MAX(CASE WHEN tran_date = '$friday' THEN '35' ELSE '0' END) AS 'friday',
    MAX(CASE WHEN tran_date = '$saturday' THEN '35' ELSE '0' END) AS 'saturday',
    MAX(CASE WHEN tran_date = '$sunday' THEN '35' ELSE '0' END) AS 'sunday'
FROM tbl_trans_logs WHERE employer = 'POWERLANE'
GROUP BY emp_name
ORDER BY emp_name;
";


                            $result = mysqli_query($con, $sql);



                            while ($row = mysqli_fetch_assoc($result)) {
                                $totalCol1 += (int)$row['monday'];
                                $totalCol2 += (int)$row['tuesday'];
                                $totalCol3 += (int)$row['wednesday'];
                                $totalCol4 += (int)$row['thursday'];
                                $totalCol5 += (int)$row['friday'];
                                $totalCol6 += (int)$row['saturday'];
                                $totalCol7 += (int)$row['sunday'];

                                $overAlltotalCol1 += (int)$row['monday'];
                                $overAlltotalCol2 += (int)$row['tuesday'];
                                $overAlltotalCol3 += (int)$row['wednesday'];
                                $overAlltotalCol4 += (int)$row['thursday'];
                                $overAlltotalCol5 += (int)$row['friday'];
                                $overAlltotalCol6 += (int)$row['saturday'];
                                $overAlltotalCol7 += (int)$row['sunday'];



                                
                                    ?>
                                    <tr>
                                        <td><?php echo $row['emp_name']; ?></td>
                                        <td><?php echo $row['monday']; ?></td>
                                        <td><?php echo $row['tuesday']; ?></td>
                                        <td><?php echo $row['wednesday']; ?></td>
                                        <td><?php echo $row['thursday']; ?></td>
                                        <td><?php echo $row['friday']; ?></td>
                                        <td><?php echo $row['saturday']; ?></td>
                                        <td><?php echo $row['sunday']; ?></td>

                                    </tr>

<?php

                            }



            ?>

<tr style=" font-weight: bold; font-size: 15px;">
                                        <td>TOTAL</td>
                                        <td><?php echo $totalCol1; ?></td>
                                        <td><?php echo $totalCol2; ?></td>
                                        <td><?php echo $totalCol3; ?></td>
                                        <td><?php echo $totalCol4; ?></td>
                                        <td><?php echo $totalCol5; ?></td>
                                        <td><?php echo $totalCol6; ?></td>
                                        <td><?php echo $totalCol7; ?></td>

                                    </tr>

        
                </tbody>
                </table>



                
                <table style="margin-top: 20px; width: 100%; text-align: center; border-collapse: collapse;">
<!-- GLORY -->
    <?php
        $Employee_list_comp_glory = "SELECT DISTINCT `emp_name` FROM emp_list WHERE employer = 'NIPPI' UNION SELECT DISTINCT `emp_name` FROM `tbl_trans_logs` WHERE tran_date BETWEEN '$fromDate' AND '$toDate' AND employer = 'NIPPI' UNION SELECT DISTINCT `lgbk_name` FROM `logbooksales` WHERE lgbk_date BETWEEN '$fromDate' AND '$toDate' AND lgbk_employer = 'NIPPI'";
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
            <th >NIPPI</th></tr>
    
                <tr>
                    <th style="width: 6%; font-weight: bold; font-size: 11px;">Employee</th>
                <?php foreach ($arrayDate as $dates_glory){ ?>
                    <th style="width: 4%; font-weight: bold; font-size: 11px;"><?php echo date('M-d',strtotime($dates_glory))?></th> <?php } ?>
                </tr>
            </thead>
            <tbody style="font-size: 10px;">

            <?php
             $totalCol1=0;
             $totalCol2=0;
             $totalCol3=0;
             $totalCol4=0;
             $totalCol5=0;
             $totalCol6=0;
             $totalCol7=0;

            $sql = "SELECT 
    emp_name,
    MAX(CASE WHEN tran_date = '$monday' THEN '35' ELSE '0' END) AS 'monday',
    MAX(CASE WHEN tran_date = '$tuesday' THEN '35' ELSE '0' END) AS 'tuesday',
    MAX(CASE WHEN tran_date = '$wednesday' THEN '35' ELSE '0' END) AS 'wednesday',
    MAX(CASE WHEN tran_date = '$thursday' THEN '35' ELSE '0' END) AS 'thursday',
    MAX(CASE WHEN tran_date = '$friday' THEN '35' ELSE '0' END) AS 'friday',
    MAX(CASE WHEN tran_date = '$saturday' THEN '35' ELSE '0' END) AS 'saturday',
    MAX(CASE WHEN tran_date = '$sunday' THEN '35' ELSE '0' END) AS 'sunday'
FROM tbl_trans_logs WHERE employer = 'NIPPI'
GROUP BY emp_name
ORDER BY emp_name;
";


                            $result = mysqli_query($con, $sql);



                            while ($row = mysqli_fetch_assoc($result)) {
                                $totalCol1 += (int)$row['monday'];
                                $totalCol2 += (int)$row['tuesday'];
                                $totalCol3 += (int)$row['wednesday'];
                                $totalCol4 += (int)$row['thursday'];
                                $totalCol5 += (int)$row['friday'];
                                $totalCol6 += (int)$row['saturday'];
                                $totalCol7 += (int)$row['sunday'];

                                $overAlltotalCol1 += (int)$row['monday'];
                                $overAlltotalCol2 += (int)$row['tuesday'];
                                $overAlltotalCol3 += (int)$row['wednesday'];
                                $overAlltotalCol4 += (int)$row['thursday'];
                                $overAlltotalCol5 += (int)$row['friday'];
                                $overAlltotalCol6 += (int)$row['saturday'];
                                $overAlltotalCol7 += (int)$row['sunday'];


                                    ?>
                                    <tr>
                                        <td><?php echo $row['emp_name']; ?></td>
                                        <td><?php echo $row['monday']; ?></td>
                                        <td><?php echo $row['tuesday']; ?></td>
                                        <td><?php echo $row['wednesday']; ?></td>
                                        <td><?php echo $row['thursday']; ?></td>
                                        <td><?php echo $row['friday']; ?></td>
                                        <td><?php echo $row['saturday']; ?></td>
                                        <td><?php echo $row['sunday']; ?></td>

                                    </tr>

<?php

                            }



            ?>

<tr style=" font-weight: bold; font-size: 15px;">
                                        <td>TOTAL</td>
                                        <td><?php echo $totalCol1; ?></td>
                                        <td><?php echo $totalCol2; ?></td>
                                        <td><?php echo $totalCol3; ?></td>
                                        <td><?php echo $totalCol4; ?></td>
                                        <td><?php echo $totalCol5; ?></td>
                                        <td><?php echo $totalCol6; ?></td>
                                        <td><?php echo $totalCol7; ?></td>

                                    </tr>

        
                </tbody>
                </table>
                

                <table style="margin-top: 20px; width: 100%; text-align: center; border-collapse: collapse;">
<!-- GLORY -->
    <?php
        $Employee_list_comp_glory = "SELECT DISTINCT `emp_name` FROM emp_list WHERE employer = 'SERVICE PROVIDER' UNION SELECT DISTINCT `emp_name` FROM `tbl_trans_logs` WHERE tran_date BETWEEN '$fromDate' AND '$toDate' AND employer = 'SERVICE PROVIDER' UNION SELECT DISTINCT `lgbk_name` FROM `logbooksales` WHERE lgbk_date BETWEEN '$fromDate' AND '$toDate' AND lgbk_employer = 'SERVICE PROVIDER'";
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
            <th >SERVICE PROVIDER</th></tr>
    
                <tr>
                    <th style="width: 6%; font-weight: bold; font-size: 11px;">Employee</th>
                <?php foreach ($arrayDate as $dates_glory){ ?>
                    <th style="width: 4%; font-weight: bold; font-size: 11px;"><?php echo date('M-d',strtotime($dates_glory))?></th> <?php } ?>
                </tr>
            </thead>
            <tbody style="font-size: 10px;">

            <?php
             $totalCol1=0;
             $totalCol2=0;
             $totalCol3=0;
             $totalCol4=0;
             $totalCol5=0;
             $totalCol6=0;
             $totalCol7=0;

            $sql = "SELECT 
    emp_name,
    MAX(CASE WHEN tran_date = '$monday' THEN '35' ELSE '0' END) AS 'monday',
    MAX(CASE WHEN tran_date = '$tuesday' THEN '35' ELSE '0' END) AS 'tuesday',
    MAX(CASE WHEN tran_date = '$wednesday' THEN '35' ELSE '0' END) AS 'wednesday',
    MAX(CASE WHEN tran_date = '$thursday' THEN '35' ELSE '0' END) AS 'thursday',
    MAX(CASE WHEN tran_date = '$friday' THEN '35' ELSE '0' END) AS 'friday',
    MAX(CASE WHEN tran_date = '$saturday' THEN '35' ELSE '0' END) AS 'saturday',
    MAX(CASE WHEN tran_date = '$sunday' THEN '35' ELSE '0' END) AS 'sunday'
FROM tbl_trans_logs WHERE employer = 'SERVICE PROVIDER'
GROUP BY emp_name
ORDER BY emp_name;
";


                            $result = mysqli_query($con, $sql);



                            while ($row = mysqli_fetch_assoc($result)) {
                                $totalCol1 += (int)$row['monday'];
                                $totalCol2 += (int)$row['tuesday'];
                                $totalCol3 += (int)$row['wednesday'];
                                $totalCol4 += (int)$row['thursday'];
                                $totalCol5 += (int)$row['friday'];
                                $totalCol6 += (int)$row['saturday'];
                                $totalCol7 += (int)$row['sunday'];


                                $overAlltotalCol1 += (int)$row['monday'];
                                $overAlltotalCol2 += (int)$row['tuesday'];
                                $overAlltotalCol3 += (int)$row['wednesday'];
                                $overAlltotalCol4 += (int)$row['thursday'];
                                $overAlltotalCol5 += (int)$row['friday'];
                                $overAlltotalCol6 += (int)$row['saturday'];
                                $overAlltotalCol7 += (int)$row['sunday'];


                                    ?>
                                    <tr>
                                        <td><?php echo $row['emp_name']; ?></td>
                                        <td><?php echo $row['monday']; ?></td>
                                        <td><?php echo $row['tuesday']; ?></td>
                                        <td><?php echo $row['wednesday']; ?></td>
                                        <td><?php echo $row['thursday']; ?></td>
                                        <td><?php echo $row['friday']; ?></td>
                                        <td><?php echo $row['saturday']; ?></td>
                                        <td><?php echo $row['sunday']; ?></td>

                                    </tr>

<?php

                            }



            ?>

<tr style=" font-weight: bold; font-size: 15px;">
                                        <td>TOTAL</td>
                                        <td><?php echo $totalCol1; ?></td>
                                        <td><?php echo $totalCol2; ?></td>
                                        <td><?php echo $totalCol3; ?></td>
                                        <td><?php echo $totalCol4; ?></td>
                                        <td><?php echo $totalCol5; ?></td>
                                        <td><?php echo $totalCol6; ?></td>
                                        <td><?php echo $totalCol7; ?></td>

                                    </tr>

        
                </tbody>

                <tr style=" font-weight: bold; font-size: 20px;">
                                        <td>OVERALL</td>
                                        <td><?php echo $overAlltotalCol1; ?></td>
                                        <td><?php echo $overAlltotalCol2; ?></td>
                                        <td><?php echo $overAlltotalCol3; ?></td>
                                        <td><?php echo $overAlltotalCol4; ?></td>
                                        <td><?php echo $overAlltotalCol5; ?></td>
                                        <td><?php echo $overAlltotalCol6; ?></td>
                                        <td><?php echo $overAlltotalCol7; ?></td>

                                    </tr>
                                    <tr style=" font-weight: bold; font-size: 20px;">
                                        <td>GRAND TOTAL</td>
                                        <td><?php echo $overAlltotalCol1 +  $overAlltotalCol2 +  $overAlltotalCol3 + $overAlltotalCol4 + $overAlltotalCol5 + $overAlltotalCol6 + $overAlltotalCol7; ?></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>

                                    </tr>

                </table>


                
    </body>
</html>
