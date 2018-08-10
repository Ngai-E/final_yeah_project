

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Login to dashboard</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link rel="stylesheet" type="text/css"  href="../style.css">
	<link rel="shortcut icon" href="../images/favicon.png" />
        <link href='../css/css.css' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" type="text/css"  href='../css/clear.css' />
        <link rel="stylesheet" type="text/css"  href='../css/common.css' />
        <link rel="stylesheet" type="text/css"  href='../css/font-awesome.min.css'/>
        <link rel="stylesheet" type="text/css"  href='../css/carouFredSel.css' />
        <link rel="stylesheet" type="text/css"  href='../css/prettyPhoto.css' />
        <link rel="stylesheet" type="text/css"  href='../css/sm-clean.css' />
        <link rel="stylesheet" type="text/css"  href='../style.css' />
<!--===============================================================================================-->
</head>
<body>

	<?php
   require("../config.php");
   
   
   if($_SERVER["REQUEST_METHOD"] == "POST") {
      // username and password sent from form 
   		$myusername = mysqli_real_escape_string($conn,$_POST['username']);
   		$password = mysqli_real_escape_string($conn,$_POST['pass']);
		$sql = "SELECT * FROM company_info WHERE password = sha1('$password') && name ='$myusername' ";
		$result = mysqli_query($conn, $sql);


		if (mysqli_num_rows($result) ==1) {
		    // login
		 	header("location: ../admin_dashboard/dashboard.php");
		    
		} else {
		    $error = "Your Login Name or Password is invalid";
         echo '<p>"$error"<p>';
		}
		mysqli_close($conn);
   }
?>



	<!-- Menu -->
        <div class="menu-wrapper center-relative">
            <nav id="header-main-menu">
                <div class="mob-menu">MENU</div>
                <ul class="main-menu sm sm-clean">
                    <li><a href="../index.html">Home</a></li>
                    <li><a href="./login.html">login</a></li>
                    <li><a href="../signup/signup.html">signup</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </nav>
        </div>
	
	<div class="container-login100" style="background-image: linear-gradient(rgba(196, 102, 0, 0.6), rgba(155, 89, 182, 0.6));">
		<div class="wrap-login100 p-l-55 p-r-55 p-t-80 p-b-30">
			<form class="login100-form validate-form" action="" method="POST" >
				<span class="login100-form-title p-b-37">
					Sign In
				</span>
					
					<div class="wrap-input100 validate-input m-b-20" data-validate="enter company name or email" >
					<input class="input100" type="text" name="username" placeholder="company name or email">
					<span class="focus-input100"></span>
				</div>

				<div class="wrap-input100 validate-input m-b-25" data-validate = "Enter password">
					<input class="input100" type="password" name="pass" placeholder="password">
					<span class="focus-input100"></span>
				</div>

				<div class="container-login100-form-btn">
					<button class="login100-form-btn"  >
						Sign In
					</button>
				</div>

				
				<div class="text-center">
					<a href="#" class="txt2 hov1">
						Sign Up
					</a>
				</div>
			</form>

			
		</div>
	</div>
	
	

	<div id="dropDownSelect1"></div>

	 	<script type="text/javascript" src="../js/jquery.js"></script>
        <script type='text/javascript' src='../js/jquery.carouFredSel-6.2.0-packed.js'></script>
        <script type='text/javascript' src='../js/main.js'></script>
	

</body>
</html>