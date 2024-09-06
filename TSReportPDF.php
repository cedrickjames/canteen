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

                if($Employee_list_count_glory > 0){
                    while($Employee_fetch_glory = mysqli_fetch_assoc($Employee_list_query_glory)){
                            $Employee_list_array_glory[] = $Employee_fetch_glory;
                    }
                }
                foreach ($Employee_list_array_glory as $Employee_glory){
                    ?>
                    <tr>
                    <td style="font-size: 11px"> <?php echo $Employee_glory['emp_name']?></td>
                    <?php

    foreach ($arrayDate as $dates2){
        $Employee_list_glory = $Employee_glory['emp_name'];
        $select_transdate_glory = "SELECT `emp_name`, `tran_date` FROM `tbl_trans_logs` WHERE emp_name= '$Employee_list_glory' AND tran_date= '$dates2' UNION SELECT `lgbk_name`, `lgbk_date` FROM `logbooksales` WHERE lgbk_name= '$Employee_list_glory' AND lgbk_date= '$dates2'";
        $select_transdate_glory2 = mysqli_query($con, $select_transdate_glory);
        $select_transdate_count_glory = mysqli_num_rows($select_transdate_glory2);
        if($select_transdate_count_glory == 0){
            
            ?>
                <td style="font-size: 11px"> 0 </td> 
                <?php }else{ 
                    
                    ?>
                <td style="font-size: 11px"> 35 </td> 
                
                
    <?php 
    }
        }
        }
    ?>
    <?php
    ?>
                </tr>
                <!-- Jatot Glory -->
                <tr>
                    <td style="width: 6%; font-weight: bold; font-size: 11px;">TOTAL</td>
                    <?php
                $count_dates_glory = "SELECT transactions, sum(count_day) as total_transactions_glory from(SELECT tran_date as transactions, COUNT(1)*35 as count_day FROM tbl_trans_logs WHERE employer = 'GLORY' AND tran_date BETWEEN '$fromDate' AND '$toDate' GROUP BY tran_date UNION SELECT lgbk_date as transactions, COUNT(1)*35 as count_day FROM logbooksales WHERE lgbk_employer = 'GLORY' AND lgbk_date BETWEEN '$fromDate' AND '$toDate' GROUP BY lgbk_date) t group by transactions";
                $count_mon_que_glory = mysqli_query($con, $count_dates_glory);
                $transaction_list_array_glory = array();

                if($dateCount >= 1){
                    while($count_dates_each_glory = mysqli_fetch_assoc($count_mon_que_glory)){
                        $count_date_array_glory[] = $count_dates_each_glory;
                    }
                }
                foreach($count_date_array_glory as $dates3){ ?>
                    <td style="font-size: 11px; font-weight: bold;"> <?php echo $dates3['total_transactions_glory']?></td> <?php 
                
                if($dates3['total_transactions_glory'] = 0){ ?> 
                    <td style="font-size: 11px; font-weight: bold;">0</td> <?php } }?>
                </tr>
                </table>

                <div class="page-break"></div>

                <table style="margin-top: 20px; width: 100%; text-align: center; border-collapse: collapse;">
<!-- MAXIM -->
    <?php
        $Employee_list_comp_maxim = "SELECT DISTINCT `emp_name` FROM emp_list WHERE employer = 'MAXIM' UNION SELECT DISTINCT `emp_name` FROM `tbl_trans_logs` WHERE tran_date BETWEEN '$fromDate' AND '$toDate' AND employer = 'MAXIM' UNION SELECT DISTINCT `lgbk_name` FROM `logbooksales` WHERE lgbk_date BETWEEN '$fromDate' AND '$toDate' AND lgbk_employer = 'MAXIM'";
        $Employee_list_query_maxim = mysqli_query($con, $Employee_list_comp_maxim);
        $Employee_list_count_maxim = mysqli_num_rows($Employee_list_query_maxim);
        $Employee_list_array_maxim = array();

    ?>    
      <!-- <thead>
        
        <tr>
            <td style="width: 6%; font-weight: bold; font-size: 11px;">Maxim De Humana International Inc.</td>
        </tr>
                </thead> -->
                <thead>
                <tr>
            <th colspan="<?php echo count($arrayDate) +1;?>">Glory Philippines Inc.</th></tr>
            <tr>
            <th colspan="<?php echo count($arrayDate) +1;?>">Total Sales Report</th></tr>
            <tr>
            <th colspan="<?php echo count($arrayDate) +1;?>">For the Period <?php echo date('M-d',strtotime($fromDate));?> to <?php echo date('M-d',strtotime($toDate))?></th></tr>

            <tr>
            <th >Maxim De Humana International Inc.</th></tr>
    
              
                <tr>
                    <th style="width: 9%; font-weight: bold; font-size: 11px;">Employee</th>
                <?php foreach ($arrayDate as $dates_maxim){ ?>
                    <th style="width: 4%; font-weight: bold; font-size: 11px;"><?php echo date('M-d',strtotime($dates_maxim))?></th> <?php } ?>
                </tr>
            </thead>
            <tbody>
            
            <?php

                if($Employee_list_count_maxim > 0){
                    while($Employee_fetch_maxim = mysqli_fetch_assoc($Employee_list_query_maxim)){
                            $Employee_list_array_maxim[] = $Employee_fetch_maxim;
                    }
                }
                foreach ($Employee_list_array_maxim as $Employee_maxim){
                    ?>
                    <tr>
                    <td style="font-size: 11px"> <?php echo $Employee_maxim['emp_name']?></td>
                    <?php

    foreach ($arrayDate as $dates2){
        $Employee_list_maxim = $Employee_maxim['emp_name'];
        $select_transdate_maxim = "SELECT `emp_name`, `tran_date` FROM `tbl_trans_logs` WHERE emp_name= '$Employee_list_maxim' AND tran_date= '$dates2' UNION SELECT `lgbk_name`, `lgbk_date` FROM `logbooksales` WHERE lgbk_name= '$Employee_list_maxim' AND lgbk_date= '$dates2'";
        $select_transdate_maxim2 = mysqli_query($con, $select_transdate_maxim);
        $select_transdate_count_maxim = mysqli_num_rows($select_transdate_maxim2);
        if($select_transdate_count_maxim == 0){
            
            ?>
                <td style="font-size: 11px"> 0 </td> 
                <?php }else{ 
                    
                    ?>
                <td style="font-size: 11px"> 35 </td> 
                
                
    <?php 
    }
        }
        }
    ?>
    <?php
    ?>
                </tr>
                <!-- TOTAL maxim -->
                <tr>
                    <td style="width: 6%; font-weight: bold; font-size: 11px;">TOTAL</td>
                    <?php
                $count_dates_maxim = "SELECT transactions, sum(count_day) as total_transactions_maxim from(SELECT tran_date as transactions, COUNT(1)*35 as count_day FROM tbl_trans_logs WHERE employer = 'MAXIM' AND tran_date BETWEEN '$fromDate' AND '$toDate' GROUP BY tran_date UNION SELECT lgbk_date as transactions, COUNT(1)*35 as count_day FROM logbooksales WHERE lgbk_employer = 'MAXIM' AND lgbk_date BETWEEN '$fromDate' AND '$toDate' GROUP BY lgbk_date) t group by transactions";
                $count_mon_que_maxim = mysqli_query($con, $count_dates_maxim);
                $transaction_list_array_maxim = array();

                if($dateCount >= 1){
                    while($count_dates_each_maxim = mysqli_fetch_assoc($count_mon_que_maxim)){
                        $count_date_array_maxim[] = $count_dates_each_maxim;
                    }
                }
                foreach($count_date_array_maxim as $dates3){ ?>
                    <td style="font-size: 11px; font-weight: bold;"> <?php echo $dates3['total_transactions_maxim']?></td> <?php 
                
                if($dates3['total_transactions_maxim'] = 0){ ?> 
                    <td style="font-size: 11px; font-weight: bold;">0</td> <?php } }?>
                </tr>
                </table>

                <div class="page-break"></div>

                <table style="margin-top: 20px; width: 100%; text-align: center; border-collapse: collapse;">
<!-- POWERLANE -->
    <?php
        $Employee_list_comp_powerlane = "SELECT DISTINCT `emp_name` FROM emp_list WHERE employer = 'POWERLANE' UNION SELECT DISTINCT `emp_name` FROM `tbl_trans_logs` WHERE tran_date BETWEEN '$fromDate' AND '$toDate' AND employer = 'POWERLANE' UNION SELECT DISTINCT `lgbk_name` FROM `logbooksales` WHERE lgbk_date BETWEEN '$fromDate' AND '$toDate' AND lgbk_employer = 'POWERLANE'";
        $Employee_list_query_powerlane = mysqli_query($con, $Employee_list_comp_powerlane);
        $Employee_list_count_powerlane = mysqli_num_rows($Employee_list_query_powerlane);
        $Employee_list_array_powerlane = array();

    ?>    
      <!-- <thead>
        <tr>
            <td style="width: 6%; font-weight: bold; font-size: 11px;">Powerlane</td>
        </tr>
            </thead> -->
            <thead>
            <tr>
            <th colspan="<?php echo count($arrayDate) +1;?>">Glory Philippines Inc.</th></tr>
            <tr>
            <th colspan="<?php echo count($arrayDate) +1;?>">Total Sales Report</th></tr>
            <tr>
            <th colspan="<?php echo count($arrayDate) +1;?>">For the Period <?php echo date('M-d',strtotime($fromDate));?> to <?php echo date('M-d',strtotime($toDate))?></th></tr>

            <tr>
            <th >Powerlane</th></tr>
    
              
                <tr>
                    <th style="width: 9%; font-weight: bold; font-size: 11px;">Employee</th>
                <?php foreach ($arrayDate as $dates_powerlane){ ?>
                    <th style="width: 4%; font-weight: bold; font-size: 11px;"><?php echo date('M-d',strtotime($dates_powerlane))?></th> <?php } ?>
                </tr>
            </thead>
            <tbody>
            
            <?php

                if($Employee_list_count_powerlane > 0){
                    while($Employee_fetch_powerlane = mysqli_fetch_assoc($Employee_list_query_powerlane)){
                            $Employee_list_array_powerlane[] = $Employee_fetch_powerlane;
                    }
                }
                foreach ($Employee_list_array_powerlane as $Employee_powerlane){
                    ?>
                    <tr>
                    <td style="font-size: 11px"> <?php echo $Employee_powerlane['emp_name']?></td>
                    <?php

    foreach ($arrayDate as $dates2){
        $Employee_list_powerlane = $Employee_powerlane['emp_name'];
        $select_transdate_powerlane = "SELECT `emp_name`, `tran_date` FROM `tbl_trans_logs` WHERE emp_name= '$Employee_list_powerlane' AND tran_date= '$dates2' UNION SELECT `lgbk_name`, `lgbk_date` FROM `logbooksales` WHERE lgbk_name= '$Employee_list_powerlane' AND lgbk_date= '$dates2'";
        $select_transdate_powerlane2 = mysqli_query($con, $select_transdate_powerlane);
        $select_transdate_count_powerlane = mysqli_num_rows($select_transdate_powerlane2);
        if($select_transdate_count_powerlane == 0){
            ?>
                <td style="font-size: 11px"> 0 </td> 
                <?php }else{ 
                    
                    ?>
                <td style="font-size: 11px">35 </td> 
                
                
    <?php 
    }
        }
        }
    ?>
    <?php
    ?>
                </tr>
                <!-- Jatot Powerlane -->
                <tr>
                    <td style="width: 6%; font-weight: bold; font-size: 11px;">TOTAL</td>
                    <?php
                $count_dates_powerlane = "SELECT transactions, sum(count_day) as total_transactions_powerlane from(SELECT tran_date as transactions, COUNT(1)*35 as count_day FROM tbl_trans_logs WHERE employer = 'POWERLANE' AND tran_date BETWEEN '$fromDate' AND '$toDate' GROUP BY tran_date UNION SELECT lgbk_date as transactions, COUNT(1)*35 as count_day FROM logbooksales WHERE lgbk_employer = 'POWERLANE' AND lgbk_date BETWEEN '$fromDate' AND '$toDate' GROUP BY lgbk_date) t group by transactions";
                $count_mon_que_powerlane = mysqli_query($con, $count_dates_powerlane);
                $transaction_list_array_powerlane = array();

                if($dateCount >= 1){
                    while($count_dates_each_powerlane = mysqli_fetch_assoc($count_mon_que_powerlane)){
                        $count_date_array_powerlane[] = $count_dates_each_powerlane;
                    }
                }
                foreach($count_date_array_powerlane as $dates3){ ?>
                    <td style="font-size: 11px; font-weight: bold;"> <?php echo $dates3['total_transactions_powerlane']?></td> <?php 
                
                if($dates3['total_transactions_powerlane'] = 0){ ?> 
                    <td style="font-size: 11px; font-weight: bold;">0</td> <?php } }?>
                </tr>
                </table>


                <div class="page-break"></div>


                <table style="margin-top: 20px; width: 100%; text-align: center; border-collapse: collapse;">
<!-- NIPPI -->
    <?php
        $Employee_list_comp_nippi = "SELECT DISTINCT `emp_name` FROM emp_list WHERE employer = 'NIPPI' UNION SELECT DISTINCT `emp_name` FROM `tbl_trans_logs` WHERE tran_date BETWEEN '$fromDate' AND '$toDate' AND employer = 'NIPPI' UNION SELECT DISTINCT `lgbk_name` FROM `logbooksales` WHERE lgbk_date BETWEEN '$fromDate' AND '$toDate' AND lgbk_employer = 'NIPPI'";
        $Employee_list_query_nippi = mysqli_query($con, $Employee_list_comp_nippi);
        $Employee_list_count_nippi = mysqli_num_rows($Employee_list_query_nippi);
        $Employee_list_array_nippi = array();

    ?>     
     <!-- <thead>
        <tr>
            <td style="width: 6%; font-weight: bold; font-size: 11px;">NIPPI</td>
        </tr>
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
                    <th style="width: 9%; font-weight: bold; font-size: 11px;">Employee</th>
                <?php foreach ($arrayDate as $dates_nippi){ ?>
                    <th style="width: 4%; font-weight: bold; font-size: 11px;"><?php echo date('M-d',strtotime($dates_nippi))?></th> <?php } ?>
                </tr>
            </thead>
            <tbody>
            
            <?php

                if($Employee_list_count_nippi > 0){
                    while($Employee_fetch_nippi = mysqli_fetch_assoc($Employee_list_query_nippi)){
                            $Employee_list_array_nippi[] = $Employee_fetch_nippi;
                    }
                }
                foreach ($Employee_list_array_nippi as $Employee_nippi){
                    ?>
                    <tr>
                    <td style="font-size: 11px"> <?php echo $Employee_nippi['emp_name']?></td>
                    <?php

    foreach ($arrayDate as $dates2){
        $Employee_list_nippi = $Employee_nippi['emp_name'];
        $select_transdate_nippi = "SELECT `emp_name`, `tran_date` FROM `tbl_trans_logs` WHERE emp_name = '$Employee_list_nippi' AND tran_date= '$dates2' UNION SELECT `lgbk_name`, `lgbk_date` FROM `logbooksales` WHERE lgbk_name= '$Employee_list_nippi' AND lgbk_date= '$dates2'";
        $select_transdate_nippi2 = mysqli_query($con, $select_transdate_nippi);
        $select_transdate_count_nippi = mysqli_num_rows($select_transdate_nippi2);
        if($select_transdate_count_nippi == 0){
            
            ?>
                <td style="font-size: 11px"> 0 </td> 
                <?php }else{ 
                    
                    ?>
                <td style="font-size: 11px"> 35 </td> 
                
                
    <?php 
    }
        }
        }
    ?>
    <?php
    ?>
                </tr>
                <!-- Jatot Nippi -->
                <tr>
                    <td style="width: 6%; font-weight: bold; font-size: 11px;">TOTAL</td>
                    <?php
                $count_dates_nippi = "SELECT transactions, sum(count_day) as total_transactions_nippi from(SELECT tran_date as transactions, COUNT(1)*35 as count_day FROM tbl_trans_logs WHERE employer = 'NIPPI' AND tran_date BETWEEN '$fromDate' AND '$toDate' GROUP BY tran_date UNION SELECT lgbk_date as transactions, COUNT(1)*35 as count_day FROM logbooksales WHERE lgbk_employer = 'NIPPI' AND lgbk_date BETWEEN '$fromDate' AND '$toDate' GROUP BY lgbk_date) t group by transactions";
                $count_mon_que_nippi = mysqli_query($con, $count_dates_nippi);
                $transaction_list_array_nippi = array();

                if($dateCount >= 1){
                    while($count_dates_each_nippi = mysqli_fetch_assoc($count_mon_que_nippi)){
                        $count_date_array_nippi[] = $count_dates_each_nippi;
                    }
                }
                foreach($count_date_array_nippi as $dates3){ ?>
                    <td style="font-size: 11px; font-weight: bold;"> <?php echo $dates3['total_transactions_nippi']?></td> <?php 
                
                if($dates3['total_transactions_nippi'] = 0){ ?> 
                    <td style="font-size: 11px; font-weight: bold;">0</td> <?php } }?>
                </tr>
                </table>

                <div class="page-break"></div>

                
                <table style="margin-top: 20px; width: 100%; text-align: center; border-collapse: collapse;">
<!-- SERVICE PROVIDER -->
    <?php
            $Employee_list_comp_service_provider = "SELECT DISTINCT `emp_name` FROM emp_list WHERE employer = 'SERVICE PROVIDER' UNION SELECT DISTINCT `emp_name` FROM `tbl_trans_logs` WHERE tran_date BETWEEN '$fromDate' AND '$toDate' AND employer = 'SERVICE PROVIDER' UNION SELECT DISTINCT `lgbk_name` FROM `logbooksales` WHERE lgbk_date BETWEEN '$fromDate' AND '$toDate' AND lgbk_employer = 'SERVICE PROVIDER'";
            $Employee_list_query_service_provider = mysqli_query($con, $Employee_list_comp_service_provider);
            $Employee_list_count_service_provider = mysqli_num_rows($Employee_list_query_service_provider);
            $Employee_list_array_service_provider = array();

        ?>   
           <!-- <thead>
        <tr>
            <td style="width: 6%; font-weight: bold; font-size: 11px;">Service Provider</td>
        </tr>
                </thead> -->
                <thead>

                <tr>
            <th colspan="<?php echo count($arrayDate) +1;?>">Glory Philippines Inc.</th></tr>
            <tr>
            <th colspan="<?php echo count($arrayDate) +1;?>">Total Sales Report</th></tr>
            <tr>
            <th colspan="<?php echo count($arrayDate) +1;?>">For the Period <?php echo date('M-d',strtotime($fromDate));?> to <?php echo date('M-d',strtotime($toDate))?></th></tr>

            <tr>
            <th >Service Provider</th></tr>

                    <tr>
                        <th style="width: 9%; font-weight: bold; font-size: 11px;">Employee</th>
                    <?php foreach ($arrayDate as $dates_service_provider){ ?>
                        <th style="width: 4%; font-weight: bold; font-size: 11px;"><?php echo date('M-d',strtotime($dates_service_provider))?></th> <?php } ?>
                    </tr>
                </thead>
                <tbody>
                
                <?php

                    if($Employee_list_count_service_provider > 0){
                        while($Employee_fetch_service_provider = mysqli_fetch_assoc($Employee_list_query_service_provider)){
                                $Employee_list_array_service_provider[] = $Employee_fetch_service_provider;
                        }
                    }
                    foreach ($Employee_list_array_service_provider as $Employee_service_provider){
                        ?>
                        <tr>
                        <td style="font-size: 11px"> <?php echo $Employee_service_provider['emp_name']?></td>
                        <?php

        foreach ($arrayDate as $dates2){
            $Employee_list_service_provider = $Employee_service_provider['emp_name'];
            $select_transdate_service_provider = "SELECT `emp_name`, `tran_date` FROM `tbl_trans_logs` WHERE emp_name = '$Employee_list_service_provider' AND tran_date= '$dates2' UNION SELECT `lgbk_name`, `lgbk_date` FROM `logbooksales` WHERE lgbk_name= '$Employee_list_service_provider' AND lgbk_date= '$dates2'";
            $select_transdate_service_provider2 = mysqli_query($con, $select_transdate_service_provider);
            $select_transdate_count_service_provider = mysqli_num_rows($select_transdate_service_provider2);
            if($select_transdate_count_service_provider == 0){
                
                ?>
                    <td style="font-size: 11px"> 0 </td> 
                    <?php }else{ 
                        
                        ?>
                    <td style="font-size: 11px"> 35 </td> 
                    
                    
        <?php 
        }
            }
            }
        ?>
        <?php
        ?>
                    </tr>
                    <!-- Jatot Service Provider -->
                    <tr>
                        <td style="width: 6%; font-weight: bold; font-size: 11px;">TOTAL</td>
                        <?php
                    $count_dates_service_provider = "SELECT transactions, sum(count_day) as total_transactions_service_provider from(SELECT tran_date as transactions, COUNT(1)*35 as count_day FROM tbl_trans_logs WHERE employer = 'SERVICE PROVIDER' AND tran_date BETWEEN '$fromDate' AND '$toDate' GROUP BY tran_date UNION SELECT lgbk_date as transactions, COUNT(1)*35 as count_day FROM logbooksales WHERE lgbk_employer = 'SERVICE PROVIDER' AND lgbk_date BETWEEN '$fromDate' AND '$toDate' GROUP BY lgbk_date) t group by transactions";
                    $count_mon_que_service_provider = mysqli_query($con, $count_dates_service_provider);
                    $transaction_list_array_service_provider = array();

                    if($dateCount >= 1){
                        while($count_dates_each_service_provider = mysqli_fetch_assoc($count_mon_que_service_provider)){
                            $count_date_array_service_provider[] = $count_dates_each_service_provider;
                        }
                    }
                    foreach($count_date_array_service_provider as $dates3){ ?>
                        <td style="font-size: 11px; font-weight: bold;"> <?php echo $dates3['total_transactions_service_provider']?></td> <?php 
                    
                    if($dates3['total_transactions_service_provider'] = 0){ ?> 
                        <td style="font-size: 11px; font-weight: bold;">0</td> <?php } }?>
                    </tr>

<!-- Overall -->

                <!-- Jatot Daily -->
                    <tr>
                        <td style="width: 6%; font-weight: bold; font-size: 11px;">DAILY</td>
                        <?php
                    $count_dates_overall = "SELECT transactions, sum(count_day) as total_transactions_overall from(SELECT tran_date as transactions, COUNT(1)*35 as count_day FROM tbl_trans_logs WHERE tran_date BETWEEN '$fromDate' AND '$toDate' GROUP BY tran_date UNION SELECT lgbk_date as transactions, COUNT(1)*35 as count_day FROM logbooksales WHERE lgbk_date BETWEEN '$fromDate' AND '$toDate' GROUP BY lgbk_date) t group by transactions";
                    $count_mon_que_overall = mysqli_query($con, $count_dates_overall);

                        while($count_dates_each_overall = mysqli_fetch_assoc($count_mon_que_overall)){
                            $count_date_array_overall[] = $count_dates_each_overall;
                        }
                    foreach($count_date_array_overall as $dates123){ ?>
                        <td style="font-size: 11px; font-weight: bold;"> <?php echo $dates123['total_transactions_overall']?></td> <?php 
                    
                    if($dates123['total_transactions_overall'] = 0){ ?> 
                        <td style="font-size: 11px; font-weight: bold;">0</td> <?php } }?>
                    </tr>

<!-- Overall -->

                <!-- Jatot Overall -->
                <tr>
                        <td style="width: 6%; font-weight: bold; font-size: 11px;">Overall</td>
                        <?php
                    $count_dates_jatot = "SELECT SUM(jatot) as total FROM (select COUNT(tran_date)*35 as jatot from tbl_trans_logs where tran_date BETWEEN '$fromDate' AND '$toDate' UNION SELECT COUNT(lgbk_date)*35 as jatot FROM logbooksales WHERE lgbk_date BETWEEN '$fromDate' AND '$toDate') t group by NULL";
                    $resultSP = mysqli_query($con, $count_dates_jatot);
                    // $resultSP = mysqli_query($con, $querySP);

                    $countSP = mysqli_num_rows($resultSP);
                    $amountSP = $countSP * 35.00;
                    $totalSP += $amountSP;
                    $count_mon_que_jatot = mysqli_query($con, $count_dates_jatot);

                        while($count_dates_each_jatot = mysqli_fetch_assoc($count_mon_que_jatot)){
                            $count_date_array_jatot[] = $count_dates_each_jatot;
                        }
                    foreach($count_date_array_jatot as $dates13){ ?>
                        <td style="font-size: 11px; font-weight: bold;"> <?php echo $dates13['total']?></td> <?php } ?>
                    </tr>
        </tbody>
        </table>
    </body>
</html>
