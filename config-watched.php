<?php

require __DIR__ . '/vendor/autoload.php';

include("includes/config.php");

if($_SERVER["REQUEST_METHOD"] == "POST") {
	$response = '';


	if($_POST['user_rating'] != 'false') {

		$addSuccessful = $User->addToWatched($_POST['user_id'], $_POST['anime_id'], $_POST['user_rating']);

		

		if($addSuccessful) {
			$response = "<br><i class='fas fa-check-circle fa-5x' style='color: green;'></i>
			<br><br><h4>Successfully added anime to your watched list!</h4>";
		} else {
			$response = "<br><i class='fas fa-times-circle fa-5x' style='color: red;'></i>
			<br><br><h4>Anime already added to watchlist!</h4>";
		}
		
		
	} else {
		$removeSuccessful = $User->removeFromWatched($_POST['user_id'], $_POST['anime_id']);

		if($removeSuccessful) {
			$response = "<br><i class='fas fa-check-circle fa-5x' style='color: green;'></i>
			<br><br><h4>Successfully removed anime from your watched list!</h4>";
		}

	}

	echo $response;


}