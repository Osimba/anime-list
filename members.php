<?php
	require __DIR__ . '/vendor/autoload.php';

	include('includes/Dbh.class.php');
	include('includes/Anime.class.php');
	include('includes/User.class.php');
	include("includes/config.php");

	session_start();

	//check if user is logged in
	if(isset($_SESSION['user'])) {

		$animeObj = new Anime;
		$anime = $animeObj->getAllAnime();

		//check for inactivity
		if(time() > $_SESSION['last_active'] + $config['session_timeout']) {
			session_destroy();
			header('location: ./index.php?timeout');
		} else {
			$_SESSION['last_active'] = time();
		}


	} else {
		//if not logged in redirect
		header('location: ./index.php?unauthorized');
	}



?>
<!DOCTYPE html>
<html>
<head>
	<title>Members</title>

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
					<a class="nav-item nav-link" href="./members.php">All Anime</a>
			    	<a class="nav-item nav-link" href="./user-list.php">My List</a>
			    </div>
			</div><!-- #navbarNav -->

			<div class="navbar-nav ml-auto">
				<p class="my-2"><? echo $_SESSION["user"]; ?></p>
				<a class="nav-link" href="./index.php?logout=true">Logout</a>
			</div>
		</nav>
	</header>

	<main id="members-page" class="container text-center">
		<h1>All Anime</h1>

		<div class="row anime-items">
			<?php



				foreach($anime as $a) {

					echo "<div class='col-md-6 col-lg-4'>";
					echo "<img class='cover-img' src='" . $a['image'] . "'>";
					echo "<p class='rating'>" . $a['rating'] . "</p>";
					echo "<h3>" . $a['title'] . "</h3>";
					echo "<p>" . $a['genre'] . "</p>";
					echo "<p>" . $a['episodes'] . " episodes</p>";
					echo "</div>";
				
				}

			?>

			
		</div>
	</main>

	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>