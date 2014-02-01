<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'NgfbOptions' ) ) {

	class NgfbOptions {

		private $upg;
		protected $p;

		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			$this->p->debug->mark();
			add_filter( $this->p->cf['lca'].'_option_type', array( &$this, 'filter_option_type' ), 10, 3 );
			do_action( $this->p->cf['lca'].'_init_options' );
		}

		public function get_site_defaults( $idx = '' ) {
			$defs = apply_filters( $this->p->cf['lca'].'_get_site_defaults', $this->p->cf['opt']['site_defaults'] );
			if ( ! empty( $idx ) ) {
				if ( array_key_exists( $idx, $defs ) )
					return $defs[$idx];
				else return false;
			} else return $defs;
		}

		public function get_defaults( $idx = '' ) {

			if ( $this->p->is_avail['ssb'] ) {
				foreach ( $this->p->cf['css'] as $id => $name ) {
					$css_file = NGFB_PLUGINDIR.'css/'.$id.'-buttons.css';
					// css files are only loaded once (when variable is empty) into defaults to minimize disk i/o
					if ( empty( $this->p->cf['opt']['defaults']['buttons_css_'.$id] ) ) {
						if ( ! $fh = @fopen( $css_file, 'rb' ) )
							$this->p->notice->err( 'Failed to open '.$css_file.' for reading.' );
						else {
							$this->p->cf['opt']['defaults']['buttons_css_'.$id] = fread( $fh, filesize( $css_file ) );
							$this->p->debug->log( 'read css from file '.$css_file );
							fclose( $fh );
						}
					}
				}
			}

			$this->p->cf['opt']['defaults'] = $this->add_to_post_types( $this->p->cf['opt']['defaults'] );

			$this->p->cf['opt']['defaults']['link_author_field'] = empty( $this->p->options['plugin_cm_gp_name'] ) ? 
				$this->p->cf['opt']['defaults']['plugin_cm_gp_name'] : $this->p->options['plugin_cm_gp_name'];

			$this->p->cf['opt']['defaults']['og_author_field'] = empty( $this->p->options['plugin_cm_fb_name'] ) ? 
				$this->p->cf['opt']['defaults']['plugin_cm_fb_name'] : $this->p->options['plugin_cm_fb_name'];

			// add description meta tag if no known SEO plugin was detected
			$this->p->cf['opt']['defaults']['inc_description'] = empty( $this->p->is_avail['seo']['*'] ) ? 1 : 0;

			// check for default values from network admin settings
			if ( is_multisite() && is_array( $this->p->site_options ) ) {
				foreach ( $this->p->site_options as $key => $val ) {
					if ( array_key_exists( $key, $this->p->cf['opt']['defaults'] ) && 
						array_key_exists( $key.':use', $this->p->site_options ) ) {

						if ( $this->p->site_options[$key.':use'] == 'default' )
							$this->p->cf['opt']['defaults'][$key] = $this->p->site_options[$key];
					}
				}
			}

			$this->p->cf['opt']['defaults'] = apply_filters( $this->p->cf['lca'].'_get_defaults', $this->p->cf['opt']['defaults'] );

			if ( ! empty( $idx ) ) 
				if ( array_key_exists( $idx, $this->p->cf['opt']['defaults'] ) )
					return $this->p->cf['opt']['defaults'][$idx];
				else return false;
			else return $this->p->cf['opt']['defaults'];
		}

		public function add_to_post_types( &$opts = array(), $add_to_prefixes = array( 'buttons', 'plugin' ) ) {
			foreach ( $add_to_prefixes as $prefix ) {
				foreach ( $this->p->util->get_post_types( $prefix ) as $post_type ) {
					$option_name = $prefix.'_add_to_'.$post_type->name;
					if ( ! array_key_exists( $option_name, $opts ) ) {
						switch ( $post_type->name ) {
							case 'product':
								$opts[$option_name] = 1;
								break;
							default:
								$opts[$option_name] = 0;
								break;
						}
					}
				}
			}
			return $opts;
		}

		public function check_options( $options_name, &$opts = array() ) {
			$opts_err_msg = '';
			if ( ! empty( $opts ) && is_array( $opts ) ) {

				// check version in saved options, upgrade if they don't match
				if ( ( empty( $opts['plugin_version'] ) || $opts['plugin_version'] !== $this->p->cf['version'] ) ||
					( empty( $opts['options_version'] ) || $opts['options_version'] !== $this->p->cf['opt']['version'] ) ) {

					// upgrade the options if options version mismatch
					if ( empty( $opts['options_version'] ) || $opts['options_version'] !== $this->p->cf['opt']['version'] ) {
						$this->p->debug->log( $options_name.' v'.$this->p->cf['opt']['version'].
							' different than saved v'.$opts['options_version'] );
						// only load upgrade class when needed to save a few Kb
						if ( ! is_object( $this->upg ) ) {
							require_once( NGFB_PLUGINDIR.'lib/upgrade.php' );
							$this->upg = new NgfbOptionsUpgrade( $this->p );
						}
						$opts = $this->upg->options( $options_name, $opts, $this->get_defaults() );
					}

					if ( $options_name == NGFB_OPTIONS_NAME &&
						$this->p->is_avail['aop'] !== true && 
						empty( $this->p->options['plugin_tid'] ) ) {

						// show the nag and update the options only if we have someone with access
						if ( current_user_can( 'manage_options' ) ) {
							if ( ! is_object( $this->p->msgs ) ) {
								require_once( NGFB_PLUGINDIR.'lib/messages.php' );
								$this->p->msgs = new NgfbMessages( $this->p );
							}
							$this->p->notice->nag( $this->p->msgs->get( 'pro-advert-nag' ), true );
							$this->save_options( $options_name, $opts );
						}
					} else $this->save_options( $options_name, $opts );
				}

				// add support for post types that may have been added since options last saved
				if ( $options_name == NGFB_OPTIONS_NAME )
					$opts = $this->add_to_post_types( $opts );

				if ( ! empty( $this->p->is_avail['seo']['*'] ) &&
					array_key_exists( 'inc_description', $opts ) ) {
					$opts['inc_description'] = 0;
					$opts['inc_description:is'] = 'disabled';
				}
			} else {
				if ( $opts === false )
					$opts_err_msg = 'could not find an entry for '.$options_name.' in';
				elseif ( ! is_array( $opts ) )
					$opts_err_msg = 'returned a non-array value when reading '.$options_name.' from';
				elseif ( empty( $opts ) )
					$opts_err_msg = 'returned an empty array when reading '.$options_name.' from';
				else $opts_err_msg = 'returned an unknown condition when reading '.$options_name.' from';

				$this->p->debug->log( 'WordPress '.$opts_err_msg.' the options database table.' );
				if ( $options_name == NGFB_SITE_OPTIONS_NAME )
					$opts = $this->get_site_defaults();
				else $opts = $this->get_defaults();
			}

			if ( is_admin() ) {
				if ( ! empty( $opts_err_msg ) ) {
					if ( $options_name == NGFB_SITE_OPTIONS_NAME )
						$url = $this->p->util->get_admin_url( 'network' );
					else $url = $this->p->util->get_admin_url( 'general' );

					$this->p->notice->err( 'WordPress '.$opts_err_msg.' the options table. 
						Plugin settings have been returned to their default values. 
						<a href="'.$url.'">Please review and save the new settings</a>.' );
				}
				if ( $options_name == NGFB_OPTIONS_NAME ) {
					if ( $this->p->options['og_img_width'] < $this->p->cf['head']['min_img_width'] || 
						$this->p->options['og_img_height'] < $this->p->cf['head']['min_img_height'] ) {

						$url = $this->p->util->get_admin_url( 'general' );
						$size_desc = $this->p->options['og_img_width'].'x'.$this->p->options['og_img_height'];
						$this->p->notice->inf( 'The image size of '.$size_desc.' for images in the Open Graph meta tags
							is smaller than the minimum of '.$this->p->cf['head']['min_img_width'].'x'.$this->p->cf['head']['min_img_height'].'. 
							<a href="'.$url.'">Please enter a larger image dimensions on the General Settings page</a>.' );
					}
					if ( $this->p->check->is_aop() &&
						! empty( $this->p->is_avail['ecom']['*'] ) &&
						$opts['tc_prod_def_l2'] === 'Location' &&
						$opts['tc_prod_def_d2'] === 'Unknown' ) {
	
						$this->p->notice->inf( 'An eCommerce plugin has been detected. Please update Twitter\'s
							<em>Product Card Default 2nd Attribute</em> option values on the '.
							$this->p->util->get_admin_url( 'general', 'General settings page' ). ' 
							(to something else than \'Location\' and \'Unknown\').' );
					}
				}
				if ( $this->p->is_avail['aop'] === true && empty( $this->p->options['plugin_tid'] ) )
					$this->p->notice->nag( $this->p->msgs->get( 'pro-activate-nag' ) );
			}
			return $opts;
		}

		// sanitize and validate options
		public function sanitize( $opts = array(), $def_opts = array() ) {

			// make sure we have something to work with
			if ( empty( $def_opts ) || ! is_array( $def_opts ) )
				return $opts;

			// unset options that no longer exist
			foreach ( $opts as $key => $val )
				// if the key isn't in the default options, then remove it
				if ( ! empty( $key ) && ! array_key_exists( $key, $def_opts ) )
					unset( $opts[$key] );

			// add missing options and sanitize values
			foreach ( $def_opts as $key => $def_val ) {
				if ( ! empty( $key ) && ! array_key_exists( $key, $opts ) )
					$opts[$key] = $def_val;
				else $opts[$key] = $this->p->util->sanitize_option_value( $key, $opts[$key], $def_val );
			}

			/*
			 * Adjust dependent options
			 */
			if ( empty( $opts['plugin_google_api_key'] ) ) {
				$opts['plugin_google_shorten'] = 0;
				$opts['plugin_google_shorten:is'] = 'disabled';
			}

			if ( ! $this->p->check->is_aop() )
				$opts['plugin_file_cache_hrs'] = 0;

			// og_desc_len must be at least 156 chars (defined in config)
			if ( array_key_exists( 'og_desc_len', $opts ) && 
				$opts['og_desc_len'] < $this->p->cf['head']['min_desc_len'] ) 
					$opts['og_desc_len'] = $this->p->cf['head']['min_desc_len'];

			if ( array_key_exists( 'plugin_tid', $opts ) ) {
				if ( empty( $opts['plugin_tid'] ) )
					delete_option( $this->p->cf['lca'].'_umsg' );
				elseif ( $opts['plugin_tid'] !== $this->p->options['plugin_tid'] )
					delete_option( $this->p->cf['lca'].'_utime' );
			}
			return $opts;
		}

		// saved both options and site options
		public function save_options( $options_name, &$opts ) {
			// make sure we have something to work with
			if ( empty( $opts ) || ! is_array( $opts ) ) {
				$this->p->debug->log( 'exiting early: options variable is empty and/or not array' );
				return $opts;
			}
			// mark the new options as current
			$previous_opts_version = $opts['options_version'];
			$opts['options_version'] = $this->p->cf['opt']['version'];
			$opts['plugin_version'] = $this->p->cf['version'];

			// update_option() returns false if options are the same or there was an error, 
			// so check to make sure they need to be updated to avoid throwing a false error
			if ( $options_name == NGFB_SITE_OPTIONS_NAME )
				$opts_current = get_site_option( $options_name, $opts );
			else $opts_current = get_option( $options_name, $opts );

			if ( $opts_current !== $opts ) {
				if ( $options_name == NGFB_SITE_OPTIONS_NAME )
					$saved = update_site_option( $options_name, $opts );
				else $saved = update_option( $options_name, $opts );

				if ( $saved === true ) {
					// if we're just saving a new plugin version string, don't bother showing the upgrade message
					if ( $previous_opts_version !== $opts['options_version'] ) {
						$this->p->debug->log( 'upgraded '.$options_name.' settings have been saved' );
						$this->p->notice->inf( 'Plugin settings ('.$options_name.') have been upgraded and saved.', true );
					}
				} else {
					$this->p->debug->log( 'failed to save the upgraded '.$options_name.' settings' );
					$this->p->notice->err( 'Plugin settings ('.$options_name.') have been upgraded, but WordPress returned an error when saving them.', true );
					return false;
				}
			} else $this->p->debug->log( 'new and old options array is identical' );

			return true;
		}

		public function filter_option_type( $ret, $key, $valid ) {
			switch ( $key ) {
				// css
				case ( strpos( $key, 'buttons_css_' ) === 0 ? true : false ):
					return 'css';
					break;

				// twitter-style usernames (prepend with an at)
				case 'tc_site':
					return 'atname';
					break;

				// strip leading urls off facebook usernames
				case 'fb_admins':
					return 'urlbase';
					break;

				// must be a url
				case ( preg_match( '/^[a-z_]+_urls?$/', $key ) ? true : false ):
					return 'url';
					break;

				// must be numeric (blank or zero is ok)
				case 'link_def_author_id':
				case 'og_desc_hashtags': 
				case 'og_img_max':
				case 'og_vid_max':
				case 'og_img_id':
				case 'og_def_img_id':
				case 'og_def_author_id':
				case 'plugin_file_cache_hrs':
					return 'numeric';
					break;

				// integer options that must be 1 or more (not zero)
				case 'og_img_width': 
				case 'og_img_height': 
				case 'fb_order': 
				case 'gp_order': 
				case 'twitter_order': 
				case 'linkedin_order': 
				case 'managewp_order': 
				case 'stumble_order': 
				case 'stumble_badge':
				case 'pin_order': 
				case 'tumblr_order': 
				case 'plugin_object_cache_exp':
				case 'plugin_min_shorten':
				case ( preg_match( '/_len$/', $key ) ? true : false ):
					return 'posnum';
					break;

				// must be texturized 
				case 'og_title_sep':
					return 'textured';
					break;

				// must be alpha-numeric uppercase
				case 'plugin_tid':
					return 'anucase';
					break;

				// text strings that can be blank
				case 'og_art_section':
				case 'fb_app_id':
				case 'gp_expandto':
				case 'og_title':
				case 'og_desc':
				case 'og_site_name':
				case 'og_site_description':
				case 'meta_desc':
				case 'tc_desc':
				case 'pin_desc':
				case 'tumblr_img_desc':
				case 'tumblr_vid_desc':
				case 'twitter_desc':
				case 'plugin_google_api_key':
				case 'plugin_bitly_api_key':
				case 'plugin_cdn_folders':
				case 'plugin_cdn_excl':
					return 'okblank';
					break;

				// options that cannot be blank
				case 'link_author_field':
				case 'og_img_id_pre': 
				case 'og_def_img_id_pre': 
				case 'og_author_field':
				case 'fb_lang': 
				case 'fb_markup': 
				case 'gp_lang': 
				case 'gp_action': 
				case 'gp_size': 
				case 'gp_annotation': 
				case 'twitter_count': 
				case 'twitter_size': 
				case 'linkedin_counter':
				case 'managewp_type':
				case 'pin_count_layout':
				case 'pin_img_size':
				case 'pin_caption':
				case 'tumblr_button_style':
				case 'tumblr_img_size':
				case 'tumblr_caption':
				case 'plugin_tid:use':
				case ( strpos( $key, 'buttons_location_' ) === 0 ? true : false ):
				case ( preg_match( '/^[a-z]+_js_loc$/', $key ) ? true : false ):
				case ( preg_match( '/^(plugin|wp)_cm_[a-z]+_(name|label)$/', $key ) ? true : false ):
					return 'notblank';
					break;
			}
			return $ret;
		}
	}
}

?>
