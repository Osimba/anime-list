<?php

require __DIR__ . '/vendor/autoload.php';

include("includes/config.php");

if($_SERVER["REQUEST_METHOD"] == "POST") {


	//
	$addSuccessful = $User->addToWatched($_POST['username'], $_POST['anime_id'], $_POST['user_rating']);
	$response = '';

	if($addSuccessful) {
		$response = 'Successfully added anime to your watched list!';
	} else {
		$response = 'Unable to add to your watched list, please try again!';
	}
}