<?php
session_start();
 
if(isset( $_SESSION['loggedin'])){
        if($_SESSION['permission']=='administ'){
        header("location:./admin/index.php");
    
        }else{
            header("location: ./user/index.php");

        }
    
}
include_once ("./includes/config/conn.php");
 
$email_address  = $password = "";
$email_address_err = $password_err = $login_err = "";

if(isset($_POST["login"])){


    if(empty(trim($_POST["email_address"]))){
        $email_address_err = "Please enter email address.";
    }else {
        $email_address = trim($_POST["email_address"]);
    }
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    }else{
        $password = trim($_POST["password"]);
    }

    if(empty($email_address_err) && empty($password_err)){

        $sql = "SELECT id, member_id, email_address, pass, first_name, last_name, permission, access FROM accounts WHERE email_address = ?";
        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            
            $param_email = $email_address;
            
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    mysqli_stmt_bind_result($stmt, $id, $member_id, $email_address, $hashed_password, $first_name, $last_name, $permission, $access);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["email_address"] = $email_address;
                            $_SESSION["first_name"] = $first_name;
                            $_SESSION["last_name"] = $last_name;
                            $_SESSION["permission"] = $permission;
                            $_SESSION["access"] = $access;
                            $_SESSION["member_id"] = $member_id;

                            if($access == "approved") {
                                echo "<script> alert('You do not have access in this website')</script>";
                                if($permission != "administ") {
                                    header("location: ./user/index.php");
                                }else {
                                    header("location: ./admin/index.php");
                                }
                            }else{
                                header("location: ./signup.php");
                            }
                        }else{
						?><script>
						Swal.fire({
											icon: 'error',
											title: 'Oops!',
											text: 'Invalid username or password.',
										}).then(function() {
											window.location = "./login.php";
										});
						</script><?php 
                            // $login_err = "Invalid username or password.";
                        }
                    }
                } else{
                                       ?><script>
                    Swal.fire({
                  icon: 'error',
                  title: 'Oops!',
                  text: 'Invalid username or password.',
                }).then(function() {
          window.location = "./login.php";
					});
                 </script><?php 
                }
            } else{
                               ?><script>
                Swal.fire({
              icon: 'error',
              title: 'Oops!',
              text: 'Something went wrong. Please try again later.',
            //   footer: '<a href="">Why do I have this issue?</a>'
            }).then(function() {
      window.location = "./login.php";
  });
             </script><?php 
            }
            mysqli_stmt_close($stmt);
        }
    }
    mysqli_close($conn);
}
?>