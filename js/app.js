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
				$("#comments-section").prepend(response);
				$("#loader").hide();

			}
		}); 

	}); //#send-comment

	$('.btn-watched').click(function() {
		
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