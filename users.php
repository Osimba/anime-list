<?php  
	require './vendor/autoload.php';

	include('./includes/config.php');

	session_start();

	//check if user is logged in
	if(isset($_SESSION['user'])) {

		$userResults = $User->getAllUsers();

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

	include('./includes/templates/header.php');
?>

<main id="users-page">

	<h1>Users</h1>

	
		
		
		<?php 
			forEach($userResults as $result) { 
				$image =  IMAGE_DIR . $result['image'];
				$profileLink = ROOT_DIR . 'profile.php?id=' . $result['id']; 
		?>
			<div class="card">
				<img class="user-image" src="<?= $image ?>">
				<p><?= $result['username']; ?></p>
				<p><?= $result['user_role']; ?></p>
				<a href="<?= $profileLink ?>"><button class="btn btn-warning">Profile</button></a>
			</div>
		<?php } ?>
		
	
</main>



<?php include('./includes/templates/footer.php') ?>