$(document).ready(function() {

	// Display form for adding to watched
	$('#add-to-watched').click(function() {
		$('#watched-form').css('display', 'block');
		$('body').css('overflow', 'hidden');
	});
	$('#watched-form-close').click(function() {
		$('#watched-form-close').css('display', 'none');

	});


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
				$("#comments-section").prepend(response);
				$("#loader").hide();

			}
		}); 

	}); //#send-comment

	$('#sendWatched').click(function() {
		
		var userName = $("#userName").val();
		var animeID = $('#animeID').val();
		var userRating = 9;

		// Sends the data using AJAX POST method
	    $.ajax({
			method: "POST",
			url: "config-watched.php",
			data: {
					username: userName,
					anime_id: animeID,
					user_rating: userRating			
		       	},
			success: function(response) {
				$("#list-update").prepend(response);

			}
		});
	});

});