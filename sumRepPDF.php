<?php
    session_start();

    require './dompdf/vendor/autoload.php';

    include("./connection.php");

    use Dompdf\Dompdf;

$x = $_SESSION['period'];
$y = $_SESSION['dateCount'] - 1;
$fromDate = date_create($_SESSION['period'][0]);
$toDate = date_create($_SESSION['period'][$y]);

$grandOverallTotal  = 0;
$html = '   <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Summary Report</title>
            </head>
            <body>
                <div>
                    <h5 style="margin: 0; padding: 0;">Glory (Philippines), Inc.</h5>
                    <h5 style="margin: 0; padding: 0;">Payment to Canteen for GPI Employee Meal Subsidy</h5>
                    <h5 style="margin: 0; padding: 0;">Period: '.date_format($fromDate,"F d, Y").' - '.date_format($toDate,"F d, Y").'</h5>
                </div>
                <table style="margin-top: 20px; width: 100%; text-align: center; border-collapse: collapse;">
                    <thead>
                        <tr style="font-size: 11px; border: 1px solid black; font-size: 12px;">
                            <th rowspan="2" style="width: 12.5%; border: 1px solid black;">Date</th>
                            <th colspan="6" style="border: 1px solid black; font-size: 12px;">ACTUAL PAYMENT</th>
                            <th rowspan="2" style="width: 12.5%; border: 1px solid black; font-size: 12px;">TOTAL MANPOWER</th>
                        </tr>
                        <tr style="font-size: 12px; border: 1px solid black;">
                            <th style="border: 1px solid black;">GPI</th>
                            <th style="border: 1px solid black;">MAXIM</th>
                            <th style="border: 1px solid black;">NIPPI</th>
                            <th style="border: 1px solid black;">POWERLANE</th>
                            <th style="border: 1px solid black;">SERVICE PROVIDER</th>
                            <th style="border: 1px solid black;">TOTAL PAYMENT</th>
                        </tr>
                    </thead>
                    <tbody>';

                    setlocale(LC_MONETARY,"en_US");

                    $totalManpower = 0;

                    $totalGlory = 0.00;
                    $totalMaxim = 0.00;
                    $totalNippi = 0.00;
                    $totalPL = 0.00;
                    $totalSP = 0.00;

                    $amountGlory = 0.00;
                    $amountMaxim = 0.00;
                    $amountNippi = 0.00;
                    $amountPL = 0.00;
                    $amountSP = 0.00;

                    for($d = 0; $d <= $y; $d++){
                        $qDate = $_SESSION['period'][$d];
                        $pDate = date_create($qDate);

                        $queryGlory = "SELECT `tran_date`, `emp_name`, `employer` FROM `tbl_trans_logs` WHERE employer = 'GLORY' AND gpi8=0 AND tran_date = '$qDate' ";
                        $resultGlory = mysqli_query($con, $queryGlory);
                        $countGlory = mysqli_num_rows($resultGlory);
                        $amountGlory = $countGlory * 35.00;
                        $totalGlory += $amountGlory;
                        
                        $queryMaxim = "SELECT `tran_date`, `emp_name`, `employer` FROM `tbl_trans_logs` WHERE employer = 'MAXIM' AND gpi8=0 AND tran_date = '$qDate' ";
                        $resultMaxim = mysqli_query($con, $queryMaxim);
                        $countMaxim = mysqli_num_rows($resultMaxim);
                        $amountMaxim = $countMaxim * 35.00;
                        $totalMaxim += $amountMaxim;
                        
                        $queryNippi = "SELECT `tran_date`, `emp_name`, `employer` FROM `tbl_trans_logs` WHERE employer = 'NIPPI' AND gpi8=0 AND tran_date = '$qDate' ";
                        $resultNippi = mysqli_query($con, $queryNippi);
                        $countNippi = mysqli_num_rows($resultNippi);
                        $amountNippi = $countNippi * 35.00;
                        $totalNippi += $amountNippi;
                        
                        $queryPL = "SELECT `tran_date`, `emp_name`, `employer` FROM `tbl_trans_logs` WHERE employer = 'POWERLANE'  AND gpi8=0 AND tran_date = '$qDate' ";
                        $resultPL = mysqli_query($con, $queryPL);
                        $countPL = mysqli_num_rows($resultPL);
                        $amountPL = $countPL * 35.00;
                        $totalPL += $amountPL;
                        
                        $querySP = "SELECT `tran_date`, `emp_name`, `employer` FROM `tbl_trans_logs` WHERE employer = 'SERVICE PROVIDER' AND gpi8=0 AND tran_date = '$qDate' ";
                        $resultSP = mysqli_query($con, $querySP);
                        $countSP = mysqli_num_rows($resultSP);
                        $amountSP = $countSP * 35.00;
                        $totalSP += $amountSP;

                        $totalPayment = $amountGlory + $amountMaxim + $amountNippi + $amountPL + $amountSP;

                        $totalManpower += $totalPayment;
                       

$html .= '              <tr style="font-size: 12px; border: 1px solid black;">
                            <td style="width: 12.5%; border: 1px solid black; line-height: 23px;">'.date_format($pDate,"F d, Y").'</td>
                            <td style="width: 12.5%; border: 1px solid black; line-height: 23px;">'.number_format($amountGlory, 2, '.', ',').'</td>
                            <td style="width: 12.5%; border: 1px solid black; line-height: 23px;">'.number_format($amountMaxim, 2, '.', ',').'</td>
                            <td style="width: 12.5%; border: 1px solid black; line-height: 23px;">'.number_format($amountNippi, 2, '.', ',').'</td>
                            <td style="width: 12.5%; border: 1px solid black; line-height: 23px;">'.number_format($amountPL, 2, '.', ',').'</td>
                            <td style="width: 12.5%; border: 1px solid black; line-height: 23px;">'.number_format($amountSP, 2, '.', ',').'</td>
                            <td style="width: 12.5%; border: 1px solid black; line-height: 23px;">'.number_format($totalPayment, 2, '.', ',').'</td>
                            <td style="width: 12.5%; border: 1px solid black; line-height: 23px;">'.($totalPayment/35).'</td>
                        </tr>';
                    }  
                    $grandOverallTotal  += $totalManpower;
$html .= '              <tr style="font-size: 12px; border: 1px solid black;">
                            <td style="border: 1px solid black; line-height: 23px;">-</td>
                            <td style="border: 1px solid black; line-height: 23px;">-</td>
                            <td style="border: 1px solid black; line-height: 23px;">-</td>
                            <td style="border: 1px solid black; line-height: 23px;">-</td>
                            <td style="border: 1px solid black; line-height: 23px;">-</td>
                            <td style="border: 1px solid black; line-height: 23px;">-</td>
                            <td style="border: 1px solid black; line-height: 23px;">-</td>
                            <td style="border: 1px solid black; line-height: 23px;">-</td>
                        </tr>

                        <tr style="font-size: 12px; border: 1px solid black;">
                            <td style="border: 1px solid black; line-height: 23px;">Total</td>
                            <td style="border: 1px solid black; line-height: 23px;">'.number_format($totalGlory, 2, '.', ',').'</td>
                            <td style="border: 1px solid black; line-height: 23px;">'.number_format($totalMaxim, 2, '.', ',').'</td>
                            <td style="border: 1px solid black; line-height: 23px;">'.number_format($totalNippi, 2, '.', ',').'</td>
                            <td style="border: 1px solid black; line-height: 23px;">'.number_format($totalPL, 2, '.', ',').'</td>
                            <td style="border: 1px solid black; line-height: 23px;">'.number_format($totalSP, 2, '.', ',').'</td>
                            <td style="border: 1px solid black; line-height: 23px;">'.number_format($totalManpower, 2, '.', ',').'</td>
                            <td style="border: 1px solid black; line-height: 23px;">'.($totalManpower/35).'</td>
                        </tr>

                        
                        </tbody>
                    </table>

                    <table style="margin-top: 20px; width: 100%; text-align: center; border-collapse: collapse;">
                    <thead>
                        <tr style="font-size: 11px; border: 1px solid black; font-size: 12px;">
                            <th rowspan="2" style="width: 20.5%; border: 1px solid black;">Date</th>
                            <th colspan="8" style="border: 1px solid black; font-size: 12px;">GPI 8 (Direct)</th>
                            <th rowspan="2" style="width: 12.5%; border: 1px solid black; font-size: 12px;">TOTAL</th>

                            <th rowspan="2" style="width: 12.5%; border: 1px solid black; font-size: 12px;">TOTAL MANPOWER</th>

                        </tr>
                        <tr style="font-size: 12px; border: 1px solid black;">
                            <th style="border: 1px solid black;">Moulding</th>
                            <th style="border: 1px solid black;">Maintenance</th>
                            <th style="border: 1px solid black;">Fabrication</th>
                            <th style="border: 1px solid black;">Admin/QA/ICT</th>
                            <th style="border: 1px solid black;">Prod Support</th>
                            <th style="border: 1px solid black;">Parts Inspection</th>
                            <th style="border: 1px solid black;">Warehouse</th>
                            <th style="border: 1px solid black;">Others</th>


                        </tr>
                    </thead>
                    <tbody>';

                    setlocale(LC_MONETARY,"en_US");

                    $totalManpower = 0;
                
                    $totalMoulding = 0.00;
                    $totalMaintenance = 0.00;
                    $totalFabrication = 0.00;
                    $totalQAdmin = 0.00;
                    $totalProdsupport = 0.00;
                    $totalPI = 0.00;
                    $totalWarehouse = 0.00;
                    $totalOthers = 0.00;



                    $amountMoulding = 0.00;
                    $amountMaintenance = 0.00;
                    $amountFabrication = 0.00;
                    $amountQAdmin = 0.00;
                    $amountProdsupport = 0.00;
                    $amountWarehouse = 0.00;
                    $amountOthers = 0.00;



                    for($d = 0; $d <= $y; $d++){
                        $qDate = $_SESSION['period'][$d];
                        $pDate = date_create($qDate);
 
                        $queryMoulding = "SELECT `tran_date`, `emp_name`, `employer` FROM `tbl_trans_logs` WHERE employer = 'GLORY' AND section = 'Moulding' AND gpi8=1 AND tran_date = '$qDate' ";
                        $resultMoulding = mysqli_query($con, $queryMoulding);
                        $countMoulding = mysqli_num_rows($resultMoulding);
                        $amountMoulding = $countMoulding * 35.00;
                        $totalMoulding += $amountMoulding;

                        
                        
                        $queryMaintenance = "SELECT `tran_date`, `emp_name`, `employer` FROM `tbl_trans_logs` WHERE employer = 'GLORY' AND section = 'Maintenance' AND gpi8=1 AND tran_date = '$qDate' ";
                        $resultMaintenance = mysqli_query($con, $queryMaintenance);
                        $countMaintenance = mysqli_num_rows($resultMaintenance);
                        $amountMaintenance = $countMaintenance * 35.00;
                        $totalMaintenance += $amountMaintenance;
                        
                        $queryFabrication = "SELECT `tran_date`, `emp_name`, `employer` FROM `tbl_trans_logs` WHERE employer = 'GLORY' AND section = 'Fabrication' AND gpi8=1 AND tran_date = '$qDate' ";
                        $resultFabrication = mysqli_query($con, $queryFabrication);
                        $countFabrication = mysqli_num_rows($resultFabrication);
                        $amountFabrication = $countFabrication * 35.00;
                        $totalFabrication += $amountFabrication;
                        
                        $queryQAdmin = "SELECT `tran_date`, `emp_name`, `employer` FROM `tbl_trans_logs` WHERE employer = 'GLORY' AND (department = 'QA' OR department = 'Administration' OR department = 'ICT') AND gpi8=1 AND tran_date = '$qDate'";
                        $resultQAdmin = mysqli_query($con, $queryQAdmin);
                        $countQAdmin = mysqli_num_rows($resultQAdmin);
                        $amountQAdmin = $countQAdmin * 35.00;
                        $totalQAdmin += $amountQAdmin;
                        
                        $queryProdsupport = "SELECT `tran_date`, `emp_name`, `employer` FROM `tbl_trans_logs` WHERE employer = 'GLORY' AND section = 'Production Support' AND gpi8=1 AND tran_date = '$qDate' ";
                        $resultProdsupport = mysqli_query($con, $queryProdsupport);
                        $countProdsupport = mysqli_num_rows($resultProdsupport);
                        $amountProdsupport = $countProdsupport * 35.00;
                        $totalProdsupport += $amountProdsupport;
                             
                        $queryPI = "SELECT `tran_date`, `emp_name`, `employer` FROM `tbl_trans_logs` WHERE employer = 'GLORY' AND department = 'Parts Inspection' AND gpi8=1 AND tran_date = '$qDate' ";
                        $resultPI = mysqli_query($con, $queryPI);
                        $countPI = mysqli_num_rows($resultPI);
                        $amountPI = $countPI * 35.00;
                        $totalPI += $amountPI;

                        $queryWarehouse = "SELECT `tran_date`, `emp_name`, `employer` FROM `tbl_trans_logs` WHERE employer = 'GLORY' AND section = 'Warehouse' AND gpi8=1 AND tran_date = '$qDate' ";
                        $resultWarehouse = mysqli_query($con, $queryWarehouse);
                        $countWarehouse = mysqli_num_rows($resultWarehouse);
                        $amountWarehouse = $countWarehouse * 35.00;
                        $totalWarehouse += $amountWarehouse;

                        $queryOthers = "SELECT `tran_date`, `emp_name`, `employer` FROM `tbl_trans_logs` WHERE employer = 'GLORY' AND `gpi8` = '1' AND (`department` != 'Parts Inspection' AND `department` != 'QA' AND `department` != 'ICT') AND `section` = '' AND tran_date = '$qDate' ";
                        $resultOthers = mysqli_query($con, $queryOthers);
                        $countOthers = mysqli_num_rows($resultOthers);
                        $amountOthers = $countOthers * 35.00;
                        $totalOthers += $amountOthers;


                        $totalPayment =$amountOthers+ $amountWarehouse + $amountMoulding + $amountMaintenance + $amountFabrication + $amountQAdmin + $amountProdsupport + $amountPI;

                        $totalManpower += $totalPayment;


$html .= '              <tr style="font-size: 12px; border: 1px solid black;">
                            <td style="width: 12.5%; border: 1px solid black; line-height: 23px;">'.date_format($pDate,"F d, Y").'</td>
                            <td style="width: 12.5%; border: 1px solid black; line-height: 23px;">'.number_format($amountMoulding, 2, '.', ',').'</td>
                            <td style="width: 12.5%; border: 1px solid black; line-height: 23px;">'.number_format($amountMaintenance, 2, '.', ',').'</td>
                            <td style="width: 12.5%; border: 1px solid black; line-height: 23px;">'.number_format($amountFabrication, 2, '.', ',').'</td>
                            <td style="width: 12.5%; border: 1px solid black; line-height: 23px;">'.number_format($amountQAdmin, 2, '.', ',').'</td>
                            <td style="width: 12.5%; border: 1px solid black; line-height: 23px;">'.number_format($amountProdsupport, 2, '.', ',').'</td>
                            <td style="width: 12.5%; border: 1px solid black; line-height: 23px;">'.number_format($amountPI, 2, '.', ',').'</td>
                            <td style="width: 12.5%; border: 1px solid black; line-height: 23px;">'.number_format($amountWarehouse, 2, '.', ',').'</td>
                            <td style="width: 12.5%; border: 1px solid black; line-height: 23px;">'.number_format($amountOthers, 2, '.', ',').'</td>

                            <td style="width: 12.5%; border: 1px solid black; line-height: 23px;">'.number_format($totalPayment, 2, '.', ',').'</td>
                            <td style="width: 12.5%; border: 1px solid black; line-height: 23px;">'.($totalPayment/35).'</td>


                        </tr>';
                    }  
                    $grandOverallTotal  += $totalManpower;

$html .= '              <tr style="font-size: 12px; border: 1px solid black;">
                            <td style="border: 1px solid black; line-height: 23px;">-</td>
                            <td style="border: 1px solid black; line-height: 23px;">-</td>
                            <td style="border: 1px solid black; line-height: 23px;">-</td>
                            <td style="border: 1px solid black; line-height: 23px;">-</td>
                            <td style="border: 1px solid black; line-height: 23px;">-</td>
                            <td style="border: 1px solid black; line-height: 23px;">-</td>
                            <td style="border: 1px solid black; line-height: 23px;">-</td>
                            <td style="border: 1px solid black; line-height: 23px;">-</td>
                            <td style="border: 1px solid black; line-height: 23px;">-</td>
                            <td style="border: 1px solid black; line-height: 23px;">-</td>
                            <td style="border: 1px solid black; line-height: 23px;">-</td>

                            

                        </tr>

                        <tr style="font-size: 12px; border: 1px solid black;">
                            <td style="border: 1px solid black; line-height: 23px;">Total</td>
                            <td style="border: 1px solid black; line-height: 23px;">'.number_format($totalMoulding, 2, '.', ',').'</td>
                            <td style="border: 1px solid black; line-height: 23px;">'.number_format($totalMaintenance, 2, '.', ',').'</td>
                            <td style="border: 1px solid black; line-height: 23px;">'.number_format($totalFabrication, 2, '.', ',').'</td>
                            <td style="border: 1px solid black; line-height: 23px;">'.number_format($totalQAdmin, 2, '.', ',').'</td>
                            <td style="border: 1px solid black; line-height: 23px;">'.number_format($totalProdsupport, 2, '.', ',').'</td>
                            <td style="border: 1px solid black; line-height: 23px;">'.number_format($totalPI, 2, '.', ',').'</td>
                            <td style="border: 1px solid black; line-height: 23px;">'.number_format($totalWarehouse, 2, '.', ',').'</td>
                            <td style="border: 1px solid black; line-height: 23px;">'.number_format($totalOthers, 2, '.', ',').'</td>

                           
                            <td style="border: 1px solid black; line-height: 23px;">'.number_format($totalManpower, 2, '.', ',').'</td>
                             <td style="border: 1px solid black; line-height: 23px;">'.($totalManpower/35).'</td>

                        </tr>

                        </tbody>
                    </table>
<div style="page-break-before: always; "></div>
                    <table style="margin-top: 20px; width: 100%; text-align: center; border-collapse: collapse;">
                    <thead>
                        <tr style="font-size: 11px; border: 1px solid black; font-size: 12px;">
                            <th rowspan="2" style="width: 20.5%; border: 1px solid black;">Date</th>
                            <th colspan="8" style="border: 1px solid black; font-size: 12px;">GPI 8 (Agency)</th>
                            <th rowspan="2" style="width: 12.5%; border: 1px solid black; font-size: 12px;">TOTAL</th>
                          <th rowspan="2" style="width: 12.5%; border: 1px solid black; font-size: 12px;">TOTAL MANPOWER</th>
                        </tr>
                        <tr style="font-size: 12px; border: 1px solid black;">
                            <th style="border: 1px solid black;">Moulding</th>
                            <th style="border: 1px solid black;">Maintenance</th>
                            <th style="border: 1px solid black;">Fabrication</th>
                            <th style="border: 1px solid black;">Admin/QA/ICT</th>
                            <th style="border: 1px solid black;">Prod Support</th>
                            <th style="border: 1px solid black;">Parts Inspection</th>
                            <th style="border: 1px solid black;">Warehouse</th>
                            <th style="border: 1px solid black;">Others</th>


                        </tr>
                    </thead>
                    <tbody>';

                    setlocale(LC_MONETARY,"en_US");

                    $totalManpower = 0;
                
                    $totalMoulding = 0.00;
                    $totalMaintenance = 0.00;
                    $totalFabrication = 0.00;
                    $totalQAdmin = 0.00;
                    $totalProdsupport = 0.00;
                    $totalPI = 0.00;
                    $totalWarehouse = 0.00;
                    $totalOthers = 0.00;



                    $amountMoulding = 0.00;
                    $amountMaintenance = 0.00;
                    $amountFabrication = 0.00;
                    $amountQAdmin = 0.00;
                    $amountProdsupport = 0.00;
                    $amountPI = 0.00;
                    $amountWarehouse = 0.00;
                    $amountOthers = 0.00;


                    for($d = 0; $d <= $y; $d++){
                        $qDate = $_SESSION['period'][$d];
                        $pDate = date_create($qDate);
 
                        $queryMoulding = "SELECT `tran_date`, `emp_name`, `employer` FROM `tbl_trans_logs` WHERE employer != 'GLORY' AND section = 'Moulding' AND gpi8=1 AND tran_date = '$qDate' ";
                        $resultMoulding = mysqli_query($con, $queryMoulding);
                        $countMoulding = mysqli_num_rows($resultMoulding);
                        $amountMoulding = $countMoulding * 35.00;
                        $totalMoulding += $amountMoulding;

                        $queryWarehouse = "SELECT `tran_date`, `emp_name`, `employer` FROM `tbl_trans_logs` WHERE employer != 'GLORY' AND section = 'Warehouse' AND gpi8=1 AND tran_date = '$qDate' ";
                        $resultWarehouse = mysqli_query($con, $queryWarehouse);
                        $countWarehouse = mysqli_num_rows($resultWarehouse);
                        $amountWarehouse = $countWarehouse * 35.00;
                        $totalWarehouse += $amountWarehouse;

                        
                        $queryMaintenance = "SELECT `tran_date`, `emp_name`, `employer` FROM `tbl_trans_logs` WHERE employer != 'GLORY' AND section = 'Maintenance' AND gpi8=1 AND tran_date = '$qDate' ";
                        $resultMaintenance = mysqli_query($con, $queryMaintenance);
                        $countMaintenance = mysqli_num_rows($resultMaintenance);
                        $amountMaintenance = $countMaintenance * 35.00;
                        $totalMaintenance += $amountMaintenance;
                        
                        $queryFabrication = "SELECT `tran_date`, `emp_name`, `employer` FROM `tbl_trans_logs` WHERE employer != 'GLORY' AND section = 'Fabrication' AND gpi8=1 AND tran_date = '$qDate' ";
                        $resultFabrication = mysqli_query($con, $queryFabrication);
                        $countFabrication = mysqli_num_rows($resultFabrication);
                        $amountFabrication = $countFabrication * 35.00;
                        $totalFabrication += $amountFabrication;
                        
                        $queryQAdmin = "SELECT `tran_date`, `emp_name`, `employer` FROM `tbl_trans_logs` WHERE employer != 'GLORY' AND (department = 'QA' OR department = 'Administration' OR department = 'ICT') AND gpi8=1 AND tran_date = '$qDate' ";
                        $resultQAdmin = mysqli_query($con, $queryQAdmin);
                        $countQAdmin = mysqli_num_rows($resultQAdmin);
                        $amountQAdmin = $countQAdmin * 35.00;
                        $totalQAdmin += $amountQAdmin;
                        
                        $queryProdsupport = "SELECT `tran_date`, `emp_name`, `employer` FROM `tbl_trans_logs` WHERE employer != 'GLORY' AND section = 'Production Support' AND gpi8=1 AND tran_date = '$qDate' ";
                        $resultProdsupport = mysqli_query($con, $queryProdsupport);
                        $countProdsupport = mysqli_num_rows($resultProdsupport);
                        $amountProdsupport = $countProdsupport * 35.00;
                        $totalProdsupport += $amountProdsupport;
                             
                        $queryPI = "SELECT `tran_date`, `emp_name`, `employer` FROM `tbl_trans_logs` WHERE employer != 'GLORY' AND department = 'Parts Inspection' AND gpi8=1 AND tran_date = '$qDate' ";
                        $resultPI = mysqli_query($con, $queryPI);
                        $countPI = mysqli_num_rows($resultPI);
                        $amountPI = $countPI * 35.00;
                        $totalPI += $amountPI;

                        $queryOthers = "SELECT `tran_date`, `emp_name`, `employer` FROM `tbl_trans_logs` WHERE employer != 'GLORY' AND `gpi8` = '1' AND (`department` != 'Parts Inspection' AND `department` != 'QA' AND `department` != 'ICT') AND `section` = '' AND tran_date = '$qDate' ";
                        $resultOthers = mysqli_query($con, $queryOthers);
                        $countOthers = mysqli_num_rows($resultOthers);
                        $amountOthers = $countOthers * 35.00;
                        $totalOthers += $amountOthers;


                        $totalPayment = $amountOthers + $amountWarehouse + $amountMoulding + $amountMaintenance + $amountFabrication + $amountQAdmin + $amountProdsupport + $amountPI;

                        $totalManpower += $totalPayment;

$html .= '              <tr style="font-size: 12px; border: 1px solid black;">
                            <td style="width: 12.5%; border: 1px solid black; line-height: 23px;">'.date_format($pDate,"F d, Y").'</td>
                            <td style="width: 12.5%; border: 1px solid black; line-height: 23px;">'.number_format($amountMoulding, 2, '.', ',').'</td>
                            <td style="width: 12.5%; border: 1px solid black; line-height: 23px;">'.number_format($amountMaintenance, 2, '.', ',').'</td>
                            <td style="width: 12.5%; border: 1px solid black; line-height: 23px;">'.number_format($amountFabrication, 2, '.', ',').'</td>
                            <td style="width: 12.5%; border: 1px solid black; line-height: 23px;">'.number_format($amountQAdmin, 2, '.', ',').'</td>
                            <td style="width: 12.5%; border: 1px solid black; line-height: 23px;">'.number_format($amountProdsupport, 2, '.', ',').'</td>
                            <td style="width: 12.5%; border: 1px solid black; line-height: 23px;">'.number_format($amountPI, 2, '.', ',').'</td>
                            <td style="width: 12.5%; border: 1px solid black; line-height: 23px;">'.number_format($amountWarehouse, 2, '.', ',').'</td>
                            <td style="width: 12.5%; border: 1px solid black; line-height: 23px;">'.number_format($amountOthers, 2, '.', ',').'</td>


                            <td style="width: 12.5%; border: 1px solid black; line-height: 23px;">'.number_format($totalPayment, 2, '.', ',').'</td>
                          <td style="width: 12.5%; border: 1px solid black; line-height: 23px;">'.($totalPayment/35).'</td>
                        </tr>';
                    }  
                    $grandOverallTotal  += $totalManpower;
                    
$html .= '              <tr style="font-size: 12px; border: 1px solid black;">
                            <td style="border: 1px solid black; line-height: 23px;">-</td>
                            <td style="border: 1px solid black; line-height: 23px;">-</td>
                            <td style="border: 1px solid black; line-height: 23px;">-</td>
                            <td style="border: 1px solid black; line-height: 23px;">-</td>
                            <td style="border: 1px solid black; line-height: 23px;">-</td>
                            <td style="border: 1px solid black; line-height: 23px;">-</td>
                            <td style="border: 1px solid black; line-height: 23px;">-</td>
                            <td style="border: 1px solid black; line-height: 23px;">-</td>
                            <td style="border: 1px solid black; line-height: 23px;">-</td>
                            <td style="border: 1px solid black; line-height: 23px;">-</td>
                            <td style="border: 1px solid black; line-height: 23px;">-</td>



                        </tr>

                        <tr style="font-size: 12px; border: 1px solid black;">
                            <td style="border: 1px solid black; line-height: 23px;">Total</td>
                            <td style="border: 1px solid black; line-height: 23px;">'.number_format($totalMoulding, 2, '.', ',').'</td>
                            <td style="border: 1px solid black; line-height: 23px;">'.number_format($totalMaintenance, 2, '.', ',').'</td>
                            <td style="border: 1px solid black; line-height: 23px;">'.number_format($totalFabrication, 2, '.', ',').'</td>
                            <td style="border: 1px solid black; line-height: 23px;">'.number_format($totalQAdmin, 2, '.', ',').'</td>
                            <td style="border: 1px solid black; line-height: 23px;">'.number_format($totalProdsupport, 2, '.', ',').'</td>
                            <td style="border: 1px solid black; line-height: 23px;">'.number_format($totalPI, 2, '.', ',').'</td>
                            <td style="border: 1px solid black; line-height: 23px;">'.number_format($totalWarehouse, 2, '.', ',').'</td>
                            <td style="border: 1px solid black; line-height: 23px;">'.number_format($totalOthers, 2, '.', ',').'</td>


                            <td style="border: 1px solid black; line-height: 23px;">'.number_format($totalManpower, 2, '.', ',').'</td>

                                                 <td style="border: 1px solid black; line-height: 23px;">'.($totalManpower/35).'</td>

                        </tr>
 
                      
                        
                        <tr style="font-size: 14px; line-height: 20px">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>


                            <td style="font-weight: bold;">GRAND TOTAL</td>
                            <td>'.number_format($grandOverallTotal, 2, '.', ',').'</td>
                            <td>'.($grandOverallTotal/35).'</td>
                        </tr>

                        </tbody>
                    </table>';



                    // <table style="margin-top: 20px; width: 100%; text-align: center; border-collapse: collapse;">
                    // <thead>
                    //     <tr style="font-size: 11px; border: 1px solid black; font-size: 12px;">
                    //         <th  style="width: 12.5%; border: 1px solid black;">Date</th>
                    //         <th colspan="7" style="border: 1px solid black; font-size: 12px;">Additional (75.00)</th>
                    //       <th  style="width: 12.5%; border: 1px solid black; font-size: 12px;">TOTAL MANPOWER</th>
                    //     </tr>
                       
                    // </thead>
                    // <tbody>
                    // <tr style="font-size: 12px; border: 1px solid black;">
                    //         <td style="border: 1px solid black; line-height: 23px;">Inventory Verifier (September 28, 2024)</td>
                            
                    //         <td colspan="7" style="border: 1px solid black; line-height: 23px;">6750</td>
                    //         <td style="border: 1px solid black; line-height: 23px;">90</td>
                    //     </tr>
                        
                    //     <tr style="font-size: 14px; line-height: 20px">
                    //         <td></td>
                    //         <td></td>
                    //         <td></td>
                    //         <td></td>
                    //         <td></td>
                    //         <td></td>

                    //         <td style="font-weight: bold;">OVER ALL TOTAL</td>
                    //         <td>'.number_format($grandOverallTotal + 6750, 2, '.', ',').'</td>
                    //         <td>'.($grandOverallTotal/35+90).'</td>
                    //     </tr>

                    //     </tbody>
                    // </table>
                    
$html .= '              

                    <hr style="margin: 5px 0 0 0; padding: 0; border-left: 0px; border-top: 0px;  border-right: 0px;"><hr style="margin-top: 3px; padding: 0; border-left: 0px; border-top: 0px;  border-right: 0px;">
                    
                    <table style="width: 100%; margin-top: 10px; text-align: center; border-collapse: collapse;">
                        <tr>
                            <td>Prepared By:</td>
                            <td>Checked By:</td>
                        </tr>

                        <tr><td></td><td></td></tr><tr><td></td><td></td></tr><tr><td></td><td></td></tr><tr><td></td><td></td></tr><tr><td></td><td></td></tr><tr><td></td><td></td></tr><tr><td></td><td></td></tr><tr><td></td><td></td></tr><tr><td></td><td></td></tr><tr><td></td><td></td></tr><tr><td></td><td></td></tr><tr><td></td><td></td></tr><tr><td></td><td></td></tr><tr><td></td><td></td></tr><tr><td></td><td></td></tr><tr><td></td><td></td></tr><tr><td></td><td></td></tr><tr><td></td><td></td></tr><tr><td></td><td></td></tr><tr><td></td><td></td></tr>
                        

                        <tr>
                            <td>Bobby John Solomon</td>
			    <td>Nathan Nemedez</td>
                        </tr>
                        <tr>
                            <td><em>Sub - Leader</em></td>
                            <td><em>Information System Leader</em></td>
                        </tr>
                    </table>
        ';

            
    
$html .= '  </body>
            </html>';

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream('total-sales-report.pdf', ['Attachment' => 0]);
?>
