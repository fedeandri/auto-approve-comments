<?php

/*
 *	Plugin Name: Auto Approve Comments
 *	Plugin URI: https://github.com/fedeandri/auto-approve-comments
 *	Description: Provides a quick way to auto approve new comments based on commenter email/name/url or username
 *	Version: 1.5
 *	Author: Federico Andrioli
 *	Author URI: https://it.linkedin.com/in/fedeandri
 *	GPLv2 or later
 *
*/


defined( 'ABSPATH' ) or die();


if ( ! class_exists( 'AutoApproveComments' ) ) {
	class AutoApproveComments
	{

		const VERSION = '1.5';
		const DOMAIN_PATTERN = '/^([a-z0-9-]+\.)*[a-z0-9-]+\.[a-z]+$/';
		const EMAIL_PATTERN = '/^[a-z0-9-.]+@[a-z0-9-]+\.[a-z]+/';
		
		public function __construct() {

			add_action( 'admin_menu', array( &$this, 'plugin_init' ) );
			add_action( 'admin_init', array( &$this, 'register_db_settings' ) );
			add_action( 'wp_insert_comment', array( &$this, 'auto_approve_comments' ), 999, 2 );
			add_action( 'admin_enqueue_scripts', array( &$this, 'enqueue_custom_admin_files') );
			add_action( 'wp_ajax_get_commenters_suggestions_ajax',  array( &$this, 'get_commenters_suggestions_ajax') );
			add_action( 'wp_ajax_get_usernames_suggestions_ajax',  array( &$this, 'get_usernames_suggestions_ajax') );

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
			$this->plugin_update();
		}

		public function add_settings_page() {

			if( !current_user_can('manage_options') ){

				wp_die('You do not have sufficient permissions to access this page.');
			}

			require('views/settings-page.php');

		}

		public function register_db_settings() {
			register_setting( 'auto-approve-comments-group', 'aac_plugin_version' );
			register_setting( 'auto-approve-comments-group', 'aac_commenters_list' );
			register_setting( 'auto-approve-comments-group', 'aac_usernames_list' );
		}

		public function enqueue_custom_admin_files( $hook ) {

			if ( 'comments_page_auto-approve-comments' != $hook ) {
		        return;
		    }

	        wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'jquery-ui-autocomplete' );
			wp_enqueue_script( 'caret', plugin_dir_url( __FILE__ ) . 'lib/jQuery-tagEditor/jquery.caret.min.js', array( 'jquery' ), '1.0.0', true );

	        wp_enqueue_script( 'auto-approve-comments-js', plugin_dir_url( __FILE__ ) . 'js/auto-approve-comments.js', array( 'jquery' ), '1.0.0', true );
			wp_register_style( 'auto-approve-comments-css', plugin_dir_url( __FILE__ ) . 'css/auto-approve-comments.css', false, '1.0.0' );
			wp_enqueue_style( 'auto-approve-comments-css' );
			
			wp_enqueue_script( 'aac-ajax-commenters-suggestions-js', plugin_dir_url( __FILE__ ) . 'js/ajax-commenters-suggestions.js', array( 'jquery' ), '1.0.0', true );
			wp_localize_script( 'aac-ajax-commenters-suggestions-js', 'get_commenters_suggestions_ajax_params', array(
				'ajaxurl' => admin_url( 'admin-ajax.php' )
			));

			wp_enqueue_script( 'aac-ajax-usernames-suggestions-js', plugin_dir_url( __FILE__ ) . 'js/ajax-usernames-suggestions.js', array( 'jquery' ), '1.0.0', true );
			wp_localize_script( 'aac-ajax-usernames-suggestions-js', 'get_usernames_suggestions_ajax_params', array(
				'ajaxurl' => admin_url( 'admin-ajax.php' )
			));

			
		}

		public function auto_approve_comments( $comment_id, $comment_object ) {

			$comment = array();
			$comment['comment_ID'] = $comment_id;
			$comment['comment_author_email'] = strtolower($comment_object->comment_author_email);

			$usernames_list = $this->get_usernames();
			$user_info = get_userdata( $comment_object->user_id );

			if( !$comment['comment_approved'] && in_array( $user_info->user_login, $usernames_list ) ) {
				
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

		public static function get_commenters_suggestions_ajax() {
			
			global $wpdb;
			
			if( current_user_can( 'manage_options' ) ) {

				$search = str_replace( "'", '', $wpdb->prepare( '%s', $_REQUEST['search'] ) );

				$sql = "SELECT DISTINCT CONCAT_WS(', ', comment_author_email, comment_author, comment_author_url)
						FROM {$wpdb->comments}
						WHERE
							comment_author_email LIKE '{$search}%' OR
							comment_author LIKE '{$search}%' OR
							comment_author_url LIKE '%{$search}%'
						ORDER BY
							comment_author_email DESC, comment_author DESC, comment_author_url DESC
						LIMIT 10;";

				$results = $wpdb->get_results( $sql, ARRAY_N );
				
				$suggestions = array();

				foreach ($results as $result) {
					$suggestions[] = preg_replace('/http(s)?:\/\//i', '', trim( $result[0], ', ' ) );
				}

				wp_send_json( $suggestions );

			}

			exit();
		}


		public static function get_usernames_suggestions_ajax() {
			
			global $wpdb;
			
			if( current_user_can( 'manage_options' ) ) {

				$search = str_replace( "'", '', $wpdb->prepare( '%s', $_REQUEST['search'] ) );

				$sql = "SELECT user_login
						FROM {$wpdb->users}
						WHERE
							user_login LIKE '{$search}%'
						ORDER BY
							user_login ASC
						LIMIT 10;";

				$results = $wpdb->get_results( $sql, ARRAY_N );
				
				$suggestions = array();

				foreach ($results as $result) {
					$suggestions[] = $result[0];
				}

				wp_send_json( $suggestions );

			}

			exit();
		}


		private function get_commenters() {

			$commenters = array();
			$commenters_parsed = array();
			$commenters = preg_split('/\n+/', get_option('aac_commenters_list'), -1, PREG_SPLIT_NO_EMPTY);

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


		private function get_usernames() {

			$usernames = array();
			$usernames = preg_split('/\n+/', get_option('aac_usernames_list'), -1, PREG_SPLIT_NO_EMPTY);

			return $usernames;

		}

		public static function userids_to_usernames( $usernames_list ) {
				
				$usernames_clean = array();
		        
		        foreach ( $usernames_list as $username ) {
		        	
		        	$username = trim( $username );
		        	
		        	if ( preg_match('/^\d+$/', $username) ) {
		        		//convert users ID to usernames
		        		$user_info = get_userdata( $username );
		        		$username = $user_info->user_login;
		        	}

	                $usernames_clean[ $username ] = true;

		        }

		        $usernames_list = implode( "\n", array_keys( $usernames_clean ) );
		        return $usernames_list;
		}

		private function plugin_update() {

			if ( get_option( 'aac_plugin_version' ) != self::VERSION ) {

				// 1.2 -> 1.5 update
				$usernames_list = preg_split( '/\n+/', get_option( 'userid_list' ), -1, PREG_SPLIT_NO_EMPTY );
		        
		        $usernames_list = $this->userids_to_usernames( $usernames_list );
				update_option( 'aac_usernames_list', $usernames_list );
				update_option( 'aac_commenters_list', get_option( 'commenters_list' ) );

				update_option( 'aac_plugin_version', self::VERSION );

				unregister_setting( 'auto-approve-comments-group', 'userid_list' );
				unregister_setting( 'auto-approve-comments-group', 'commenters_list' );

				delete_option( 'userid_list' );
				delete_option( 'commenters_list' );

			}

		}

		public static function read_input() {

	        if( isset($_POST['commenters_list']) && isset($_POST['usernames_list']) ) {

		        // commenters
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
		                $commenters_clean[trim( $commenter )] = true;
		            }
		        }

		        $commenters_list = implode( "\n", array_keys( $commenters_clean ) );
		        update_option( 'aac_commenters_list', $commenters_list );

		        //usernames
		        $usernames_list = strtolower( trim( preg_replace('/\s+/', "\n", $_POST['usernames_list'] ) ) );
		        $usernames_list = preg_replace('/,/', '', $usernames_list );

		        $usernames_list = preg_split( '/\n+/', $usernames_list, -1, PREG_SPLIT_NO_EMPTY );
				
				$usernames_list = self::userids_to_usernames( $usernames_list );
		        
		        update_option( 'aac_usernames_list', $usernames_list );
	        }

		}
	}

	new AutoApproveComments;
}










