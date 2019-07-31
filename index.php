<?php

	include('includes/Dbh.class.php');
	include('includes/Anime.class.php');
	include('includes/User.class.php');

	session_start();


	if(isset($_GET['logout'])) {
		session_unset();
		session_destroy();

		header('location: ./index.php');



	} else if(isset($_SESSION['user'])) {
		header('location: ./members.php');

	} else if($_SERVER["REQUEST_METHOD"] == "POST") {

		$username = trim($_POST['username']);
		$password = $_POST['password'];
		
		$userObj = new User;

		if($userObj->checkUserCredentials($username, $password)) {

			$_SESSION['user'] = $username;

			header('location: ./members.php');
		} else {
			header('location: ./index.php?wrongCredentials=true');
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

		<?php
			
			/*
			echo "<h1>Anime List:</h1><br>";
			echo "<table style='border: solid 1px black;'>";
			echo "<tr><th>ID</th><th>Title</th><th>Genre</th><th>Rating</th><th># of Episodes</th><th>Image File</th></tr>";

			$obj = new Anime;
			$row = $obj->getAllAnime();


			echo "<tr><td>" . $row['id'] . "</td><td>" . $row['title'] . "</td><td>" . $row['genre'] . "</td><td>" . $row['rating'] . "</td><td>" . $row['episodes'] . "</td><td>" . $row['image'] . "</td></tr>";

			echo "</table>";
			*/
			
		?>

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
				if(isset($_GET['wrongCredentials'])) {
					echo "The username or password you entered is incorrect!";
					echo "<script>document.getElementsByName('username')[0].focus()</script>";
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

