<?php

	include('includes/Dbh.class.php');
	include('includes/Anime.class.php');
	include('includes/User.class.php');

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

?>

<!DOCTYPE html>
<html>
<head>
	<title>Home</title>

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
	
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>

