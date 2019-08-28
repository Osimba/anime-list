<?php 

	require __DIR__ . '/vendor/autoload.php';

	include('includes/Dbh.class.php');
	include('includes/Anime.class.php');
	include('includes/User.class.php');
	include("includes/config.php");

	if($_SERVER["REQUEST_METHOD"] == "POST") {

		$animeObj = new Anime;
		$userObj = new User;
		$comment = $animeObj->addComment($_POST['user_id'], $_POST['anime_id'], $_POST['time_stamp'], $_POST['comment']);


		$output = "<div id='comment-" . $comment['comment_num'] . "' class='comment'>
						<div class='comment-info'>
							<img src='images/" . $userObj->getUserInfo($comment['user_id'])['image'] . "' alt='avatar'>
							<p>" . $userObj->getUserInfo($comment['user_id'])['username'] . " - " . $comment['time_stamp'] . "</p>
						</div>
						<div class='comment-text'>
							<p>" . $comment['comment'] . "</p>
							<a href='#comment-" . $comment['comment_num'] . "'>Reply</a>
						</div>	 	
					</div>";

		echo $output;
	}
?>