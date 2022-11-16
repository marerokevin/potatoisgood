<?php
session_start();
include_once ("../includes/config/conn.php");
date_default_timezone_set("Asia/Singapore");

if(!isset( $_SESSION['loggedin'])){
    header("location:../login.php");
  }else{
    if($_SESSION['permission']=='userist'){
    header("location:../user/index.php");

    }
  }
$db = $conn;

$getDateNow = new DateTime();
$DateForToday = $getDateNow->format('Y-m-d');
$DateForThisMonth = $getDateNow->format('Y-m');
$Year = $getDateNow->format('Y');
 //change rebates ammount

 if(isset($_POST['changeRebate'])){
    for($i=1; $i<=10; $i++){
        $rebatesA = $_POST['rebatesA'.$i];
        $rebatesB = $_POST['rebatesB'.$i];
        // echo "<script>console.log('$rebatesA'); </script>";
        // echo $rebatesA;
        // echo $rebatesB;
        $sqlUpdateRebates=  "UPDATE `rebatesamount` SET `rebatesA`='$rebatesA',`rebatesB`='$rebatesB' WHERE `id` = '$i'";
    mysqli_query($conn, $sqlUpdateRebates);
  
    }
    
    
  }

$selectFromCodes = "SELECT COUNT(`codetype`) * 2000 as 'DI_TotalSales' FROM `referral_codes` WHERE `codetype`= 'DI' AND `status` = 'used'";
$resultFromDI = mysqli_query($conn, $selectFromCodes);
while($userRow = mysqli_fetch_assoc($resultFromDI)){
    $DI_TotalSales = $userRow['DI_TotalSales'];
}

$selectFromCodesRA= "SELECT COUNT(`codetype`) * 1125 as 'RA_TotalSales' FROM `referral_codes` WHERE `codetype`= 'RA' AND `status` = 'used'";
$resultFromRA = mysqli_query($conn, $selectFromCodesRA);
while($userRow = mysqli_fetch_assoc($resultFromRA)){
    $RA_TotalSales = $userRow['RA_TotalSales'];
}
$selectFromCodesRB= "SELECT COUNT(`codetype`) * 1000 as 'RB_TotalSales' FROM `referral_codes` WHERE `codetype`= 'RB' AND `status` = 'used'";
$resultFromRB = mysqli_query($conn, $selectFromCodesRB);
while($userRow = mysqli_fetch_assoc($resultFromRB)){
    $RB_TotalSales = $userRow['RB_TotalSales'];
}

$totalSales = $DI_TotalSales+$RA_TotalSales+$RB_TotalSales;


// totalsales for todays video
$selectFromCodesToday = "SELECT COUNT(`codetype`) * 2000 as 'DI_TotalSales_Today' FROM `referral_codes` WHERE `codetype`= 'DI' AND `status` = 'used' AND `transfer_date` LIKE '%$DateForToday%'";
$resultFromDIToday = mysqli_query($conn, $selectFromCodesToday);
while($userRow = mysqli_fetch_assoc($resultFromDIToday)){
    $DI_TotalSales_Today = $userRow['DI_TotalSales_Today'];
}

$selectFromCodesRAToday= "SELECT COUNT(`codetype`) * 1125 as 'RA_TotalSales_Today' FROM `referral_codes` WHERE `codetype`= 'RA' AND `status` = 'used' AND `transfer_date` LIKE '%$DateForToday%'";
$resultFromRAToday = mysqli_query($conn, $selectFromCodesRAToday);
while($userRow = mysqli_fetch_assoc($resultFromRAToday)){
    $RA_TotalSalesToday = $userRow['RA_TotalSales_Today'];
}

$selectFromCodesRBToday= "SELECT COUNT(`codetype`) * 1000 as 'RB_TotalSalesToday' FROM `referral_codes` WHERE `codetype`= 'RB' AND `status` = 'used' AND `transfer_date` LIKE '%$DateForToday%'";
$resultFromRBToday = mysqli_query($conn, $selectFromCodesRBToday);
while($userRow = mysqli_fetch_assoc($resultFromRBToday)){
    $RB_TotalSalesToday = $userRow['RB_TotalSalesToday'];
}
$totalSalesToday = $DI_TotalSales_Today+$RA_TotalSalesToday+$RB_TotalSalesToday;

// totalsales for this month
$selectFromCodesThisMonth = "SELECT COUNT(`codetype`) * 2000 as 'DI_TotalSales_ThisMonth' FROM `referral_codes` WHERE `codetype`= 'DI' AND `status` = 'used' AND `transfer_date` LIKE '%$DateForThisMonth%'";
$resultFromDIThisMonth = mysqli_query($conn, $selectFromCodesThisMonth);
while($userRow = mysqli_fetch_assoc($resultFromDIThisMonth)){
    $DI_TotalSales_ThisMonth = $userRow['DI_TotalSales_ThisMonth'];
}

$selectFromCodesRAThisMonth= "SELECT COUNT(`codetype`) * 1125 as 'RA_TotalSales_ThisMonth' FROM `referral_codes` WHERE `codetype`= 'RA' AND `status` = 'used' AND `transfer_date` LIKE '%$DateForThisMonth%'";
$resultFromRAThisMonth = mysqli_query($conn, $selectFromCodesRAThisMonth);
while($userRow = mysqli_fetch_assoc($resultFromRAThisMonth)){
    $RA_TotalSalesThisMonth = $userRow['RA_TotalSales_ThisMonth'];
}

$selectFromCodesRBThisMonth= "SELECT COUNT(`codetype`) * 1000 as 'RB_TotalSalesThisMonth' FROM `referral_codes` WHERE `codetype`= 'RB' AND `status` = 'used' AND `transfer_date` LIKE '%$DateForThisMonth%'";
$resultFromRBThisMonth = mysqli_query($conn, $selectFromCodesRBThisMonth);
while($userRow = mysqli_fetch_assoc($resultFromRBThisMonth)){
    $RB_TotalSalesThisMonth = $userRow['RB_TotalSalesThisMonth'];
}


// echo $DI_TotalSales_today." ".$RA_TotalSalesToday." ".$RB_TotalSalesToday;
$totalSalesThisMonth = $DI_TotalSales_ThisMonth+$RA_TotalSalesThisMonth+$RB_TotalSalesThisMonth;


// totalsales for this month
$selectFromCodesThisYear = "SELECT COUNT(`codetype`) * 2000 as 'DI_TotalSales_ThisYear' FROM `referral_codes` WHERE `codetype`= 'DI' AND `status` = 'used' AND `transfer_date` LIKE '%$Year%'";
$resultFromDIThisYear = mysqli_query($conn, $selectFromCodesThisYear);
while($userRow = mysqli_fetch_assoc($resultFromDIThisYear)){
    $DI_TotalSales_ThisYear = $userRow['DI_TotalSales_ThisYear'];
}

$selectFromCodesRAThisYear= "SELECT COUNT(`codetype`) * 1125 as 'RA_TotalSales_ThisYear' FROM `referral_codes` WHERE `codetype`= 'RA' AND `status` = 'used' AND `transfer_date` LIKE '%$Year%'";
$resultFromRAThisYear = mysqli_query($conn, $selectFromCodesRAThisYear);
while($userRow = mysqli_fetch_assoc($resultFromRAThisYear)){
    $RA_TotalSalesThisYear = $userRow['RA_TotalSales_ThisYear'];
}

$selectFromCodesRBThisYear= "SELECT COUNT(`codetype`) * 1000 as 'RB_TotalSalesThisYear' FROM `referral_codes` WHERE `codetype`= 'RB' AND `status` = 'used' AND `transfer_date` LIKE '%$Year%'";
$resultFromRBThisYear = mysqli_query($conn, $selectFromCodesRBThisYear);
while($userRow = mysqli_fetch_assoc($resultFromRBThisYear)){
    $RB_TotalSalesThisYear = $userRow['RB_TotalSalesThisYear'];
}


// echo $DI_TotalSales_today." ".$RA_TotalSalesToday." ".$RB_TotalSalesToday;
$totalSalesThisYear = $DI_TotalSales_ThisYear+$RA_TotalSalesThisYear+$RB_TotalSalesThisYear;

$selectFromMember= "SELECT COUNT(`member_id`)  as 'Members' FROM `accounts` WHERE `permission` != 'administ'";
$resultFromMembers = mysqli_query($conn, $selectFromMember);
while($userRow = mysqli_fetch_assoc($resultFromMembers)){
    $membersCount = $userRow['Members'];
}

$selectFromMemberToday= "SELECT COUNT(`member_id`)  as 'MembersToday' FROM `accounts` WHERE `permission` != 'administ' AND `date` LIKE '%$DateForToday%'";
$resultFromMembersToday = mysqli_query($conn, $selectFromMemberToday);
while($userRow = mysqli_fetch_assoc($resultFromMembersToday)){
    $membersCountToday = $userRow['MembersToday'];
}

$selectTotalAmountReleased= "SELECT SUM(`amount`) AS totalAmount FROM `payout_request` WHERE `status` = 'released';";
$resultselectTotalAmountReleased = mysqli_query($conn, $selectTotalAmountReleased);
while($userRow = mysqli_fetch_assoc($resultselectTotalAmountReleased)){
    $totalAmountRelased = $userRow['totalAmount'];
}

$selectTotalAmountReleasedThisMonth= "SELECT SUM(`amount`) AS totalAmountThisMonth FROM `payout_request` WHERE `status` = 'released' AND `date_released` LIKE '%$DateForThisMonth%';";
$resultselectTotalAmountReleasedThisMonth = mysqli_query($conn, $selectTotalAmountReleasedThisMonth);
while($userRow = mysqli_fetch_assoc($resultselectTotalAmountReleasedThisMonth)){
    $totalAmountRelasedThisMonth = $userRow['totalAmountThisMonth'];
}



// code for getting the points//
$tableNamePoints="rebates_points";
$columnsPoints= ['rebates_points_id','user_id', 'email_address', 'pointsEarned','first_name', 'last_name'];
$fetchDataPoints= fetch_data_Points($db, $tableNamePoints, $columnsPoints);


function fetch_data_Points($db, $tableNamePoints, $columnsPoints){


 if(empty($db)){
  $msg= "Database connection error";
 }elseif (empty($columnsPoints) || !is_array($columnsPoints)) {
  $msg="columns Name must be defined in an indexed array";
 }elseif(empty($tableNamePoints)){
   $msg= "Table Name is empty";
}else{
$columnName = implode(", ", $columnsPoints);

$query = "SELECT rebates_points.*, accounts.first_name, accounts.last_name FROM `rebates_points` INNER JOIN accounts ON rebates_points.user_id = accounts.member_id ORDER BY rebates_points.pointsEarned DESC";

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
//end of code for getting the points

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
	<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="sweetalert2.all.min.js"></script>
    <script src="sweetalert2.min.js"></script>
	<link rel="stylesheet" href="sweetalert2.min.css">
    <title>Admin</title>
    <script src="../js/tailwind-3.1.8.js"></script>
    <script src="https://unpkg.com/flowbite@1.5.3/dist/flowbite.js"></script>
    <script src="../node_modules/tw-elements/dist/js/index.min.js"></script>

    <script src="../js/jquery-3.6.1.min.js"></script>
    <script src="../node_modules/tw-elements/dist/js/index.min.js"></script>
    <title>Arvie Cosmetic & Skincare  ProductsTrading</title>

    <style>
        @media screen and (max-width: 1023px) {
            .sales-dashboard{
                height: 66vh !important;
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
            .sales-dashboard{
                height: calc(33% - 15px) !important;
            }
            .content-container{
                height: calc(100vh - 73px);
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
  <?php include_once "./admin-header.php"; ?>
  <div class="content-container lg:flex lg:flex-row w-full">
    <div class="display-none lg:display-block lg:w-1/4 xl:w-1/5 2xl:w-1/5">
      <?php include_once "./admin-nav.php"; ?>
    </div>
    <div class="user-dashboard-content-container pt-5 px-6 pb-5 bg-emerald-100 w-full lg:w-3/4 xl:w-4/5 2xl:w-4/5">
        <!-- SALES -->
        <div class="sales-dashboard grid mb-5 rounded-lg border border-gray-200 shadow-sm lg:grid-cols-4 shadow-xl">
            <figure class="flex flex-col justify-center items-center text-center bg-white rounded-t-lg border-b border-gray-200 lg:rounded-t-none lg:rounded-tl-lg lg:border-r">
                    <h1 class="mb-2 font-medium text-2xl md:text-3xl lg:text-lg xl:text-xl 2xl:text-3xl">Total Sales</h1>
                    <p class="mt-2 font-bold text-4xl md:text-5xl lg:text-xl xl:text-2xl 2xl:text-4xl">₱ <?php $TOTALSALES = number_format($totalSales, 2); echo $TOTALSALES;?></p>
            </figure>

            <figure class="flex flex-col justify-center items-center text-center bg-white border-b border-gray-200 lg:border-r">
                    <h1 class="mb-2 font-medium text-xl md:text-2xl lg:text-lg xl:text-xl 2xl:text-2xl">Sales Today</h1>
                    <p class="mt-2 font-bold text-2xl md:text-4xl lg:text-xl xl:text-2xl 2xl:text-3xl">₱ <?php $TOTALSALES = number_format($totalSalesToday, 2); echo $TOTALSALES;?</p>
            </figure>

            <figure class="flex flex-col justify-center items-center text-center bg-white border-b border-gray-200 lg:border-b-0 lg:border-r">
                    <h1 class="mb-2 font-medium text-xl md:text-2xl lg:text-lg xl:text-xl 2xl:text-2xl">Sales This Month</h1>
                    <p class="mt-2 font-bold text-2xl md:text-4xl lg:text-xl xl:text-2xl 2xl:text-3xl">₱ <?php $TOTALSALES = number_format($totalSalesThisMonth, 2); echo $TOTALSALES;?></p>
            </figure>

            <figure class="flex flex-col justify-center items-center text-center bg-white rounded-b-lg border-gray-200 lg:rounded-br-lg">
                    <h1 class="mb-2 font-medium text-xl md:text-2xl lg:text-lg xl:text-xl 2xl:text-2xl">Sales This Year</h1>
                    <p class="mt-2 font-bold text-2xl md:text-4xl lg:text-xl xl:text-2xl 2xl:text-3xl">₱ <?php $TOTALSALES = number_format($totalSalesThisYear, 2); echo $TOTALSALES;?></p>
            </figure>
        </div>

        <div style="height: 66%;" class="grid grid-rows-4 lg:grid-rows-2 grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- MEMBERS -->
            <div class="order-first row-span-1 col-span-2 grid mb-6 rounded-lg border border-gray-200 shadow-sm grid-cols-2 shadow-xl w-full h-full">
                <figure class="flex flex-col justify-center items-center text-center bg-white rounded-l-lg border-b border-gray-200 lg:rounded-t-none lg:rounded-tl-lg border-r">
                    <h1 class="mb-2 font-medium text-lg md:text-3xl lg:text-lg xl:text-2xl 2xl:text-3xl">Total Members</h1>
                    <p class="mt-2 font-bold text-xl md:text-5xl lg:text-xl xl:text-2xl 2xl:text-4xl"><?php $TOTALMEMBERS = number_format($membersCount); echo $TOTALMEMBERS;?></p>
                </figure>

                <figure class="flex flex-col justify-center items-center text-center bg-white border-b border-gray-200 rounded-r-lg">
                    <h1 class="mb-2 font-medium text-lg md:text-2xl lg:text-lg xl:text-2xl 2xl:text-2xl">New Members Today</h1>
                    <p class="mt-2 font-bold text-xl md:text-4xl lg:text-xl xl:text-2xl 2xl:text-3xl"><?php $TOTALMEMBERS = number_format($membersCountToday); echo $TOTALMEMBERS;?></p>
                </figure>
            </div>
            <!-- MEMBERS WITH MOST INVITES -->
            <div class="order-last lg:order-12 row-span-2 col-span-2 grid mb-6 rounded-lg border border-gray-200 shadow-sm lg:grid-cols-1 shadow-xl w-full h-full">
                <figure class="overflow-auto flex flex-col pt-2 md:pt-3 2xl:pt-5 text-center  bg-white rounded-lg border-b border-gray-200 lg:rounded-t-none lg:rounded-tl-lg lg:border-r">
                    <h1 class="pb-2 2xl:pb-5 font-black md:text-3xl lg:text-lg xl:text-2xl 2xl:text-5xl sticky top-0 bg-white">Top Points Earner</h1>
					<?php           
					if(is_array($fetchDataPoints)){   
						$no = 1;
						foreach($fetchDataPoints as $data){
							$fname = $data['first_name'];
							$lname = $data['last_name'];
							$points = $data['pointsEarned'];
					?>
					<span class="mt-2">
                        <p class="ml-5 inline-block font-medium float-left md:text-xl 2xl:text-3xl"><?php echo $no.". ".$fname." ".$lname?></p>
                        <p class="mr-5 inline-block font-medium float-right md:text-xl 2xl:text-3xl"><?php echo $points?></p>
                    </span>
					<?php
						$no++; 
						}
					}else{
					}
                        ?>
                </figure>
            </div>
            <!-- PAYOUT -->
            <div class="lg:order-last col-span-2 grid mb-6 rounded-lg border border-gray-200 shadow-sm grid-cols-2 shadow-xl w-full h-full">
                <figure class="flex flex-col justify-center items-center text-center bg-white rounded-l-lg border-b border-gray-200 lg:rounded-t-none lg:rounded-tl-lg border-r">
                    <h1 class="mb-2 font-medium text-lg md:text-2xl lg:text-lg xl:text-2xl 2xl:text-3xl">Total Payout</h1>
                    <p class="mt-2 font-bold text-xl md:text-4xl lg:text-xl xl:text-2xl 2xl:text-4xl">₱ <?php $TOTALRELEASED = number_format($totalAmountRelased, 2); echo $TOTALRELEASED;?></p>
                </figure>

                <figure class="flex flex-col justify-center items-center text-center bg-white border-b border-gray-200 rounded-r-lg">
                    <h1 class="mb-2 font-medium text-lg md:text-xl lg:text-lg xl:text-2xl 2xl:text-2xl">Payout This Month</h1>
                    <p class="mt-2 font-bold text-xl md:text-3xl lg:text-xl xl:text-2xl 2xl:text-3xl">₱ <?php $TOTALRELEASEDThisMonth= number_format($totalAmountRelasedThisMonth, 2); echo $TOTALRELEASEDThisMonth;?></p>
                </figure>
            </div>

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

<?php include "./editRebates.php"?>;
<?php include_once "./changePassword.php"; ?>



    <script>
        $(document).ready(function(){
            $("#dashboard").addClass("bg-emerald-700");
            $("#dashboard").addClass("text-white");
            $("#dashboard").removeClass("text-gray-600");
        });
    </script>
</body>
</html>
