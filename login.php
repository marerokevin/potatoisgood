<?php

$loginPage = true; ?>
<?php 

date_default_timezone_set("Asia/Singapore");
$date = new DateTime(); 
$completeDateNow  = $date->format('M d, Y');  
$day  = $date->format('D'); 
$timenow = date("h:i a"); 
// echo $timenow;
// $sqlinsertTransact2= "INSERT INTO `points_report`( `member_id`, `date`, `time`, `month`, `year`, `points_earned`) VALUES ('1234','11-04-2022','$timenow','Nov','2022','10')";
// mysqli_query($conn, $sqlinsertTransact2);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/styles.css">
    <!-- <link rel="stylesheet" href="./dist/output.css"> -->
    <link rel="stylesheet" href="https://unpkg.com/flowbite@1.5.3/dist/flowbite.min.css" />
    <title>Arvie Direct Sales - Login</title>
    <script src="./js/tailwind-3.1.8.js"></script>
    <script src="https://unpkg.com/flowbite@1.5.3/dist/flowbite.js"></script>
    <script src="./js/jquery-3.6.1.min.js"></script>
	<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="sweetalert2.all.min.js"></script>
    <script src="sweetalert2.min.js"></script>
    <link rel="stylesheet" href="sweetalert2.min.css">
</head>
<body class="h-screen w-screen">
	<?php
    include "./includes/auth/login.php"; 
    include_once "./header.php"; ?>
    
    <div class="container bg-white relative top-24 md:top-28 mx-auto w-11/12 max-w-sm">
        <div class="container shadow-xl p-5 rounded-lg">
            <h1 class="text-emerald-800 text-2xl text-center mb-5">Welcome to<br>Arvie Direct Sales</h1>
            <hr>

            <!-- Error Message (Normally Hidden)-->
            <div id="alert-border-2" class="hidden flex p-4 mb-4 bg-red-100 border-t-4 border-red-500 dark:bg-red-200" role="alert">
                <svg class="flex-shrink-0 w-5 h-5 text-red-700" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                <div class="ml-3 text-sm font-medium text-red-700">
                Error Message na Malufet!
                </div>
                <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-red-100 dark:bg-red-200 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 dark:hover:bg-red-300 inline-flex h-8 w-8"  data-dismiss-target="#alert-border-2" aria-label="Close">
                    <span class="sr-only">Dismiss</span>
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>
            </div>

            <!-- Login Form -->
            <form class="mt-5" action="./login.php" method="POST">
                <div class="mb-6">
                    <label for="email_address" class="block mb-2 text-sm font-medium text-gray-900"> <?php //echo $test; ?>Member ID or Email</label>
                    <input type="text" id="email_address" name="email_address" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required="">
                </div>
                <div class="mb-6">
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password</label>
                    <input type="password" id="password" name="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required="">
                </div>
                <button type="submit" name="login" id="login" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full px-5 py-2.5 text-center">Sign In</button>
            </form>

        </div>
        <p class="text-center mt-3">Don’t have an account? <a href="./signup.php" class="text-blue-700">Sign up</a></p>
    </div>

</body>
</html>