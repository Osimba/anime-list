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

			$userObj = new User;


			$animeObj = new Anime;
			$anime = $animeObj->getAnime($_GET['id']);
			$comments = $animeObj->getComments($anime['id']);

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

	-->

	<div class="container">

		<h1><?= $anime['title'] ?></h1>

		<div class="anime-info">
			<img src="images/attack-on-titan-cover.jpg">
			<p>Genres: Action Anime, Drama Anime, Dub Anime, Fantasy Anime, Shounen Anime, Super Power Anime, Thriller Anime</p>
			<p>Average Rating: 10/10</p>
			<p>Summary: Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
			tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
			quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
			consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
			cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
			proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
		</div>

		<hr>

		<h2>Comments</h2>

		<?php forEach($comments as $comment) { ?>


			<div id="<?php echo 'comment-' . $comment['comment_num']; ?>" class="comment">
				<div class="comment-info">
					<img src="<?php echo 'images/' . $userObj->getUserInfo($comment['user_id'])['image']; ?>" alt="avatar">
					<p><?php echo $userObj->getUserInfo($comment['user_id'])['username'] . ' - ' . $comment['time_stamp']; ?></p>
				</div>
				<div class="comment-text">
					<p><?php echo $comment['comment']; ?></p>
					<a href="<?php echo '#comment-' . $comment['comment_num']; ?>">Reply</a>
					</div>

				 
				
				
			</div>

		<?php } ?>


	</div>

</main>

<?php include('./includes/templates/footer.php') ?>