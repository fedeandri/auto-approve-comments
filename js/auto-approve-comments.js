(function($) {
	
	var commenters_list_placeholder = "Start typing to look for commenters";
	var usernames_list_placeholder = "Start typing to look for usernames";

	$( document ).ready( function() {
		$('#aac-commenters-autocomplete').attr("placeholder", commenters_list_placeholder);
		$('#aac-usernames-autocomplete').attr("placeholder", usernames_list_placeholder);
	});

	$( document ).on( 'click', '.nav-tab-wrapper a', function() {

		$( 'section' ).hide();
		$( 'h2.nav-tab-wrapper a' ).removeClass( 'nav-tab-active' );
		$( event.target ).addClass( 'nav-tab-active' ).blur();;
		$( 'section' ).eq($(this).index()).show();

		return false;
	})

	$( document ).on( 'click', '#add_commenter', function() {

		if( $('#aac-commenters-autocomplete').val() != '' ){
			var commenters_list;
			commenters_list = $('#aac-commenters-autocomplete').val() + "\n" + $('#commenters_list').val();

			$('#commenters_list').val(commenters_list);
			$('#aac-commenters-autocomplete').val('');
			$('#aac-commenters-autocomplete').attr("placeholder", commenters_list_placeholder);
		}
	})
	
	$( document ).on( 'click', '#add_username', function() {

		if( $('#aac-usernames-autocomplete').val() != '' ){
			var usernames_list;
			usernames_list = $('#aac-usernames-autocomplete').val() + "\n" + $('#usernames_list').val();

			$('#usernames_list').val(usernames_list);
			$('#aac-usernames-autocomplete').val('');
			$('#aac-usernames-autocomplete').attr("placeholder", usernames_list_placeholder);
		}
	})
	
})( jQuery );