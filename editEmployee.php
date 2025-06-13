<?php 
include ("connection.php");


header('Content-Type: application/json');


$empId = $_POST['empId'];
$editDBId = $_POST['editDBId'];
$fullName = $_POST['fullName'];
$cardNumber = $_POST['cardNumber'];
$employer = $_POST['employer'];
$optionGpi8 = $_POST['editoptionGpi8'];
$department = $_POST['department'];
$section = $_POST['section'];

if($optionGpi8 == 0){
    $department = '';
    $section = '';
}

// Check for duplicate values
$checkDuplicate = "SELECT * FROM `emp_list` WHERE `emp_idNum` = '$empId' AND `emp_id` != '$editDBId'";
$resultDuplicate = mysqli_query($con, $checkDuplicate);

if(mysqli_num_rows($resultDuplicate) > 0) {
    echo json_encode(["message" => "Duplicate Value"]);
} else {

    $checkDuplicate1 = "SELECT * FROM `emp_list` WHERE `emp_cardNum` = '$cardNumber' AND `emp_id` != '$editDBId'";
$resultDuplicate1 = mysqli_query($con, $checkDuplicate1);
if(mysqli_num_rows($resultDuplicate1) > 0) {
    echo json_encode(["message" => "Duplicate RFID"]);
}
else{

    

    $sql = "UPDATE `emp_list` SET `emp_idNum`='$empId',`emp_name`='$fullName',`emp_cardNum`='$cardNumber',`employer`='$employer',`department`='$department',`section`='$section',`gpi8`='$optionGpi8' WHERE `emp_id` = '$editDBId'";

    $results = mysqli_query($con, $sql);
    
    if($results) {
        echo json_encode(["message" => "Success"]);
    } else {
        echo json_encode(["message" => "Error"]);
    }
}


 
}
?>
