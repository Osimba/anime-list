<!DOCTYPE html>
<html>
<head>
	<title>Anime Database</title>

	<!--CSS Style Sheets -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="<? echo ROOT_DIR; ?>css/style.css">
</head>
<body>

	<header>
		<nav class="navbar navbar-expand-lg navbar-light">
				<a class="navbar-brand" href="<?= ROOT_DIR ?>">Anime Database</a>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
				</button>
			<div class="collapse navbar-collapse" id="navbarNav">
				<?php if(isset($_SESSION["user"])): ?>
					<div class="navbar-nav">
						<a class="nav-item nav-link" href="<? echo ROOT_DIR . 'members.php' ?>">All Anime</a>
				    	<a class="nav-item nav-link" href="<? echo ROOT_DIR . 'users/profile?id=' . $userID ?>">My List</a>
				    </div>
				    <div class="navbar-nav ml-auto">
						<a class="nav-link" href="<? echo ROOT_DIR . 'users/profile?id=' . $userID ?>"><? echo $_SESSION["user"]; ?></a>
						<a class="nav-link" href="<? echo ROOT_DIR . 'index.php?logout=true' ?>">Logout</a>
					</div>
				<?php else:?>
					<div class="navbar-nav">
						<a class="nav-item nav-link" href="<? echo ROOT_DIR . 'register-user.php' ?>">Sign up</a>
					</div>

				<?php endif; ?>
			</div><!-- #navbarNav -->
		</nav>
	</header>