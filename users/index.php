<?php  
	require '../vendor/autoload.php';

	include('../includes/Dbh.class.php');
	include('../includes/Anime.class.php');
	include('../includes/User.class.php');
	include('../includes/config.php');

	session_start();

	//check if user is logged in
	if(isset($_SESSION['user'])) {

		$userObj = new User;
		$users = $userObj->getAllUsers();

		//check for inactivity
		if(time() > $_SESSION['last_active'] + $config['session_timeout']) {
			session_destroy();
			header('location: ../index.php?timeout');
		} else {
			$_SESSION['last_active'] = time();
		}


	} else {
		//if not logged in redirect
		header('location: ../index.php?unauthorized');
	}

	include('../includes/templates/header.php');
?>

<main id="members-page">

	<h1>Users</h1>

	
		
		
		<?php 
			forEach($users as $user) { 
				$image =  ROOT_DIR . 'images/' . $user['image'];
		?>
			<div class="card">
				<img class="user-image" src="<? echo $image; ?>">
				<p><? echo $user['username']; ?></p>
				<p><? echo $user['user_role']; ?></p>
			</div>
		<?php } ?>
		
	
</main>



<?php include('../includes/templates/footer.php') ?>