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
			<img src="<?php echo ROOT_DIR . $anime['image']; ?>">
			<p><strong>Genres:</strong> <?php echo $anime['genre']; ?></p>
			<p><strong>Average Rating:</strong> <?php echo $anime['rating'] . '/10'; ?></p>
			<p><strong>Summary:</strong> <?php echo $anime['summary']; ?> </p>
		</div>

		<hr>

		<h2>Comments</h2>



		<input id="userID" type="hidden" class="form-control" name="userID" value="<?= $userObj->getUserId($_SESSION['user']) ?>">

		<input id="animeID" type="hidden" class="form-control" name="animeID" value="<?= $anime['id'] ?>">
		
		<input id="timeStamp" type="hidden" class="form-control" name="timeStamp" value="<?= date("Y-m-d H:i:s") ?>">

		<textarea id="newComment" class="form-control" name="newComment" placeholder="Add comment..."></textarea>

		<button id="sendComment" class="btn btn-primary">Send Comment</button>


		<hr>

		<div id="loader"></div>

		<div id="result"></div>

		<div id="comments-section">

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

		</div> <!-- #comments-section-->


	</div><!-- .container -->

</main>

<script src="https://code.jquery.com/jquery-3.4.1.js"
			integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
			crossorigin="anonymous"></script>

<script type="text/javascript">
	$(document).ready(function() {

		

		// Check if Send Comment button was clicked
		$('#sendComment').click(function() {
			$("#loader").show();

			var userID = $('#userID').val();
			var animeID = $('#animeID').val();
			var timeStamp = $('#timeStamp').val();
			var newComment = $('#newComment').val();

			// Sends the data using AJAX POST method
		    $.ajax({
				method: "POST",
				url: "add-comment.php",
				data: {
						user_id: userID,
						anime_id: animeID,
						time_stamp: timeStamp,
						comment: newComment					
			       	},
				success: function(response) {
					$("#result").html(response);
					$("#loader").hide();

				}
			}); 



		}); //#send-comment




	});
</script>

<?php include('./includes/templates/footer.php') ?>