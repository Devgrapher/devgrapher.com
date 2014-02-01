<?php
/* 
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'SucomUpdate' ) ) {

	class SucomUpdate {
	
		private $p;
		private static $umsg = null;
	
		public $json_url = '';
		public $json_expire = 3600;	// cache retrieved update json for 1 hour
		public $lca = 'plugin';
		public $slug = '';
		public $base = '';
		public $cron_hook = 'plugin_updates';
		public $sched_hours = 12;
		public $sched_name = 'every12hours';
		public $option_name = '';
		public $update_timestamp = '';
	
		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			$this->p->debug->mark();

			if ( ! empty( $this->p->options['plugin_tid'] ) )
				$this->json_url = $this->p->cf['url']['pro_update'].'?tid='.$this->p->options['plugin_tid'];

			$this->lca = $this->p->cf['lca'];				// lowercase acronym
			$this->slug = $this->p->cf['slug'];				// nextgen-facebook
			$this->base = constant( $this->p->cf['uca'].'_PLUGINBASE' );	// nextgen-facebook/nextgen-facebook.php
			$this->cron_hook = 'plugin_updates-'.$this->slug;		// plugin_updates-nextgen-facebook
			$this->sched_hours = $this->p->cf['update_hours'];		// 12
			$this->sched_name = 'every'.$this->sched_hours.'hours';		// every12hours
			$this->option_name = 'external_updates-'.$this->slug;		// external_updates-nextgen-facebook
			$this->install_hooks();
		}

		public static function get_umsg( $lca ) {
			if ( self::$umsg === null ) {
				self::$umsg = base64_decode( get_option( $lca.'_umsg' ) );
				if ( empty( self::$umsg ) )
					self::$umsg = false;
			}
			return self::$umsg;
		}

		public function install_hooks() {
			add_filter( 'plugins_api', array( &$this, 'inject_data' ), 10, 3 );
			add_filter( 'site_transient_update_plugins', array( &$this, 'inject_update' ) );

			// in a multisite environment, each site checks for updates
			if ( $this->sched_hours > 0 ) {
				add_filter( 'cron_schedules', array( &$this, 'custom_schedule' ) );
				add_action( $this->cron_hook, array( &$this, 'check_for_updates' ) );
				$schedule = wp_get_schedule( $this->cron_hook );
				// check for schedule mismatch
				if ( ! empty( $schedule ) && $schedule !== $this->sched_name ) {
					$this->p->debug->log( 'changing '.$this->cron_hook.' schedule from '.$schedule.' to '.$this->sched_name );
					wp_clear_scheduled_hook( $this->cron_hook );
				}
				// add schedule if it doesn't exist
				if ( ! defined('WP_INSTALLING') && ! wp_next_scheduled( $this->cron_hook ) ) {
					// remove old schedule name (if it exists)
					if ( wp_get_schedule( 'pcfu_updates-'.$this->slug ) )
						wp_clear_scheduled_hook( 'pcfu_updates-'.$this->slug );
					wp_schedule_event( time(), $this->sched_name, $this->cron_hook );	// since wp 2.1.0
				}
			} else wp_clear_scheduled_hook( $this->cron_hook );
		}
	
		public function inject_data( $result, $action = null, $args = null ) {
		    	$found = ( $action == 'plugin_information' ) && isset( $args->slug ) && ( $args->slug == $this->slug );
			if ( ! $found ) return $result;
			$plugin_data = $this->get_json();
			if ( ! empty( $plugin_data ) ) 
				return $plugin_data->json_to_wp();
			return $result;
		}
	
		public function inject_update( $updates ) {
			if ( ! empty( $updates->response[$this->base] ) ) {
				unset( $updates->response[$this->base] );
			}
			$option_data = get_site_option( $this->option_name );
			if ( ! empty( $option_data ) && is_object( $option_data->update ) && ! empty( $option_data->update ) ) {
				if ( version_compare( $option_data->update->version, $this->get_installed_version(), '>' ) ) {
					$updates->response[$this->base] = $option_data->update->json_to_wp();
				}
			}
			return $updates;
		}
	
		public function custom_schedule( $schedule ) {
			if ($this->sched_hours > 0) {
				$schedule[$this->sched_name] = array(
					'interval' => $this->sched_hours * 3600,
					'display' => sprintf('Every %d hours', $this->sched_hours)
				);
			}
			return $schedule;
		}
	
		public function check_for_updates() {
			$option_data = get_site_option( $this->option_name );
			if ( empty( $option_data ) ) {
				$option_data = new StdClass;
				$option_data->lastCheck = 0;
				$option_data->checkedVersion = 0;
				$option_data->update = null;
			}
			$option_data->lastCheck = time();
			$option_data->checkedVersion = $this->get_installed_version();
			$option_data->update = $this->get_update();
			update_site_option( $this->option_name, $option_data );
		}
	
		public function get_update() {
			$plugin_data = $this->get_json();
			if ( empty( $plugin_data ) ) 
				return null;
			return SucomPluginUpdate::from_plugin_data( $plugin_data );
		}
	
		public function get_json( $query = array() ) {

			global $wp_version;
			$update_url = $this->json_url;
			$site_url = get_bloginfo( 'url' );
			$query['installed_version'] = $this->get_installed_version();

			if ( empty( $update_url ) ) {
				$this->p->debug->log( 'exiting early: empty update url' );
				return null;
			} elseif ( ! empty( $query ) ) 
				$update_url = add_query_arg( $query, $update_url );

			$cache_salt = __METHOD__.'(update_url:'.$update_url.'_site_url:'.$site_url.')';
			$cache_id = $this->lca.'_'.md5( $cache_salt );		// use lca prefix for plugin clear cache
			$cache_type = 'object cache';
			$this->p->debug->log( $cache_type.': plugin data transient salt '.$cache_salt );
			$last_update = get_option( $this->lca.'_utime' );
			if ( $last_update !== false ) {
				$plugin_data = get_transient( $cache_id );
				if ( $plugin_data !== false ) {
					$this->p->debug->log( $cache_type.': plugin data retrieved from transient '.$cache_id );
					return $plugin_data;
				}
			}

			$ua = 'WordPress/'.$wp_version.' ('.( apply_filters( $this->lca.'_ua_plugin', 
				$this->slug.'/'.$query['installed_version'] ) ).'); '.$site_url;
			$options = array(
				'timeout' => 10, 
				'user-agent' => $ua,
				'headers' => array( 
					'Accept' => 'application/json',
					'X-WordPress-Id' => $ua,
				),
			);
			$plugin_data = null;
			$result = wp_remote_get( $update_url, $options );
			if ( ! is_wp_error( $result )
				&& isset( $result['response']['code'] )
				&& ( $result['response']['code'] == 200 )
				&& ! empty( $result['body'] ) ) {
	
				if ( ! empty( $result['headers']['x-smp-error'] ) ) {
					self::$umsg = json_decode( $result['body'] );
					update_option( $this->lca.'_umsg', base64_encode( self::$umsg ) );
				} else {
					self::$umsg = false;
					delete_option( $this->lca.'_umsg' );
					$plugin_data = SucomPluginData::from_json( $result['body'] );
				}
			}

			$this->update_timestamp = time();
			update_option( $this->lca.'_utime', $this->update_timestamp );

			set_transient( $cache_id, ( $plugin_data === null ? '' : $plugin_data ), $this->json_expire );
			$this->p->debug->log( $cache_type.': plugin data saved to transient '.$cache_id.' ('.$this->json_expire.' seconds)');

			return $plugin_data;
		}
	
		public function get_installed_version() {
			$version = 0;
			if ( ! function_exists( 'get_plugins' ) ) 
				require_once( ABSPATH.'/wp-admin/includes/plugin.php' );
			$plugins = get_plugins();
			if ( array_key_exists( $this->base, $plugins ) && 
				array_key_exists( 'Version', $plugins[$this->base] ) )
					$version = $plugins[$this->base]['Version'];
			return apply_filters( $this->lca.'_installed_version', $version );
		}
	}
}
	
if ( ! class_exists( 'SucomPluginData' ) ) {

	class SucomPluginData {
	
		public $id = 0;
		public $name;
		public $slug;
		public $version;
		public $homepage;
		public $sections;
		public $download_url;
		public $author;
		public $author_homepage;
		public $requires;
		public $tested;
		public $upgrade_notice;
		public $rating;
		public $num_ratings;
		public $downloaded;
		public $last_updated;
	
		public static function from_json( $json ) {
			$json_data = json_decode( $json );
			if ( empty( $json_data ) || ! is_object( $json_data ) ) 
				return null;
			$exists = isset( $json_data->name ) && !empty( $json_data->name )
				&& isset( $json_data->version ) && !empty( $json_data->version );
			if ( ! $exists ) return null;
			$plugin_data = new SucomPluginData();
			foreach( get_object_vars( $json_data ) as $key => $value) {
				$plugin_data->$key = $value;
			}
			return $plugin_data;
		}
	
		public function json_to_wp(){
			$fields = array(
				'name', 
				'slug', 
				'version', 
				'requires', 
				'tested', 
				'rating', 
				'upgrade_notice',
				'num_ratings', 
				'downloaded', 
				'homepage', 
				'last_updated',
				'download_url',
				'author_homepage');
			$data = new StdClass;
			foreach ( $fields as $field ) {
				if ( isset( $this->$field ) ) {
					if ($field == 'download_url') {
						$data->download_link = $this->download_url; }
					elseif ($field == 'author_homepage') {
						$data->author = sprintf('<a href="%s">%s</a>', $this->author_homepage, $this->author); }
					else { $data->$field = $this->$field; }
				} elseif ( $field == 'author_homepage' )
					$data->author = $this->author;
			}
			if ( is_array( $this->sections ) ) 
				$data->sections = $this->sections;
			elseif ( is_object( $this->sections ) ) 
				$data->sections = get_object_vars( $this->sections );
			else 
				$data->sections = array( 'description' => '' );
			return $data;
		}
	}
}
	
if ( ! class_exists( 'SucomPluginUpdate' ) ) {

	class SucomPluginUpdate {
	
		public $id = 0;
		public $slug;
		public $version = 0;
		public $homepage;
		public $download_url;
		public $upgrade_notice;
	
		public function from_json( $json ) {
			$plugin_data = SucomPluginData::from_json( $json );
			if ( $plugin_data !== null ) 
				return self::from_plugin_data( $plugin_data );
			else return null;
		}
	
		public static function from_plugin_data($data){
			$plugin_update = new SucomPluginUpdate();
			$fields = array(
				'id', 
				'slug', 
				'version', 
				'homepage', 
				'download_url', 
				'upgrade_notice');
			foreach( $fields as $field )
				$plugin_update->$field = $data->$field;
			return $plugin_update;
		}
	
		public function json_to_wp() {
			$data = new StdClass;
			$fields = array(
				'id' => 'id',
				'slug' => 'slug',
				'new_version' => 'version',
				'url' => 'homepage',
				'package' => 'download_url',
				'upgrade_notice' => 'upgrade_notice');
			foreach ( $fields as $new_field => $old_field ) {
				if ( isset( $this->$old_field ) )
					$data->$new_field = $this->$old_field;
			}
			return $data;
		}
	}
}

?>
