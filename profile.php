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

</main>

<?php include('includes/templates/footer.php') ?>