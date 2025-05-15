<?php 
include ("connection.php");


header('Content-Type: application/json');


$empId = $_POST['empId'];
$fullName = $_POST['fullName'];
$cardNumber = $_POST['cardNumber'];
$employer = $_POST['employer'];
$optionGpi8 = $_POST['optionGpi8'];
$department = $_POST['department'];
$section = $_POST['section'];

if($optionGpi8 == 0){
    $department = '';
    $section = '';
}

// Check for duplicate values
$checkDuplicate = "SELECT * FROM `emp_list` WHERE `emp_idNum` = '$empId'";
$resultDuplicate = mysqli_query($con, $checkDuplicate);

if(mysqli_num_rows($resultDuplicate) > 0) {
    echo json_encode(["message" => "Duplicate Value"]);
} else {

    $checkDuplicate1 = "SELECT * FROM `emp_list` WHERE `emp_cardNum` = '$cardNumber'";
$resultDuplicate1 = mysqli_query($con, $checkDuplicate1);
if(mysqli_num_rows($resultDuplicate1) > 0) {
    echo json_encode(["message" => "Duplicate RFID"]);
}
else{
    $sql = "INSERT INTO `emp_list`(`emp_id`, `emp_idNum`, `emp_name`, `emp_cardNum`, `employer`, `department`, `section`, `gpi8`) VALUES (null ,'$empId','$fullName','$cardNumber','$employer','$department','$section','$optionGpi8')";
    $results = mysqli_query($con, $sql);
    
    if($results) {
        echo json_encode(["message" => "Success"]);
    } else {
        echo json_encode(["message" => "Error"]);
    }
}


 
}
?>
