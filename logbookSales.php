<?php
    session_start();

    include("./connection.php");

    if(!isset($_SESSION['connected'])){
        header('location: login.php');
    }

    if(!isset($_SESSION['selEmp'])){
        $_SESSION['selEmp'] = 0;
    }
    if(!isset($_SESSION['lgbkEditTitle'])){
        $_SESSION['lgbkEditTitle'] = 0;
    }

    $prevDate = date("Y-m-d");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/styles.css">
    <script src="src/cdn_tailwindcss.js"></script>
    <link rel="icon" href="./obj/canteen.png">
    <title>Logbook Sales</title>
    <link rel="stylesheet" href="DataTables/datatables.min.css">
    <link rel="stylesheet" type="text/css" href="DataTables/Responsive-2.3.0/css/responsive.dataTables.min.css"/>
    
    <link href="node_modules/select2/dist/css/select2.min.css" rel="stylesheet" />
    <script src="./sweetalert.min.js"></script>
    <script src="./jquery.min.js"></script>
    <style>
        #sideBar{
            width: 80px;
        }
        #sideBar .contentContainer ul li a img{
            min-width: 60px;
        }
        #sideBar .contentContainer ul li .dDown .repImg{
            min-width: 60px;
            height: 60px;
        }
    </style>
    <style>
        .radio-group {
            margin: 10px 0;
        }

        .lgbkDelete {
  
  width: 110px;
  height: 23px;
  right: 70px;

  letter-spacing: 5px;
  display: inline;
  font-family: Arial, "Helvetica", sans-serif;
  font-size: 14px;
  font-weight: bold;
  color: #fff;
  text-decoration: none;
  text-transform: uppercase;
  text-align: center;
  text-shadow: 1px 1px 0px #E15963;
  padding-top: 9px;
  padding-left: 5px;
  cursor: pointer;
  border: none;
  background-color: #E15963;
  background-image: -webkit-gradient(linear, left top, left bottom, from(#e0565f), to(#3111));
  background-image: linear-gradient(top, #e0565f, #3111);
  border-top-left-radius: 5px;
  border-top-right-radius: 5px;
  border-bottom-right-radius: 5px;
  border-bottom-left-radius: 5px;
  -webkit-box-shadow: inset 0px 1px 0px #ec2a2a, 0px 5px 0px 0px #ac444b, 0px 10px 5px #999;
          box-shadow: inset 0px 1px 0px #ec2a2a, 0px 5px 0px 0px #ac444b, 0px 10px 5px #999;
}
        .lgbkEdit{
   
  width: 110px;
  height: 23px;

  letter-spacing: 5px;

  font-family: Arial, "Helvetica", sans-serif;
  font-size: 14px;
  font-weight: bold;
  color: #fff;
  text-decoration: none;
  text-transform: uppercase;
  text-align: center;
  text-shadow: 1px 1px 0px #188FF7;
  padding-top: 9px;
  padding-left: 5px;
  cursor: pointer;
  border: none;
  background-color: #188FF7;
  background-image: -webkit-gradient(linear, left top, left bottom, from(#1b8ff5), to(#3111));
  background-image: linear-gradient(top, #1b8ff5, #3111);
  border-top-left-radius: 5px;
  border-top-right-radius: 5px;
  border-bottom-right-radius: 5px;
  border-bottom-left-radius: 5px;
  -webkit-box-shadow: inset 0px 1px 0px #2ab7ec, 0px 5px 0px 0px #1765aa, 0px 10px 5px #999;
          box-shadow: inset 0px 1px 0px #2ab7ec, 0px 5px 0px 0px #1765aa, 0px 10px 5px #999;
        }

    </style>

</head>

<body id="logbookBody" onload="navFuntion()">

    <?php

        if(isset($_POST['lgbkCancel'])){
            unset($_SESSION['selEmp']);
            unset($_GET['lgbkEdit']);
            $_SESSION['selEmp'] = "0";
            $_SESSION['lgbkEditTitle'] = 0;
            header('location: logbookSales.php');
        }

        if(isset($_POST['lgbkAdd'])){
            $lgbkDate = $_REQUEST['lgbkInputDate'];
            $lgbkName = $_REQUEST['lgbkEmpNameTemp'];
            $lgbkEmp = strtoupper($_REQUEST['lgbkOption']);
            $lgbkEmpId = strtoupper($_REQUEST['lgbkEmpId']);
            $lgbkCard = strtoupper($_REQUEST['lgbkCard']);

            $lgbkEmpDept = strtoupper($_REQUEST['lgbkDepartment']);
            $lgbkEmpSection = strtoupper($_REQUEST['lgbkSection']);
            $lgbkGPI8 = strtoupper($_REQUEST['lgbkGPI8']);

            
            
            

            $queryLgbkSales = "SELECT * FROM `tbl_trans_logs`  WHERE `tran_date` = '$lgbkDate' AND `emp_id` = '$lgbkEmpId';";

            $resultLgbkSales = mysqli_query($con, $queryLgbkSales);
            if(mysqli_num_rows($resultLgbkSales) > 0){
                
                $_SESSION['recentDate'] = $lgbkDate;
                if($lgbkEmp == "GLORY"){
                    $_SESSION['selEmp'] = 1;
                }else if($lgbkEmp == "MAXIM"){
                    $_SESSION['selEmp'] = 2;
                }else if($lgbkEmp == "NIPPI"){
                    $_SESSION['selEmp'] = 3;
                }else if($lgbkEmp == "POWERLANE"){
                    $_SESSION['selEmp'] = 4;
                }else if($lgbkEmp == "SERVICE PROVIDER"){
                    $_SESSION['selEmp'] = 5;
                }else{
                    $_SESSION['selEmp'] = 0;
                }
                ?> <script>swal ( "Oops" ,  "Employee is already in logs!" ,  "error" ).then((value) => { $('#lgbkInputName').focus(); });</script> <?php
            }else{
                // $insLgbkEmp = "INSERT INTO `logbooksales`(`logbook_ID`,  `emp_id`, `tran_date`, `lgbk_name`, `lgbk_employer`, `department`, `section`, `gpi8`) VALUES (null, '$lgbkEmpId', '$lgbkDate', '$lgbkName', '$lgbkEmp', '$lgbkEmpDept','$lgbkEmpSection', '$lgbkGPI8')";
                // $inserttbl = mysqli_query($con, $insLgbkEmp);
                
                $tran_insert = "INSERT INTO `tbl_trans_logs`(`transaction_id`, `emp_id`, `emp_name`, `emp_cardNum`, `employer`, `tran_date`, `department`,`section`,`gpi8`,`logbook`) VALUES (null ,'$lgbkEmpId','$lgbkName','$lgbkCard','$lgbkEmp','$lgbkDate', '$lgbkEmpDept','$lgbkEmpSection','$lgbkGPI8','1')";
                $inserttbl =  mysqli_query($con, $tran_insert);


                $_SESSION['recentDate'] = $lgbkDate;

                if($lgbkEmp == "GLORY"){
                    $_SESSION['selEmp'] = 1;
                }else if($lgbkEmp == "MAXIM"){
                    $_SESSION['selEmp'] = 2;
                }else if($lgbkEmp == "NIPPI"){
                    $_SESSION['selEmp'] = 3;
                }else if($lgbkEmp == "POWERLANE"){
                    $_SESSION['selEmp'] = 4;
                }else if($lgbkEmp == "SERVICE PROVIDER"){
                    $_SESSION['selEmp'] = 5;
                }else{
                    $_SESSION['selEmp'] = 0;
                }
            if($inserttbl){
                ?> <script language="javascript"> alert('Employee successfully added!').then((value) => { $('#lgbkInputName').focus(); }); </script> <?php
            }
            else{
                ?> <script language="javascript"> swal ( "Error" ,  "Theres and error!" ,  "error" ).then((value) => { $('#lgbkInputName').focus(); }); </script> <?php

            }
            }
        }

        GLOBAL $lgbkEditDate;

        if(isset($_GET['lgbkEdit'])){
            $lgbkEditID = $_GET['lgbkEdit'];
            $_SESSION['lgbkEditID'] = $lgbkEditID;

            $queryLgbkEdit = "SELECT * FROM `tbl_trans_logs` WHERE transaction_id = '$lgbkEditID' LIMIT 1";
            $resultLgbkEdit = mysqli_query($con, $queryLgbkEdit);
//  echo $queryLgbkEdit;
            while($rowLgbkEdit = mysqli_fetch_assoc($resultLgbkEdit)){
                $lgbkEditDate = $rowLgbkEdit['tran_date'];
                $lgbkemp_id = $rowLgbkEdit['emp_id'];
                $lgbkemp_nametemp = $rowLgbkEdit['emp_name'];

                
                $emp_cardNum = $rowLgbkEdit['emp_cardNum'];
                $department = $rowLgbkEdit['department'];
                $section = $rowLgbkEdit['section'];




                $lgbkEditName = strtoupper($rowLgbkEdit['emp_name']);
                $lgbkEditEmp = strtoupper($rowLgbkEdit['employer']);

                if($lgbkEditEmp == "GLORY"){
                    $_SESSION['selEmp'] = 1;
                }else if($lgbkEditEmp == "MAXIM"){
                    $_SESSION['selEmp'] = 2;
                }else if($lgbkEditEmp == "NIPPI"){
                    $_SESSION['selEmp'] = 3;
                }else if($lgbkEditEmp == "POWERLANE"){
                    $_SESSION['selEmp'] = 4;
                }else if($lgbkEditEmp == "SERVICE PROVIDER"){
                    $_SESSION['selEmp'] = 5;
                }else{
                    $_SESSION['selEmp'] = 0;
                }

                $_SESSION['lgbkEditTitle'] = 1;
            }
            // echo $lgbkEditDate;
            // echo $lgbkEditEmp;
            
        }

        if(isset($_POST['lgbkEditCon'])){
            $lgbkEditDate = $_POST['lgbkInputDate'];
            
            $lgbkEditName = strtoupper($_REQUEST['lgbkInputName']);
            $lgbkEditEmp = strtoupper($_REQUEST['lgbkOption']);

            $queryLgbkEdit = "SELECT * FROM `tbl_trans_logs` WHERE transaction_id = '$lgbkEditID' LIMIT 1";
            $resultLgbkEdit = mysqli_query($con, $queryLgbkEdit);
            // echo  $queryLgbkEdit;
            while($rowLgbkEdit = mysqli_fetch_assoc($resultLgbkEdit)){
                $lgbkEditID = $rowLgbkEdit['transaction_id'];
            // echo  $lgbkEditID;

                $checkLgbkValidation = "SELECT * FROM `tbl_trans_logs` WHERE emp_name = '$lgbkEditName' AND tran_date = '$lgbkEditDate'";
            // echo  $checkLgbkValidation;
                
                $queryLgbkValidation = mysqli_query($con, $checkLgbkValidation);
                $countLgbkValidation = mysqli_num_rows($queryLgbkValidation);
                if($countLgbkValidation > 0){
                    ?> <script language="javascript">swal ( "Oops" ,  "Employee is already in logs!" ,  "error" );</script> <?php
                }

                else if(($lgbkEditDate == $rowLgbkEdit['tran_date']) && ($lgbkEditName == $rowLgbkEdit['emp_name']) && ($lgbkEditEmp == $rowLgbkEdit['employer'])){
                    ?> <script language="javascript">swal ( "Oops" ,  "Employee is already in logs!" ,  "error" );</script> <?php
                }else if($countLgbkValidation == 0){

                    mysqli_query($con, "UPDATE `tbl_trans_logs` SET `transaction_id`= '$lgbkEditID',`tran_date`='$lgbkEditDate',`emp_name`='$lgbkEditName',`employer`='$lgbkEditEmp' WHERE transaction_id = '$lgbkEditID'");

                    unset($_GET['lgbkEdit']);
                    $_SESSION['selEmp'] = "0";
                    $_SESSION['lgbkEditTitle'] = 0;

                    ?>
                        <script language="javascript">
                        swal ( "Success" ,  "Employee successfully updated!" ,  "success" ).then((value) => {
                            window.location = "./logbookSales.php";
                        }); 
                        </script>
                    <?php
                }
            }
        }

        if(isset($_GET['delIdConf'])){
            $lgbkDel = $_GET['delIdConf'];
            $_SESSION['lgbkDelID'] = $lgbkDel;

            mysqli_query($con, "DELETE FROM `tbl_trans_logs` WHERE transaction_id = '$lgbkDel'");
            header("location: logbookSales.php");
        }
    ?>

    <!-- Include Navigation Side Bar -->
    <?php require_once 'nav.php';?>

    <h1>LOGBOOK SALES</h1>

    <div class="lbkCon">
        <div class="lbkToolBar">
            <!-- <input type="text" class="lbkSearch" placeholder="Search..." > -->
            <button class="lbkAdd" style="margin-right: 350px;" onclick="lbkAdd()">Add</button>
        </div>

        <div class="contentCon" >
            <div class="">
                


                <table  id="lbkTable"  class="display" style="width:100%; margin-left: 60px; margin-right: 60px;; margin-top: 100px">
                    <thead>
                        <tr>
                        <th>No.</th>

                            <th>Date</th>
                            <th>Name</th>
                            <th>Employer</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php 

                        // Get the first date of the current month
$firstDate = date('Y-m-01');

// Get the last date of the current month
$lastDate = date('Y-m-t');

// echo "First date of the month: " . $firstDate . "\n";
// echo "Last date of the month: " . $lastDate . "\n";


                            $queryLgbk = "SELECT * FROM `tbl_trans_logs` where logbook = '1' and tran_date between '$firstDate' and '$lastDate' ORDER BY transaction_id DESC LIMIT 300";
                            $resultLgbk = mysqli_query($con, $queryLgbk);
                            $a=1;
                        
                                while($lgbkRow = mysqli_fetch_assoc($resultLgbk)){
                                    ?>
                                        <tr>
                                        <td class=""> <?php   echo $a;?>  </td>
                                            <td><?php echo $lgbkRow['tran_date']; ?></td>
                                            <td><?php echo $lgbkRow['emp_name']; ?></td>
                                            <td><?php echo $lgbkRow['employer']; ?></td>
                                            <td class="lgbkAction">
                                                <a href="./logbookSales.php?lgbkEdit=<?php echo $lgbkRow['transaction_id'] ?>" class="lgbkEdit">Edit</a>
                                                <a href="#" data-name="<?php echo $lgbkRow['emp_name']; ?>" data-id="<?php echo $lgbkRow['transaction_id'] ?>" class="lgbkDelete">Delete</a>
                                            </td>
                                        </tr>
                                    <?php
                                      $a++;
                                }
                            
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="lgbkBG" id="lgbkBG" style="visibility:<?php if(!isset($_SESSION['selEmp']) || $_SESSION['selEmp'] == '0'){ echo 'hidden;'; }else{ echo 'visible;'; } ?>;">
        <div class="lgbkModal">
            <form method="POST" class="lgbkForm">
                <h1 class="lgbkFormTitle" id="lgbkFormTitle"><?php if($_SESSION['lgbkEditTitle'] == "1"){ echo "EDIT"; }else{ echo "ADD"; } ?></h1>
                                <div class="grid grid-cols-2 gap-4 ">
                                    <div class="col-span-1 border border-indigo-600">
                                    <table class="lgbkFormTable ">
                    <tr>
                        <td>Date</td>
                        <td><input type="date" name="lgbkInputDate" data-date="" data-date-format="DD-MM-YYYY" id="lgbkInputDate" value="<?php if($_SESSION['lgbkEditTitle'] == 1){ echo $lgbkEditDate; }else if(($_SESSION['lgbkEditTitle'] == 0) && ($_SESSION['selEmp'] == 0)){ echo $prevDate; }else if(($_SESSION['lgbkEditTitle'] == 0) && ($_SESSION['selEmp'] > 0)){ echo $_SESSION['recentDate']; } ?>"></td>
                    </tr>
                    <tr>
                        <td>Full Name</td>
                        <td>
                        <select name="lgbkInputName"   id="lgbkInputName" autocomplete="off" autofocus onkeyup="lgbkUpName()" value="<?php if($_SESSION['lgbkEditTitle'] == 1){ echo $lgbkEditName; } ?>" class="js-fullname  lgbkInputName bg-gray-50 border border-gray-300 text-gray-900 text-[12px] 2xl:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
          <option selected disabled>Select Name</option>
          <?php
          $sql1 = "SELECT * FROM `emp_list` ORDER BY `employer` ASC";
          $result = mysqli_query($con, $sql1);
          while ($emp_row = mysqli_fetch_assoc($result)) {
          ?>
            <option <?php if(isset($lgbkEditName)){if ($lgbkEditName == $emp_row['emp_name']){ echo 'selected';}}  ?>  data-empcard="<?php echo $emp_row['emp_cardNum']; ?>" data-empid="<?php echo $emp_row['emp_idNum']; ?>" data-gpi8="<?php echo $emp_row['gpi8']; ?>"  data-empsection="<?php echo $emp_row['section']; ?>" data-name="<?php echo $emp_row['emp_name']; ?>" data-department="<?php echo $emp_row['department']; ?>" data-emp="<?php echo $emp_row['employer']; ?>" ><?php echo $emp_row['emp_name']; ?></option>
          <?php

            //  echo "<option value='$diagnosis' >$diagnosis</option>";

          }
          ?>
          <!-- <option value="ced" selected>ced</option> -->

        </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Emp ID</td>
                        <td><input type="text" readonly  name="lgbkEmpId"  id="lgbkEmpId" value="<?php if(isset($lgbkemp_id)){echo $lgbkemp_id;}?>"></td>

                        <input type="text" readonly style="display: none" name="lgbkEmpNameTemp"  id="lgbkEmpNameTemp" value="<?php if(isset($lgbkemp_nametemp)){echo $lgbkemp_nametemp;}  ?>">
                            
                    </tr>
                    <tr>
                        <td>Card Number</td>
                        <td><input type="text" readonly  name="lgbkCard"  id="lgbkCard" value="<?php if(isset($emp_cardNum)){echo $emp_cardNum;}?>" ></td>
                    </tr>
                    <tr>
                        <td>Department</td>
                        <td><input type="text" readonly name="lgbkDepartment" value="<?php if(isset($department)){echo $department;} ?>"  id="lgbkDepartment" ></td>
                    </tr>
                    <tr>
                        <td>Section</td>
                        <td><input type="text" readonly name="lgbkSection" value="<?php if(isset($section)){echo $section;} ?>"  id="lgbkSection" ></td>
                        <td style="display:none"><input type="text" name="lgbkGPI8"  id="lgbkGPI8" ></td>

                    </tr>
                    <tr>
                        <td>Employer</td>
                        <td>
                            <select id="lgbkOption"readonly  name="lgbkOption" change onchange="lgbkUp()">
                            <option value="" disabled hidden <?php if(!isset($_SESSION['selEmp']) || $_SESSION['selEmp'] == '0'){ echo 'selected'; }?>>Select your option</option>
                            <option value="GLORY" <?php if($_SESSION['selEmp'] == '1'){ echo 'selected'; }?>>GLORY</option>
                            <option value="MAXIM" <?php if($_SESSION['selEmp'] == '2'){ echo 'selected'; }?>>MAXIM</option>
                            <option value="NIPPI" <?php if($_SESSION['selEmp'] == '3'){ echo 'selected'; }?>>NIPPI</option>
                            <option value="POWERLANE" <?php if($_SESSION['selEmp'] == '4'){ echo 'selected'; }?>>POWERLANE</option>
                            <option value="SERVICE PROVIDER" <?php if($_SESSION['selEmp'] == '5'){ echo 'selected'; }?>>SERVICE PROVIDER</option>
                        </td>
                    </tr>
                </table>
                                    </div>
                                    <div class="col-span-1 border border-indigo-600">

                                    <table  id="lbkTableTemp"  class="display" style="width:100%;">
                    <thead>
                        <tr>
                        <th>No.</th>

                            <th>Date</th>
                            <th>Name</th>
                            <th>Employer</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="h-32 overflow-y-auto">
                  
                    </tbody>

                  
                </table>
                                    </div>

                                
                                </div>
                
                <div class="lgbkModalBtn">
                    <input type="submit" tabindex="-1" name="lgbkAdd" id="lgbkAdd"  style="display: none" value="Add" disabled>
                    <input type="submit" tabindex="-1" name="lgbkEditCon" id="lgbkEdit"  style="display: none" value="Edit" <?php if($_SESSION['lgbkEditTitle'] == "0"){ echo "disabled"; } ?>>
                    <input type="submit" tabindex="-1" name="lgbkCancel" id="lgbkCancel" value="Cancel" style="display: none">
                    <input type="button" name="lgbkSaveBtn" class="lgbkAddBtn" id="lgbkSaveBtn" value="Add" onclick="lgbkSav3Btn()">
                    <input type="button" name="lgbkCloseModal" class="lgbkCloseModal" id="lgbkCloseModal" value="Cancel" onclick="lgbkCloseM0dal()">
                    <input type="button" class="lgbkSaveBtn" id="submitData" value="SAVE">
                </div>
            </form>
        </div>
    </div>
    <script src="node_modules/jquery/dist/jquery.min.js"></script>
    <script type="text/javascript" src="DataTables/datatables.min.js"></script>
    <script type="text/javascript" src="DataTables/Responsive-2.3.0/js/dataTables.responsive.min.js"></script>
    
    <script src="node_modules/select2/dist/js/select2.min.js"></script>
    <script>
$("#submitData").on("click", function() {
    if (tableDataArray.length === 0) {
        alert("No data to submit!");
        return;
    }

    $.ajax({
        url: "saveLogBook.php", // Your PHP file
        type: "POST",
        data: { tableData: JSON.stringify(tableDataArray) }, // Send the array as JSON
        success: function(response) {
            console.log(response);
            alert(response);
            location.href='logbookSales.php';

        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
});



$(document).ready(function () {
  
  $('#lbkTable').DataTable(  {
  "columnDefs": [
      { "width": "1%", "targets": 0},
      {"className": "dt-center", "targets": "_all"}
  ],
      responsive: true,
      
  }   );

  $('#lbkTableTemp').DataTable(  {
    
"pageLength": 3000,
                      "columnDefs": [
                        { "width": "1%", "targets": 0, },
                        {"className": "dt-center", "targets": "_all"}
                      ],
                        responsive: true,
                        scrollCollapse: false,
    scrollY: '25vh',
    scrollCollapse: false,
    ordering: false, // Disable or enable sorting for proper numbering
    // order: [[0, 'desc']], 
    rowCallback: function(row, data, index) {
        $('td:eq(0)', row).html(index + 1); 
         }// Auto-numbering first column
  }   );
  
  });


      $('#lgbkInputName').change(function() {
            var selectedNameEmpId = $(this).find('option:selected').data('empid');
            var selectedName = $(this).find('option:selected').data('name');

            var selectedCard= $(this).find('option:selected').data('empcard');

            var selectedNameDepartment = $(this).find('option:selected').data('department');
            var selectedSection = $(this).find('option:selected').data('empsection');
            var selectedGpi8 = $(this).find('option:selected').data('gpi8');

            
            var employer = $(this).find('option:selected').data('emp');
            
            
            $('#lgbkEmpId').val(selectedNameEmpId);
            $('#lgbkEmpNameTemp').val(selectedName);

            $('#lgbkCard').val(selectedCard);

            $('#lgbkDepartment').val(selectedNameDepartment);
            $('#lgbkSection').val(selectedSection);
            $('#lgbkGPI8').val(selectedGpi8);

            $('#lgbkOption').val(employer).change();
            console.log(employer);

        });


$('.js-fullname').select2();

        function navFuntion(){
            var wRep = document.getElementById("wkRep");
            wRep.classList.add("active");

            var wRep = document.getElementById("o1");
            wRep.classList.add("activeReport");
        }

        var dateVal = document.getElementById("lgbkInputDate");
        var nameVal = document.getElementById("lgbkInputName");
        var empSel = document.getElementById("lgbkOption");
        var SbmtAdd = document.getElementById("lgbkAdd");
        var SbmtEdit = document.getElementById("lgbkEdit");
        var suggList = document.getElementById('suggList');
        var x = 0;

        function lbkAdd(){
            document.getElementById("lgbkFormTitle").innerHTML = "ADD"
            dateVal.value = "";
            nameVal.value = "";
            empSel.selectedIndex = 0;
            document.getElementById("lgbkBG").style.visibility = "visible";
            document.getElementById("lgbkInputName").focus();
            
            // GET YESTERDAY DATE
            const today = new Date();
            const yesterday = new Date(today);
            yesterday.setDate(yesterday.getDate() - 1);
            var yesDate = yesterday.toDateString();
            
            var d = new Date(yesDate),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();
            if (month.length < 2) {
                month = '0' + month;
            }
            if (day.length < 2) {
                day = '0' + day;
            }

            var nYesDate = [year, month, day].join('-');
            document.getElementById('lgbkInputDate').value = nYesDate;
        }

        function lgbkUpName(){
            var input, filter, ul, li, a, i, txtValue;
            x=0;
            input = document.getElementById("lgbkInputName");
            filter = input.value.toUpperCase();
            ul = suggList;
            li = ul.getElementsByTagName("li");
            for (i = 0; i < li.length; i++) {
                a = li[i].getElementsByTagName("a")[0];
                txtValue = a.textContent || a.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    x++;
                    li[i].style.display = "";
                } else {
                    li[i].style.display = "none";
                }
            }

            if(x == 0 || input.value == ""){
                suggList.style.visibility = "hidden";
            }else if(x == 1){
                suggList.style.visibility = "visible";
                $('#suggList').show();
                suggList.style.height = "30px";
            }else if(x == 2){
                suggList.style.visibility = "visible";
                $('#suggList').show();
                suggList.style.height = "61px";
            }else{
                suggList.style.visibility = "visible";
                $('#suggList').show();
                suggList.style.height = "92px";
            }

            if(nameVal.value === "" || empSel.selectedIndex === 0){
                SbmtAdd.disabled = true;
                SbmtEdit.disabled = true;
            }else{
                if(document.getElementById("lgbkFormTitle").innerHTML == "ADD"){
                    SbmtAdd.disabled = false;
                }else if(document.getElementById("lgbkFormTitle").innerHTML == "EDIT"){
                    SbmtEdit.disabled = false;
                }
            }
        }

        $(document).click(function(e) {
            if ($(e.target).is("ul") && $(e.target).has("li")){
                $('#suggList').show();
            }else{
                $('#suggList').hide();
            }
            
            if ($(e.target).is(':focus')){
                $('#suggList').show();
            }
        });

        $(".suggA").click(function(e) {
            e.preventDefault();
            var Ename = $(this).data("name");
            var Eemp = $(this).data("emp");
            document.getElementById('lgbkInputName').value = Ename;
            if(Eemp == "GLORY"){
                empSel.selectedIndex = 1;
            }else if(Eemp == "MAXIM"){
                empSel.selectedIndex = 2;
            }else if(Eemp == "NIPPI"){
                empSel.selectedIndex = 3;
            }else if(Eemp == "POWERLANE"){
                empSel.selectedIndex = 4;
            }else if(Eemp == "SERVICE PROVIDER"){
                empSel.selectedIndex = 5;
            }
            suggList.style.visibility = "hidden";

            if(document.getElementById("lgbkFormTitle").innerHTML == "ADD"){
                SbmtAdd.disabled = false;
            }else if(document.getElementById("lgbkFormTitle").innerHTML == "EDIT"){
                SbmtEdit.disabled = false;
            }
            
            document.getElementById("lgbkAdd").click();
            document.getElementById("lgbkInputDate").focus();

        });

        function lgbkUp(){
            if(nameVal.value === "" || empSel.selectedIndex === 0){
                SbmtAdd.disabled = true;
                SbmtEdit.disabled = true;
            }else{
                if(document.getElementById("lgbkFormTitle").innerHTML == "ADD"){
                    SbmtAdd.disabled = false;
                }else if(document.getElementById("lgbkFormTitle").innerHTML == "EDIT"){
                    SbmtEdit.disabled = false;
                }
            }
        }

        var count=1;

        let tableDataArray = [];

        function lgbkSav3Btn(){
            if(nameVal.value == ""){
                swal ( "Oops" ,  "You have entered an invalid Employee Name!" ,  "error" );
            }else if(empSel.selectedIndex === 0){
                swal ( "Oops" ,  "You have selected an invalid Employer!" ,  "error" );
            }else{
                if(document.getElementById("lgbkFormTitle").innerHTML == "ADD"){


                    let lgbkInputDate = $("#lgbkInputDate").val();
    let lgbkInputName = $("#lgbkInputName").val();
    let lgbkEmp = $("#lgbkOption").val();
    let lgbkEmpId = $("#lgbkEmpId").val();
    let lgbkCard = $("#lgbkCard").val();
    let lgbkEmpDept = $("#lgbkDepartment").val();
    let lgbkEmpSection = $("#lgbkSection").val();
    let lgbkGPI8 = $("#lgbkGPI8").val();

    // Store row data in an object
    let rowData = {
        date: lgbkInputDate,
        name: lgbkInputName,
        employer: lgbkEmp,
        empId: lgbkEmpId,
        card: lgbkCard,
        department: lgbkEmpDept,
        section: lgbkEmpSection,
        gpi8: lgbkGPI8
    };

    // Push the object into the array
    tableDataArray.push(rowData);
    
                    // document.getElementById("lbkAdd").click();
                    lgbkInputName = $("#lgbkInputName").val();
                    lgbkInputDate = $("#lgbkInputDate").val();
                    console.log(lgbkInputDate);
                    lgbkOption = $("#lgbkOption").val();
                    let table = $("#lbkTableTemp").DataTable(); // Get DataTable instance
                    table.row.add([
        '', // Placeholder for numbering
        lgbkInputDate,
        lgbkInputName,
        lgbkOption,
        "<button class='remove focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900'>Remove</button>" // Styled Remove button
    ]).draw(false); // Add row and redraw table


updateRowNumbers(); // Update numbering
console.log(tableDataArray);


var table1 = $('#lbkTableTemp').DataTable();
var tableContainer = $('#lbkTableTemp_wrapper .dataTables_scrollBody');

// Scroll to the bottom of the table
tableContainer.scrollTop(tableContainer.prop("scrollHeight"));



                    // $("#lbkTableTemp tbody").append("<tr><td></td><td>"+lgbkInputDate+"</td><td>"+lgbkInputName+"</td><td>"+lgbkOption+"</td><td><button class='remove' >Remove</button></td></tr>");
                }else if(document.getElementById("lgbkFormTitle").innerHTML == "EDIT"){
                    document.getElementById("lgbkEdit").click();
                }
            }
        }


        document.addEventListener("keydown", function(event) {
    if (event.key === "Enter") {
        // Your function here
        // console.log("Enter key pressed");
        // Call your function
        lgbkSav3Btn();
    }
});


        function updateRowNumbers() {
    let table = $("#lbkTableTemp").DataTable();
    table.rows().every(function(index) {
        this.cell(index, 0).data(index + 1); // Update first column with row number
    });
}

$("#lbkTableTemp tbody").on("click", ".remove", function() {
    let table = $("#lbkTableTemp").DataTable();
    let rowIndex = $(this).closest("tr").index();

    // Remove data from the array
    tableDataArray.splice(rowIndex, 1);

    // Remove the row from the table
    table.row($(this).parents("tr")).remove().draw();

    updateRowNumbers(); // Re-number rows after removal
    console.log(tableDataArray); // Debugging: Check updated array
});
        function lgbkCloseM0dal(){
            document.getElementById("lgbkCancel").click();
        }

        $(".lgbkDelete").click(function(e) {
            console.log("TEST");
            var delDataName = $(this).data("name");
            var delDataID = $(this).data("id");
            console.log(delDataName);
            console.log(delDataID);

            swal({
                title: "Are you sure you want to delete this record?",
                text: "NAME: " + delDataName,
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if(willDelete) {
                    swal("User details was sucessfully Deleted!", {
                        icon: "success",
                    }).then(function() {
                        window.location = "./logbookSales.php?delIdConf=" + delDataID;
                    });
                }
            })
        });
    </script>
</body>
</html>
