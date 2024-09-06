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
    <link rel="icon" href="./obj/canteen.png">
    <title>Logbook Sales</title>
    <link href="node_modules/select2/dist/css/select2.min.css" rel="stylesheet" />
    <script src="./sweetalert.min.js"></script>
    <script src="./jquery.min.js"></script>
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
            $lgbkName = strtoupper($_REQUEST['lgbkInputName']);
            $lgbkEmp = strtoupper($_REQUEST['lgbkOption']);
            $lgbkEmpId = strtoupper($_REQUEST['lgbkEmpId']);
            $lgbkCard = strtoupper($_REQUEST['lgbkCard']);

            $lgbkEmpDept = strtoupper($_REQUEST['lgbkDepartment']);
            $lgbkEmpSection = strtoupper($_REQUEST['lgbkSection']);
            $lgbkGPI8 = strtoupper($_REQUEST['lgbkGPI8']);

            
            
            

            $queryLgbkSales = "SELECT lgbk_date, lgbk_name FROM `logbooksales` WHERE lgbk_date = '$lgbkDate' AND lgbk_name = '$lgbkName' UNION SELECT tran_date, emp_name FROM `tbl_trans_logs` WHERE tran_date = '$lgbkDate' AND emp_name = '$lgbkName'";

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
                $insLgbkEmp = "INSERT INTO `logbooksales`(`logbook_ID`,  `emp_id`, `lgbk_date`, `lgbk_name`, `lgbk_employer`, `department`, `section`, `gpi8`) VALUES (null, '$lgbkEmpId', '$lgbkDate', '$lgbkName', '$lgbkEmp', '$lgbkEmpDept','$lgbkEmpSection', '$lgbkGPI8')";
                mysqli_query($con, $insLgbkEmp);

                $tran_insert = "INSERT INTO `tbl_trans_logs`(`transaction_id`, `emp_id`, `emp_name`, `emp_cardNum`, `employer`, `tran_date`, `department`,`section`,`gpi8`,`logbook`) VALUES (null ,'$lgbkEmpId','$lgbkName','$lgbkCard','$lgbkEmp','$lgbkDate', '$lgbkEmpDept','$lgbkEmpSection','$lgbkGPI8',1)";
                mysqli_query($con, $tran_insert);


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
                ?> <script language="javascript"> swal ( "Success" ,  "Employee successfully added!" ,  "success" ).then((value) => { $('#lgbkInputName').focus(); }); </script> <?php
            }
        }

        GLOBAL $lgbkEditDate;

        if(isset($_GET['lgbkEdit'])){
            $lgbkEditID = $_GET['lgbkEdit'];
            $_SESSION['lgbkEditID'] = $lgbkEditID;

            $queryLgbkEdit = "SELECT * FROM `logbooksales` WHERE logbook_ID = '$lgbkEditID' LIMIT 1";
            $resultLgbkEdit = mysqli_query($con, $queryLgbkEdit);

            while($rowLgbkEdit = mysqli_fetch_assoc($resultLgbkEdit)){
                $lgbkEditDate = $rowLgbkEdit['lgbk_date'];
                $lgbkEditName = strtoupper($rowLgbkEdit['lgbk_name']);
                $lgbkEditEmp = strtoupper($rowLgbkEdit['lgbk_employer']);

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
            echo $lgbkEditDate;
        }

        if(isset($_POST['lgbkEditCon'])){
            $lgbkEditDate = $_POST['lgbkInputDate'];
            $lgbkEditName = strtoupper($_REQUEST['lgbkInputName']);
            $lgbkEditEmp = strtoupper($_REQUEST['lgbkOption']);

            $queryLgbkEdit = "SELECT * FROM `logbooksales` WHERE logbook_ID = '$lgbkEditID' LIMIT 1";
            $resultLgbkEdit = mysqli_query($con, $queryLgbkEdit);
            
            while($rowLgbkEdit = mysqli_fetch_assoc($resultLgbkEdit)){
                $lgbkEditID = $rowLgbkEdit['logbook_ID'];

                $checkLgbkValidation = "SELECT * FROM `logbooksales` WHERE lgbk_name = '$lgbkEditName' AND lgbk_date = '$lgbkEditDate' UNION SELECT * FROM tbl_trans_logs WHERE emp_name = '$lgbkEditName' AND trans_date = '$lgbokEditDate'";
                $queryLgbkValidation = mysqli_query($con, $checkLgbkValidation);
                $countLgbkValidation = mysqli_num_rows($checkLgbkEdit);
                if($countLgbkValidation > 0){
                    ?> <script language="javascript">swal ( "Oops" ,  "Employee is already in logs!" ,  "error" );</script> <?php
                }

                else if(($lgbkEditDate == $rowLgbkEdit['lgbk_date']) && ($lgbkEditName == $rowLgbkEdit['lgbk_name']) && ($lgbkEditEmp == $rowLgbkEdit['lgbk_employer'])){
                    ?> <script language="javascript">swal ( "Oops" ,  "Employee is already in logs!" ,  "error" );</script> <?php
                }else if($countLgbkValidation == 0){

                    mysqli_query($con, "UPDATE `logbooksales` SET `logbook_ID`= '$lgbkEditID',`lgbk_date`='$lgbkEditDate',`lgbk_name`='$lgbkEditName',`lgbk_employer`='$lgbkEditEmp' WHERE logbook_ID = '$lgbkEditID'");

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

            mysqli_query($con, "DELETE FROM `logbooksales` WHERE logbook_ID = '$lgbkDel'");
            header("location: logbookSales.php");
        }
    ?>

    <!-- Include Navigation Side Bar -->
    <?php require_once 'nav.php';?>

    <h1>LOGBOOK SALES</h1>

    <div class="lbkCon">
        <div class="lbkToolBar">
            <input type="text" class="lbkSearch" placeholder="Search..." >
            <button class="lbkAdd" onclick="lbkAdd()">Add</button>
        </div>

        <div class="contentCon">
            <div class="lbkTableCon">
                <table class="lbkTable">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Name</th>
                            <th>Employer</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php 
                            $queryLgbk = "SELECT * FROM `logbooksales` ORDER BY `lgbk_date` DESC, `lgbk_employer` ASC LIMIT 10";
                            $resultLgbk = mysqli_query($con, $queryLgbk);
                            if(mysqli_num_rows($resultLgbk) > 0){
                                while($lgbkRow = mysqli_fetch_assoc($resultLgbk)){
                                    ?>
                                        <tr>
                                            <td><?php echo $lgbkRow['lgbk_date']; ?></td>
                                            <td><?php echo $lgbkRow['lgbk_name']; ?></td>
                                            <td><?php echo $lgbkRow['lgbk_employer']; ?></td>
                                            <td class="lgbkAction">
                                                <a href="./logbookSales.php?lgbkEdit=<?php echo $lgbkRow['logbook_ID'] ?>" class="lgbkEdit">Edit</a>
                                                <a href="#" data-name="<?php echo $lgbkRow['lgbk_name']; ?>" data-id="<?php echo $lgbkRow['logbook_ID'] ?>" class="lgbkDelete">Delete</a>
                                            </td>
                                        </tr>
                                    <?php
                                }
                            }else{
                                ?>
                                    <h1 class="noRecord">NO RECORD FOUND!</h1>
                                <?php
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
                <table class="lgbkFormTable">
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
            <option data-empcard="<?php echo $emp_row['emp_cardNum']; ?>" data-empid="<?php echo $emp_row['emp_idNum']; ?>" data-gpi8="<?php echo $emp_row['gpi8']; ?>"  data-empsection="<?php echo $emp_row['section']; ?>" data-name="<?php echo $emp_row['emp_name']; ?>" data-department="<?php echo $emp_row['department']; ?>" data-emp="<?php echo $emp_row['employer']; ?>" ><?php echo $emp_row['emp_name']; ?></option>
          <?php

            //  echo "<option value='$diagnosis' >$diagnosis</option>";

          }
          ?>
        </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Emp ID</td>
                        <td><input type="text" name="lgbkEmpId"  id="lgbkEmpId" ></td>
                    </tr>
                    <tr>
                        <td>Card Number</td>
                        <td><input type="text" name="lgbkCard"  id="lgbkCard" ></td>
                    </tr>
                    <tr>
                        <td>Department</td>
                        <td><input type="text" name="lgbkDepartment"  id="lgbkDepartment" ></td>
                    </tr>
                    <tr>
                        <td>Section</td>
                        <td><input type="text" name="lgbkSection"  id="lgbkSection" ></td>
                        <td style="display:none"><input type="text" name="lgbkGPI8"  id="lgbkGPI8" ></td>

                    </tr>
                    <tr>
                        <td>Employer</td>
                        <td>
                            <select id="lgbkOption" name="lgbkOption" change onchange="lgbkUp()">
                            <option value="" disabled hidden <?php if(!isset($_SESSION['selEmp']) || $_SESSION['selEmp'] == '0'){ echo 'selected'; }?>>Select your option</option>
                            <option value="GLORY" <?php if($_SESSION['selEmp'] == '1'){ echo 'selected'; }?>>GLORY</option>
                            <option value="MAXIM" <?php if($_SESSION['selEmp'] == '2'){ echo 'selected'; }?>>MAXIM</option>
                            <option value="NIPPI" <?php if($_SESSION['selEmp'] == '3'){ echo 'selected'; }?>>NIPPI</option>
                            <option value="POWERLANE" <?php if($_SESSION['selEmp'] == '4'){ echo 'selected'; }?>>POWERLANE</option>
                            <option value="SERVICE PROVIDER" <?php if($_SESSION['selEmp'] == '5'){ echo 'selected'; }?>>SERVICE PROVIDER</option>
                        </td>
                    </tr>
                </table>
                <div class="lgbkModalBtn">
                    <input type="submit" tabindex="-1" name="lgbkAdd" id="lgbkAdd"  style="display: none" value="Add" disabled>
                    <input type="submit" tabindex="-1" name="lgbkEditCon" id="lgbkEdit"  style="display: none" value="Edit" <?php if($_SESSION['lgbkEditTitle'] == "0"){ echo "disabled"; } ?>>
                    <input type="submit" tabindex="-1" name="lgbkCancel" id="lgbkCancel" value="Cancel" style="display: none">
                    <input type="button" name="lgbkSaveBtn" class="lgbkSaveBtn" id="lgbkSaveBtn" value="Save" onclick="lgbkSav3Btn()">
                    <input type="button" name="lgbkCloseModal" class="lgbkCloseModal" id="lgbkCloseModal" value="Cancel" onclick="lgbkCloseM0dal()">
                </div>
            </form>
        </div>
    </div>
    <script src="node_modules/jquery/dist/jquery.min.js"></script>
    <script src="node_modules/select2/dist/js/select2.min.js"></script>
    <script>
      $('#lgbkInputName').change(function() {
            var selectedNameEmpId = $(this).find('option:selected').data('empid');
            var selectedCard= $(this).find('option:selected').data('empcard');

            var selectedNameDepartment = $(this).find('option:selected').data('department');
            var selectedSection = $(this).find('option:selected').data('empsection');
            var selectedGpi8 = $(this).find('option:selected').data('gpi8');

            
            var employer = $(this).find('option:selected').data('emp');
            

            $('#lgbkEmpId').val(selectedNameEmpId);
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

        function lgbkSav3Btn(){
            if(nameVal.value == ""){
                swal ( "Oops" ,  "You have entered an invalid Employee Name!" ,  "error" );
            }else if(empSel.selectedIndex === 0){
                swal ( "Oops" ,  "You have selected an invalid Employer!" ,  "error" );
            }else{
                if(document.getElementById("lgbkFormTitle").innerHTML == "ADD"){
                    document.getElementById("lgbkAdd").click();
                }else if(document.getElementById("lgbkFormTitle").innerHTML == "EDIT"){
                    document.getElementById("lgbkEdit").click();
                }
            }
        }

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
