<?php  

	require __DIR__ . '/vendor/autoload.php';

	include('includes/Dbh.class.php');
	include('includes/Anime.class.php');
	include('includes/User.class.php');
	include("includes/config.php");


	session_start();

	//check if user is logged in
	if(isset($_SESSION['user'])) {

		//make sure anime selected
		if(!isset($_GET['id'])) {
			header('location: ./members.php');
		} else {

			$animeObj = new Anime;
			$anime = $animeObj->getAnime($_GET['id']);

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





	include('./includes/templates/header.php');
?>

<main id="anime-room">
	
	<!-- 
		Display all the information for the given anime
		If GET[id] isn't set, then user should be redirected to the members page
		impliment time out rules, session rules, etc.
		Comments on the anime should be from newest to oldest
		comments should display user, image, datetime and text
	-->

	<h1><?php echo $anime['title'] ?></h1>

</main>

<?php include('./includes/templates/footer.php') ?>