<?php  

	require __DIR__ . '/vendor/autoload.php';

	include("includes/config.php");


	session_start();

	//check if user is logged in
	if(isset($_SESSION['user'])) {

		//make sure user selected
		if(!isset($_GET['id'])) {

			if(isset($_GET['user'])) {

				if(!strcmp($_GET['user'], $_SESSION['user'])) {
					$userId = $User->getUserId($_GET['user']);

					header('location: ./profile.php?id=' . $userId);
				}

			}

			header('location: ./users.php');

		} else {

			$userInfo = $User->getUserInfo($_GET['id']);

			//check for inactivity
			if(time() > $_SESSION['last_active'] + $config['session_timeout']) {
				session_destroy();
				header('location: ./index.php?timeout');
			} else {
				$_SESSION['last_active'] = time();
			}
		}
	

	} else {
		//if not logged in redirect
		header('location: ./index.php?unauthorized');
	}

	include('includes/templates/header.php');
?>


<main id="profile-page">
	
	<h1><?= $userInfo['username'] ?></h1>

	<h3>Watched Anime</h3>

	<div class="watched-table">
		<div class="row head text-center">
			<div class="col-5">Title</div>
			<p class="col-2">Rating</p>
			<p class="col-3">Your Rating</p>
			<p class="col-2">Status</p>
		</div>

		<div class="row list-item">
			<div class="col-5">
				<img src="images/attack-on-titan-cover.jpg">
				<h4>Attack on Titan</h4>
				<p>24 eps</p>
			</div>
			<p class="col-2 text-center">10.0</p>
			<p class="col-3 text-center">10.0</p>
			<p class="col-2 text-center">Remove from Watched</p>
		</div>

	</div>

</main>

<?php include('includes/templates/footer.php') ?>