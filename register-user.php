<?php

	include('includes/Dbh.class.php');
	include('includes/User.class.php');

	/* Register New User */

	session_start();
	$error['alert'] = '';


	/* 
		- Username (8-20)
		- Password (8-20)
		- Email 
		- Button to Create Account
		- "Already have an account?" Sign in

	*/

	if($_SERVER["REQUEST_METHOD"] == "POST") {

		if($_POST["password"] != $_POST["password2"]) {
			$error['alert'] = "The passwords do not match!";
		} else {

			$userObj = new User;
			$result = $userObj->createUser(trim($_POST["username"]), trim($_POST["email"]), password_hash($_POST["password"], PASSWORD_BCRYPT));
			echo $result;
		}
	}

?>

<!DOCTYPE html>
<html>
<head>
	<title></title>

	<!--CSS Style Sheets -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="./css/style.css">
</head>
<body>

<header>
			<nav class="navbar navbar-expand-lg navbar-light bg-primary">
  				<a class="navbar-brand" href="./index.php">Anime Database</a>
  				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    				<span class="navbar-toggler-icon"></span>
  				</button>
				<div class="collapse navbar-collapse" id="navbarNav">
					<div class="navbar-nav">

				    </div>
				</div><!-- #navbarNav -->
			</nav>
		</header>

		<main id="login-page" class="container">
			<h1>Register New User</h1>

			<?php 
				if($error['alert'] != '') {
					echo "<div class='alert alert-danger'>" . $error['alert'] . "</div>";
				}
			?>

			<form class="registration-form"  action="<? echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">

				<div class="form-group">
					<input type="text" class="form-control" name="username" placeholder="Username" required>

					<input type="email" class="form-control" name="email" placeholder="Email Address" required>

					<input type="password" class="form-control" name="password" placeholder="Password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required>

					<input type="password" class="form-control" name="password2" placeholder="Retype your password" required>
				</div>

				<input class="btn btn-primary btn-block" type="submit" name="login" value="Create Account">
				
			</form>

			<p>Already have an account? <a href="./index.php">Login</a></p>
		</main>
	
		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</body>
</html>