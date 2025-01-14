<?php
    session_start();

    include("./connection.php");

    $dateNow1 = date("Y-m-d");
    $strDate = strval($dateNow1);
    // $query_dup = "SELECT * FROM dup_status";
    // $dup_res = mysqli_query($con,$query_dup);

    date_default_timezone_set("Asia/Hong_Kong");

    // $row = mysqli_fetch_assoc($dup_res);
    // if($row['date_now'] != $strDate){
    //     $dupStat = "UPDATE dup_status SET dup_stat='NO', date_now = '$strDate'";
    //     mysqli_query($con, $dupStat);
    // }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/dist/charts.css">
    <link rel="stylesheet" href="./styles/styles.css?v=<?php echo time(); ?>">
    <link rel="icon" href="./obj/canteen.png">
    <title>Canteen POS</title>
</head>
<body style="background-color: #a797cf" onload="onloadFunction()">
    <h1 id="head">CANTEEN (GPI 5)</h1>

    <form method="POST">
        <span>
            <input type="textbox" id="tapInput" name="tapInput" autocomplete="off" onkeypress="myFunction(event)" autofocus>
            <p class="dte" id="dateNow" name="dateNow"></p>
        </span>
        <input type="submit" name="inputSubmit" id="inputSubmit" onclick="btnClicked()">
    </form>

    <div class="grid-container">
        <div class="grid-item item1">

            <?php
                if(isset($_POST['inputSubmit'])){
                    $cardNum = $_REQUEST['tapInput'];
                    $dateNow = date("Y-m-d");
                    $query_emp = "SELECT * from emp_list WHERE emp_cardNum = '$cardNum' LIMIT 1";
                    $result_emp = mysqli_query($con, $query_emp);
                    if(mysqli_num_rows($result_emp) > 0){

                        $emp_row = mysqli_fetch_assoc($result_emp);
                        $empID = $emp_row['emp_idNum'];
                        $empName = $emp_row['emp_name'];
                        $empCardNum = $emp_row['emp_cardNum'];
                        $empEmployer = $emp_row['employer'];
                        $department = $emp_row['department'];
                        $section = $emp_row['section'];
                        $gpi8 = $emp_row['gpi8'];

                        
                        $timeNow = date("h:i:s a");

                        $query_tran = "SELECT * from tbl_trans_logs WHERE emp_cardNum = '$cardNum' AND tran_date = '$dateNow' LIMIT 1";
                        $result_tran = mysqli_query($con, $query_tran);
                        if(mysqli_num_rows($result_tran) > 0){

                            while($tran_row = mysqli_fetch_assoc($result_tran)){
                                ?>
                                    <h1 id="tapName"><?php echo $tran_row['emp_name'] ?></h1>
                                    <h3 class="anim">Insufficient Balance!</h3>
                                <?php 
                            }
                        }else{

                            $tran_insert = "INSERT INTO `tbl_trans_logs`(`transaction_id`, `emp_id`, `emp_name`, `emp_cardNum`, `employer`, `tran_date`, `tran_time`, `department`,`section`,`gpi8`,`pos`) VALUES (null ,'$empID','$empName','$empCardNum','$empEmployer','$dateNow','$timeNow', '$department','$section','$gpi8','5')";
                            $success = mysqli_query($con, $tran_insert);
                            if($success){
                                ?>
                                <h1 class="tapName"><?php echo $empName?></h1>
                              <h3 class="anim2">Thank you!</h3>
                          <?php
                            }
                            else{
                                ?> <script language="javascript"> swal ( "Error" ,  "Theres and error!" ,  "error" ).then((value) => { $('#lgbkInputName').focus(); }); </script> <?php
                                ?>
                                <h1 id="tapName">THERE WAS AN ERROR WITH THE SYSTEM</h1>
                                <h3 class="anim">CONTACT YOUR SYSTEM ADMINISTRATOR</h3>
                            <?php 
                                
                            }
// echo
                            
                            
                        }
                    }else{
                        ?>
                            <h2 class="anim nreg">THE CARD IS NOT REGISTERED!</h1>
                        <?php
                    }
                }
            ?>  

        </div>  

        <div class="grid-item item2">
            <h1 id="rTrans">RECENT TRANSACTION</h1>
            <table>
            <?php
                $dateNow = date("Y-m-d");
                $queryRT = "SELECT * FROM tbl_trans_logs WHERE tran_date = '$dateNow' AND pos = '5' ORDER BY transaction_id DESC LIMIT 8";
                $resultRT = mysqli_query($con,$queryRT);
                while ($row = mysqli_fetch_assoc($resultRT)){
            ?>  
                    <tr>
                        <td><?php echo $row['emp_name'] ?></td>
                    </tr>
            <?php
                }
            ?>
            </table>
        </div>

        <div class="grid-item item3">
        <?php

$dateNow = date("Y-m-d");

// ========================== GLORY PERCENTAGE ==========================

$query_glory = "SELECT * FROM `tbl_trans_logs` WHERE employer = 'GLORY' AND tran_date = '$dateNow' AND pos = '5'";
$resultGlory = mysqli_query($con, $query_glory);
$rowGlory = mysqli_num_rows($resultGlory);



// ========================== MAXIM PERCENTAGE ==========================

$query_maxim = "SELECT * FROM `tbl_trans_logs` WHERE employer = 'MAXIM' AND tran_date = '$dateNow' AND pos = '5'";
$resultMaxim = mysqli_query($con, $query_maxim);
$rowMaxim = mysqli_num_rows($resultMaxim);


// ========================== NIPPI PERCENTAGE ==========================

$query_nippi = "SELECT * FROM `tbl_trans_logs` WHERE employer = 'NIPPI' AND tran_date = '$dateNow' AND pos = '5'";
$resultNippi = mysqli_query($con, $query_nippi);
$rowNippi = mysqli_num_rows($resultNippi);



// ========================== POWERLANE PERCENTAGE ==========================

$query_pl = "SELECT * FROM `tbl_trans_logs` WHERE employer = 'POWERLANE' AND tran_date = '$dateNow' AND pos = '5'";
$resultPL = mysqli_query($con, $query_pl);
$rowPL = mysqli_num_rows($resultPL);



// ========================== SERVICE PROVIDER ==========================

$query_sp = "SELECT * FROM `tbl_trans_logs` WHERE employer = 'SERVICE PROVIDER' AND tran_date = '$dateNow' AND pos = '5'";
$resultsp = mysqli_query($con, $query_sp);
$rowsp = mysqli_num_rows($resultsp);




?>
            <table  style="height: 100%" class="empTable">
                <thead style="height: 20%">
                    <tr>
                    <th>TOTAL</th>
                        <th>GPI</th>
                        <th>MAXIM</th>
                        <th>NIPPI</th>
                        <th>POWERLANE</th>
                        <th>Service Provider</th>
                     


                    </tr>
                </thead>
                <tbody style="height: 80%">
                        
                        <tr>
                        <th style="font-size: 65px;">
                                <span ><?php echo $rowGlory+$rowsp+$rowMaxim+$rowNippi+$rowPL; ?></span>
                            </th>
                            <th style="font-size: 65px;" >
                                <span ><?php echo $rowGlory; ?></span>
                            </th>
                            <th style="font-size: 65px;">
                                <span ><?php echo $rowMaxim; ?></span>
                            </th>
                               
                            <th style="font-size: 65px;">
                                <span ><?php echo $rowNippi; ?></span>
                            </th>
                            <th style="font-size: 65px;">
                                <span ><?php echo $rowPL; ?></span>
                            </th>
                            
                            <th style="font-size: 65px;">
                                <span ><?php echo $rowsp; ?></span>
                            </th>
                           
                        </tr>
                        
                        
                       
                    </tbody>
            </table>

            <div style="display: none" class="chart">
                <table id="effect-example-2" class="charts-css column hide-data">
                    <caption> 3D Effect Example #2 </caption>
    
                    <thead>
                    <tr>
                        <th>GLORY (PHILS.) INC.</th>
                        <th>MAXIM</th>
                        <th>NIPPI</th>
                        <th>POWERLANE</th>
                        <th>Service Provider</th>

                    </tr>
                </thead>

                    <?php

                        $dateNow = date("Y-m-d");

                        // ========================== GLORY PERCENTAGE ==========================

                        $query_glory = "SELECT * FROM `tbl_trans_logs` WHERE employer = 'GLORY' AND tran_date = '$dateNow' AND pos = '5'";
                        $resultGlory = mysqli_query($con, $query_glory);
                        $rowGlory = mysqli_num_rows($resultGlory);

                        

                        // ========================== MAXIM PERCENTAGE ==========================

                        $query_maxim = "SELECT * FROM `tbl_trans_logs` WHERE employer = 'MAXIM' AND tran_date = '$dateNow' AND pos = '5'";
                        $resultMaxim = mysqli_query($con, $query_maxim);
                        $rowMaxim = mysqli_num_rows($resultMaxim);


                        // ========================== NIPPI PERCENTAGE ==========================

                        $query_nippi = "SELECT * FROM `tbl_trans_logs` WHERE employer = 'NIPPI' AND tran_date = '$dateNow' AND pos = '5'";
                        $resultNippi = mysqli_query($con, $query_nippi);
                        $rowNippi = mysqli_num_rows($resultNippi);

            

                        // ========================== POWERLANE PERCENTAGE ==========================

                        $query_pl = "SELECT * FROM `tbl_trans_logs` WHERE employer = 'POWERLANE' AND tran_date = '$dateNow' AND pos = '5'";
                        $resultPL = mysqli_query($con, $query_pl);
                        $rowPL = mysqli_num_rows($resultPL);

               

                        // ========================== SERVICE PROVIDER ==========================

                        $query_sp = "SELECT * FROM `tbl_trans_logs` WHERE employer = 'SERVICE PROVIDER' AND tran_date = '$dateNow' AND pos = '5'";
                        $resultsp = mysqli_query($con, $query_sp);
                        $rowsp = mysqli_num_rows($resultsp);




                    ?>
           
    
                    <tbody>
                        
                        <tr>
                            <th scope="row"> Graph1 </th>
                            <td style="justify-content: center;
    font-size: 65px;
    align-items: center;
    color: #000000;
        background-color: #f0f8ff00; " >
                                <span ><?php echo $rowGlory; ?></span>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row"> Graph2 </th>
                            <td style="justify-content: center;
    font-size: 65px;
    align-items: center;
    color: #000000;
        background-color: #f0f8ff00; ">
                                <span ><?php echo $rowMaxim; ?></span>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row"> Graph3 </th>
                            <td style="justify-content: center;
    font-size: 65px;
    align-items: center;
    color: #000000;
        background-color: #f0f8ff00; ">
                                <span ><?php echo $rowNippi; ?></span>
                            </td>
                        </tr> 
                        
                        <tr>
                            <th scope="row"> Graph4 </th>
                            <td style="justify-content: center;
    font-size: 65px;
    align-items: center;
    color: #000000;
        background-color: #f0f8ff00; ">
                                <span ><?php echo $rowPL; ?></span>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"> Graph5 </th>
                            <td style="justify-content: center;
    font-size: 65px;
    align-items: center;
    color: #000000;
        background-color: #f0f8ff00; ">
                                <span ><?php echo $rowsp; ?></span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    

    <script type="text/javascript">

        function onloadFunction(){

            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var yyyy = today.getFullYear();
            var month;

            switch (new Date().getMonth()){
                case 0:
                    month = "January";
                    break;
                case 1:
                    month = "February";
                    break;
                case 2:
                    month = "March";
                    break;
                case 3:
                    month = "April";
                    break;
                case 4:
                    month = "May";
                    break;
                case 5:
                    month = "June";
                    break;
                case 6:
                    month = "July";
                    break;
                case 7:
                    month = "August";
                    break;
                case 8:
                    month = "September";
                    break;
                case 9:
                    month = "October";
                    break;
                case 10:
                    month = "November";
                    break;
                case 11:
                    month = "December";
                    break;
                default:
                    month = "Error";
            }

            today = month + ' ' + dd + ", " + yyyy;

            document.getElementById("dateNow").innerHTML = today;
        };

        function myFunction(event) {
            let unicode= event.which;
    
            if(unicode == 13){
                document.getElementById("inputSubmit").click();
            }
        };

    </script>

</body>
</html>