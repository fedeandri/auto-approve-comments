<?php

/*
 *	Plugin Name: Auto Approve Comments
 *	Plugin URI: http://automattic.com
 *	Description: Provides a quick way to auto approve new comments based on user email
 *	Version: 1.0
 *	Author: Federico Andrioli
 *	Author URI: https://it.linkedin.com/in/fedeandri
 *	GPLv2 or later
 *
*/

add_action('admin_menu', 'fa_auto_approve_comments');

function fa_auto_approve_comments() {

	add_comments_page(
		'Auto Approve Comments',
		'Auto Approve Comments',
		'manage_options',
		'auto-approve-comments',
		'fa_auto_approve_comments_option_page'
		);
}

function fa_auto_approve_comments_option_page() {

	if(!current_user_can('manage_options')){

		wp_die('You do not have sufficient permissions to access this page.');
	}

	require('views/settings-page.php');

}


add_action( 'admin_init', 'fa_auto_approve_comments_settings' );

function fa_auto_approve_comments_settings() {
	register_setting( 'auto-approve-comments-group', 'email_list' );
}


add_action('wp_insert_comment','fa_comment_auto_approve',999,2);

function fa_comment_auto_approve($comment_id, $comment_object) {

	$email_list = get_option('email_list');
	$emails = preg_split('/\n+/', $email_list, -1, PREG_SPLIT_NO_EMPTY);

    $comment = array();
	$comment['comment_ID'] = $comment_id;
	$comment['comment_author_email'] = strtolower($comment_object->comment_author_email);

    if (in_array($comment['comment_author_email'] , $emails)) {

		$comment['comment_approved'] = 1;

    }

	wp_update_comment( $comment );

}











