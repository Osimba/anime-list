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

	include('includes/templates/header.php');
	
?>

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
	
<?php include('includes/templates/footer.php') ?>