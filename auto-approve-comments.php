<?php

/*
 *	Plugin Name: Auto Approve Comments
 *	Plugin URI: https://github.com/fedeandri/auto-approve-comments
 *	Description: Provides a quick way to auto approve new comments based on user email, name, url and ID
 *	Version: 1.2
 *	Author: Federico Andrioli
 *	Author URI: https://it.linkedin.com/in/fedeandri
 *	GPLv2 or later
 *
*/


defined( 'ABSPATH' ) or die();


if ( ! class_exists( 'AutoApproveComments' ) ) {
	class AutoApproveComments
	{


		const DOMAIN_PATTERN = '/^([a-z0-9-]+\.)*[a-z0-9-]+\.[a-z]+$/';
		const EMAIL_PATTERN = '/^[a-z0-9-.]+@[a-z0-9-]+\.[a-z]+/';
		
		public function __construct() {

			add_action( 'admin_menu', array( &$this, 'plugin_init' ) );
			add_action( 'admin_init', array( &$this, 'register_db_settings' ) );
			add_action( 'wp_insert_comment', array( &$this, 'auto_approve_comments' ), 999, 2 );

		}

		public function plugin_init() {

			add_comments_page(
				'Auto Approve Comments',
				'Auto Approve Comments',
				'manage_options',
				'auto-approve-comments',
				array( &$this, 'add_settings_page' )
				);

			$this->read_input();
		}

		public function add_settings_page() {

			if( !current_user_can('manage_options') ){

				wp_die('You do not have sufficient permissions to access this page.');
			}

			require('views/settings-page.php');

		}

		public function register_db_settings() {
			register_setting( 'auto-approve-comments-group', 'commenters_list' );
			register_setting( 'auto-approve-comments-group', 'userid_list' );
		}

		public function auto_approve_comments( $comment_id, $comment_object ) {

			$comment = array();
			$comment['comment_ID'] = $comment_id;
			$comment['comment_author_email'] = strtolower($comment_object->comment_author_email);

			$userid_list = $this->get_usersid();

			if( !$comment['comment_approved'] && in_array($comment_object->user_id , $userid_list)) {
				
				$comment['comment_approved'] = 1;

			} elseif ( !$comment['comment_approved'] ) {

				$commenters_list = $this->get_commenters();

				$email = $comment['comment_author_email'];
				$name = strtolower(trim($comment_object->comment_author));
				$url = preg_replace('/https?:\/\//', '', strtolower(trim($comment_object->comment_author_url)));

				
				if( isset($commenters_list[$email])
					&& ( $commenters_list[$email]['name'] == $name || !$commenters_list[$email]['name'] )
					&& ( $commenters_list[$email]['url']  == $url  || !$commenters_list[$email]['url']  )
					) {

					$comment['comment_approved'] = 1;
				}
			}

			wp_update_comment( $comment );
		}

		private function get_commenters() {

			$commenters = array();
			$commenters_parsed = array();
			$commenters = preg_split('/\n+/', get_option('commenters_list'), -1, PREG_SPLIT_NO_EMPTY);

			foreach ($commenters as $commenter) {
				$features = preg_split('/,/', trim($commenter), -1, PREG_SPLIT_NO_EMPTY);
				
				if(isset($features[1])) {
				
					if(preg_match(self::DOMAIN_PATTERN,$features[1])) {
						$commenters_parsed[$features[0]]['url'] = $features[1];
					} else {
						$commenters_parsed[$features[0]]['name'] = $features[1];
					}

				}

				if(isset($features[2])) {

					if(preg_match(self::DOMAIN_PATTERN,$features[2])) {
						$commenters_parsed[$features[0]]['url'] = $features[2];
					} else {
						$commenters_parsed[$features[0]]['name'] = $features[2];
					}

				}
			}

			return $commenters_parsed;
		}

		private function get_usersid(){

			$usersid = array();
			$usersid = preg_split('/\n+/', get_option('userid_list'), -1, PREG_SPLIT_NO_EMPTY);

			return $usersid;

		}

		public static function read_input() {

	        if( isset($_POST['commenters_list']) && isset($_POST['userid_list']) ) {

		        $commenters_list = strtolower( trim( preg_replace('/\n+/', "\n", $_POST['commenters_list'] ) ) );
		        $commenters_list = preg_replace( '/[ ]*,[ ]*/', ',', $commenters_list );
		        $commenters_list = preg_replace( '/(\w)[ ]+(\w)/', "$1 $2", $commenters_list );
		        $commenters_list = preg_replace( '/https?:\/\//', '', $commenters_list );
		        $commenters_list = preg_replace('/,\s/', "\n", $commenters_list );
				$commenters_list = preg_replace('/,$/', '', $commenters_list );

		        $commenters = preg_split( '/\n+/', $commenters_list, -1, PREG_SPLIT_NO_EMPTY );
		        $commenters_clean = array();
		        
		        foreach ( $commenters as $commenter ) {
		            if( preg_match( self::EMAIL_PATTERN, $commenter ) ){
		                $commenters_clean[] = $commenter;
		            }
		        }

		        $commenters_list = implode( "\n", $commenters_clean );
		        update_option( 'commenters_list', $commenters_list );




		        $userid_list = strtolower( trim( preg_replace('/\s+/', "\n", $_POST['userid_list'] ) ) );
		        $userid_list = preg_replace('/,/', '', $userid_list );

		        $userids = preg_split( '/\n+/', $userid_list, -1, PREG_SPLIT_NO_EMPTY );
		        $userids_clean = array();
		        
		        foreach ( $userids as $userid ) {
		            if( preg_match( '/^[0-9]+$/', trim($userid) ) ){
		                $userids_clean[] = $userid;
		            }
		        }

		        $userid_list = implode( "\n", $userids_clean );
		        update_option( 'userid_list', $userid_list );
	        }

		}
	}

	new AutoApproveComments;
}










