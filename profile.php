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
			$userWatchedList = $User->getWatchedAnime($userInfo['id']);

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


	<ul class="watched-table">
		<li class="row head text-center">
			<div class="col-5">Title</div>
			<p class="col-2">Rating</p>
			<p class="col-3">User Rating</p>
			<p class="col-2">Status</p>
		</li>
	<?php 

		if(count($userWatchedList) == 0):
			echo "<p>No Anime on list</p>";
		else: foreach ($userWatchedList as $animeInfo) { 

	?>

	
		<li class="row list-item">
			<div class="col-5">
				<img src="<?php echo IMAGE_DIR . $animeInfo['image']; ?>">
				<h4><?php echo $animeInfo['title']; ?></h4>
				<p><?php echo $animeInfo['episodes'] . " eps"; ?></p>
			</div>
			<p class="col-2 text-center"><?php echo $animeInfo['rating']; ?></p>
			<p class="col-3 text-center"><?php echo $animeInfo['user_rating']; ?></p>
			<p class="col-2 text-center">
				<?php if($User->hasWatched($userInfo['id'], $animeInfo['id'])): ?>
					<button id="remove-from-watched" class="btn btn-secondary">Remove</button> 
				<?php else: ?> 
					<button id="add-to-watched" class="btn btn-watched">Add</button> 
				<?php endif ?>
					
			</p>
		</li>
		
	<?php } endif; ?>
	</ul>
</main>

<?php include('includes/templates/footer.php') ?>