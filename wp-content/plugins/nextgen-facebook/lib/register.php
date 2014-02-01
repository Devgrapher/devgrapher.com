<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'NgfbRegister' ) ) {

	class NgfbRegister {

		protected $p;

		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			register_activation_hook( NGFB_FILEPATH, array( &$this, 'network_activate' ) );
			register_deactivation_hook( NGFB_FILEPATH, array( &$this, 'network_deactivate' ) );
			register_uninstall_hook( NGFB_FILEPATH, array( __CLASS__, 'network_uninstall' ) );
		}

		public function network_activate( $sitewide ) {
			self::do_multisite( $sitewide, array( &$this, 'activate_plugin' ) );
		}

		public function network_deactivate( $sitewide ) {
			self::do_multisite( $sitewide, array( &$this, 'deactivate_plugin' ) );
		}

		public static function network_uninstall() {
			$sitewide = true;
			$lca = NgfbConfig::get_config( 'lca' );
			delete_site_option( $lca.'_site_options' );
			self::do_multisite( $sitewide, array( __CLASS__, 'uninstall_plugin' ) );
		}

		private static function do_multisite( $sitewide, $method, $args = array() ) {
			if ( is_multisite() && $sitewide ) {
				global $wpdb, $blog_id;
				$dbquery = 'SELECT blog_id FROM '.$wpdb->blogs;
				$ids = $wpdb->get_col( $dbquery );
				foreach ( $ids as $id ) {
					switch_to_blog( $id );
					call_user_func_array( $method, array( $args ) );
				}
				switch_to_blog( $blog_id );
			} else call_user_func_array( $method, array( $args ) );
		}

		private function activate_plugin() {
			global $wp_version;
			if ( version_compare( $wp_version, $this->p->cf['wp']['min_version'], '<' ) ) {
				deactivate_plugins( NGFB_PLUGINBASE );
				error_log( NGFB_PLUGINBASE.' requires WordPress '.$this->p->cf['wp']['min_version'].' or higher ('.$wp_version.' reported).' );
				wp_die( '<p>'. sprintf( __( 'The %1$s plugin cannot be activated - it requires WordPress %2$s or higher.', NGFB_TEXTDOM ), 
					$this->p->cf['full'], $this->p->cf['wp']['min_version'] ) .'</p>' );
			}
			$this->p->set_objects( true );
		}

		private function deactivate_plugin() {
			wp_clear_scheduled_hook( 'plugin_updates-'.$this->p->cf['slug'] );
		}

		private static function uninstall_plugin() {
			global $wpdb;
			$cf = NgfbConfig::get_config();
			$options = get_option( $cf['lca'].'_options' );

			if ( empty( $options['plugin_preserve'] ) ) {
				delete_option( $cf['lca'].'_options' );
				delete_post_meta_by_key( '_'.$cf['lca'].'_meta' );
				NgfbUser::delete_metabox_prefs();
			}

			// delete update related options
			delete_option( 'external_updates-'.$cf['slug'] );
			delete_option( $cf['lca'].'_umsg' );
			delete_option( $cf['lca'].'_utime' );

			// delete stored admin notices
			foreach ( array( 'nag', 'err', 'inf' ) as $type ) {
				$msg_opt = $cf['lca'].'_notices_'.$type;
				delete_option( $msg_opt );
				foreach ( get_users( array( 'meta_key' => $msg_opt ) ) as $user )
					delete_user_option( $user->ID, $msg_opt );
			}

			// delete transients
			$dbquery = 'SELECT option_name FROM '.$wpdb->options.' WHERE option_name LIKE \'_transient_timeout_'.$cf['lca'].'_%\';';
			$expired = $wpdb->get_col( $dbquery ); 
			foreach( $expired as $transient ) { 
				$key = str_replace('_transient_timeout_', '', $transient);
				if ( ! empty( $key ) )
					delete_transient( $key );
			}
		}
	}
}

?>
