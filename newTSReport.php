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
    $days = [];
    $datesArray = [];

    foreach ($period as $date) {
        $datesArray[] = $date->format('Y-m-d');
        // echo $date->format('Y-m-d'), '<br>';
        $dayName = strtolower($date->format('l')); // 'l' returns the full day name
    
        // Add the day name to the array
        if (!in_array($dayName, $days)) {
            $days[] = $dayName;
        }
    }


    // $sqlDynamic = "SELECT emp_name, ";

    // // Loop through the days and build the CASE statements
    // $num=0;
    // foreach ($days as $day) {
    //     $sqlDynamic .= "MAX(CASE WHEN tran_date = '$datesArray[$num]' THEN '35' ELSE '0' END) AS '$day', ";
    //     $num++;
    // }
    
    // // Remove the last comma and space
    // $sqlDynamic = rtrim($sqlDynamic, ', ');
    
    // // Complete the SQLDynamic query
    // $sqlDynamic .= " FROM tbl_trans_logs WHERE employer = 'GLORY' GROUP BY emp_name ORDER BY emp_name;";
    
    // echo $sqlDynamic;




    // $monday= $datesArray[0] ;
    // $tuesday=  $datesArray[1];
    // $wednesday=  $datesArray[2];
    // $thursday=  $datesArray[3];
    // $friday=  $datesArray[4];
    // $saturday=  $datesArray[5];
    // $sunday=  $datesArray[6];


//     echo $monday , '<br>';
// echo $tuesday, '<br>';
// echo $wednesday, '<br>';
// echo $thursday, '<br>';
// echo $friday, '<br>';
// echo $saturday, '<br>';
// echo $sunday, '<br>';


// $overAlltotalCol1=0;
// $overAlltotalCol2=0;
// $overAlltotalCol3=0;
// $overAlltotalCol4=0;
// $overAlltotalCol5=0;
// $overAlltotalCol6=0;
// $overAlltotalCol7=0;

          
$overAlltotalCol = [
    'monday' => 0,
    'tuesday' => 0,
    'wednesday' => 0,
    'thursday' => 0,
    'friday' => 0,
    'saturday' => 0,
    'sunday' => 0,
];


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

             $totals = [
                'monday' => 0,
                'tuesday' => 0,
                'wednesday' => 0,
                'thursday' => 0,
                'friday' => 0,
                'saturday' => 0,
                'sunday' => 0,
            ];

  


            
             $sqlDynamic = "SELECT emp_name, ";

             // Loop through the days and build the CASE statements
             $num=0;
             foreach ($days as $day) {
                 $sqlDynamic .= "MAX(CASE WHEN tran_date = '$datesArray[$num]' THEN '35' ELSE '0' END) AS '$day', ";
                 $num++;
             }
             
             // Remove the last comma and space
             $sqlDynamic = rtrim($sqlDynamic, ', ');
             
             // Complete the SQLDynamic query
             $sqlDynamic .= " FROM tbl_trans_logs WHERE employer = 'GLORY' GROUP BY emp_name ORDER BY emp_name;";
             
             // Now you can use this $sql in your database query
            //  echo $sqlDynamic;




                            $result = mysqli_query($con, $sqlDynamic);



                            while ($row = mysqli_fetch_assoc($result)) {
                        
                                
                                foreach ($days as $day) {
                                    $totals[$day] += (int)$row[$day];
                                    $overAlltotalCol[$day] += (int)$row[$day];

                                }
                                


                                    ?>
                                    <tr>
                                        <td><?php echo $row['emp_name']; ?></td>
                                        <?php
                                        foreach ($days as $day) {
                                           ?>  <td><?php echo $row[$day]; ?></td> <?php
                                        }
                                        
                                        ?>
                                        
                                    </tr>

<?php

                            }



            ?>

<tr style=" font-weight: bold; font-size: 15px;">
                                        <td>TOTAL</td>
                                        <?php
                                        foreach ($days as $day) {
                                           ?>  <td><?php echo $overAlltotalCol[$day]; ?></td> <?php
                                        }
                                        
                                        ?>
                                    </tr>

        
                </tbody>
                </table>



                <div class="page-break"></div>


                <table style="margin-top: 20px; width: 100%; text-align: center; border-collapse: collapse;">
<!-- GLORY -->
 
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

 $totals = [
    'monday' => 0,
    'tuesday' => 0,
    'wednesday' => 0,
    'thursday' => 0,
    'friday' => 0,
    'saturday' => 0,
    'sunday' => 0,
];





 $sqlDynamic = "SELECT emp_name, ";

 // Loop through the days and build the CASE statements
 $num=0;
 foreach ($days as $day) {
     $sqlDynamic .= "MAX(CASE WHEN tran_date = '$datesArray[$num]' THEN '35' ELSE '0' END) AS '$day', ";
     $num++;
 }
 
 // Remove the last comma and space
 $sqlDynamic = rtrim($sqlDynamic, ', ');
 
 // Complete the SQLDynamic query
 $sqlDynamic .= " FROM tbl_trans_logs WHERE employer = 'MAXIM' GROUP BY emp_name ORDER BY emp_name;";
 
 // Now you can use this $sql in your database query
//  echo $sqlDynamic;




                $result = mysqli_query($con, $sqlDynamic);



                while ($row = mysqli_fetch_assoc($result)) {
            
                    
                    foreach ($days as $day) {
                        $totals[$day] += (int)$row[$day];
                        $overAlltotalCol[$day] += (int)$row[$day];

                    }
                    


                        ?>
                        <tr>
                            <td><?php echo $row['emp_name']; ?></td>
                            <?php
                            foreach ($days as $day) {
                               ?>  <td><?php echo $row[$day]; ?></td> <?php
                            }
                            
                            ?>
                            
                        </tr>

<?php

                }



?>

<tr style=" font-weight: bold; font-size: 15px;">
                            <td>TOTAL</td>
                            <?php
                            foreach ($days as $day) {
                               ?>  <td><?php echo $totals[$day]; ?></td> <?php
                            }
                            
                            ?>
                        </tr>


    </tbody>
                </table>




                <table style="margin-top: 20px; width: 100%; text-align: center; border-collapse: collapse;">
<!-- GLORY -->

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

             $totals = [
                'monday' => 0,
                'tuesday' => 0,
                'wednesday' => 0,
                'thursday' => 0,
                'friday' => 0,
                'saturday' => 0,
                'sunday' => 0,
            ];

  


            
             $sqlDynamic = "SELECT emp_name, ";

             // Loop through the days and build the CASE statements
             $num=0;
             foreach ($days as $day) {
                 $sqlDynamic .= "MAX(CASE WHEN tran_date = '$datesArray[$num]' THEN '35' ELSE '0' END) AS '$day', ";
                 $num++;
             }
             
             // Remove the last comma and space
             $sqlDynamic = rtrim($sqlDynamic, ', ');
             
             // Complete the SQLDynamic query
             $sqlDynamic .= " FROM tbl_trans_logs WHERE employer = 'POWERLANE' GROUP BY emp_name ORDER BY emp_name;";
             
             // Now you can use this $sql in your database query
            //  echo $sqlDynamic;




                            $result = mysqli_query($con, $sqlDynamic);



                            while ($row = mysqli_fetch_assoc($result)) {
                        
                                
                                foreach ($days as $day) {
                                    $totals[$day] += (int)$row[$day];
                                    $overAlltotalCol[$day] += (int)$row[$day];

                                }
                                


                                    ?>
                                    <tr>
                                        <td><?php echo $row['emp_name']; ?></td>
                                        <?php
                                        foreach ($days as $day) {
                                           ?>  <td><?php echo $row[$day]; ?></td> <?php
                                        }
                                        
                                        ?>
                                        
                                    </tr>

<?php

                            }



            ?>

<tr style=" font-weight: bold; font-size: 15px;">
                                        <td>TOTAL</td>
                                        <?php
                                        foreach ($days as $day) {
                                           ?>  <td><?php echo $totals[$day]; ?></td> <?php
                                        }
                                        
                                        ?>
                                    </tr>

        
                </tbody>
                </table>



                
                <table style="margin-top: 20px; width: 100%; text-align: center; border-collapse: collapse;">
<!-- GLORY -->
 
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

 $totals = [
    'monday' => 0,
    'tuesday' => 0,
    'wednesday' => 0,
    'thursday' => 0,
    'friday' => 0,
    'saturday' => 0,
    'sunday' => 0,
];





 $sqlDynamic = "SELECT emp_name, ";

 // Loop through the days and build the CASE statements
 $num=0;
 foreach ($days as $day) {
     $sqlDynamic .= "MAX(CASE WHEN tran_date = '$datesArray[$num]' THEN '35' ELSE '0' END) AS '$day', ";
     $num++;
 }
 
 // Remove the last comma and space
 $sqlDynamic = rtrim($sqlDynamic, ', ');
 
 // Complete the SQLDynamic query
 $sqlDynamic .= " FROM tbl_trans_logs WHERE employer = 'NIPPI' GROUP BY emp_name ORDER BY emp_name;";
 
 // Now you can use this $sql in your database query
//  echo $sqlDynamic;




                $result = mysqli_query($con, $sqlDynamic);



                while ($row = mysqli_fetch_assoc($result)) {
            
                    
                    foreach ($days as $day) {
                        $totals[$day] += (int)$row[$day];
                        $overAlltotalCol[$day] += (int)$row[$day];

                    }
                    


                        ?>
                        <tr>
                            <td><?php echo $row['emp_name']; ?></td>
                            <?php
                            foreach ($days as $day) {
                               ?>  <td><?php echo $row[$day]; ?></td> <?php
                            }
                            
                            ?>
                            
                        </tr>

<?php

                }



?>

<tr style=" font-weight: bold; font-size: 15px;">
                            <td>TOTAL</td>
                            <?php
                            foreach ($days as $day) {
                               ?>  <td><?php echo $totals[$day]; ?></td> <?php
                            }
                            
                            ?>
                        </tr>


    </tbody>
                </table>
                

                <table style="margin-top: 20px; width: 100%; text-align: center; border-collapse: collapse;">
<!-- GLORY -->

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

 $totals = [
    'monday' => 0,
    'tuesday' => 0,
    'wednesday' => 0,
    'thursday' => 0,
    'friday' => 0,
    'saturday' => 0,
    'sunday' => 0,
];





 $sqlDynamic = "SELECT emp_name, ";

 // Loop through the days and build the CASE statements
 $num=0;
 foreach ($days as $day) {
     $sqlDynamic .= "MAX(CASE WHEN tran_date = '$datesArray[$num]' THEN '35' ELSE '0' END) AS '$day', ";
     $num++;
 }
 
 // Remove the last comma and space
 $sqlDynamic = rtrim($sqlDynamic, ', ');
 
 // Complete the SQLDynamic query
 $sqlDynamic .= " FROM tbl_trans_logs WHERE employer = 'SERVICE PROVIDER' GROUP BY emp_name ORDER BY emp_name;";
 
 // Now you can use this $sql in your database query
//  echo $sqlDynamic;




                $result = mysqli_query($con, $sqlDynamic);



                while ($row = mysqli_fetch_assoc($result)) {
            
                    
                    foreach ($days as $day) {
                        $totals[$day] += (int)$row[$day];
                        $overAlltotalCol[$day] += (int)$row[$day];

                    }
                    


                        ?>
                        <tr>
                            <td><?php echo $row['emp_name']; ?></td>
                            <?php
                            foreach ($days as $day) {
                               ?>  <td><?php echo $row[$day]; ?></td> <?php
                            }
                            
                            ?>
                            
                        </tr>

<?php

                }



?>

<tr style=" font-weight: bold; font-size: 15px;">
                            <td>TOTAL</td>
                            <?php
                            foreach ($days as $day) {
                               ?>  <td><?php echo $totals[$day]; ?></td> <?php
                            }
                            
                            ?>
                        </tr>


    </tbody>

                <tr style=" font-weight: bold; font-size: 20px;">
                                        <td>OVERALL</td>
                                        <?php
                            foreach ($days as $day) {
                               ?>  <td><?php echo $overAlltotalCol[$day]; ?></td> <?php
                            }
                            
                            ?>

                                    </tr>
                                    <tr style=" font-weight: bold; font-size: 20px;">
                                        <td>GRAND TOTAL</td>
                                        <td>
                                        <?php
                                        $grandTotal = 0;
                                        foreach ($days as $day) {
                                            // echo $overAlltotalCol[$day], '<br>'; 
                                            $grandTotal += $overAlltotalCol[$day]; 
                                        }
                                        echo $grandTotal;
                            ?>
                                        </td>
                                   

                                    </tr>

                </table>


                
    </body>
</html>
