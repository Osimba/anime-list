<?php

require __DIR__ . '/vendor/autoload.php';

include("includes/config.php");

if($_SERVER["REQUEST_METHOD"] == "POST") {
	$response = '';


	if($_POST['user_rating'] != 'false') {

		$addSuccessful = $User->addToWatched($_POST['user_id'], $_POST['anime_id'], $_POST['user_rating']);

		

		if($addSuccessful) {
			$response = "<i class='fas fa-check-circle'></i>
			<br><p>Successfully added anime to your watched list!</p>
			<br><br>
			<br><br><button class='btn btn-secondary'>Done</button>";
		} else {
			$response = 'Anime already added to watchlist!';
		}
		
		
	} else {
		$removeSuccessful = $User->removeFromWatched($_POST['user_id'], $_POST['anime_id']);

		if($removeSuccessful) {
			$response = "<p>Successfully removed anime from your watched list!</p>";
		}

	}

	echo $response;


}