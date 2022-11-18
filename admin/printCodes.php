<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="./dist/output.css"> -->
    <link rel="stylesheet" href="https://unpkg.com/flowbite@1.5.3/dist/flowbite.min.css" />
    <!-- <link rel="stylesheet" href="http://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css"> -->
    <title>Admin</title>
    <script src="../js/tailwind-3.1.8.js"></script>
    <script src="https://unpkg.com/flowbite@1.5.3/dist/flowbite.js"></script>
	<script type="text/javascript" src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="../node_modules/tw-elements/dist/js/index.min.js"></script>

    <title>Arvie Cosmetic & Skincare  ProductsTrading</title>


</head>
<body>
<?php
                        include_once ("../includes/config/conn.php");

                        $transaction = $_GET["transaction"];
                        $transaction_select = "SELECT codetype, counter, ref_code, transaction_id from referral_codes WHERE generation_batch = '$transaction'";
                        $transaction_query = mysqli_query($conn, $transaction_select);
                    ?>
<div class="grid grid-cols-3 gap-2">

<?php
                        $transaction = $_GET["transaction"];
                        $transaction_select_code = "SELECT codetype, counter, ref_code, transaction_id from referral_codes WHERE generation_batch = '$transaction'";
                        $transaction_query_code = mysqli_query($conn, $transaction_select_code);
                        while ($specific_transaction = mysqli_fetch_assoc($transaction_query_code)) {

                            echo "<div class='text-2xl font-medium my-10 inline-block align-middle'>"; echo $specific_transaction['ref_code']; echo "</div>"; 

                        }
                    ?>

</div>

</body>
</html>