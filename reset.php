<?php

	include('includes/Dbh.class.php');
	include('includes/User.class.php');
	include('includes/config.php');

	require './vendor/autoload.php';

	$alert['error'] = '';
	$alert['success'] = '';


	if (isset($_GET['email']) && isset($_GET['token'])) {
		$email = trim($_GET['email']);
		$token = trim($_GET['token']);

	} else if ($_SERVER["REQUEST_METHOD"] == "POST") {

		$email = trim($_POST['email']);
		$token = trim($_POST['token']);

		if ($_POST["password"] != $_POST["password2"]) {
			$alert['error'] = "The passwords do not match!";
		} else {

			$userObj = new User;
			$result = $userObj->changePassword($email, $token, password_hash($_POST["password"], PASSWORD_BCRYPT));
			

			if ($result) {
				$alert['success'] = "Password reset successfully! Please log in!";
			} else {
				$alert['error'] = "An error occured when trying to process your request. Please try again."
			}

		}

	} else {
		header('location: ./index.php');
	}

	
?>

<!DOCTYPE html>
	<html>
	<head>
		<title>Reset Password</title>

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
			<h1>Reset Password</h1>

			<?php 
				if($alert['error'] != '') {
					echo "<div class='alert alert-danger'>" . $alert['error'] . "</div>";
				}
			?>

			<?php if($alert['success'] != ''): ?>

				<div class="alert alert-success"><?php echo $alert['success']; ?></div>

			<?php else: ?>

				<form class="login-form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">

					<div class="form-group">
						<p>Enter your new password.</p>
						<input type="password" class="form-control" name="password" placeholder="New Password" 
						pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" 
						title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required>

						<input type="password" class="form-control" name="password2" placeholder="Confirm Password" required>

						<input type="hidden" class="form-control" name="email" value="<?= $email ?>">

						<input type="hidden" class="form-control" name="token" value="<?= $token ?>">
					</div>

					<input class="btn btn-primary btn-block" type="submit" name="login" value="Update Password">
					
				</form>

			<?php endif; ?>

			<a href="./index.php">Return to homepage</a>
		</main>
	
		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	</body>
</html>