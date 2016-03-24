(function($) {
	
	$('#aac-commenters-autocomplete').on('input', function() {

		var data = {
			action: 'get_commenters_suggestions_ajax',
			search: $('#aac-commenters-autocomplete').val()
		}

		$.ajax({
			url: get_commenters_suggestions_ajax_params.ajaxurl,
			type: 'post',
			data: data,
			success: function( result ) {
				
				console.log("commenters autocomplete");
				console.log(result);

				$('#aac-commenters-autocomplete').autocomplete({
					source: result
				});
			}
		})
	})
	
})( jQuery );