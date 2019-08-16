<?php

	include('includes/Dbh.class.php');
	include('includes/Anime.class.php');
	include('includes/User.class.php');
	include('includes/config.php');

	session_start();

	$alert['error'] = '';


	if($_SERVER["REQUEST_METHOD"] == "POST") {

		$username = trim($_POST['username']);
		$password = $_POST['password'];
		
		$userObj = new User;

		if($userObj->checkUserCredentials($username, $password)) {

			$_SESSION['user'] = $username;
			$_SESSION['last_active'] = time();

			header('location: ./members.php');
		} else {
			header('location: ./index.php?wrongCredentials');
		}

	} else {

		if(isset($_GET['logout'])) {
			session_unset();
			session_destroy();

			header('location: ./index.php');
		} 

		if(isset($_SESSION['user'])) {
			header('location: ./members.php');
		} 

		if(isset($_GET['unauthorized'])) {
	        $alert['error'] = 'Please login to view this page!';
	    }

	    if(isset($_GET['wrongCredentials'])) {
	        $alert['error'] = 'Incorrect username or password!';
	    }
	    
	    if(isset($_GET['timeout'])) {
	        $alert['error'] = 'Session timed out! Please login again!';
	    }
	}

	include('includes/templates/header.php');

?>



		<main id="login-page" class="container">
			<h1>Login to the Database</h1>

			<?php 
				if($alert['error'] != '') {
					echo "<div class='alert alert-danger'>" . $alert['error'] . "</div>";
				}
			?>

			<form class="login-form" action="<? echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">

				<div class="form-group">
					<input type="text" class="form-control" name="username" placeholder="Username" required>

					<input type="password" class="form-control" name="password" placeholder="Password" required>
				</div>

				<input class="btn btn-primary btn-block" type="submit" name="login" value="Login">
				<a href="./register-user.php"><div class="btn btn-secondary btn-block">Sign Up</div></a>
			</form>

			<a href="./forgot-password.php">Forgot Password</a>
		</main>

<?php include('includes/templates/footer.php') ?>

