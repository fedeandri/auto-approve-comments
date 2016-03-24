(function($) {
	
	$('#aac-usernames-autocomplete').on('input', function() {

		var data = {
			action: 'get_usernames_suggestions_ajax',
			search: $('#aac-usernames-autocomplete').val()
		}

		$.ajax({
			url: get_usernames_suggestions_ajax_params.ajaxurl,
			type: 'post',
			data: data,
			success: function( result ) {
				
				console.log("usernames autocomplete");
				console.log(result);

				$('#aac-usernames-autocomplete').autocomplete({
					source: result
				});
			}
		})
	})
	
})( jQuery );