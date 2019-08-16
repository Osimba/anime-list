<?php

	include('includes/Dbh.class.php');
	include('includes/User.class.php');

	/* Register New User */

	session_start();
	$alert['error'] = '';
	$alert['success'] = '';

	/* 
		- Username (8-20)
		- Password (8-20)
		- Email 
		- Button to Create Account
		- "Already have an account?" Sign in

	*/

	if($_SERVER["REQUEST_METHOD"] == "POST") {

		if($_POST["password"] != $_POST["password2"]) {
			$alert['error'] = "The passwords do not match!";
		} else {

			$userObj = new User;
			$result = $userObj->createUser(trim($_POST["username"]), trim($_POST["email"]), password_hash($_POST["password"], PASSWORD_BCRYPT));

			switch ($result) {
				case 101:
					$alert['success'] = "Successfully created account!";
					break;
				case 102:
					$alert['error'] = "Failed to create user. Please try again.";
					break;
				case 103:
					$alert['error'] = "User already exists!";
					break;
				default:
					$alert['error'] = "An error occured when trying to process your request. Please try again.";
					break;
			}
		}
	}

	include('includes/templates/header.php');

?>



		<main id="login-page" class="container">
			<h1>Register New User</h1>

			<?php 
				if($alert['error'] != '') {
					echo "<div class='alert alert-danger'>" . $error['alert'] . "</div>";
				}

				if($alert['success'] != '') {
					echo "<div class='alert alert-success'>" . $success['alert'] . "</div>";
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
	
<?php include('includes/templates/footer.php') ?>