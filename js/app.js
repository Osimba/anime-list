$(document).ready(function() {

	// Check if Send Comment button was clicked
	$('#send-comment').click(function() {

		// Sends the data using AJAX POST method
	    $.ajax({
			method: "POST",
			url: "add-comment.php",
			data: {
					user_id: coinFilterInfo['era'],
					anime_id: coinFilterInfo['civilization'],
					time_stamp: coinFilterInfo['time_issued'],
					comment: coinFilterInfo['metal_composition']					
		       	},
			success: function(response) {
				$("#result").html(response);
				$("#loader").hide();

			}
		});



	}); //#send-comment




});