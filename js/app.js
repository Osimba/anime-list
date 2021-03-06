$(document).ready(function() {

	// Display form for adding to watched
	$('#add-to-watched').click(function() {
		$('#watched-form-close').css('display', 'block');
		$('#watched-form').css('display', 'block');
		$('body').css('overflow', 'hidden');
	});
	$('#remove-from-watched').click(function() {
		var userID = $("#userID").val();
		var animeID = $('#animeID').val();
		var userRating = 'false';

		// Sends the data using AJAX POST method
	    $.ajax({
			method: "POST",
			url: "config-watched.php",
			data: {
					user_id: userID,
					anime_id: animeID,
					user_rating: userRating			
		       	},
			success: function(response) {
				$('#watched-form-close').css('display', 'block');
				$('body').css('overflow', 'hidden');
				$("#watched-form").html(response);
				$('#watched-form').css('display', 'block');
			}
		});
		
	});

	$('.close-popup').click(function() {
		$('#watched-form').css('display', 'none');
		$('#watched-form-close').css('display', 'none');
		$('body').css('overflow', 'unset');
		window.location.reload();
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
		$("#loader").show();
		
		var userID = $("#userID").val();
		var animeID = $('#animeID').val();
		var userRating = $('#userRating').val();

		if (userRating >= 1 && userRating <= 10) {
			// Sends the data using AJAX POST method
		    $.ajax({
				method: "POST",
				url: "config-watched.php",
				data: {
						user_id: userID,
						anime_id: animeID,
						user_rating: userRating			
			       	},
				success: function(response) {
					console.log(response);
					$("#loader").hide();
					$("#addAlert").hide();
					$("#watched-form").html(response);

				}
			});
		} else {
			$("#loader").hide();
			$("#addAlert").show();
		}

		
	});

});