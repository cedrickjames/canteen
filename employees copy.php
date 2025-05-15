<?php
    session_start();

    include("./connection.php");

    if(!isset($_SESSION['connected'])){
        header('location: login.php');
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
                    <a type="button" data-modal-target="importPreEmployment" data-modal-toggle="importPreEmployment" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Bulk Registration</a>
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
                            <option value="POWERLANE">POWERLANE</option>
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
                <div id="successMessage" class="hidden p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                <span class="font-medium">Update successfull!</span>
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
                            <option value="POWERLANE">POWERLANE</option>
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




            

    <script src="jquery.min.js"></script>
    <script type="text/javascript" src="DataTables/datatables.min.js"></script>
    <script type="text/javascript" src="DataTables/Responsive-2.3.0/js/dataTables.responsive.min.js"></script>
  <script>
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



  </script>

</body>
</html>
