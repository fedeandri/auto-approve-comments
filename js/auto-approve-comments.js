(function($) {
	
	var commenters_list_placeholder = "Start typing to look for commenters";
	var usernames_list_placeholder = "Start typing to look for usernames";
	var roles_list_placeholder = "Start typing to look for roles";

	$( document ).ready( function() {
		$('#aac-commenters-autocomplete').attr("placeholder", commenters_list_placeholder);
		$('#aac-usernames-autocomplete').attr("placeholder", usernames_list_placeholder);
		$('#aac-roles-autocomplete').attr("placeholder", roles_list_placeholder);
	});

	$( document ).on( 'click', '.nav-tab-wrapper a', function() {

		$( 'section' ).hide();
		$( 'h2.nav-tab-wrapper a' ).removeClass( 'nav-tab-active' );
		$( event.target ).addClass( 'nav-tab-active' ).blur();
		$( 'section' ).eq($(this).index()).show();

		return false;
	})

	$( document ).on( 'click', '#aac-add-commenter', function() {

		if( $('#aac-commenters-autocomplete').val() != '' ){
			var commenters_list;
			commenters_list = $('#aac-commenters-autocomplete').val() + "\n" + $('#aac-commenters-list').val();

			$('#aac-commenters-list').val(commenters_list);
			$('#aac-commenters-autocomplete').val('');
			$('#aac-commenters-autocomplete').attr("placeholder", commenters_list_placeholder);
		}
	})
	
	$( document ).on( 'click', '#aac-add-username', function() {

		if( $('#aac-usernames-autocomplete').val() != '' ){
			var usernames_list;
			usernames_list = $('#aac-usernames-autocomplete').val() + "\n" + $('#aac-usernames-list').val();

			$('#aac-usernames-list').val(usernames_list);
			$('#aac-usernames-autocomplete').val('');
			$('#aac-usernames-autocomplete').attr("placeholder", usernames_list_placeholder);
		}
	})

	$( document ).on( 'click', '#aac-add-role', function() {

		if( $('#aac-roles-autocomplete').val() != '' ){
			var roles_list;
			roles_list = $('#aac-roles-autocomplete').val() + "\n" + $('#aac-roles-list').val();

			$('#aac-roles-list').val(roles_list);
			$('#aac-roles-autocomplete').val('');
			$('#aac-roles-autocomplete').attr("placeholder", roles_list_placeholder);
		}
	})

	$( document ).on( 'click', '#aac-notice-success-dismiss', function() {

		$( '#aac-notice-success' ).fadeOut(500);
	})
	
	$( document ).on( 'click', '#aac-notice-error-dismiss', function() {

		$( '#aac-notice-error' ).fadeOut(500);
	})
	
})( jQuery );