(function($) {
	
	$('#aac-commenters-autocomplete').on('input', function() {

		var data = {
			action: 'aac_ajax_get_commenters_suggestions',
			search: $('#aac-commenters-autocomplete').val()
		}

		$.ajax({
			url: auto_approve_comments_ajax_params.ajaxurl,
			type: 'post',
			data: data,
			success: function( response ) {

				$('#aac-commenters-autocomplete').autocomplete({
					source: response
				});
			}
		})
	})
	
})( jQuery );