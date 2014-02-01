<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'SucomNotice' ) ) {

	class SucomNotice {

		private $p;
		private $log = array(
			'err' => array(),
			'inf' => array(),
			'nag' => array(),
		);

		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			$this->p->debug->mark();

			add_action( 'all_admin_notices', array( &$this, 'admin_notices' ) );
		}

		public function nag( $msg = '', $store = false, $user = true ) { $this->log( 'nag', $msg, $store, $user ); }
		public function err( $msg = '', $store = false, $user = true ) { $this->log( 'err', $msg, $store, $user ); }
		public function inf( $msg = '', $store = false, $user = true ) { $this->log( 'inf', $msg, $store, $user ); }

		public function log( $type, $msg = '', $store = false, $user = true ) {
			if ( empty( $msg ) ) 
				return;
			if ( $store == true ) {						// save the message in the database
				$user_id = get_current_user_id();			// since wp 3.0
				if ( empty( $user_id ) )				// exclude wp-cron and/or empty user ids
					$user = false;
				$msg_opt = $this->p->cf['lca'].'_notices_'.$type;	// the option name
				if ( $user == true )					// get the message array from the user table
					$msg_arr = get_user_option( $msg_opt, $user_id );
				else $msg_arr = get_option( $msg_opt );			// get the message array from the options table
				if ( $msg_arr === false ) 
					$msg_arr = array();				// if the array doesn't already exist, define a new one
				if ( ! in_array( $msg, $msg_arr ) ) {			// dont't save duplicates
					$this->p->debug->log( 'storing '.$type.' message'.
						( $user == true ? ' for user '.$user_id : '' ).': '.$msg );
					$msg_arr[] = $msg;
				}
				if ( $user == true )					// update the user option table
					update_user_option( $user_id, $msg_opt, $msg_arr );
				else update_option( $msg_opt, $msg_arr );		// update the option table
			} elseif ( ! in_array( $msg, $this->log[$type] ) )		// dont't save duplicates
				$this->log[$type][] = $msg;
		}

		public function trunc( $type ) {
			$user_id = get_current_user_id();	// since wp 3.0
			$msg_opt = $this->p->cf['lca'].'_notices_'.$type;
			// delete doesn't always work, so set an empty value first
			if ( get_option( $msg_opt ) ) {
				update_option( $msg_opt, array() );
				delete_option( $msg_opt );
			}
			if ( get_user_option( $msg_opt, $user_id ) ) {
				update_user_option( $user_id, $msg_opt, array() );
				delete_user_option( $user_id, $msg_opt );
			}
			$this->log[$type] = array();
		}

		public function admin_notices() {
			foreach ( array( 'nag', 'err', 'inf' ) as $type ) {
				$user_id = get_current_user_id();	// since wp 3.0
				$msg_opt = $this->p->cf['lca'].'_notices_'.$type;
				$msg_arr = array_unique( array_merge( 
					(array) get_option( $msg_opt ), 
					(array) get_user_option( $msg_opt, $user_id ), 
					$this->log[$type] 
				) );
				$this->trunc( $type );
				if ( $type == 'err' ) {
					if ( class_exists( 'SucomUpdate' ) &&
						( $umsg = SucomUpdate::get_umsg( $this->p->cf['lca'] ) ) !== false )
							$msg_arr[] = $umsg;
				}
				if ( ! empty( $msg_arr ) ) {
					if ( $type == 'nag' ) {
						echo '
						<style type="text/css">
							.sucom-update-nag {
								display:block;
								line-height:1.4em;
								color:#333;
								background:#eeeeff;
								background-image: -webkit-gradient(linear, left bottom, left top, color-stop(7%, #eeeeff), color-stop(77%, #ddddff));
								background-image: -webkit-linear-gradient(bottom, #eeeeff 7%, #ddddff 77%);
								background-image:    -moz-linear-gradient(bottom, #eeeeff 7%, #ddddff 77%);
								background-image:      -o-linear-gradient(bottom, #eeeeff 7%, #ddddff 77%);
								background-image: linear-gradient(to top, #eeeeff 7%, #ddddff 77%);
								border:1px dashed #ccc;
								padding:10px 40px 10px 40px;
								margin-top:0;
							}
							.sucom-update-nag p,
							.sucom-update-nag ul {
								max-width:900px;
								margin:15px auto 15px auto;
								text-align:center;
							}
							.sucom-update-nag li {
								list-style:circle outside none;
								text-align:left;
								margin:5px 0 5px 20px;
							}
						</style>';
					}
					foreach ( $msg_arr as $msg ) {
						if ( ! empty( $msg ) )
							switch ( $type ) {
							case 'nag' :
								echo '<div class="update-nag sucom-update-nag">', $msg, '</div>', "\n";
								break;
							case 'err' :
								echo '<div class="error"><div style="float:left;"><p><b>', 
									$this->p->cf['menu'], ' Warning</b> :</p></div> <p>', $msg, '</p></div>', "\n";
								break;
							case 'inf' :
								echo '<div class="updated fade"><div style="float:left;"><p><b>', 
									$this->p->cf['menu'], ' Info</b> :</p></div> <p>', $msg, '</p></div>', "\n";
								break;
						}
					}
				}
			}
		}
	}
}

?>
