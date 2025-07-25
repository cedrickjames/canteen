<?php
    session_start();

    include("./connection.php");
    
            $monDate = date("Y-m-d", strtotime("monday this week"));

        // Get Date of Mondays for the last 8 Weeks
        $lastMon = date_create($monDate)->modify("-7 days")->format("Y-m-d");
        $_SESSION['lastMon'] = $lastMon;


         $lastSun = date_create($monDate)->modify("-1 days")->format("Y-m-d");
        $_SESSION['lastSun'] = $lastSun;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/styles.css">
    <link rel="icon" href="./obj/canteen.png">
    <title>Total Sales Report</title>

    <script src="./jquery.min.js"></script>
</head>

<body id="totalSalesBody" onload="navFuntion()">

    <?php

        if(isset($_POST['sbmtPrint'])){
            
            $outputType = $_POST['outputType'];

            $fromDate = $_POST['totalFrom'];
            $toDate = $_POST['totalTo'];
            $arrayDate = array();
            $dateCount = 0;

            for($x = $fromDate; $x < date_create($toDate)->modify("+1 days")->format("Y-m-d"); $x = date_create($x)->modify("+1 days")->format("Y-m-d")){
                $dateCount++;
                array_push($arrayDate, $x);
            }

            $_SESSION['period'] = $arrayDate;
            $_SESSION['dateCount'] = $dateCount;
            $_SESSION['totalFrom'] = $fromDate;
            $_SESSION['totalTo'] = $toDate;

                if($outputType=="html"){
 ?>
                <script type="text/javascript">
                    // window.open('./TSReportPDF.php', '_blank');
                    window.open('./newTSReport.php', '_blank');

                </script>
            <?php
                }
                else{
                     ?>
                <script type="text/javascript">
                    // window.open('./TSReportPDF.php', '_blank');
                    window.open('./newTSReportExcel.php', '_blank');

                </script>
            <?php
                }
           

        }
    ?>

    <!-- Include Navigation Side Bar -->
    <?php require_once 'nav.php'; ?>

    
    <h1>TOTAL SALES REPORT</h1>

    <div class="totalSalesContainer">
        <form method="POST">
            <div class="totalSalesControl">
                <span class="fromCon">
                    From: <input type="date" name="totalFrom" id="dateFrom" onchange="btnClickF()" value="<?php echo $_SESSION['lastMon']; ?>">
                </span>
                <span class="toCon">
                    To: <input type="date" name="totalTo" id="dateTo" onchange="btnClickT()" value="<?php echo $_SESSION['lastSun']; ?>">
                </span>
                
                
            </div>
            <span class="type" name="">
                    Type: <select name="outputType" id="">
                <option value="html">HTML</option>
                <option value="excel">EXCEL</option>

            </select>
                </span>
            
            
            <input type="button" value="PRINT" class="btnPrint">
            <input type="submit" name="sbmtPrint" id="sbmtPrint" target="_blank" style="opacity: 0">
        </form>
    </div>
    
    <script>
        function navFuntion(){
            var wRep = document.getElementById("wkRep");
            wRep.classList.add("active");
            var wRep = document.getElementById("o2");
            wRep.classList.add("activeReport");
        }

        function btnClickF() {

            var fdate = document.getElementById("dateFrom").value;
            var tdate = document.getElementById("dateTo").value;

            var ftime = new Date(fdate).getTime();
            var ttime = new Date(tdate).getTime();

            if(ftime > ttime){
                document.getElementById("dateFrom").value = tdate;
            }
        }

        function btnClickT() {

            var fdate = document.getElementById("dateFrom").value;
            var tdate = document.getElementById("dateTo").value;

            var ftime = new Date(fdate).getTime();
            var ttime = new Date(tdate).getTime();

            if(ttime < ftime){
                document.getElementById("dateTo").value = fdate;
            }
        }

        $(".btnPrint").click(function(e) {
            $('#sbmtPrint').click();
        });

    </script>
</body>
</html>