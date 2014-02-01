<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'NgfbUtil' ) && class_exists( 'SucomUtil' ) ) {

	class NgfbUtil extends SucomUtil {

		private $urls_found = array();	// array to detect duplicate images, etc.

		protected $p;

		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			$this->p->debug->mark();
			$this->add_actions();
		}

		protected function add_actions() {
			add_action( 'wp_scheduled_delete', array( &$this, 'delete_expired_transients' ) );
			add_action( 'wp_scheduled_delete', array( &$this, 'delete_expired_file_cache' ) );
		}

		public function get_post_types( $prefix, $output = 'objects' ) {
			$include = false;
			$post_types = array();
			switch ( $prefix ) {
				case 'buttons':
					$include = array( 'public' => true );
					break;
				case 'plugin':
					$include = array( 'show_ui' => true, 'public' => true );
					break;
			}
			$post_types = $include !== false ? 
				get_post_types( $include, $output ) : array();
			return apply_filters( $this->p->cf['lca'].'_post_types', $post_types, $prefix, $output );
		}

		public function flush_post_cache( $post_id ) {
			switch ( get_post_status( $post_id ) ) {

			case 'draft' :
			case 'pending' :
			case 'private' :
			case 'publish' :
				$lang = get_locale();
				$name = is_page( $post_id ) ? 'Page' : 'Post';
				$cache_type = 'object cache';
				$sharing_url = $this->p->util->get_sharing_url( $post_id );
				$transients = array(
					'NgfbOpengraph::get_array' => array(
						'og array' => 'lang:'.$lang.'_sharing_url:'.$sharing_url,
					),
					'NgfbSocial::filter' => array(
						'the_excerpt' => 'lang:'.$lang.'_post:'.$post_id.'_type:the_excerpt',
						'the_content' => 'lang:'.$lang.'_post:'.$post_id.'_type:the_content',
						'admin_sharing' => 'lang:'.$lang.'_post:'.$post_id.'_type:admin_sharing',
					),
				);
				$objects = array(
					'SucomWebpage::get_content' => array(
						'filtered content' => 'lang:'.$lang.'_post:'.$post_id.'_filtered',
						'unfiltered content' => 'lang:'.$lang.'_post:'.$post_id.'_unfiltered',
					),
					'SucomWebpage::get_hashtags' => array(
						'hashtags' => 'lang:'.$lang.'_post:'.$post_id,
					),
				);
				$deleted = 0;
				foreach ( $transients as $group => $arr ) {
					foreach ( $arr as $name => $val ) {
						$cache_salt = $group.'('.$val.')';
						$cache_id = $this->p->cf['lca'].'_'.md5( $cache_salt );
						if ( delete_transient( $cache_id ) ) $deleted++;
					}
				}
				foreach ( $objects as $group => $arr ) {
					foreach ( $arr as $name => $val ) {
						$cache_salt = $group.'('.$val.')';
						$cache_id = $this->p->cf['lca'].'_'.md5( $cache_salt );
						if ( wp_cache_delete( $cache_id, $group ) ) $deleted++;
					}
				}
				if ( $deleted > 0 && $this->p->debug->is_on() )
					$this->p->notice->inf( $deleted.' items flushed from the WordPress object and transient caches for post ID #'.$post_id, true );
				break;
			}
		}

		public function get_topics() {
			if ( $this->p->is_avail['cache']['transient'] ) {
				$cache_salt = __METHOD__.'('.NGFB_TOPICS_LIST.')';
				$cache_id = $this->p->cf['lca'].'_'.md5( $cache_salt );
				$cache_type = 'object cache';
				$this->p->debug->log( $cache_type.': topics array transient salt '.$cache_salt );
				$topics = get_transient( $cache_id );
				if ( is_array( $topics ) ) {
					$this->p->debug->log( $cache_type.': topics array retrieved from transient '.$cache_id );
					return $topics;
				}
			}
			if ( ( $topics = file( NGFB_TOPICS_LIST, 
				FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES ) ) === false ) {
				$this->p->notice->err( 'Error reading <u>'.NGFB_TOPICS_LIST.'</u>.' );
				return $topics;
			}
			$topics = apply_filters( $this->p->cf['lca'].'_topics', $topics );
			natsort( $topics );
			$topics = array_merge( array( 'none' ), $topics );	// after sorting the array, put 'none' first

			if ( ! empty( $cache_id ) ) {
				set_transient( $cache_id, $topics, $this->p->cache->object_expire );
				$this->p->debug->log( $cache_type.': topics array saved to transient '.$cache_id.' ('.$this->p->cache->object_expire.' seconds)');
			}
			return $topics;
		}

		public function sanitize_option_value( $key, $val, $def_val ) {
			$valid_types = array( 'css', 'atname', 'urlbase', 'url', 'numeric', 'posnum', 'textured', 'anucase', 'okblank', 'notblank', 'checkbox' );
			$option_type = apply_filters( $this->p->cf['lca'].'_option_type', false, $key, $valid_types );
			$reset_msg = __( 'resetting the option to its default value.', NGFB_TEXTDOM );
			$charset = get_bloginfo( 'charset' );
			switch ( $option_type ) {
				// don't remove / encode html tags from css
				case 'css':
					break;
				default:
					$val = stripslashes( $val );
					$val = wp_filter_nohtml_kses( $val );
					$val = htmlentities( $val, ENT_QUOTES, $charset, false );	// double_encode = false
					break;
			}
			switch ( $option_type ) {
				// twitter-style usernames (prepend with an at)
				case 'atname':
					$val = substr( preg_replace( '/[^a-z0-9_]/', '', strtolower( $val ) ), 0, 15 );
					if ( ! empty( $val ) ) 
						$val = '@'.$val;
					break;

				// strip leading urls off facebook usernames
				case 'urlbase':
					$val = preg_replace( '/(http|https):\/\/[^\/]*?\//', '', $val );
					break;

				// must be a url
				case 'url':
					if ( ! empty( $val ) && strpos( $val, '://' ) === false ) {
						$this->p->notice->inf( 'The value of option \''.$key.'\' must be a URL'.' - '.$reset_msg, true );
						$val = $def_val;
					}
					break;

				// must be numeric (blank or zero is ok)
				case 'numeric':
					if ( ! empty( $val ) && ! is_numeric( $val ) ) {
						$this->p->notice->inf( 'The value of option \''.$key.'\' must be numeric'.' - '.$reset_msg, true );
						$val = $def_val;
					}
					break;

				// integer options that must be 1 or more (not zero)
				case 'posnum':
					if ( empty( $val ) || ! is_numeric( $val ) ) {
						$this->p->notice->inf( 'The value of option \''.$key.'\' must be greater or equal to 1'.' - '.$reset_msg, true );
						$val = $def_val;
					}
					break;

				// must be texturized 
				case 'textured':
					$val = trim( wptexturize( ' '.$val.' ' ) );
					break;
				// must be alpha-numeric uppercase
				case 'anucase':
					if ( ! empty( $val ) && preg_match( '/[^A-Z0-9]/', $val ) ) {
						$this->p->notice->inf( '\''.$val.'\' is not an accepted value for option \''.$key.'\''.' - '.$reset_msg, true );
						$val = $def_val;
					}
					break;

				// text strings that can be blank
				case 'okblank':
					if ( ! empty( $val ) )
						$val = trim( $val );
					break;

				// options that cannot be blank
				case 'css':
				case 'notblank':
					if ( empty( $val ) ) {
						$this->p->notice->inf( 'The value of option \''.$key.'\' cannot be empty'.' - '.$reset_msg, true );
						$val = $def_val;
					}
					break;

				// everything else is a 1/0 checkbox option 
				case 'checkbox':
				default:
					// make sure the default option is also 1/0, just in case
					if ( $def_val === 0 || $def_val === 1 )
						$val = empty( $val ) ? 0 : 1;
					break;
			}
			return $val;
		}
	}
}

?>
