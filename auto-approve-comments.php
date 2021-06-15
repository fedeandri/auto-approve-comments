<?php

/*
 *	Plugin Name: Auto Approve Comments
 *	Plugin URI: https://github.com/fedeandri/auto-approve-comments
 *	Description: Auto approve new comments based on commenter (email/name/url), username or role.
 *	Version: 2.8
 *	Author: Federico Andrioli
 *	Author URI: https://it.linkedin.com/in/fedeandri
 *	GPLv2 or later
 *
*/


defined( 'ABSPATH' ) or die();


if ( ! class_exists( 'AutoApproveComments' ) ) {
	class AutoApproveComments
	{

		const VERSION = '2.8';
		const PLUGIN_SLUG = 'auto-approve-comments';
		const DOMAIN_PATTERN = '/^\w+([\.-]\w+)*(\.\w{2,10})+$/';
		/* important: email has to match against the beginning but not against the end of the string */
		const EMAIL_PATTERN = '/^\w+([\.\-\+\?]\w+)*@\w+([\.-]\w+)*(\.\w{2,10})+/';
		
		public function __construct() {

			add_action( 'admin_menu', array( &$this, 'plugin_init' ) );
			add_filter( 'plugin_action_links_'.plugin_basename(__FILE__), array( &$this, 'add_settings_link') );
			add_action( 'admin_init', array( &$this, 'register_db_settings' ) );
			add_action( 'wp_insert_comment', array( &$this, 'auto_approve_comments' ), 999, 2 );			
			add_action( 'admin_enqueue_scripts', array( &$this, 'enqueue_custom_admin_files') );
			add_action( 'wp_ajax_aac_ajax_get_commenters_suggestions',  array( &$this, 'aac_ajax_get_commenters_suggestions') );
			add_action( 'wp_ajax_aac_ajax_get_usernames_suggestions',  array( &$this, 'aac_ajax_get_usernames_suggestions') );
			add_action( 'wp_ajax_aac_ajax_get_roles_suggestions',  array( &$this, 'aac_ajax_get_roles_suggestions') );
			//add_action( 'wp_ajax_aac_ajax_get_categories_suggestions',  array( &$this, 'aac_ajax_get_categories_suggestions') );
			//add_action( 'wp_ajax_aac_ajax_get_postspages_suggestions',  array( &$this, 'aac_ajax_get_postspages_suggestions') );
			add_action( 'wp_ajax_aac_ajax_save_configuration',  array( &$this, 'aac_ajax_save_configuration' ) );
			add_action( 'wp_ajax_aac_ajax_refresh_configuration',  array( &$this, 'aac_ajax_refresh_configuration' ) );

		}

		public function plugin_init() {

			add_comments_page(
				'Auto Approve Comments',
				'Auto Approve Comments',
				'manage_options',
				self::PLUGIN_SLUG,
				array( &$this, 'add_settings_page' )
				);

			$this->plugin_update();
		}

		public function add_settings_page() {

			if( !current_user_can('manage_options') ){

				wp_die('You do not have sufficient permissions to access this page.');
			}

			require('views/settings-page.php');

		}
		
		public function add_settings_link( $links ) {

			$links[] = '<a href="'.network_admin_url( 'edit-comments.php?page='.self::PLUGIN_SLUG ).'">'.__('Settings').'</a>';
			return $links;

		}

		public function register_db_settings() {
			register_setting( self::PLUGIN_SLUG.'-group', 'aac_plugin_version' );
			register_setting( self::PLUGIN_SLUG.'-group', 'aac_commenters_list' );
			register_setting( self::PLUGIN_SLUG.'-group', 'aac_usernames_list' );
			register_setting( self::PLUGIN_SLUG.'-group', 'aac_roles_list' );
			//register_setting( self::PLUGIN_SLUG.'-group', 'aac_categories_list' );
			//register_setting( self::PLUGIN_SLUG.'-group', 'aac_postspages_list' );
		}

		public function enqueue_custom_admin_files() {
            
            $currentScreen = get_current_screen();
            
			if ($currentScreen->id != 'comments_page_'.self::PLUGIN_SLUG) {
		        return;
		    }

	        wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'jquery-ui-autocomplete' );
			
	        wp_enqueue_script( self::PLUGIN_SLUG.'-js', plugin_dir_url( __FILE__ ) . 'js/'.self::PLUGIN_SLUG.'.js', array( 'jquery', 'jquery-ui-autocomplete' ), '1.2.0', true );
			wp_localize_script( self::PLUGIN_SLUG.'-js', 'auto_approve_comments_ajax_params', array(
				'ajaxurl' => admin_url( 'admin-ajax.php' )
			));

			wp_register_style( self::PLUGIN_SLUG.'-css', plugin_dir_url( __FILE__ ) . 'css/'.self::PLUGIN_SLUG.'.css', false, '1.1.0' );
			wp_enqueue_style( self::PLUGIN_SLUG.'-css' );
			
			wp_enqueue_script( 'aac-ajax-commenters-suggestions-js', plugin_dir_url( __FILE__ ) . 'js/ajax-commenters-suggestions.js', array( self::PLUGIN_SLUG.'-js' ), '1.2.0', true );
			wp_enqueue_script( 'aac-ajax-usernames-suggestions-js', plugin_dir_url( __FILE__ ) . 'js/ajax-usernames-suggestions.js', array( self::PLUGIN_SLUG.'-js' ), '1.2.0', true );
			wp_enqueue_script( 'aac-ajax-roles-suggestions-js', plugin_dir_url( __FILE__ ) . 'js/ajax-roles-suggestions.js', array( self::PLUGIN_SLUG.'-js' ), '1.2.0', true );
			//wp_enqueue_script( 'aac-ajax-categories-suggestions-js', plugin_dir_url( __FILE__ ) . 'js/ajax-categories-suggestions.js', array( self::PLUGIN_SLUG.'-js' ), '1.0.0', true );
			//wp_enqueue_script( 'aac-ajax-postspages-suggestions-js', plugin_dir_url( __FILE__ ) . 'js/ajax-postspages-suggestions.js', array( self::PLUGIN_SLUG.'-js' ), '1.0.0', true );
			
			wp_enqueue_script( 'aac-ajax-save-refresh-configuration-js', plugin_dir_url( __FILE__ ) . 'js/ajax-save-refresh-configuration.js', array( self::PLUGIN_SLUG.'-js' ), '1.2.0', true );
			
		}

		public function auto_approve_comments( $comment_id, $comment_object ) {

			$comment = array();
			$comment['comment_ID'] = $comment_id;
			$comment['comment_author_email'] = strtolower($comment_object->comment_author_email);
			
			$passAkismetTest = true;
			
			if ( class_exists( 'Akismet' ) ) {
			    
			    if (
			        'true' === get_comment_meta( $comment['comment_ID'], 'akismet_result', true )
			        || get_comment_meta( $comment['comment_ID'], 'akismet_error', true ) ) {
	                
			        $passAkismetTest = false;
			    }
			}
			
			$user_info = get_userdata( $comment_object->user_id );

			/* ROLES */
			if( 
			    $passAkismetTest
				&& !$comment['comment_approved'] 
				&& $user_info 
				&& $this->auto_approve_roles($user_info->roles) ) {

				$comment['comment_approved'] = 1;

			/* USERNAMES */
			} elseif( 
				$passAkismetTest
				&& !$comment['comment_approved'] 
				&& $user_info 
				&& $this->auto_approve_usernames($user_info->user_login) ) {
				
				$comment['comment_approved'] = 1;

			/* COMMENTERS */
			} elseif ( 
				$passAkismetTest
				&& !$comment['comment_approved'] 
				&& $this->auto_approve_commenters($comment_object) ) {

				$comment['comment_approved'] = 1;
			}
            
            if( $comment['comment_approved'] )
			    wp_update_comment( $comment );
		}

		public function aac_ajax_get_commenters_suggestions() {
			
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

				if ( count($suggestions) < 1 ) {
					$suggestions[] = 'no matches for "'.$search.'"';
				}

				wp_send_json( $suggestions );

			}

			exit();
		}

		public function aac_ajax_get_usernames_suggestions() {
			
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
				if ( count($suggestions) < 1 ) {
					$suggestions[] = 'no matches for "'.$search.'"';
				}
				wp_send_json( $suggestions );
			}
			exit();
		}

		public function aac_ajax_get_roles_suggestions() {
			
			global $wpdb;
			
			if( current_user_can( 'manage_options' ) ) {

				$search = str_replace( "'", '', $_REQUEST['search'] );

				$sql = "SELECT option_value
						FROM {$wpdb->options}
						WHERE
							option_name = 'wp_user_roles'
						LIMIT 1;";

				$results = array_keys( unserialize( $wpdb->get_var($sql) ) );

				$suggestions = array();

				foreach ($results as $result) {
					if (strpos($result, $search) !== false) {
						$suggestions[] = $result;
					}
					
				}

				if ( count($suggestions) < 1 ) {
					$suggestions[] = 'no matches for "'.$search.'"';
				}

				wp_send_json( $suggestions );

			}

			exit();
		}

		public function aac_ajax_refresh_configuration() {
			
			if( current_user_can( 'manage_options' ) ) {

				$response['commenters'] = get_option('aac_commenters_list');
				$response['usernames'] = get_option('aac_usernames_list');
				$response['roles'] = get_option('aac_roles_list');

				wp_send_json( $response );

			}

			exit();
		}

		public function aac_ajax_save_configuration () {

			#BugFu::log($_REQUEST['nonce']);
			#BugFu::log( wp_verify_nonce( $_REQUEST['nonce'], 'aac-save-configuration-nonce' ) );

			if( current_user_can( 'manage_options' ) && wp_verify_nonce( $_REQUEST['nonce'], 'aac-save-configuration-nonce' ) ) {

				$response = array();
				
				/* COMMENTERS */
		        $commenters_list = strtolower( trim( preg_replace('/\n+/', "\n", $_REQUEST['commenters'] ) ) );
		        $commenters_list = preg_replace( '/[ ]*,[ ]*/', ',', $commenters_list );
		        $commenters_list = preg_replace( '/([^ ]+)[ ]+([^ ]+)/', "$1 $2", $commenters_list );
		        $commenters_list = preg_replace( '/https?:\/\//', '', $commenters_list );
		        $commenters_list = preg_replace('/,\s+/', "\n", $commenters_list );
		        $commenters_list = preg_replace('/,$/', '', $commenters_list );

		        $commenters = preg_split( '/\n+/', $commenters_list, -1, PREG_SPLIT_NO_EMPTY );

		        $commenters_clean = array();
		        
		        # remove duplicates and bad patterns
		        foreach ( $commenters as $commenter ) {
		            if( preg_match( self::EMAIL_PATTERN, $commenter ) ){
		                $commenters_clean[trim( $commenter )] = true;
		            }
		        }
		        
		        $commenters_list = implode( "\n", array_keys( $commenters_clean ) );

		        if (update_option( 'aac_commenters_list', $commenters_list ) ) {
		        	$response['commenters_updated'] = true;
		        }


				/* USERNAMES */
		        $usernames_list = strtolower( trim( preg_replace('/\s+/', "\n", $_REQUEST['usernames'] ) ) );
		        $usernames_list = preg_replace('/,/', '', $usernames_list );

		        $usernames_list = preg_split( '/\n+/', $usernames_list, -1, PREG_SPLIT_NO_EMPTY );
				
				$usernames_list = self::userids_to_usernames( $usernames_list );
		        
		        if (update_option( 'aac_usernames_list', $usernames_list ) ) {
		        	$response['usernames_updated'] = true;
		        }


				/* ROLES */
		        $roles_list = strtolower( trim( preg_replace('/\s+/', "\n", $_REQUEST['roles'] ) ) );
		        $roles_list = preg_replace('/,/', '', $roles_list );

		        $roles = preg_split( '/\n+/', $roles_list, -1, PREG_SPLIT_NO_EMPTY );
		        $roles_clean = array();
                
                # remove duplicates
		        foreach ( $roles as $role ) {
	                $roles_clean[trim( $role )] = true;
		        }
		        
		        $roles_list = implode( "\n", array_keys( $roles_clean ) );
		        
		        if ( update_option( 'aac_roles_list', $roles_list ) ) {
		        	$response['roles_updated'] = true;
		        }

		        wp_send_json_success( $response );

	        } else {

				wp_send_json_error();
			}

	        exit();
		}

		private function get_commenters() {

			$commenters = array();
			$commenters_parsed = array();
			$commenters = preg_split('/\n+/', get_option('aac_commenters_list'), -1, PREG_SPLIT_NO_EMPTY);

			foreach ($commenters as $commenter) {
				$features = preg_split('/,/', trim($commenter), -1, PREG_SPLIT_NO_EMPTY);
				
				$commenters_parsed[$features[0]]['email'] = $features[0];

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

		private function get_roles() {

			$roles = array();
			$roles = preg_split('/\n+/', get_option('aac_roles_list'), -1, PREG_SPLIT_NO_EMPTY);

			return $roles;

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

		private function auto_approve_roles( $user_roles ) {
				
		        $user_is_allowed = false;
				$roles_list = $this->get_roles();
		        
		        foreach ( $user_roles as $user_role ) {
		        	
		        	if ( in_array($user_role, $roles_list) ) {

		                $user_is_allowed = true;
		                break;
		        	}

		        }

		        return $user_is_allowed;
		}

		private function auto_approve_usernames( $username ) {
				
		        $user_is_allowed = false;
				$usernames_list = $this->get_usernames();

	        	if ( in_array($username, $usernames_list) ) {

	                $user_is_allowed = true;
	        	}

		        return $user_is_allowed;
		}

		private function auto_approve_commenters( $comment_object ) {
				
			$commenter_approved = false;
			$commenters_list = $this->get_commenters();

			$email = strtolower($comment_object->comment_author_email);
			$name = strtolower(trim($comment_object->comment_author));
			$url = preg_replace('/https?:\/\//', '', strtolower(trim($comment_object->comment_author_url)));

			if( isset($commenters_list[$email])
				&& ( $commenters_list[$email]['name'] == $name || !$commenters_list[$email]['name'] )
				&& ( $commenters_list[$email]['url']  == $url  || !$commenters_list[$email]['url']  )
				) {

				$commenter_approved = true;
			}

			return $commenter_approved;
		}

		private function plugin_update() {

			$current_plugin_version = get_option( 'aac_plugin_version' );

			if ( $current_plugin_version != self::VERSION ) {

				if ( $current_plugin_version == '1.2' ) {
					$usernames_list = preg_split( '/\n+/', get_option( 'userid_list' ), -1, PREG_SPLIT_NO_EMPTY );
			        
			        $usernames_list = $this->userids_to_usernames( $usernames_list );
					update_option( 'aac_usernames_list', $usernames_list );
					update_option( 'aac_commenters_list', get_option( 'commenters_list' ) );

					update_option( 'aac_plugin_version', self::VERSION );

					unregister_setting( self::PLUGIN_SLUG.'-group', 'userid_list' );
					unregister_setting( self::PLUGIN_SLUG.'-group', 'commenters_list' );

					delete_option( 'userid_list' );
					delete_option( 'commenters_list' );
				}

			}

		}

	}

	new AutoApproveComments;
}

