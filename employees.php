<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;



    session_start();

    include("./connection.php");

    if(!isset($_SESSION['connected'])){
        header('location: login.php');
    }


    if (isset($_POST['addEmployeeImport'])) {

    $failedData  = [];


    $format1 = explode(',', $_POST['departmentFormat1']);
    $format2 = explode(',', $_POST['departmentFormat2']);
    $format2 = array_map('trim', $format2);

    $employerFormat1 = explode(',', $_POST['employerFormat1']);
    $employerFormat2 = explode(',', $_POST['employerFormat2']);
    $employerFormat2 = array_map('trim', $employerFormat2);

    $sectionFormat1 = explode(',', $_POST['sectionFormat1']);
    $sectionFormat2 = explode(',', $_POST['sectionFormat2']);
    $sectionFormat2 = array_map('trim', $sectionFormat2);

      $gpi8Format1 = explode(',', $_POST['gpi8Format1']);
    $gpi8Format2 = explode(',', $_POST['gpi8Format2']);
    $gpi8Format2 = array_map('trim', $gpi8Format2);



    $fileName = $_FILES['import_file']['name'];
    $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);
    $allowed_ext = ['csv'];
    if (in_array($file_ext, $allowed_ext)) {
        $count = 0;
        $inputFileNamePath = $_FILES['import_file']['tmp_name'];
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileNamePath);
        $data = $spreadsheet->getActiveSheet()->toArray();
        $highestRow = $spreadsheet->getActiveSheet()->getHighestRow();

        try {
            // Save data to database and collect error logs
            $result = saveToDatabase($con, $data, $count,$format1,$format2,$employerFormat1,$employerFormat2,$sectionFormat1,$sectionFormat2,$gpi8Format1,$gpi8Format2);
            $errorLogs = $result['errorLogs'];
            $failedData = $result['failedData'];
            $count1 = $result['count1'];

            $unsuccessfullcount =  $highestRow - $count1 - 1;
            echo "<script>alert('There are $count1 successfully imported and $unsuccessfullcount unsuccessful!');</script>";
            // print_r($failedData);
    $_SESSION['failedData'] = $failedData;
    if($unsuccessfullcount>=1){
       
          echo "<script> location.href='downloadAndRedirect.php'; </script>";
        // echo "<script>
        //   location.href = 'failedDataFromImporting.php';
        //     setTimeout(function() {
        //     location.href = 'employees copy.php';
        //         }, 10000); // Redirect to anotherPage.php after 3 seconds
            
        // </script>";


    }
    else{
        echo "<script> location.href='employees.php'; </script>";
    }

            // Close database connection
            // mysqli_close($con);

            // Output success or error messages
            // $errorLogsMessage ='';
            // $error1 = '';
            // if (empty($errorLogs)) {
            //     echo "<script>alert('Data imported and saved successfully.!') </script>";
            // } else {
                
            //     // echo "Errors occurred during import:<br>";
                
            //     foreach ($errorLogs as $error) {
            //         // echo "$error";
            //         $error1 .= "$error";
            //         // echo "asdasd",$error1;

            //     }
            //     // echo $error1;
            //     echo "<script>alert ('Errors occurred during import: Id number/s $error1 not found in the employees list.')</script>";
            //     $_SESSION['failedData'] = $failedData;
            //     echo "<script> location.href='failedDataFromImportingPreEmp.php'; </script>";
            //     // echo "<script> location.href='index.php?employer=$employer'; </script>";

            // }
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    } else {
        echo "<script>alert('Invalid file format. Allowed format: csv');</script>";
    }
}





// Function to check if Id Number exists in database and save non-existent ones in an array
function isCardIdExist($con,$cardId)
{
    // Escape the Id Number to prevent SQL injection (assuming $con is your mysqli connection)
    // $idNumber = mysqli_real_escape_string($con, $idNumber);

    // Query to check if Id Number exists
    $query = "SELECT COUNT(*) AS count FROM emp_list WHERE `emp_cardNum` = '$cardId'";
    $result = mysqli_query($con, $query);

    // Check if query execution was successful
    if ($result === false) {
        die("Query failed: " . mysqli_error($con));
    }

    // Fetch the count from the result
    $row = mysqli_fetch_assoc($result);
    $count = (int)$row['count'];

    // Free result set
    mysqli_free_result($result);

    // Return true if count > 0 (ID Number exists), false otherwise
    return $count > 0;
}

function isEmpIdExist($con,$empId)
{
    // Escape the Id Number to prevent SQL injection (assuming $con is your mysqli connection)
    // $idNumber = mysqli_real_escape_string($con, $idNumber);

    // Query to check if Id Number exists
    $query = "SELECT COUNT(*) AS count FROM emp_list WHERE `emp_idNum` = '$empId'";
    $result = mysqli_query($con, $query);

    // Check if query execution was successful
    if ($result === false) {
        die("Query failed: " . mysqli_error($con));
    }

    // Fetch the count from the result
    $row = mysqli_fetch_assoc($result);
    $count = (int)$row['count'];

    // Free result set
    mysqli_free_result($result);

    // Return true if count > 0 (ID Number exists), false otherwise
    return $count > 0;
}

// Function to save data to database
function saveToDatabase($con, $data, $count,$format1,$format2,$employerFormat1,$employerFormat2,$sectionFormat1,$sectionFormat2,$gpi8Format1,$gpi8Format2)
{
    // Initialize an array to collect errors
    $errorLogs = [];
    $failedData = [];
    $count1 = 0;
    foreach ($data as $row) {
        if ($count > 0) {

            $employeeId = $row['0'];
            $name = $row['1'];
            $cardNumber = $row['2'];

            $employer = $row['3'];
            $employerindex = array_search($employer, $employerFormat1);
            $employercorrespondingValue = $employerFormat2[$employerindex] ?? null; // Use null if index doesn't exist in $format2

             $department = $row['4'];
            $index = array_search($department, $format1);
            $correspondingValue = $format2[$index] ?? null; // Use null if index doesn't exist in $format2

            $section = $row['5'];
            $sectionindex = array_search($section, $sectionFormat1);
            $sectioncorrespondingValue = $sectionFormat2[$sectionindex] ?? null;


               $gpi8 = $row['6'];
            $gpi8index = array_search($gpi8, $gpi8Format1);
            $gpi8correspondingValue = $gpi8Format2[$gpi8index] ?? null;



            // Check if Id Number exists in db_table
            if (isCardIdExist($con, $cardNumber)) {
                // Log error for non-existent Id Numbers
                $errorLogs[] = "$cardNumber, ";

                if($gpi8correspondingValue==1){
                    $gpi8correspondingValue="Yes";
                }
                else{
                    $gpi8correspondingValue="No";
                }
                array_push($failedData, [$employeeId,$name,$cardNumber,$employercorrespondingValue,$correspondingValue,$sectioncorrespondingValue,$gpi8correspondingValue]);

                // array_push($failedData, [$dateReceivedFormatted, $datePerformedFormatted, $name, $IMC, $OEH, $PE, $CBC, $U_A, $FA, $CXR, $VA, $DEN, $DT, $PT, $otherTest, $followUpStatus, $status, $attendee, $confirmationDate, $FMC]); 

                continue; // Skip saving this row
            }
            
            else{

                  if (isEmpIdExist($con, $employeeId)) {
                // Log error for non-existent Id Numbers
                $errorLogs[] = "$cardNumber, ";

                
                if($gpi8correspondingValue==1){
                    $gpi8correspondingValue="Yes";
                }
                else{
                    $gpi8correspondingValue="No";
                }

                array_push($failedData, [$employeeId,$name,$cardNumber,$employercorrespondingValue,$correspondingValue,$sectioncorrespondingValue,$gpi8correspondingValue]);

                // array_push($failedData, [$dateReceivedFormatted, $datePerformedFormatted, $name, $IMC, $OEH, $PE, $CBC, $U_A, $FA, $CXR, $VA, $DEN, $DT, $PT, $otherTest, $followUpStatus, $status, $attendee, $confirmationDate, $FMC]); 

                continue; // Skip saving this row
            }
            else{

                 try {
                    $addEmployeeSql = "INSERT INTO `emp_list`(`emp_id`, `emp_idNum`, `emp_name`, `emp_cardNum`, `employer`, `department`, `section`, `gpi8`) VALUES (null ,'$employeeId','$name','$cardNumber','$employercorrespondingValue','$correspondingValue','$sectioncorrespondingValue','$gpi8correspondingValue')";
                    $resultInfo = mysqli_query($con, $addEmployeeSql);
        
        
                    // Check if query execution was successful
                    // if ($resultInfo === false) {
                    //     $errorLogs[] = "Failed to insert data for applicant '$name': " . mysqli_error($con);
                    //     array_push($failedData, [$dateReceivedFormatted, $datePerformedFormatted, $IMC, $OEH, $PE, $CBC, $U_A, $FA, $CXR, $VA, $DEN, $DT, $PT, $otherTest, $followUpStatus, $status, $attendee, $confirmationDate, $FMC]); 
        
                    // }
                
                    if ($resultInfo) {
                        $count1++;
                        // Success message (optional)
                        // echo "<script>alert('Data imported and saved successfully!');</script>";
                    }

          



                } catch (mysqli_sql_exception $e) {
                    // Catch the exception and get the error message
                    $error = $e->getMessage();
                    // Display the error in an alert
    
    
                    echo "<script>alert('Error: " . addslashes($error) . "');</script>";
                    array_push($failedData, [$employeeId,$name,$cardNumber,$employercorrespondingValue,$correspondingValue,$sectioncorrespondingValue,$gpi8correspondingValue]);
                    // $failedData .= "<tr>";
                    // $failedData .= "\n<td>$idNumber</td>";
                    // $failedData .= "\n<td>$name</td>";
                    // $failedData .= "\n<td>$email</td>";
                    // $failedData .= "\n<td>$birthday</td>";
                    // $failedData .= "\n<td>$age</td>";
                    // $failedData .= "\n<td>$sexcorrespondingValue</td>";
                    // $failedData .= "\n<td>$address</td>";
                    // $failedData .= "\n<td>$civilcorrespondingValue</td>";
                    // $failedData .= "\n<td>$employer</td>";
                    // $failedData .= "\n<td>$building</td>";
                    // $failedData .= "\n<td>$correspondingValue</td>";
                    // $failedData .= "\n<td>$section</td>";
                    // $failedData .= "\n<td>$position</td>";
                    // $failedData .= "\n<td>$dateHired</td>";
                    // $failedData .= "</tr>";
    
    
                    
                }

            }

               
            }

            // If validation passes, save to database
            // $result = mysqli_query($con, "SELECT `Name`, `section` FROM `employeespersonalinfo` WHERE `idNumber` = '$idNumber' AND `employer` ='$employer'");
            // while ($userRow = mysqli_fetch_assoc($result)) {
            //     $name = $userRow['Name'];
            //     $section = $userRow['section'];

            //     $result1 = mysqli_query($con, "SELECT * FROM `preemployment` WHERE `idNumber` = '$idNumber'");
            //     $numrows = mysqli_num_rows($result1);
            //     if ($numrows > 0) {
            //         $addPreEmploymentGpi = "UPDATE `preemployment` SET `dateReceived` = '$dateReceived', `datePerformed` = '$datePerformed', `name`='$name', `section`='$section', `IMC` = '$IMC', `OEH`='$OEH', `PE` = '$PE', `CBC` ='$CBC', `U_A` = '$U_A', `FA`='$FA', `CXR` ='$CXR', `VA`='$VA', `DEN`='$DEN', `DT`='$DT', `PT` = '$PT', `otherTest` = '$otherTest', `followUpStatus` = '$followUpStatus', `status`='$status', `attendee`='$attendee', `confirmationDate`='$confirmationDate', `FMC`='$FMC' WHERE `idNumber` = '$idNumber'";
            //         $resultInfo = mysqli_query($con, $addPreEmploymentGpi);
            //     } else {
            //         $addPreEmploymentGpi = "INSERT INTO `preemployment`(`dateReceived`, `datePerformed`, `idNumber`, `name`, `section`, `IMC`, `OEH`, `PE`, `CBC`, `U_A`, `FA`, `CXR`, `VA`, `DEN`, `DT`, `PT`, `otherTest`, `followUpStatus`, `status`, `attendee`,`confirmationDate`, `FMC`) VALUES ('$dateReceived','$datePerformed','$idNumber','$name','$section','$IMC','$OEH','$PE','$CBC','$U_A','$FA','$CXR', '$VA', '$DEN', '$DT', '$PT', ' $otherTest', ' $followUpStatus', '$status', '$attendee','$confirmationDate', '$FMC')";
            //         $resultInfo = mysqli_query($con, $addPreEmploymentGpi);
            //     }
            // }

           

            
        }
        $count = 1;
    }

    // Return error logs array
    return [
        'count1' => $count1,
        'errorLogs' => $errorLogs,
        'failedData' => $failedData
    ];
}




?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <script src="src/cdn_tailwindcss.js"></script>
    <script src="node_modules/flowbite/dist/flowbite.min.js"></script>
  
    <link rel="stylesheet" href="./styles/styles.css?v=<?php echo time(); ?>">
    <link rel="icon" href="./obj/canteen.png">
    <title>Employees</title>
    <link rel="stylesheet" href="DataTables/datatables.min.css">
    <link rel="stylesheet" type="text/css" href="DataTables/Responsive-2.3.0/css/responsive.dataTables.min.css"/>
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
        .dataTables_wrapper .dataTables_paginate {
    color: #333;
    display: flex;
}
.dataTables_wrapper .dataTables_paginate .ellipsis {
    padding: 0;
    width: auto;
}
.dataTables_wrapper .dataTables_paginate span{
    display: flex;
}
    </style>
</head>



<body id="emp-body"  style="    width: 80%; margin: auto;" onload="navFuntion()">
    <!-- Include Navigation Side Bar -->
    <?php require_once 'nav.php';?>
    <div class="emp-container" style="">
    <div class="topPage" id="topPage">
           
            
        </div>
        <div class="flex justify-between">
        <p class="mb-2 my-auto"><span class=" self-center text-md font-semibold whitespace-nowrap   text-[#193F9F]">Employee List </p>
        <div class="flex items-center order-2">
        <button type="button" data-dropdown-toggle="options" class="lg:block text-white bg-gradient-to-r from-cyan-400 via-cyan-500 to-cyan-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-cyan-300 font-medium rounded-lg text-[12px] 2xl:text-sm px-5 py-2.5 text-center me-2 mb-2 mx-3 md:mx-2">Add Employee</button>


        <div id="options" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
            <ul class="py-2  text-gray-700 dark:text-gray-200" aria-labelledby="options">
                <li>
                    <a type="button" onclick="openAddEmpModal()"  class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Manual Registration</a>
                </li>
                <li>
                    <a type="button" data-modal-target="bulkRegistration" data-modal-toggle="bulkRegistration" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Bulk Registration</a>
                </li>
            </ul>

        </div>
        </div>
        <!-- <a href="ticketForm.php" type="button" class="text-white bg-gradient-to-r from-cyan-400 via-cyan-500 to-cyan-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-cyan-300 dark:focus:ring-cyan-800  rounded-lg  px-5 py-2.5 text-center me-2 mb-2">Create a Ticket</a> -->
       
    </div>
    <div id="" class="">
        <div class=" p-4 rounded-lg  bg-gray-50 " id="headApproval" role="tabpanel" aria-labelledby="profile-tab">
            <form action="index.php" method="post">
                <section class="mt-2 2xl:mt-10">
                    <table id="employeeList" class="display text-[9px] 2xl:text-sm" style="width:100%">
                    <thead>
            <tr>
                <th>No.</th>
            <th>Employee ID</th>
            <th>Name</th>
            <th>Employer</th>
            <th>Section</th>
            <th>Card Number</th>
          

            <th>Action</th>

            </tr>
        </thead>
                        <tbody>
                        <?php
                         $queryEmp = "SELECT * FROM `emp_list` ORDER BY `employer` ASC"; //added LIMIT 10 para di bumagal ang pagencode ni Alien
                         $resultEmp = mysqli_query($con, $queryEmp);
                         $a=1;
                        while($emp_row = mysqli_fetch_assoc($resultEmp)){
                            ?>
                                <tr>
                                <td class=""> <?php   echo $a;?>  </td>
                                    <td><?php echo $emp_row['emp_idNum']; ?></td>
                                    <td><?php echo $emp_row['emp_name']; ?></td>
                                    <td><?php echo $emp_row['employer']; ?></td>
                                    <td><?php echo $emp_row['section']; ?></td>

                                    <td><?php echo $emp_row['emp_cardNum']; ?></td>
                                    <td class="actionTab">
                                        <a onclick="openEditEmpModal(this)" data-id="<?php echo $emp_row['emp_id']; ?>" data-empid="<?php echo $emp_row['emp_idNum']; ?>" data-card="<?php echo $emp_row['emp_cardNum']; ?>" data-name="<?php echo $emp_row['emp_name']; ?>" data-employer="<?php echo $emp_row['employer']; ?>" data-gpi8="<?php echo $emp_row['gpi8']; ?>" data-department="<?php echo $emp_row['department']; ?>" data-section="<?php echo $emp_row['section']; ?>" class="text-white bg-gradient-to-r from-teal-400 via-teal-500 to-teal-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-teal-300 dark:focus:ring-teal-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Edit</a>
                                        <a  class="text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Delete</a>
                                    </td>
                                </tr>
                            <?php
                            $a++;
                        }
                    ?>
                        </tbody>
                    </table>
                </section>
            </form>
        </div>
    </div>
    </div>


    <div id="addEmployeeModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                   Add Employee
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" onclick="openAddEmpModal()">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <form method="POST"  class="p-4 md:p-5">
                <div id="successMessage" class="hidden p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                <span class="font-medium">Registration successfull!</span>
                </div>
                <div id="duplicateMessage" class="hidden p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                <span class="font-medium">Your Employee Id is already used!</span>
                </div>
                <div id="duplicateRFID" class="hidden p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                <span class="font-medium">Your Card Id (RFID) is already used!</span>
                </div>
                <div id="incompleteForm" class="hidden flex items-center p-4 mb-4 gap-2 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300" role="alert">
                <svg class="shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                </svg>
                <span class="sr-only">Info</span>
                <div>
                    <span class="font-medium">Incomplete Form!</span>
                </div>
                </div>
                <div class="grid gap-4 mb-4 grid-cols-2">
                <div class="col-span-2">
                        <label for="empId" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Employee Id</label>
                        <input type="text" name="empId" id="empId" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="" required="">
                    </div>
                    <div class="col-span-2">
                        <label for="fullName" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Full Name</label>
                        <input type="text" name="fullName" id="fullName" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Type full name" required="">
                    </div>
                    <div class="col-span-2">
                        <label for="cardNumber" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Card Number</label>
                        <input type="text" name="cardNumber" id="cardNumber" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Enter Card RFID" required="">
                    </div>
                    <div class="col-span-2">
                        <label for="employer" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Employer</label>
                        <select id="employer" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        <option value="" disabled  >Select employer</option>
                            <option value="GLORY">GLORY</option>
                            <option value="MAXIM">MAXIM</option>
                            <option value="NIPPI">NIPPI</option>
                            <option value="NATCORP">NATCORP</option>
                            <option value="SERVICE PROVIDER">SERVICE PROVIDER</option>
                        </select>
                    </div>
                    <div class="col-span-2">
                    <div style="width: 100%; display: flex">
                    <div style="width: 50%"><label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" style="padding-left: 20px;font-size: 15px;
    line-height: 40px;">GPI 8</label></div>
                    <div style="width: 50%">
                    <input type="radio"  name="optionGpi8" style="position:relative; width: 20px;height: 30px; width: 30px; padding-left: 20px;font-size: 15px;
    line-height: 40px;" value="1"> <label style="padding-left: 10px;font-size: 15px;
    line-height: 40px;">Yes</label>
                    <input type="radio"   name="optionGpi8" style="position:relative; width: 20px;height: 30px; width: 30px; padding-left: 20px;font-size: 15px;
    line-height: 40px;" value="0"> <label style="padding-left: 10px;font-size: 15px;
    line-height: 40px;">No</label>
                    </div>
                 
                   </div>                   
                    </div>
                    <div id="gpi8options" class="hidden col-span-2">
                    <div class="col-span-2">
                        <label for="department" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Department</label>
                        <select id="department" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        <option disabled selected value="">Select Department</option>
                                <?php
                                $querydept = "SELECT DISTINCT `department` FROM `emp_list` WHERE `department` != ''"; //added LIMIT 10 para di bumagal ang pagencode ni Alien
                                $resultDept = mysqli_query($con, $querydept);
                            
                                while($emp_row = mysqli_fetch_assoc($resultDept)){
                                    ?>
                                    <option  value="<?php echo $emp_row['department']; ?>"><?php echo $emp_row['department']; ?></option>
                                    <?php
                                
                                }
                                ?>
                          <option  value="">Others</option>
                        
                        </select>
                    </div>

                    <div class="col-span-2">
                        <label for="section" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Section</label>
                        <select id="section" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        <option disabled selected value="">Select Section</option>
                        <?php
                        $querydept = "SELECT DISTINCT `section` FROM `emp_list` WHERE `section` != ''"; //added LIMIT 10 para di bumagal ang pagencode ni Alien
                        $resultDept = mysqli_query($con, $querydept);
                    
                        while($emp_row = mysqli_fetch_assoc($resultDept)){
                            ?>
                            <option  value="<?php echo $emp_row['section']; ?>"><?php echo $emp_row['section']; ?></option>
                            <?php
                        
                        }
                        ?>
                        <option  value="">Others</option>
                                        
                        </select>
                    </div>



</div>
                </div>
                <button type="button" onclick="addEmployeeToDatabase()" class="justify-center w-full text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Add new employee
                </button>
            </form>
        </div>
    </div>
</div> 






<div id="editEmployeeModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                   Add Employee
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" onclick="closeModalEditEmployee()">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <form method="POST"  class="p-4 md:p-5">
                <input type="text" class="hidden" name="editDBId" id="editDBId">
                <div id="successMessageEdit" class="hidden p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                <span class="font-medium">Update successfull!</span>
                </div>
                <div id="duplicateMessageEdit" class="hidden p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                <span class="font-medium">Your Employee Id is already used!</span>
                </div>
                <div id="duplicateRFIDEdit" class="hidden p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                <span class="font-medium">Your Card Id (RFID) is already used!</span>
                </div>
                <div id="incompleteFormEdit" class="hidden flex items-center p-4 mb-4 gap-2 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300" role="alert">
                <svg class="shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                </svg>
                <span class="sr-only">Info</span>
                <div>
                    <span class="font-medium">Incomplete Form!</span>
                </div>
                </div>
                <div class="grid gap-4 mb-4 grid-cols-2">
                   
                <div class="col-span-2">
                        <label for="editempId" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Employee Id</label>
                        <input type="text" name="editempId" id="editempId" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="" required="">
                    </div>
                    <div class="col-span-2">
                        <label for="editfullName" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Full Name</label>
                        <input type="text" name="editfullName" id="editfullName" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Type full name" required="">
                    </div>
                    <div class="col-span-2">
                        <label for="editcardNumber" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Card Number</label>
                        <input type="text" name="editcardNumber" id="editcardNumber" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Enter Card RFID" required="">
                    </div>
                    <div class="col-span-2">
                        <label for="editemployer" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Employer</label>
                        <select id="editemployer" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        <option value="" disabled  >Select employer</option>
                            <option value="GLORY">GLORY</option>
                            <option value="MAXIM">MAXIM</option>
                            <option value="NIPPI">NIPPI</option>
                            <option value="NATCORP">NATCORP</option>
                            <option value="SERVICE PROVIDER">SERVICE PROVIDER</option>
                        </select>
                    </div>
                    <div class="col-span-2">
                    <div style="width: 100%; display: flex">
                    <div style="width: 50%"><label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" style="padding-left: 20px;font-size: 15px;
    line-height: 40px;">GPI 8</label></div>
                    <div style="width: 50%">
                    <input type="radio"  name="editoptionGpi8" style="position:relative; width: 20px;height: 30px; width: 30px; padding-left: 20px;font-size: 15px;
    line-height: 40px;" value="1"> <label style="padding-left: 10px;font-size: 15px;
    line-height: 40px;">Yes</label>
                    <input type="radio"   name="editoptionGpi8" style="position:relative; width: 20px;height: 30px; width: 30px; padding-left: 20px;font-size: 15px;
    line-height: 40px;" value="0"> <label style="padding-left: 10px;font-size: 15px;
    line-height: 40px;">No</label>
                    </div>
                 
                   </div>                   
                    </div>
                    <div id="gpi8editoptions" class="hidden col-span-2">
                    <div class="col-span-2">
                        <label for="editdepartment" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Department</label>
                        <select id="editdepartment" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        <option disabled selected value="">Select Department</option>
                                <?php
                                $querydept = "SELECT DISTINCT `department` FROM `emp_list` WHERE `department` != ''"; //added LIMIT 10 para di bumagal ang pagencode ni Alien
                                $resultDept = mysqli_query($con, $querydept);
                            
                                while($emp_row = mysqli_fetch_assoc($resultDept)){
                                    ?>
                                    <option  value="<?php echo $emp_row['department']; ?>"><?php echo $emp_row['department']; ?></option>
                                    <?php
                                
                                }
                                ?>
                          <option  value="">Others</option>
                        
                        </select>
                    </div>

                    <div class="col-span-2">
                        <label for="editsection" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Section</label>
                        <select id="editsection" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        <option disabled selected value="">Select Section</option>
                        <?php
                        $querydept = "SELECT DISTINCT `section` FROM `emp_list` WHERE `section` != ''"; //added LIMIT 10 para di bumagal ang pagencode ni Alien
                        $resultDept = mysqli_query($con, $querydept);
                    
                        while($emp_row = mysqli_fetch_assoc($resultDept)){
                            ?>
                            <option  value="<?php echo $emp_row['section']; ?>"><?php echo $emp_row['section']; ?></option>
                            <?php
                        
                        }
                        ?>
                        <option  value="">Others</option>
                                        
                        </select>
                    </div>



</div>
                </div>
                <button type="button" onclick="updateEmployeeData()" class="justify-center w-full text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Edit employee's data
                </button>
            </form>
        </div>
    </div>
</div> 




<div id="bulkRegistration" tabindex="-1" aria-hidden="true" class=" hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-2 border-b rounded-t dark:border-gray-600">
                <h3 class=" font-semibold text-gray-900 dark:text-white">
                    Import Employed
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="bulkRegistration">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <form method="POST" class="px-4 md:px-5 py-2 text-[8pt]" enctype="multipart/form-data">
                <div class="grid gap-2 mb-4 grid-cols-2">
                    <div class="col-span-2">

                        <div class="flex gap-2">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file_input">Upload file (.CSV ONLY) </label>  <a href="#" onclick="downloadTemplate()" class="underline mb-2 text-sm font-medium text-blue-600 dark:text-blue-500 hover:underline">Download Template</a></div>
                        <input type="file" accept=".csv" name="import_file" class="block w-full  text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" id="file_input">

                        <input type="text" name="employerFormat1" class="hidden" id="employerFormat1">
                        <input type="text" name="employerFormat2" class="hidden" id="employerFormat2">


                        <input type="text" name="departmentFormat1" class="hidden" id="departmentFormat1">
                        <input type="text" name="departmentFormat2" class="hidden" id="departmentFormat2">

                   

                        <input type="text" name="sectionFormat1" class="hidden" id="sectionFormat1">
                        <input type="text" name="sectionFormat2" class="hidden" id="sectionFormat2">

                          <input type="text" name="gpi8Format1" class="hidden" id="gpi8Format1">
                        <input type="text" name="gpi8Format2" class="hidden" id="gpi8Format2">

                    </div>


                </div>

                
                <button data-modal-target="equivalentValues" data-modal-toggle="equivalentValues" type="button" id="proceedImportButton" name="proceedImportButton"  class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300  rounded-lg  px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Proceed
                    <svg class="me-1 -ms-1 w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"/>
</svg>
                </button>

              <button type="submit" id="addEmployeeImport" name="addEmployeeImport" class="hidden text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300  rounded-lg  px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                    </svg>
                    Import Data
                </button>
     

            </form>
        </div>
    </div>
</div>



<div id="equivalentValues" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-[100] justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-2xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Choose Equivalent Values
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="equivalentValues">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4 md:p-5 space-y-4">
            <div class="grid grid-cols-2">
    <div class="flex justify-center border border-gray-300 font-bold">Your Format</div> 
    <div class="flex justify-center border border-gray-300 font-bold">Equivalent</div>
    <div class="flex border border-gray-300">Employer</div>    <div class="flex border border-gray-300">Employer</div>
    <div class="col-span-2 grid grid-cols-2" id="employerDiv">   </div>
    <div class="flex border border-gray-300">Department</div> <div class="flex border border-gray-300">Department</div>
    <div class="col-span-2 grid grid-cols-2" id="departmentDiv"> </div>
    <div class="flex border border-gray-300">Section</div>    <div class="flex border border-gray-300">Section</div>
    <div class="col-span-2 grid grid-cols-2" id="sectionDiv">   </div>
    <div class="flex border border-gray-300">GPI8</div>    <div class="flex border border-gray-300">GPI8</div>
    <div class="col-span-2 grid grid-cols-2" id="gpi8Div">   </div>
    <!-- <div class="flex justify-center border border-gray-300">PI</div> <div class="flex justify-center border border-gray-300">Parts Inspection</div>
    <div class="flex justify-center border border-gray-300">Admin</div> <div class="flex justify-center border border-gray-300">Administration</div> -->
    </div>
    
</div>


            </div>
            <!-- Modal footer -->
            <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                <button id="proceedButton" type="button"  data-modal-hide="equivalentValues" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Proceed</button>
            </div>
        </div>
    </div>
</div>







            

    <script src="jquery.min.js"></script>
    <script type="text/javascript" src="node_modules/xlsx/dist/xlsx.full.min.js"></script>
    <script type="text/javascript" src="DataTables/datatables.min.js"></script>
    <script type="text/javascript" src="DataTables/Responsive-2.3.0/js/dataTables.responsive.min.js"></script>
  <script>




document.getElementById('proceedImportButton').addEventListener('click', () => {
  const fileInput = document.getElementById('file_input');
  const file = fileInput.files[0];

  if (!file) {
    alert("Please select a file to upload.");
    return;
}
const reader = new FileReader();

reader.onload = (event) => {
    const data = new Uint8Array(event.target.result);
    const workbook = XLSX.read(data, { type: 'array' });

    // Assuming the first sheet contains the data
    const sheetName = workbook.SheetNames[0];
    const sheet = workbook.Sheets[sheetName];

    // Convert sheet data to JSON
    const jsonData = XLSX.utils.sheet_to_json(sheet, { header: 1 });

    const employer = jsonData.slice(1).map(row => row[3]);
    const uniqueEmployer = [...new Set(employer)];
    document.getElementById("employerFormat1").value=uniqueEmployer;

    const department = jsonData.slice(1).map(row => row[4]);
    const uniqueDepartments = [...new Set(department)];
console.log(uniqueDepartments);
document.getElementById("departmentFormat1").value=uniqueDepartments;





    const section = jsonData.slice(1).map(row => row[5]);
    const uniqueSection = [...new Set(section)];
    document.getElementById("sectionFormat1").value=uniqueSection;

        const gpi8 = jsonData.slice(1).map(row => row[6]);
    const uniqueGpi8 = [...new Set(gpi8)];
    document.getElementById("gpi8Format1").value=uniqueGpi8;
    // Display or process the data


                        var increment = 1;

    uniqueDepartments.forEach(department => {
     
    $('#departmentDiv').append(
  '<div class="flex justify-center border border-gray-300">'+department+'</div>' +   `<select id="department`+increment+`" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            <option selected disabled>Select Department</option>
                            <?php
                          
                            $sql = "SELECT DISTINCT `department` FROM `emp_list` WHERE `department` != ''";
                            $result = mysqli_query($con, $sql);
                            while ($row = mysqli_fetch_assoc($result)) {

                            ?>
                            <option value="<?php echo $row['department']; ?> "><?php echo $row['department']; ?> </option>
                            <?php }?>
                            <option value="">N/A</option>
                        </select>`
);
increment++;

    })

    var incrementEmployer = 1;
    uniqueEmployer.forEach(employer => {
    $('#employerDiv').append(
  '<div class="flex justify-center border border-gray-300">'+employer+'</div>' + `<select  id="employer`+incrementEmployer+`" class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full px-2.5 py-1.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            <option selected disabled>Select Employer</option>
                             <option value="GLORY">GLORY</option>
                            <option value="MAXIM">MAXIM</option>
                            <option value="NIPPI">NIPPI</option>
                            <option value="NATCORP">NATCORP</option>
                            <option value="SERVICE PROVIDER">SERVICE PROVIDER</option>
                            
                        </select>`
);
incrementEmployer++;
    })
    var incrementSection = 1;
    uniqueSection.forEach(section => {
    $('#sectionDiv').append(
  '<div class="flex justify-center border border-gray-300">'+section+'</div>' +   `<select id="section`+incrementSection+`" class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full px-2.5 py-1.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            <option selected disabled>Select Section</option>
                            <?php
                        $querydept = "SELECT DISTINCT `section` FROM `emp_list` WHERE `section` != ''"; //added LIMIT 10 para di bumagal ang pagencode ni Alien
                        $resultDept = mysqli_query($con, $querydept);
                    
                        while($emp_row = mysqli_fetch_assoc($resultDept)){
                            ?>
                            <option  value="<?php echo $emp_row['section']; ?>"><?php echo $emp_row['section']; ?></option>
                            <?php
                        
                        }
                        ?>
                        <option value="">N/A</option>
                        </select>`
);
incrementSection++;

    })


        var incrementGpi8 = 1;
    uniqueGpi8.forEach(gpi8 => {
    $('#gpi8Div').append(
  '<div class="flex justify-center border border-gray-300">'+gpi8+'</div>' +   `<select id="gpi8`+incrementGpi8+`" class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full px-2.5 py-1.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            <option selected disabled>Select Section</option>
                             <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>`
);
incrementGpi8++;

    })




    document.getElementById("proceedButton").addEventListener("click", function() {
console.log("asdasd");
$("#addEmployeeImport").removeClass("hidden");
      
      $("#addNewEmployeesImport").removeClass("hidden");
      $("#proceedImportButton").addClass("hidden");


      var uniqueDepartmentsArray = []; 
      var uniqueEmployerArray = []; 
      var uniqueSectionArray = []; 
      var uniqueGpi8Array = []; 


      // console.log("Number of unique departments:", uniqueDepartments.length);
      for(var i=1; i<=uniqueDepartments.length; i++){
        var dept = "department"+i;
        // console.log(document.getElementById(dept).value);
        const deptValues = document.getElementById(dept).value;
        uniqueDepartmentsArray.push(deptValues);
        // console.log(deptValues); 
      }
      for(var i=1; i<=uniqueEmployer.length; i++){
        var employer = "employer"+i;
        console.log(document.getElementById(employer).value);
        const employerValues = document.getElementById(employer).value;
        uniqueEmployerArray.push(employerValues);
        // console.log(sexValues); 
      }
      for(var i=1; i<=uniqueSection.length; i++){
        var section = "section"+i;
        console.log(document.getElementById(section).value);
        const sectionValues = document.getElementById(section).value;
        uniqueSectionArray.push(sectionValues);
        // console.log(civilValues); 
      }

         for(var i=1; i<=uniqueGpi8.length; i++){
        var gpi8 = "gpi8"+i;
        console.log(document.getElementById(gpi8).value);
        const gpi8Values = document.getElementById(gpi8).value;
        uniqueGpi8Array.push(gpi8Values);
        // console.log(civilValues); 
      }

      console.log(uniqueDepartmentsArray); 

    document.getElementById("departmentFormat2").value=uniqueDepartmentsArray;
    document.getElementById("employerFormat2").value=uniqueEmployerArray;
    document.getElementById("sectionFormat2").value=uniqueSectionArray;
    document.getElementById("gpi8Format2").value=uniqueGpi8Array;





    });


    // document.getElementById('output').innerText = JSON.stringify(jsonData, null, 2);
};

reader.readAsArrayBuffer(file);
});



              $(document).ready(function () {
  
  $('#employeeList').DataTable(  {
  "columnDefs": [
      { "width": "1%", "targets": 0},
      {"className": "dt-center", "targets": "_all"}
  ],
      responsive: true,
      
  }   );



  function toggleDiv() {
                if ($('input[name="optionGpi8"]:checked').val() === "1") {
            $('#gpi8options').css('display', 'block'); // Show the div
        } else {
            $('#gpi8options').css('display', 'none'); // Hide the div
        }
            }

            // Call toggleDiv on page load to apply the default selection logic
            toggleDiv();

            // Event listener for radio button change
            $('input[name="optionGpi8"]').change(function() {
                toggleDiv(); // Call toggleDiv when the selection changes
            });


  });

  const addEmpModal = document.getElementById('addEmployeeModal');

// options with default values
const addEmployee = {

   
    closable: false,
    onHide: () => {
        console.log('modal is hidden');
    },
    onShow: () => {
        console.log('modal is shown');
    },
    onToggle: () => {
        console.log('modal has been toggled');
    },
};

const modalAddEmployee = new Modal(addEmpModal, addEmployee);

function openAddEmpModal(){
modalAddEmployee.toggle();
}


const editEmpModal = document.getElementById('editEmployeeModal');

// options with default values
const editEmployee = {

   
    closable: false,
    onHide: () => {
        console.log('modal is hidden');
    },
    onShow: () => {
        console.log('modal is shown');
    },
    onToggle: () => {
        console.log('modal has been toggled');
    },
};

const modalEditEmployee = new Modal(editEmpModal, editEmployee);

function openEditEmpModal(values){
    document.getElementById("editDBId").value = values.getAttribute("data-id");
    document.getElementById("editempId").value = values.getAttribute("data-empid");
    document.getElementById("editcardNumber").value = values.getAttribute("data-card");
    document.getElementById("editfullName").value = values.getAttribute("data-name");
    document.getElementById("editemployer").value = values.getAttribute("data-employer");
    // document.getElementById("editName").value = element.getAttribute("data-gpi8");
    document.getElementById("editdepartment").value = values.getAttribute("data-department");
    document.getElementById("editsection").value = values.getAttribute("data-section");

    var gpi8 = values.getAttribute("data-gpi8")
    if(gpi8==="1"){
        $('input[name="editoptionGpi8"][value="1"]').prop('checked', true);
        toggleEditDiv();

    }
    else{
$('input[name="editoptionGpi8"][value="0"]').prop('checked', true);
toggleEditDiv();
    }
    




// $("#employeeID").val()

modalEditEmployee.toggle();
}

function closeModalEditEmployee(){
    modalEditEmployee.toggle();
};



function toggleEditDiv() {
                if ($('input[name="editoptionGpi8"]:checked').val() === "1") {
            $('#gpi8editoptions').css('display', 'block'); // Show the div
        } else {
            $('#gpi8editoptions').css('display', 'none'); // Hide the div
        }
            }

            // Call toggleDiv on page load to apply the default selection logic

            // Event listener for radio button change
            $('input[name="editoptionGpi8"]').change(function() {
                toggleEditDiv(); // Call toggleDiv when the selection changes
            });





  
function addEmployeeToDatabase() {
    // console.log("lkasdhf");

 
    var empId = document.getElementById("empId").value;
    var fullName = document.getElementById("fullName").value;
    var cardNumber = document.getElementById("cardNumber").value;
    var employer = document.getElementById("employer").value;
    var department = document.getElementById("department").value;
    var section = document.getElementById("section").value;

    
function getSelectedRadioValue(name) {
  return $('input[name="' + name + '"]:checked').val();
}

// Example usage
var optionGpi8 = getSelectedRadioValue('optionGpi8');
// console.log("Selected value: " + selectedValue);


console.log( empId, fullName, cardNumber, employer, optionGpi8)



if(empId==='' || fullName==='' || cardNumber==='' || employer==='' ){
    $('#duplicateMessage').addClass('hidden');
        $('#successMessage').addClass('hidden');
        $('#duplicateRFID').addClass('hidden');
        $('#incompleteForm').removeClass('hidden');


}
else{
    var addEmployee = new XMLHttpRequest();
addEmployee.open("POST", "addEmployee.php", true);
addEmployee.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
addEmployee.onreadystatechange = function() {
    console.log(addEmployee);
  if (addEmployee.readyState === XMLHttpRequest.DONE) {
    if (addEmployee.status === 200) {
      var response = JSON.parse(addEmployee.responseText);
      if (response.message === "Duplicate Value") {
        // alert("Duplicate Value");
        $('#duplicateMessage').removeClass('hidden');
        $('#successMessage').addClass('hidden');
        $('#incompleteForm').addClass('hidden');
        $('#duplicateRFID').addClass('hidden');



      } else if (response.message === "Success") {
        $('#successMessage').removeClass('hidden');
        $('#duplicateMessage').addClass('hidden');
        $('#incompleteForm').addClass('hidden');
        $('#duplicateRFID').addClass('hidden');



      }
      else if (response.message === "Duplicate RFID") {
        $('#duplicateRFID').removeClass('hidden');
        $('#successMessage').addClass('hidden');
        $('#duplicateMessage').addClass('hidden');
        $('#incompleteForm').addClass('hidden');
        

      }
      
      
      
      
      else {
        console.log("Error: " + response.message);
      }
    } else {
      console.log("Error: " + addEmployee.status);
    }
  }
};


    // Construct the data to be updated
    var data = "empId=" + encodeURIComponent(empId);
    data += "&fullName=" + encodeURIComponent(fullName);
    data += "&cardNumber=" + encodeURIComponent(cardNumber);
    data += "&employer=" + encodeURIComponent(employer);
    data += "&optionGpi8=" + encodeURIComponent(optionGpi8);
    data += "&department=" + encodeURIComponent(department);
    data += "&section=" + encodeURIComponent(section);





    // Add any other parameters needed for the update

    addEmployee.send(data);
 
}



  }




  
function updateEmployeeData() {
    // console.log("lkasdhf");

    var editDBId = document.getElementById("editDBId").value;
    var empId = document.getElementById("editempId").value;
    var fullName = document.getElementById("editfullName").value;
    var cardNumber = document.getElementById("editcardNumber").value;
    var employer = document.getElementById("editemployer").value;
    var department = document.getElementById("editdepartment").value;
    var section = document.getElementById("editsection").value;

    
function getSelectedRadioValue(name) {
  return $('input[name="' + name + '"]:checked').val();
}

// Example usage
var editoptionGpi8 = getSelectedRadioValue('editoptionGpi8');
// console.log("Selected value: " + selectedValue);


console.log( empId, fullName, cardNumber, employer, editoptionGpi8)



if(empId==='' || fullName==='' || cardNumber==='' || employer==='' ){
    $('#duplicateMessageEdit').addClass('hidden');
        $('#successMessageEdit').addClass('hidden');
        $('#duplicateRFIDEdit').addClass('hidden');
        $('#incompleteFormEdit').removeClass('hidden');


}
else{
    var editEmployee = new XMLHttpRequest();
editEmployee.open("POST", "editEmployee.php", true);
editEmployee.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
editEmployee.onreadystatechange = function() {
    console.log(editEmployee);
  if (editEmployee.readyState === XMLHttpRequest.DONE) {
    if (editEmployee.status === 200) {
      var response = JSON.parse(editEmployee.responseText);
      if (response.message === "Duplicate Value") {
        // alert("Duplicate Value");
        $('#duplicateMessageEdit').removeClass('hidden');
        $('#successMessageEdit').addClass('hidden');
        $('#incompleteFormEdit').addClass('hidden');
        $('#duplicateRFIDEdit').addClass('hidden');



      } else if (response.message === "Success") {
        $('#successMessageEdit').removeClass('hidden');
        $('#duplicateMessageEdit').addClass('hidden');
        $('#incompleteFormEdit').addClass('hidden');
        $('#duplicateRFIDEdit').addClass('hidden');



      }
      else if (response.message === "Duplicate RFID") {
        $('#duplicateRFIDEdit').removeClass('hidden');
        $('#successMessageEdit').addClass('hidden');
        $('#duplicateMessageEdit').addClass('hidden');
        $('#incompleteFormEdit').addClass('hidden');
        

      }
      
      
      
      
      else {
        console.log("Error: " + response.message);
      }
    } else {
      console.log("Error: " + editEmployee.status);
    }
  }
};


    // Construct the data to be updated
    var data = "empId=" + encodeURIComponent(empId);
    data += "&editDBId=" + encodeURIComponent(editDBId);
    data += "&fullName=" + encodeURIComponent(fullName);
    data += "&cardNumber=" + encodeURIComponent(cardNumber);
    data += "&employer=" + encodeURIComponent(employer);
    data += "&editoptionGpi8=" + encodeURIComponent(editoptionGpi8);
    data += "&department=" + encodeURIComponent(department);
    data += "&section=" + encodeURIComponent(section);





    // Add any other parameters needed for the update

    editEmployee.send(data);
 
}



  }




  
    function downloadTemplate() {
       
    var rows = [];
    column0 = 'Employee Id Number';
    column1 = 'Full Name';
    column2 = 'Card Number';
    column3 = 'Employer';
    column4 = 'Department';
    column5 = 'Section';
    column6 = 'GPI 8 (Yes or No)';

rows.push(
    [
    column0,
    column1,
    column2,
    column3, 
    column4,
    column5,
    column6,
    ]
);


for (var i = 0, row; i < 1; i++) {
            column0 = '';
            column1 = '';
            column2 = '';
            column3 = "";
            column4 = '';
            column5 = '';
            column6 = '';

            rows.push(
                [
                    column0,
                    column1,
                    column2,
                    column3,
                    column4,
                    column5,
                    column6,
                ]
            );

        }

csvContent = "data:text/csv;charset=utf-8,";
/* add the column delimiter as comma(,) and each row splitted by new line character (\n) */
rows.forEach(function(rowArray) {
    row = rowArray.join('","');
    row = '"' + row + '"';
    csvContent += row + "\r\n";
});

/* create a hidden <a> DOM node and set its download attribute */
var encodedUri = encodeURI(csvContent);
var link = document.createElement("a");
link.setAttribute("href", encodedUri);
link.setAttribute("download", "Canteen Registration Template.csv");
document.body.appendChild(link);
/* download the data file named "Stock_Price_Report.csv" */
link.click();
}




  </script>

</body>
</html>
