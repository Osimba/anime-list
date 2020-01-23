<?php  

	require __DIR__ . '/vendor/autoload.php';

	include("includes/config.php");


	session_start();

	//check if user is logged in
	if(isset($_SESSION['user'])) {

		//make sure anime selected
		if(!isset($_GET['id'])) {
			header('location: ./members.php');
		} else {

			$loggedUserId = $User->getUserId($_SESSION['user']);
			$userWatched = $User->hasWatched($loggedUserId, $_GET['id']);
			

			$animeInfo = $Anime->getAnime($_GET['id']);
			$commentsInfo = $Comments->getComments($animeInfo['id']);

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
	
	<input id="userID" type="hidden" class="form-control" name="userID" value="<?= $loggedUserId ?>">

	<input id="animeID" type="hidden" class="form-control" name="animeID" value="<?= $animeInfo['id'] ?>">
	<!-- 
		Display all the information for the given anime

	-->

	<div class="container">

		<h1><?= $animeInfo['title'] ?></h1>

		<div id="list-update"></div>

		<div class="anime-info">
			<img src="<?php echo ROOT_DIR . $animeInfo['image']; ?>">
			<p><strong>Genres:</strong> <?php echo $animeInfo['genre']; ?></p>
			<p><strong>Average Rating:</strong> <?php echo $animeInfo['rating']; ?></p>
			<p><strong>Summary:</strong> <?php echo $animeInfo['summary']; ?> </p>
			<br>
			<?php if($userWatched): ?>
				<button id="remove-from-watched" class="btn btn-secondary">Remove from Watched List</button> 
			<?php else: ?> 
				<button id="add-to-watched" class="btn btn-watched">Add to Watched List</button> 
			<?php endif ?>
			
		</div>
		<div id="watched-form-close" class="close-popup"></div>
		<div id="watched-form">
			<br>
			<h3>Add to Watched List</h3>
			<label>Rating (1 to 10):</label>

			<input id="userRating" type="number" name="userRating" min="1" max="10"><br><br>
			<p></p>
			<button id="sendWatched" class="btn btn-primary">Add to Watched</button>
			<br><br>
		</div>

		<hr>

		<h2>Comments</h2>



		
		<input id="timeStamp" type="hidden" class="form-control" name="timeStamp" value="<?= date("Y-m-d H:i:s") ?>">

		<textarea id="newComment" class="form-control" name="newComment" placeholder="Add comment..."></textarea>

		<button id="sendComment" class="btn btn-primary">Send Comment</button>


		<hr>

		<div id="loader"></div>

		<div id="result"></div>

		<div id="comments-section">

		<?php forEach($commentsInfo as $comment) { ?>


			<div id="<?php echo 'comment-' . $comment['comment_num']; ?>" class="comment">
				<div class="comment-info">
					<img src="<?php echo 'images/' . $User->getUserInfo($comment['user_id'])['image']; ?>" alt="avatar">
					<p><?php echo $User->getUserInfo($comment['user_id'])['username'] . ' - ' . $comment['time_stamp']; ?></p>
				</div>
				<div class="comment-text">
					<p><?php echo $comment['comment']; ?></p>
					<a href="<?php echo '#comment-' . $comment['comment_num']; ?>">Reply</a>
				</div>	 	
			</div>

		<?php } ?>

		</div> <!-- #comments-section-->


	</div><!-- .container -->

</main>

<script src="https://code.jquery.com/jquery-3.4.1.js"
			integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
			crossorigin="anonymous"></script>

<script src="js/app.js"	type="text/javascript"></script>

<?php include('./includes/templates/footer.php') ?>