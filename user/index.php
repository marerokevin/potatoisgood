<?php
session_start();

include_once ("../includes/config/conn.php");

if(!isset( $_SESSION['loggedin'])){
    header("location:../login.php");
  }else{
    if($_SESSION['permission']=='administ'){
    header("location:../admin/index.php");

    }
  }



$db= $conn;
date_default_timezone_set("Asia/Singapore");
$dateNow = new DateTime(); 
$dateNow  = $dateNow->format('M d, Y'); 
$DayNow = new DateTime(); 

$day  = $DayNow->format('D'); 

// echo $day; 




$member_id = $_SESSION["member_id"];
$email = $_SESSION["email_address"];
$id = $_SESSION["id"];
$SelectInfo ="SELECT * FROM `accounts` WHERE `member_id` = '$member_id';";
$resultInfo= mysqli_query($conn, $SelectInfo);
$fname="";
$lname="";

while($userRow = mysqli_fetch_assoc($resultInfo)){
    $fname = $userRow['first_name'];
    $lname = $userRow['last_name'];


}
$SelectPresentBalance ="SELECT * FROM `totalbalance` WHERE `userID` = '$member_id';";
$resultPresentBalance = mysqli_query($conn, $SelectPresentBalance);

while($userRow = mysqli_fetch_assoc($resultPresentBalance)){
    $totalBalance = $userRow['totalBalance'];
    $unclaimableBalance = $userRow['unclaimable'];
    $totalIncome = $userRow['totalIncome'];


}
$fullname = $fname.' '.$lname;
$email = $_SESSION["email_address"];
if(isset($_POST['enterCode'])){

    $EnteredCode = $_POST['EnteredCode'];

    $sqlSelectCode= "SELECT * FROM `referral_codes` WHERE `ref_code` = '$EnteredCode' AND `codetype`='RA' OR `ref_code` = '$EnteredCode' AND `codetype`='RB';";

    $resultSelectCode = mysqli_query($conn, $sqlSelectCode);
    $num_of_select_code = mysqli_num_rows($resultSelectCode);
    echo "<script>console.log('cedrick$num_of_select_code')</script>";
    while($userRow = mysqli_fetch_assoc($resultSelectCode))
        {
        $userNameOfCodeOwner = $userRow['userNameOfCodeOwner'];
        $type = $userRow['codetype'];


            if($num_of_select_code !=0)
            {
                if($userNameOfCodeOwner !=""){
                    // echo "This is code is already used. Please enter another code";
                    echo "<script>alert('This is code is already used. Please enter another code')</script>";
                }
                else{
                    $sqlSelectUnclaimable= "SELECT * FROM `totalbalance` WHERE `userID` = '$member_id'";
                    $resultSelectUnclaimable = mysqli_query($conn, $sqlSelectUnclaimable);
                    while($userRow = mysqli_fetch_assoc($resultSelectUnclaimable))
                    {
                        $unclaimable = $userRow['unclaimable'];
                        $totalBalance4 = $userRow['totalBalance'];

                        if($unclaimable>=0){
                            $updatedBalance4 = $unclaimable+$totalBalance4;
                            $sqlAddBalance3= "UPDATE `totalbalance` SET `totalBalance`='$updatedBalance4' WHERE `userID` = '$member_id'";
                            mysqli_query($conn, $sqlAddBalance3);

                            $code = "TR";
                            $get_month = date('m', strtotime("now"));
                        
                            $sqlLastID = "SELECT MAX(transactionIdBasis) as 'idnumber' FROM `transaction` WHERE 1";//select the highest number_basis
                            $getLastId = mysqli_query($conn, $sqlLastID);
                            while($userRow = mysqli_fetch_assoc($getLastId)){
                                $lastId = $userRow['idnumber'];
                                $lastId++; //increment the number_basis
                            }
    
                            
                            $getDateNow = new DateTime();
                            $getYearNow  = $getDateNow->format('Y'); 
                            $getMonthNow  = $getDateNow->format('m'); 
                            $getDateNow  = $getDateNow->format('d'); 
                            $getDateNowReal = new DateTime();
                            $FullDateOfthisDay = $getDateNowReal->format('Y-m-d'); 
                            $timenow = date("h:i a"); 
    
                            $transactionId = $code."-".$getYearNow."".$getDateNow."".$getMonthNow."".$lastId;


                            $sqlinsertTransact= "INSERT INTO `transaction`(`transaction2ndId`,`Date`,`time`,`type`, `userName`, `userId`, `addedAmount`, `TotalBalance`,`transactionIdBasis`)VALUES ('$transactionId','$FullDateOfthisDay','$timenow','Claimed Rebates','$email','$member_id','$unclaimable','$updatedBalance4','$lastId')";
                            mysqli_query($conn, $sqlinsertTransact);
                            $sqlReset= "UPDATE `totalbalance` SET `unclaimable`='0' WHERE `userID` = '$member_id'";
                            mysqli_query($conn, $sqlReset);

                        }
                    }
                    $sqlUpdateCodeOwner= "UPDATE `referral_codes` SET `userNameOfCodeOwner`='$email', `transfer_date`=current_timestamp(), `referee`='$member_id', `status`='used'  WHERE `ref_code` = '$EnteredCode'";

                    mysqli_query($conn, $sqlUpdateCodeOwner);
                    // echo "You have successfully enter the code!";
                    $sqlSelectRebatesPoints= "SELECT * FROM `rebates_points` WHERE `user_id` = '$member_id'";
                    $resultSelectRPoints = mysqli_query($conn, $sqlSelectRebatesPoints);
                    $num_of_select_points = mysqli_num_rows($resultSelectRPoints);
                    if($num_of_select_points==0){
                        $sqlinsertPoints= "INSERT INTO `rebates_points`(`user_id`, `email_address`, `pointsEarned`) VALUES ('$member_id','$email','1')";
                        mysqli_query($conn, $sqlinsertPoints);

                        
                        $code = "TR";
                        $get_month = date('m', strtotime("now"));
                    
                        $sqlLastID = "SELECT MAX(transactionIdBasis) as 'idnumber' FROM `transaction` WHERE 1";//select the highest number_basis
                        $getLastId = mysqli_query($conn, $sqlLastID);
                        while($userRow = mysqli_fetch_assoc($getLastId)){
                            $lastId = $userRow['idnumber'];
                            $lastId++; //increment the number_basis
                        }

                        
                        $getDateNow = new DateTime();
                        $getYearNow  = $getDateNow->format('Y'); 
                        $getMonthNow  = $getDateNow->format('m'); 
                        $getDateNow  = $getDateNow->format('d'); 
                        $getDateNowReal = new DateTime();
                        $FullDateOfthisDay = $getDateNowReal->format('Y-m-d'); 
                        $timenow = date("h:i a"); 

                        $transactionId = $code."-".$getYearNow."".$getDateNow."".$getMonthNow."".$lastId;


                          $sqlinsertTransacPoints= "INSERT INTO `transaction`(`transaction2ndId`,`Date`,`time`,`type`, `userName`, `userId`, `packageType`, `addedPoints`, `totalPoints`,`transactionIdBasis`)VALUES ('$transactionId','$FullDateOfthisDay','$timenow','Points','$email','$member_id','$type','1','1','$lastId')";
                                mysqli_query($conn, $sqlinsertTransacPoints);
                    }
                    else{
                        $sqlSelectRebatesPoints2= "SELECT * FROM `rebates_points` WHERE `user_id` = '$member_id'";
                        $resultSelectRPoints2 = mysqli_query($conn, $sqlSelectRebatesPoints2);

                        while($userRow = mysqli_fetch_assoc($resultSelectRPoints2))
                        {
                        $pointsEarned = $userRow['pointsEarned'];
                        $pointsEarned++;
                        $sqlUpdatePointsEarned= "UPDATE `rebates_points` SET `pointsEarned`='$pointsEarned' WHERE `user_id` = '$member_id'";
                        mysqli_query($conn, $sqlUpdatePointsEarned);

                        
                        
                        $code = "TR";
                        $get_month = date('m', strtotime("now"));
                    
                        $sqlLastID = "SELECT MAX(transactionIdBasis) as 'idnumber' FROM `transaction` WHERE 1";//select the highest number_basis
                        $getLastId = mysqli_query($conn, $sqlLastID);
                        while($userRow = mysqli_fetch_assoc($getLastId)){
                            $lastId = $userRow['idnumber'];
                            $lastId++; //increment the number_basis
                        }

                        
                        $getDateNow = new DateTime();
                        $getYearNow  = $getDateNow->format('Y'); 
                        $getMonthNow  = $getDateNow->format('m'); 
                        $getDateNow  = $getDateNow->format('d'); 
                        $getDateNowReal = new DateTime();
                        $FullDateOfthisDay = $getDateNowReal->format('Y-m-d'); 
                        $timenow = date("h:i a"); 

                        $transactionId = $code."-".$getYearNow."".$getDateNow."".$getMonthNow."".$lastId;
                        
                        $sqlinsertTransacPoints= "INSERT INTO `transaction`(`transaction2ndId`,`Date`,`time`,`type`, `userName`, `userId`, `packageType`, `addedPoints`, `totalPoints`,`transactionIdBasis`)VALUES ('$transactionId','$FullDateOfthisDay','$timenow','Points','$email','$member_id','$type','1','$pointsEarned','$lastId')";
                        mysqli_query($conn, $sqlinsertTransacPoints);
                        }                
                    }
                    if($type == "RA"){
                        $sponsor=$member_id;
                    
                        $sqlGetL1= "SELECT `rebatesA` FROM `rebatesamount` WHERE `rebatesA` != ''; ";
                        $resultL1 = mysqli_query($conn, $sqlGetL1);
                        $numrows = mysqli_num_rows($resultL1);
    
                        for ($i = 1; $i<=$numrows; $i++)
                        { 
                               
                        //Update sponsor unclaimmable balance
                        $sqlUserSponsor= "SELECT * FROM `accounts` WHERE `member_id` = '$sponsor';";
                        $resultUserSponsor = mysqli_query($conn, $sqlUserSponsor);
                        while($userRow = mysqli_fetch_assoc($resultUserSponsor))
                            {
                                $inviteeID = $userRow['sponsor'];
                                $selectPoints= "SELECT * FROM `rebates_points` WHERE `user_id` = '$inviteeID';";
                                $resultPoints = mysqli_query($conn, $selectPoints);
                                $points=0;
                                while($userRow = mysqli_fetch_assoc($resultPoints))
                                {
                                    $points = $userRow['pointsEarned'];
                                }
                                if($points==0){
                                    $sqlGetTotalBalance= "SELECT * FROM `totalbalance` WHERE `userID` = '$inviteeID'";
                                    $resultTotalBalance = mysqli_query($conn, $sqlGetTotalBalance);
                                    
                                    $unclaimableBalance = 0;
                                    $emailOfSponsor="";
                                    while($userRow = mysqli_fetch_assoc($resultTotalBalance)){
                                        $unclaimableBalance = $userRow['unclaimable'];
                                        $emailOfSponsor = $userRow['userName'];
                                    }
        
        
                                    $sqlGetL1= "SELECT `rebatesA` FROM `rebatesamount` WHERE `id` = '$i'";
                                        $resultL1 = mysqli_query($conn, $sqlGetL1);
                                        
                                        $L1 = 0;
                                        while($userRow = mysqli_fetch_assoc($resultL1)){
                                            $L1 = $userRow['rebatesA'];
                                        }
                                        $updatedBalance = $unclaimableBalance + $L1;

                                          
                        
                        $code = "TR";
                        $get_month = date('m', strtotime("now"));
                    
                        $sqlLastID = "SELECT MAX(transactionIdBasis) as 'idnumber' FROM `transaction` WHERE 1";//select the highest number_basis
                        $getLastId = mysqli_query($conn, $sqlLastID);
                        while($userRow = mysqli_fetch_assoc($getLastId)){
                            $lastId = $userRow['idnumber'];
                            $lastId++; //increment the number_basis
                        }

                        
                        $getDateNow = new DateTime();
                        $getYearNow  = $getDateNow->format('Y'); 
                        $getMonthNow  = $getDateNow->format('m'); 
                        $getDateNow  = $getDateNow->format('d'); 
                        $getDateNowReal = new DateTime();
                        $FullDateOfthisDay = $getDateNowReal->format('Y-m-d'); 
                        $timenow = date("h:i a"); 

                        $transactionId = $code."-".$getYearNow."".$getDateNow."".$getMonthNow."".$lastId;
                        

                                        
                                        $sqlinsertTransact= "INSERT INTO `transaction`(`transaction2ndId`,`Date`,`time`,`type`, `userName`, `userId`, `packageType`, `codeOwner`, `codeOwnerId`, `addedAmount`, `TotalBalance`,`transactionIdBasis`)VALUES ('$transactionId','$FullDateOfthisDay','$timenow','Unclaimable Rebates','$emailOfSponsor','$inviteeID','$type','$fullname','$member_id','$L1','$updatedBalance','$lastId')";
                                        mysqli_query($conn, $sqlinsertTransact);
       
          
                                    $sqlAddBalance= "UPDATE `totalbalance` SET `unclaimable`='$updatedBalance' WHERE `userID` = '$inviteeID'";
                                    mysqli_query($conn, $sqlAddBalance);
        
                                 
        
        
                                    $sponsor = $inviteeID;
                                }
                                else{
                                    //Update sponsor total balance
                           $sqlUserSponsor= "SELECT * FROM `accounts` WHERE `member_id` = '$sponsor';";
                           $resultUserSponsor = mysqli_query($conn, $sqlUserSponsor);
                           while($userRow = mysqli_fetch_assoc($resultUserSponsor))
                               {
                                   $inviteeID = $userRow['sponsor'];
                                       
                                   $sqlGetTotalBalance= "SELECT * FROM `totalbalance` WHERE `userID` = '$inviteeID'";
                                   $resultTotalBalance = mysqli_query($conn, $sqlGetTotalBalance);
                                   
                                   $totalBalance1 = 0;
                                   $emailOfSponsor="";
                                   while($userRow = mysqli_fetch_assoc($resultTotalBalance)){
                                       $totalBalance1 = $userRow['totalBalance'];
                                       $emailOfSponsor = $userRow['userName'];
                                   }
       
       
                                   $sqlGetL1= "SELECT `rebatesA` FROM `rebatesamount` WHERE `id` = '$i'";
                                       $resultL1 = mysqli_query($conn, $sqlGetL1);
                                       
                                       $L1 = 0;
                                       while($userRow = mysqli_fetch_assoc($resultL1)){
                                           $L1 = $userRow['rebatesA'];
                                       }
                                       $updatedBalance = $totalBalance1 + $L1;

                                       
                        $code = "TR";
                        $get_month = date('m', strtotime("now"));
                    
                        $sqlLastID = "SELECT MAX(transactionIdBasis) as 'idnumber' FROM `transaction` WHERE 1";//select the highest number_basis
                        $getLastId = mysqli_query($conn, $sqlLastID);
                        while($userRow = mysqli_fetch_assoc($getLastId)){
                            $lastId = $userRow['idnumber'];
                            $lastId++; //increment the number_basis
                        }

                        
                        $getDateNow = new DateTime();
                        $getYearNow  = $getDateNow->format('Y'); 
                        $getMonthNow  = $getDateNow->format('m'); 
                        $getDateNow  = $getDateNow->format('d'); 
                        $getDateNowReal = new DateTime();
                        $FullDateOfthisDay = $getDateNowReal->format('Y-m-d'); 
                        $timenow = date("h:i a"); 

                        $transactionId = $code."-".$getYearNow."".$getDateNow."".$getMonthNow."".$lastId;
                        

                                       $sqlinsertTransact= "INSERT INTO `transaction`(`transaction2ndId`,`Date`,`time`,`type`, `userName`, `userId`, `packageType`, `codeOwner`, `codeOwnerId`, `addedAmount`, `TotalBalance`,`transactionIdBasis`)VALUES ('$transactionId','$FullDateOfthisDay','$timenow','Rebates','$emailOfSponsor','$inviteeID','$type','$fullname','$member_id','$L1','$updatedBalance','$lastId')";
                                       mysqli_query($conn, $sqlinsertTransact);
      
         
                                   $sqlAddBalance= "UPDATE `totalbalance` SET `totalBalance`='$updatedBalance' WHERE `userID` = '$inviteeID'";
                                   mysqli_query($conn, $sqlAddBalance);
       
                                
       
       
                                   $sponsor = $inviteeID;
                             }
                               }
                               
                          }
                            
                            

                       
                        }
                        echo "<script>alert('You have successfully entered the code!')</script>";
    
                    }
                    else if($type == "RB"){
                        $sponsor=$member_id;
                    
                        $sqlGetL1= "SELECT `rebatesB` FROM `rebatesamount` WHERE `rebatesB` != ''; ";
                        $resultL1 = mysqli_query($conn, $sqlGetL1);
                        $numrows = mysqli_num_rows($resultL1);
    
                        for ($i = 1; $i<=$numrows; $i++)
                        { 
                               
                        //Update sponsor unclaimmable balance
                        $sqlUserSponsor= "SELECT * FROM `accounts` WHERE `member_id` = '$sponsor';";
                        $resultUserSponsor = mysqli_query($conn, $sqlUserSponsor);
                        while($userRow = mysqli_fetch_assoc($resultUserSponsor))
                            {
                                $inviteeID = $userRow['sponsor'];
                                $selectPoints= "SELECT * FROM `rebates_points` WHERE `user_id` = '$inviteeID';";
                                $resultPoints = mysqli_query($conn, $selectPoints);
                                $points=0;
                                while($userRow = mysqli_fetch_assoc($resultPoints))
                                {
                                    $points = $userRow['pointsEarned'];
                                }
                                if($points==0){
                                    $sqlGetTotalBalance= "SELECT * FROM `totalbalance` WHERE `userID` = '$inviteeID'";
                                    $resultTotalBalance = mysqli_query($conn, $sqlGetTotalBalance);
                                    
                                    $unclaimableBalance = 0;
                                    $emailOfSponsor="";
                                    while($userRow = mysqli_fetch_assoc($resultTotalBalance)){
                                        $unclaimableBalance = $userRow['unclaimable'];
                                        $emailOfSponsor = $userRow['userName'];
                                    }
        
        
                                    $sqlGetL1= "SELECT `rebatesB` FROM `rebatesamount` WHERE `id` = '$i'";
                                        $resultL1 = mysqli_query($conn, $sqlGetL1);
                                        
                                        $L1 = 0;
                                        while($userRow = mysqli_fetch_assoc($resultL1)){
                                            $L1 = $userRow['rebatesB'];
                                        }
                                        $updatedBalance = $unclaimableBalance + $L1;

                                        
                        $code = "TR";
                        $get_month = date('m', strtotime("now"));
                    
                        $sqlLastID = "SELECT MAX(transactionIdBasis) as 'idnumber' FROM `transaction` WHERE 1";//select the highest number_basis
                        $getLastId = mysqli_query($conn, $sqlLastID);
                        while($userRow = mysqli_fetch_assoc($getLastId)){
                            $lastId = $userRow['idnumber'];
                            $lastId++; //increment the number_basis
                        }

                        
                        $getDateNow = new DateTime();
                        $getYearNow  = $getDateNow->format('Y'); 
                        $getMonthNow  = $getDateNow->format('m'); 
                        $getDateNow  = $getDateNow->format('d'); 
                        $getDateNowReal = new DateTime();
                        $FullDateOfthisDay = $getDateNowReal->format('Y-m-d'); 
                        $timenow = date("h:i a"); 

                        $transactionId = $code."-".$getYearNow."".$getDateNow."".$getMonthNow."".$lastId;
                        


                                        $sqlinsertTransact= "INSERT INTO `transaction`(`transaction2ndId`,`Date`,`time`,`type`, `userName`, `userId`, `packageType`, `codeOwner`, `codeOwnerId`, `addedAmount`, `TotalBalance`,`transactionIdBasis`)VALUES ('$transactionId','$FullDateOfthisDay','$timenow','Unclaimable Rebates','$emailOfSponsor','$inviteeID','$type','$fullname','$member_id','$L1','$updatedBalance','$lastId')";
                                        mysqli_query($conn, $sqlinsertTransact);
       
          
                                    $sqlAddBalance= "UPDATE `totalbalance` SET `unclaimable`='$updatedBalance' WHERE `userID` = '$inviteeID'";
                                    mysqli_query($conn, $sqlAddBalance);
        
                                 
        
        
                                    $sponsor = $inviteeID;
                                }
                                else{
                                    //Update sponsor total balance
                           $sqlUserSponsor= "SELECT * FROM `accounts` WHERE `member_id` = '$sponsor';";
                           $resultUserSponsor = mysqli_query($conn, $sqlUserSponsor);
                           while($userRow = mysqli_fetch_assoc($resultUserSponsor))
                               {
                                   $inviteeID = $userRow['sponsor'];
                                       
                                   $sqlGetTotalBalance= "SELECT * FROM `totalbalance` WHERE `userID` = '$inviteeID'";
                                   $resultTotalBalance = mysqli_query($conn, $sqlGetTotalBalance);
                                   
                                   $totalBalance2 = 0;
                                   $emailOfSponsor="";
                                   while($userRow = mysqli_fetch_assoc($resultTotalBalance)){
                                       $totalBalance2 = $userRow['totalBalance'];
                                       $emailOfSponsor = $userRow['userName'];
                                   }
       
       
                                   $sqlGetL1= "SELECT `rebatesB` FROM `rebatesamount` WHERE `id` = '$i'";
                                       $resultL1 = mysqli_query($conn, $sqlGetL1);
                                       
                                       $L1 = 0;
                                       while($userRow = mysqli_fetch_assoc($resultL1)){
                                           $L1 = $userRow['rebatesB'];
                                       }
                                       $updatedBalance = $totalBalance2 + $L1;


                                       
                                        
                        $code = "TR";
                        $get_month = date('m', strtotime("now"));
                    
                        $sqlLastID = "SELECT MAX(transactionIdBasis) as 'idnumber' FROM `transaction` WHERE 1";//select the highest number_basis
                        $getLastId = mysqli_query($conn, $sqlLastID);
                        while($userRow = mysqli_fetch_assoc($getLastId)){
                            $lastId = $userRow['idnumber'];
                            $lastId++; //increment the number_basis
                        }

                        
                        $getDateNow = new DateTime();
                        $getYearNow  = $getDateNow->format('Y'); 
                        $getMonthNow  = $getDateNow->format('m'); 
                        $getDateNow  = $getDateNow->format('d'); 
                        $getDateNowReal = new DateTime();
                        $FullDateOfthisDay = $getDateNowReal->format('Y-m-d'); 
                        $timenow = date("h:i a"); 

                        $transactionId = $code."-".$getYearNow."".$getDateNow."".$getMonthNow."".$lastId;
                        


                                       $sqlinsertTransact= "INSERT INTO `transaction`(`transaction2ndId`,`Date`,`time`,`type`, `userName`, `userId`, `packageType`, `codeOwner`, `codeOwnerId`, `addedAmount`, `TotalBalance`,`transactionIdBasis`)VALUES ('$transactionId','$FullDateOfthisDay','$timenow','Rebates','$emailOfSponsor','$inviteeID','$type','$fullname','$member_id','$L1','$updatedBalance','$lastId')";
                                       mysqli_query($conn, $sqlinsertTransact);
      
         
                                   $sqlAddBalance= "UPDATE `totalbalance` SET `totalBalance`='$updatedBalance' WHERE `userID` = '$inviteeID'";
                                   mysqli_query($conn, $sqlAddBalance);
       
                                
       
       
                                   $sponsor = $inviteeID;
                             }
                               }
                               
                          }
                            
                            

                       
                        }
                        echo "<script>alert('You have successfully enter the code!')</script>";
    
    
                    }
                   
                }
            }
           
         }
         if($num_of_select_code==0){
            echo "<script> alert('This code does not exist or already been used.')</script>";
        }
}

// getiing the points\
$totalPoints=0;
$sqlSelectRebatesPoints3= "SELECT * FROM `rebates_points` WHERE `user_id` = '$member_id'";
$resultSelectRPoints3 = mysqli_query($conn, $sqlSelectRebatesPoints3);
while($userRow = mysqli_fetch_assoc($resultSelectRPoints3)){
    $totalPoints = $userRow['pointsEarned'];
}

// code for getting the transaction//
$tableNameTransaction="transaction";
$columnsTransaction= ['transactionId', 'Date', 'time','type','userName','userId','inviteName','inviteeName' ,'addedAmount', 'TotalBalance'];
$fetchDataTransaction= fetch_transaction($db, $tableNameTransaction, $columnsTransaction);


function fetch_transaction($db, $tableNameTransaction, $columnsTransaction){


 if(empty($db)){
  $msg= "Database connection error";
 }elseif (empty($columnsTransaction) || !is_array($columnsTransaction)) {
  $msg="columns Name must be defined in an indexed array";
 }elseif(empty($tableNameTransaction)){
   $msg= "Table Name is empty";
}else{
$columnName = implode(", ", $columnsTransaction);
$member_id = $_SESSION["member_id"];
$query = "SELECT * FROM `transaction` WHERE `userId` = '$member_id' ORDER BY `transactionId` DESC";

//  SELECT * FROM `usertask` WHERE `username` = 'cjorozo';
$result = $db->query($query);
if($result== true){ 
 if ($result->num_rows > 0) {
    $row= mysqli_fetch_all($result, MYSQLI_ASSOC);
    $msg= $row;
 } else {
    $msg= "No Data Found"; 
 }
}else{
  $msg= mysqli_error($db);
}
}
return $msg;
}
// end of code for getting the transaction//


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/styles.css">
    <!-- <link rel="stylesheet" href="./dist/output.css"> -->
    <link rel="stylesheet" href="https://unpkg.com/flowbite@1.5.3/dist/flowbite.min.css" />
    <link rel="stylesheet" href="../node_modules/tw-elements/dist/css/index.min.css" />

    <title>Arvie Direct Sales</title>
    <script src="../js/tailwind-3.1.8.js"></script>
    <script src="https://unpkg.com/flowbite@1.5.3/dist/flowbite.js"></script>
    <script src="../node_modules/tw-elements/dist/js/index.min.js"></script>
    <script src="../js/jquery-3.6.1.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="sweetalert2.all.min.js"></script>
    <script src="sweetalert2.min.js"></script>
<link rel="stylesheet" href="sweetalert2.min.css">
    <title>Arvie Cosmetic & Skincare  ProductsTrading</title>

    <style>
        @media screen and (min-width: 768px) {
            .user-dashboard-content-container {
                width: calc(100vw);
            }
            .bottom-content{
                height: calc(100% - 184px);
            }
            .navbar div .nav-items ul li {
                position: relative;
                padding: 0 25px;
                margin-left: 0 !important;
            }
            .navbar div .nav-items ul li:not(:last-child):after {
                position: absolute;
                right: 0;
                top: 50%;
                transform: translateY(-50%);
                content: "";
                width: 1px;
                height: 10px;
                background-color: #374151;
            }
        }
        @media screen and (min-width: 1024px) {
            .user-dashboard-content-container {
                width: calc(100vw - 256px);
            }
            .bottom-content{
                height: calc(100% - 184px);
            }
            .navbar div .nav-items ul li {
                position: relative;
                padding: 0 25px;
                margin-left: 0 !important;
            }
            .navbar div .nav-items ul li:not(:last-child):after {
                position: absolute;
                right: 0;
                top: 50%;
                transform: translateY(-50%);
                content: "";
                width: 1px;
                height: 10px;
                background-color: #374151;
            }
        }

        @media screen and (min-width: 1280px) {
            .user-dashboard-content-container {
                width: calc(100vw - 288px);
            }
            .bottom-content{
                height: calc(100% - 216px);
            }
            .navbar div .nav-items ul li {
                position: relative;
                padding: 0 25px;
                margin-left: 0 !important;
            }
            .navbar div .nav-items ul li:not(:last-child):after {
                position: absolute;
                right: 0;
                top: 50%;
                transform: translateY(-50%);
                content: "";
                width: 1px;
                height: 10px;
                background-color: #374151;
            }
        }
    </style>
</head>
<body>
    <?php include_once "./user-header.php"; ?>
    <?php include_once "./user-footer.php"; ?>

    <div class="flex flex-row">
        <div class="basis-0 lg:basis-64 xl:basis-72 hidden md:flex h-screen">
            <?php include_once "./user-nav.php"; ?>
        </div>
        <?php include_once "./offcanvas.php"; ?>

        <!-- Modal -->
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="exampleModalCenter" tabindex="-1" aria-labelledby="exampleModalCenterTitle" aria-modal="true" role="dialog">
            <div class="modal-dialog modal-dialog-centered relative w-auto pointer-events-none">
                
                    <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="modal-header flex flex-shrink-0 items-center justify-between p-4 border-b border-gray-200 rounded-t-md">
                        <h5 class="text-xl font-medium leading-normal text-gray-800" id="exampleModalScrollableLabel">
                        Enter Code
                        </h5>
                        <button type="button"
                        class="btn-close box-content w-4 h-4 p-1 text-black border-none rounded-none opacity-50 focus:shadow-none focus:outline-none focus:opacity-100 hover:text-black hover:opacity-75 hover:no-underline"
                        data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body relative p-4">
                    <form action="index.php" method="POST">
                    <div class="form-group mb-6">
                        <input type="text" name="EnteredCode" class="form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" id="exampleInput90" placeholder="Enter Code">
                    </div>
                    </div>
                    <div
                        class="modal-footer flex flex-shrink-0 flex-wrap items-center justify-end p-4 border-t border-gray-200 rounded-b-md">
                        <button type="button"
                        class="inline-block px-6 py-2.5 bg-gray-400 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-gray-500 hover:shadow-lg focus:bg-gray-600 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-gray-600 active:shadow-lg transition duration-150 ease-in-out"
                        data-bs-dismiss="modal">
                        Close
                        </button>
                        <button type="submit" name="enterCode"
                        class="inline-block px-6 py-2.5 bg-blue-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out ml-1">
                        Submit
                        </button>
                    </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="payoutModal" tabindex="-1" aria-labelledby="exampleModalCenterTitle" aria-modal="true" role="dialog">
            <div class="modal-dialog modal-xl relative w-auto pointer-events-none">
                
                    <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="modal-header flex flex-shrink-0 items-center justify-between p-4 border-b border-gray-200 rounded-t-md">
                        <h5 class="text-xl font-medium leading-normal text-gray-800" id="exampleModalScrollableLabel">
                        Payout Request
                        </h5>
                        <button type="button"
                        class="btn-close box-content w-4 h-4 p-1 text-black border-none rounded-none opacity-50 focus:shadow-none focus:outline-none focus:opacity-100 hover:text-black hover:opacity-75 hover:no-underline"
                        data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body relative p-4">
                        <?php 
                 
                        $DayNow = new DateTime(); 
                        $day  = $DayNow->format('D'); 
                        if($day !="Mon"){
                            echo "<h5 class='text-xl font-medium leading-normal text-gray-800'> Note: You can only request payout every Monday.</h5>";
                        }
                        if($totalBalance<1000){
                            echo "<h5 class='text-xl font-medium leading-normal text-gray-800'> Note: You don't have enough balance to request a payout. ₱ 1000.00 is the minimum amount.</h5>";
                        }
                        ?>
                    <h5 class="text-xl font-medium leading-normal text-gray-800" id="">
                        Your Total Balance is : <span> ₱ <?php $totalBalance3 = number_format($totalBalance, 2);echo $totalBalance3; //cedrick code?></span>
                        </h5>
                        <br>
                        <br>
                    <form action="index.php" method="POST">
                    <div class="mb-3 xl:w-96">
        <label for="exampleFormControlInput2" class="form-label inline-block mb-2 text-gray-700 text-xl">Enter amount</label>
        <input type="number" class=" form-control block w-full px-4 py-2  text-xl  font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" id="POAmount" name="POAmount" placeholder="Please enter a valid amount">
      </div>
      <div class="mb-3 xl:w-96">
        <label for="exampleFormControlInput2" class="form-label inline-block mb-2 text-gray-700 text-xl">Mode of Payment</label>
        <select class="form-select form-select-lg mb-3 appearance-none block w-full px-4 py-2 text-xl font-normal text-gray-700 bg-white bg-clip-padding bg-no-repeat border border-solid border-gray-300 rounded transition  ease-in-out  m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" aria-label=".form-select-lg example">
          <option disabled selected="">Choose mode of payment</option>
          <option value="Cash">Cash</option>
          <option value="Gcash">Gcash</option>
          <option value="BPI">BPI</option>
      </select></div>
                    
                    </div>
                    <div
                        class="modal-footer flex flex-shrink-0 flex-wrap items-center justify-end p-4 border-t border-gray-200 rounded-b-md">
                        <button type="button"
                        class="inline-block px-6 py-2.5 bg-gray-400 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-gray-500 hover:shadow-lg focus:bg-gray-600 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-gray-600 active:shadow-lg transition duration-150 ease-in-out"
                        data-bs-dismiss="modal">
                        Close
                        </button>
                        <button type="submit" name="enterCode"
                        class="inline-block px-6 py-2.5 bg-blue-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out ml-1">
                        Submit
                        </button>
                    </div>
                    </div>
                </form>
            </div>
        </div>

        <div class=" user-dashboard-content-container pt-20 px-2 sm:px-6 pb-6 bg-emerald-100 h-screen">
        <!-- <div class="grid grid-rows-2 md:grid-cols-2 gap-4 bg-gradient">
  <div class="...">01</div>
  <div class="...">02</div>

</div> -->
        <!-- <div class="grid-rows-2 p-3 h-sm-25 bg-success balance-container ">
            <div class="col-12 col-sm-6 order-2 order-sm-1">
                <h5 class="fs-5" id="totalIncomeLabel">Total Income Balance</h5>

                <h1 class="fw-bold fs-sm-8">P 100,000</h1>

            </div>
            <div class="col-12 col-sm-6 order-1 order-sm-2">
                <h8>Overall Income</h8>
                <h3>P 215, 340</h3>
            </div>

        </div> -->
    <!-- Error Message (Normally Hidden)-->
    <div id="alert-border-2" class="hidden flex p-4 mb-4 bg-red-100 border-t-4 border-red-500 " role="alert">
                <svg class="flex-shrink-0 w-5 h-5 text-red-700" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                <div class="ml-3 text-sm font-medium text-red-700">
                Error Message na Malufet!
                </div>
                <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-red-100  text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200  inline-flex h-8 w-8"  data-dismiss-target="#alert-border-2" aria-label="Close">
                    <span class="sr-only">Dismiss</span>
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>
            </div>
            <!-- Top Content -->
            <!-- <div class="hidden flex flex-col lg:flex-row h-80 sm:72 md:h-60 lg:h-52 xl:h-48 bg-gradient rounded-2xl">
                <div class="lg:w-1/2">
                    <div class="h-36 xl:h-48 rounded-2xl">
                        <div class="h-full pl-3 py-2 text-white items-center">
                            <div class="font-medium text-lg sm:text-lg lg:text-xl xl:text-2xl">Unclaimable Balance</div>
                            <div class="row-span-2 text-xl sm:text-2xl xl:text-3xl font-black">₱ <?php $unclaimableB = number_format($unclaimableBalance, 2);echo $unclaimableB; //cedrick code?></div>
                            <div class="row-span-2 text-lg sm:text-xl lg:text-xl xl:text-2xl font-medium">Available Balance as of <?php echo $dateNow; ?></div>
                            <div class="row-span-4 text-3xl sm:text-4xl xl:text-5xl font-black glow-font">₱ <?php $totalBalance2 = number_format($totalBalance, 2);echo $totalBalance2; //cedrick code?></div>
                        </div>
                    </div>
                </div>
                <div class="flex text-white pl-5 py-2 lg:w-1/2">
                    <div class="h-16">
                        <div class="font-medium text-md sm:text-lg xl:text-xl">Points Earned</div>
                        <div class="row-span-2 text-2xl xl:text-3xl font-black <?php if($totalPoints<=0){echo "text-red-600";} ?>"><?php echo $totalPoints;?></div>
                        <div class="row-span-2 text-lg sm:text-xl lg:text-xl xl:text-2xl font-medium">Total Income</div>
                        <div class="row-span-2 text-xl sm:text-2xl xl:text-3xl font-black">₱ <?php $totalIncomeA = number_format($totalIncome, 2);echo $totalIncomeA; //cedrick code?></div>


                    </div>

                    <div class="h-16 pt-2 ml-6">
                        <button type="button" class="px-6 py-2.5 bg-blue-600 text-white font-medium text-sm lg:text-sm uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out" data-bs-toggle="modal" data-bs-target="#exampleModalCenter">
                        Redeem Code
                        </button>
                    </div>
                </div>
            </div> -->
<!-- Top content testing -->
<button type="button" onclick="setClipboard('<?php echo $member_id; ?>')" class="block sm:hidden w-full inline-block mb-3 px-6 py-2 border-2 border-blue-600 text-blue-600 font-medium text-xs leading-normal uppercase rounded hover:bg-black hover:bg-opacity-5 focus:outline-none focus:ring-0 transition duration-150 ease-in-out">
Copy Referral Link
</button>
            <div class="grid grid-cols-2 gap-2 flex-col lg:flex-row h-96 sm:72 md:h-60 lg:h-52 xl:h-56 bg-gradient rounded-2xl">

                <div class="col-span-2 sm:col-span-1">
                    <div class="grid grid-cols-1 gap-2 flex-col w-full h-full pl-3 py-2 text-white">
                        <div class="grid grid-cols-2 gap w-full h-full">
                            <div class="col-span-1 sm:col-span-2 w-full h-full p-3">
                                <div class="col-span-2 font-medium text-lg sm:text-lg lg:text-xl xl:text-2xl">Unclaimable Balance</div>
                                <div class="col-span-2 row-span-2 text-xl sm:text-2xl xl:text-3xl font-black">₱ <?php $unclaimableB = number_format($unclaimableBalance, 2);echo $unclaimableB; //cedrick code?></div>
                            </div>
                            <div class=" block sm:hidden col-span-1 sm:col-span-2 w-full h-full p-3">
                                <div class="col-span-2 font-medium text-lg sm:text-lg lg:text-xl xl:text-2xl">Total Income</div>
                                <div class="col-span-2 row-span-2 text-xl sm:text-2xl xl:text-3xl font-black">₱ <?php $totalIncomeA = number_format($totalIncome, 2);echo $totalIncomeA; //cedrick code?></div>
                            </div>
                        </div>
                        <div class="block sm:hidden grid grid-cols-2 gap w-full h-full p-3">
                            <div class="col-span-1 sm:col-span-2 w-full h-full p-3">
                                <div class="col-span-2 row-span-2 text-lg sm:text-xl lg:text-xl xl:text-2xl font-medium">Total Balance</div>
                                <div class="col-span-2 row-span-4 text-3xl sm:text-4xl xl:text-5xl font-black glow-font">₱ <?php $totalBalance2 = number_format($totalBalance, 2);echo $totalBalance2; //cedrick code?></div>
                            </div>
                            <div class=" block sm:hidden col-span-1 sm:col-span-2 w-full h-full p-3">
                                <div class="col-span-2 font-medium text-lg sm:text-lg lg:text-xl xl:text-2xl">Points Earned</div>
                                <div class="col-span-2 row-span-2 text-3xl sm:text-4xl xl:text-5xl font-black <?php if($totalPoints<=0){echo "text-red-600";} ?>"><?php echo $totalPoints;?></div>
                            </div>
                        </div>
                        <div class="hidden sm:block col-span-2 row-span-2 text-lg sm:text-xl lg:text-xl xl:text-2xl font-medium">Available Balance as of <?php echo $dateNow; ?></div>
                        <div class="hidden sm:block col-span-2 row-span-4 text-3xl sm:text-4xl xl:text-5xl font-black glow-font">₱ <?php $totalBalance2 = number_format($totalBalance, 2);echo $totalBalance2; //cedrick code?></div>
                        
                    </div>   
                </div>
                <div data-bs-toggle="modal" data-bs-target="#exampleModalCenter" class="block sm:hidden col-span-2 p-2.5"><button type="button" class=" block sm:hidden w-full inline-block mb-3 px-6 py-2 px-6 py-2.5 bg-blue-600 text-white font-medium text-sm lg:text-sm uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out">
                Redeem Code
                </button></div>
                <div class="hidden sm:block col-span-2 sm:col-span-1">
                     <div class="grid grid-cols-2 gab-2 w-full h-full pl-3 py-2 text-white">
                     <div class="col-span-2 font-medium text-md sm:text-lg xl:text-xl">Points Earned</div>
                        <div class="col-span-1 row-span-2 text-2xl xl:text-3xl font-black <?php if($totalPoints<=0){echo "text-red-600";} ?>"><?php echo $totalPoints;?></div>
                        <div class="col-span-1 row-span-2 text-2xl xl:text-3xl font-black">
                             <div class="h-16 pt-2 ml-6">
                                <button type="button" class="px-6 py-2.5 bg-blue-600 text-white font-medium text-sm lg:text-sm uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out" data-bs-toggle="modal" data-bs-target="#exampleModalCenter">
                                    Redeem Code
                                </button>
                            </div>
                        </div>
                        <div class="hidden sm:block col-span-2 row-span-2 text-lg sm:text-xl lg:text-xl xl:text-2xl font-medium">Total Income</div>
                        <div class="hidden sm:block col-span-2 row-span-2 text-xl sm:text-2xl xl:text-3xl font-black">₱ <?php $totalIncomeA = number_format($totalIncome, 2);echo $totalIncomeA; //cedrick code?></div>

                    </div>
                </div>
            </div>
            <!-- Bottom Content -->
            <div class="bottom-content mt-6 mb-28 bg-gradient-transaction rounded-2xl">
            <!-- <div class="absolute  z-10 h-full w-full coffee-pattern bottom-content rounded-2xl"> </div> -->
            <div class="w-full h-full bottom-content px-3 py-3 rounded-2xl">
                <h1 class="text-xl xl:text-2xl font-black text-white">Income Details</h1>
                <h2 class="text-lg xl:text-xl font-black text-neutral-300">Latest</h2>
                <div style="height: calc(100% - 60px);" class="w-full h-full overflow-auto">
                    <!-- i-while loop lang to -->

                        <?php           
                            if(is_array($fetchDataTransaction)){      
                         
                                foreach($fetchDataTransaction as $data){
                                    $type = $data['type'];
                                    $inviteName = $data['inviteName'];
                                    $inviteeName = $data['inviteeName'];
                                    $date = $data['Date'];
                                    $time = $data['time'];

                                    if(strlen($inviteeName) >= '8'){

                                    };


                                    $addedAmount = $data['addedAmount'];
                                    $package = $data['packageType'];
                                    $codeOwner = $data['codeOwner'];

                                        if($type=="Direct Referral"){
                        ?>
                        <!-- Pag from Direct Referral -->
                        <div class="w-full h-28 lg:h-20 bg-white mt-3 rounded-xl grid grid-cols-5 grid-rows-3">
                            <div class="self-end text-center text-xs sm:text-sm xl:text-base font-medium">Category</div>
                            <div class="self-end text-center text-xs sm:text-sm xl:text-base font-medium">Invite's Name</div>
                            <div class="self-end text-center text-xs sm:text-sm xl:text-base font-medium"></div>

                            <div class="col-end-6 col-span-2 self-end text-center text-xs sm:text-sm xl:text-base font-medium"><?php echo $date.' '.$time; ?></div>

                            <div class="row-span-2 pl-1 mr-1 self-center text-center text-xs sm:text-base xl:text-xl font-bold text-green-600"> <?php echo $type; ?></div>
                            <div class="row-span-2 pl-1 mr-1 self-center text-center whitespace-normal overflow-hidden lg:whitespace-nowrap text-xs sm:text-base xl:text-xl font-bold"><?php echo $inviteName; ?></div>
                            <div class="row-span-2 pl-1 mr-1 self-center text-center whitespace-normal overflow-hidden lg:whitespace-nowrap text-xs sm:text-base xl:text-xl font-bold"></div>
                            <div class="col-span-2 row-span-2 self-center text-center mr-5 text-lg sm:text-xl md:text-2xl xl:text-3xl font-black">+  ₱ <?php $addedAmount = number_format($addedAmount, 2); echo $addedAmount;//cedrick code ?></div> 

                        </div>

                        <?php 
                                        }else if($type=="Indirect Referral"){
                        ?>
                        <!-- Pag from Indirect Referral -->
                        <div class="w-full h-28 lg:h-20 bg-white mt-3 rounded-xl grid grid-cols-5 grid-rows-3">
                            <div class="self-end text-center text-xs sm:text-sm xl:text-base font-medium">Category</div>
                            <div class="self-end text-center text-xs sm:text-sm xl:text-base font-medium">Downline Name</div>
                            <div class="self-end text-center text-xs sm:text-sm xl:text-base font-medium">Invite's Name</div>
                            <div class="col-end-6 col-span-2 self-end text-center text-xs sm:text-sm xl:text-base font-medium"><?php echo $date.' '.$time; ?></div>

                            <div class="row-span-2 pl-1 mr-1 self-center text-center text-xs sm:text-base xl:text-xl font-bold text-green-600"><?php echo $type; ?></div>
                            <div class="row-span-2 pl-1 mr-1 self-center text-center whitespace-normal overflow-hidden lg:whitespace-nowrap text-xs sm:text-base xl:text-xl font-bold"><?php echo $inviteeName; ?></div>
                            <div class="row-span-2 pl-1 mr-1 self-center text-center whitespace-normal overflow-hidden lg:whitespace-nowrap text-xs sm:text-base xl:text-xl font-bold"><?php echo $inviteName; ?></div>
                            <div class="col-span-2 row-span-2 self-center text-center mr-5 text-lg sm:text-xl md:text-2xl xl:text-3xl font-black">+ ₱ <?php $addedAmount = number_format($addedAmount, 2); echo $addedAmount;?></div>

                        </div>

                        <?php 
                                        }else if($type=="Rebates"){
                        ?>
                        <!-- Pag from rebate -->
                        <div class="w-full h-28 lg:h-20 bg-white mt-3 rounded-xl grid grid-cols-5 grid-rows-3">
                            <div class="self-end text-center text-xs sm:text-sm xl:text-base font-medium">Category</div>
                            <div class="self-end text-center text-xs sm:text-sm xl:text-base font-medium">Type</div>
                            <div class="self-end text-center text-xs sm:text-sm xl:text-base font-medium">Downline Name</div>
                            <div class="col-end-6 col-span-2 self-end text-center text-xs sm:text-sm xl:text-base font-medium"><?php echo $date.' '.$time; ?></div>

                            <div class="row-span-2 pl-1 mr-1 self-center text-center text-xs sm:text-base md:text-lg xl:text-xl font-bold text-orange-600">Rebate</div>
                            <div class="row-span-2 pl-1 mr-1 self-center text-center text-xs sm:text-base md:text-lg xl:text-xl font-bold"><?php if($package == 'RA'){ echo'Botanical';}else {echo 'Kapenato';} ?></div>
                            <div class="row-span-2 pl-1 mr-1 self-center text-center text-xs sm:text-base md:text-lg xl:text-xl overflow-hidden font-bold"><?php echo $codeOwner;?></div>
                            <div class="col-span-2 row-span-2 self-center text-center mr-5 text-lg sm:text-xl md:text-2xl xl:text-3xl font-black">+ ₱ <?php $addedAmount = number_format($addedAmount, 2); echo $addedAmount;?></div>

                        </div>

                        <?php 
                                        }
                                        else if($type=="Claimed Rebates"){
                                            ?>
                                            <!-- Pag from rebate -->
                                            <div class="w-full h-28 lg:h-20 bg-white mt-3 rounded-xl grid grid-cols-5 grid-rows-3">
                                                <div class="self-end text-center text-xs sm:text-sm xl:text-base font-medium">Category</div>
                                                <div class="self-end text-center text-xs sm:text-sm xl:text-base font-medium">Date </div>
                                                <div class="self-end text-center text-xs sm:text-sm xl:text-base font-medium">Time</div>
                                                <div class="row-span-3 col-span-2 self-center text-end mr-5 text-lg sm:text-xl md:text-2xl xl:text-3xl font-black">+ ₱ <?php $addedAmount = number_format($addedAmount, 2); echo $addedAmount;?></div>
                                                <div class="row-span-2 pl-1 mr-1 self-center text-center text-xs sm:text-base md:text-lg xl:text-xl font-bold text-orange-600">Claimed Rebates</div>
                                                <div class="row-span-2 pl-1 mr-1 self-center text-center whitespace-normal overflow-hidden lg:whitespace-nowrap text-xs sm:text-base xl:text-xl font-bold"><?php echo $date;?> </div>
                                                <div class="row-span-2 pl-1 mr-1 self-center text-center whitespace-normal overflow-hidden lg:whitespace-nowrap text-xs sm:text-base xl:text-xl font-bold"><?php echo $time;?> </div>
                                            </div>
                    
                                            <?php 
                                                            }
                                        else if($type=="Unclaimable Rebates"){
                                            ?>
                                            <!-- Pag from rebate -->
                                            <div class="w-full h-28 lg:h-20 bg-white mt-3 rounded-xl grid grid-cols-5 grid-rows-3">
                                                <div class="self-end text-center text-xs sm:text-sm xl:text-base font-medium">Category</div>
                                                <div class="self-end text-center text-xs sm:text-sm xl:text-base font-medium">Type</div>
                                                <div class="self-end text-center text-xs sm:text-sm xl:text-base font-medium">Downline Name</div>
                                                <div class="col-end-6 col-span-2 self-end text-center text-xs sm:text-sm xl:text-base font-medium"><?php echo $date.' '.$time; ?></div>

                                                <div class="row-span-2 pl-1 mr-1 self-center text-center text-xs sm:text-base md:text-lg xl:text-xl font-bold text-red-600">Unclaimable Rebates</div>
                                                <div class="row-span-2 pl-1 mr-1 self-center text-center text-xs sm:text-base md:text-lg xl:text-xl font-bold"><?php if($package == 'RA'){ echo'Botanical';}else {echo 'Kapenato';} ?></div>
                                                <div class="row-span-2 pl-1 mr-1 self-center text-center text-xs sm:text-base md:text-lg xl:text-xl overflow-hidden font-bold"><?php echo $codeOwner;?></div>
                                                <div class="col-span-2 row-span-2 self-center text-center mr-5 text-lg sm:text-xl md:text-2xl xl:text-3xl font-black">+ ₱ <?php $addedAmount = number_format($addedAmount, 2); echo $addedAmount;?></div>
                                           
                                            </div>
                    
                                            <?php 
                                                            }
                                                            elseif($type=="Points"){
                        ?>
                        <!-- Pag from rebate -->
                        <div class="w-full h-28 lg:h-20 bg-white mt-3 rounded-xl grid grid-cols-5 grid-rows-3">
                            <div class="self-end text-center text-xs sm:text-sm xl:text-base font-medium">Category</div>
                            <div class="self-end text-center text-xs sm:text-sm xl:text-base font-medium">Type</div>
                            <div class="self-end text-center text-xs sm:text-sm xl:text-base font-medium"></div>
                            <div class="col-end-6 col-span-2 self-end text-center text-xs sm:text-sm xl:text-base font-medium"><?php echo $date.' '.$time; ?></div>

                            <div class="row-span-2 pl-1 mr-1 self-center text-center text-xs sm:text-base md:text-lg xl:text-xl font-bold text-red-600">Points</div>
                            <div class="row-span-2 pl-1 mr-1 self-center text-center text-xs sm:text-base md:text-lg xl:text-xl overflow-hidden font-bold"><?php if($package == 'RA'){ echo'Botanical';}else {echo 'Kapenato';} ?></div>
                            <div class="row-span-2 pl-1 mr-1 self-center text-center whitespace-normal overflow-hidden lg:whitespace-nowrap text-xs sm:text-base xl:text-xl font-bold"></div>

                            <div class="col-span-2 row-span-2 self-center text-center mr-5 text-lg sm:text-xl md:text-2xl xl:text-3xl font-black">+ 1</div>

                            <div class="self-start text-center text-2xl md:text-lg xl:text-xl font-bold"></div>
                        </div>

                        <?php 
                                        }elseif($type=="Withdrawal"){
                        ?>
                        <!-- Pag out or withdraw -->
                        <div class="w-full h-28 lg:h-20 bg-white mt-3 rounded-xl grid grid-cols-5 grid-rows-2">
                            <div class="self-center text-center text-xs sm:text-sm xl:text-base font-medium">Category</div>
                            <div class="self-center text-center text-xs sm:text-sm xl:text-base font-medium">Date </div>
                            <div class="self-center text-center text-xs sm:text-sm xl:text-base font-medium">Time</div>
                            <div class="row-span-3 col-span-2 self-center text-end mr-5 text-sm sm:text-xl md:text-2xl xl:text-3xl font-black">- ₱ <?php $addedAmount = number_format($addedAmount, 2); echo $addedAmount;?></div>
                            <div class="row-span-2 pl-1 mr-1 self-center text-center text-xs sm:text-base xl:text-xl font-bold text-red-600">Withdrawal</div>
                            <div class="row-span-2 pl-1 mr-1 self-center text-center whitespace-normal overflow-hidden lg:whitespace-nowrap text-xs sm:text-base xl:text-xl font-bold"><?php echo $date;?> </div>
                            <div class="row-span-2 pl-1 mr-1 self-center text-center whitespace-normal overflow-hidden lg:whitespace-nowrap text-xs sm:text-base xl:text-xl font-bold"><?php echo $time;?> </div>

                        </div>

                        <?php 
                                        }
                                    }}else{

                                        }
                        ?>
                    <!-- end -->
                </div>
            </div>
          </div>   
          <br>
          <br>
   

        </div>
    </div>

	<?php


$member_id = $_SESSION["member_id"];


 if(isset($_POST['changePassword'])){
// echo "<script> console.log('asdasd')</script>";
    $selectpass = "SELECT `pass` FROM `accounts` WHERE `member_id` ='$member_id'";
$resultselectpass = mysqli_query($conn, $selectpass);
while($userRow = mysqli_fetch_assoc($resultselectpass)){
    $pass = $userRow['pass'];
}
$oldPassword = $_POST['oldPassword'];
$newPassword = $_POST['newPassword'];
$confirmPassword = $_POST['confirmPassword'];

$hash = password_hash($newPassword, PASSWORD_DEFAULT);
if(password_verify($oldPassword, $pass)){
    if($newPassword == $confirmPassword){

        $sqlUpdatePass=  "UPDATE `accounts` SET `pass`='$hash' WHERE `member_id` = '$member_id';";
        $updatepass = mysqli_query($conn, $sqlUpdatePass);
        if($updatepass){
            ?><script>
            Swal.fire({
          icon: 'success',
          title: 'Successful',
          text: 'Password Changed',
        //   footer: '<a href="">Why do I have this issue?</a>'
        }).then(function() {
            window.location = "index.php";
    });
         </script><?php 
        }

    }
    else{
        ?><script>
        Swal.fire({
      icon: 'error',
      title: 'Unsuccessful',
      text: 'Password not match.',
    //   footer: '<a href="">Why do I have this issue?</a>'
    }).then(function() {
        $('#changePassword').modal('show');
});
     </script><?php 

    }
    }
    else{
        ?><script>
        Swal.fire({
      icon: 'error',
      title: 'Unsuccessful',
      text: 'Wrong Old Password',
    //   footer: '<a href="">Why do I have this issue?</a>'
    }).then(function() {
        $('#changePassword').modal('show');
});
     </script><?php 

    }

  }
?>

<?php include_once "./changePassword.php"; ?>



    <script>
        $(document).ready(function(){
            $("#header_dashboard").addClass("bg-gray-300");
            $("#nav_dashboard").addClass("bg-emerald-700");
            $("#nav_dashboard").addClass("text-white");
            $("#nav_dashboard").removeClass("text-gray-600");
            $("#header_dashboard1").addClass("bg-gray-300");
            $("#nav_dashboard1").addClass("bg-emerald-700");
            $("#nav_dashboard1").addClass("text-white");
            $("#nav_dashboard1").removeClass("text-gray-600");

            $("#payoutFooter").addClass("hidden");
            $("#memberFooter").addClass("hidden");
            $("#profileFooter").addClass("hidden");
            $("#dashboardFooter").removeClass("hidden");

            $("#dashboardFooterA").removeClass("focus:text-orange-500");
            $("#dashboardFooterA").addClass("text-orange-500");


            focus:text-orange-500
        });
    </script>



</body>
</html>