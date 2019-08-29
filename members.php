<?php
	require __DIR__ . '/vendor/autoload.php';

	include("includes/config.php");

	session_start();

	//check if user is logged in
	if(isset($_SESSION['user'])) {

		$Anime = new Anime;
		$animeResults = $Anime->getAllAnime();

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

	$userID = 001;

	include('includes/templates/header.php');

?>

	<main id="members-page" class="container text-center">
		<h1>All Anime</h1>

		<ul class="row anime-items">
			
			<?php foreach($animeResults as $result) { ?>
				
				<li class="col-md-6 col-lg-4 card">
					<a href="<?php echo ROOT_DIR . 'anime.php?id=' . $result['id']; ?>">
						<h3> <?= $result['title'] ?> </h3>
						<img class="cover-img" src="<?= $result['image'] ?>">
						<p class='rating'> <?= $result['rating'] ?>/10</p>
						<p> <?= $result['genre'] ?> </p>
						<p> <?= $result['episodes'] ?> episodes</p>
					</a>
				</li>
				
			<?php } ?>

		</ul>

	</main>

<?php include('includes/templates/footer.php'); ?>