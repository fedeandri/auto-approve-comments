"use strict";

if (typeof jQuery != 'function') {
    document.getElementById('aac-notice-error-jquery').style.display = 'block';
} else {

    (function($) {
    	
    	$('#aac-usernames-autocomplete').on('input', function() {
    
    		var data = {
    			action: 'aac_ajax_get_usernames_suggestions',
    			search: $('#aac-usernames-autocomplete').val()
    		}
    
    		$.ajax({
    			url: auto_approve_comments_ajax_params.ajaxurl,
    			type: 'post',
    			data: data,
    			success: function( response ) {
    				
    				$('#aac-usernames-autocomplete').autocomplete({
    					source: response
    				});
    			}
    		})
    	})
    	
    })( jQuery );
}
