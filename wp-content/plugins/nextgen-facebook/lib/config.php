<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'NgfbConfig' ) ) {

	class NgfbConfig {

		private static $cf = array(
			'version' => '6.22.2',			// plugin version
			'lca' => 'ngfb',			// lowercase acronym
			'cca' => 'Ngfb',			// camelcase acronym
			'uca' => 'NGFB',			// uppercase acronym
			'slug' => 'nextgen-facebook',
			'menu' => 'Open Graph+',		// menu item label
			'full' => 'NGFB Open Graph+',		// full plugin name
			'full_pro' => 'NGFB Open Graph+ Pro',
			'update_hours' => 12,			// check for pro updates
			'cache' => array(
				'file' => true,
				'object' => true,
				'transient' => true,
			),
			'lib' => array(				// libraries
				'setting' => array (
					'contact' => 'Contact Methods',
				),
				'submenu' => array (
					'general' => 'General',
					'advanced' => 'Advanced',
					'social' => 'Social Sharing',
					'style' => 'Social Style',
					'about' => 'About',
				),
				'site_submenu' => array(
					'network' => 'Network',
				),
				'website' => array(
					'facebook' => 'Facebook', 
					'gplus' => 'GooglePlus',
					'twitter' => 'Twitter',
					'linkedin' => 'LinkedIn',
					'managewp' => 'ManageWP',
					'pinterest' => 'Pinterest',
					'stumbleupon' => 'StumbleUpon',
					'tumblr' => 'Tumblr',
					'youtube' => 'YouTube',
					'skype' => 'Skype',
				),
				'shortcode' => array(
					'ngfb' => 'Ngfb',
				),
				'widget' => array(
					'social' => 'SocialSharing',
				),
				'pro' => array(
					'seo' => array(
						'aioseop' => 'All in One SEO Pack',
						'seou' => 'SEO Ultimate',
						'wpseo' => 'WordPress SEO',
					),
					'ecom' => array(
						'woocommerce' => 'WooCommerce',
						'marketpress' => 'MarketPress',
						'wpecommerce' => 'WP e-Commerce',
					),
					'forum' => array(
						'bbpress' => 'bbPress',
					),
					'social' => array(
						'buddypress' => 'BuddyPress',
					),
					'media' => array(
						'ngg' => 'NextGEN Gallery',
						'photon' => 'Jetpack Photon',
						'wistia' => 'Wistia Video API',
					),
					'util' => array(
						'rewrite' => 'URL Rewriter',
						'shorten' => 'URL Shortener',
					),
				),
			),
			'opt' => array(				// options
				'version' => '222',		// increment when changing default options
				'defaults' => array(
					'meta_desc_len' => 156,
					'link_author_field' => '',
					'link_def_author_id' => 0,
					'link_def_author_on_index' => 0,
					'link_def_author_on_search' => 0,
					'link_publisher_url' => '',
					'fb_admins' => '',
					'fb_app_id' => '',
					'fb_lang' => 'en_US',
					'og_site_name' => '',
					'og_site_description' => '',
					'og_publisher_url' => '',
					'og_art_section' => '',
					'og_img_width' => 1200,
					'og_img_height' => 630,
					'og_img_crop' => 1,
					'og_img_resize' => 1,
					'og_img_max' => 1,
					'og_vid_max' => 1,
					'og_vid_https' => 1,
					'og_def_img_id_pre' => 'wp',
					'og_def_img_id' => '',
					'og_def_img_url' => '',
					'og_def_img_on_index' => 1,
					'og_def_img_on_search' => 1,
					'og_def_author_id' => 0,
					'og_def_author_on_index' => 0,
					'og_def_author_on_search' => 0,
					'og_ngg_tags' => 0,
					'og_page_parent_tags' => 0,
					'og_page_title_tag' => 0,
					'og_author_field' => '',
					'og_author_fallback' => 0,
					'og_title_sep' => '-',
					'og_title_len' => 70,
					'og_desc_len' => 300,
					'og_desc_hashtags' => 0,
					'og_desc_strip' => 0,
					'og_empty_tags' => 0,
					'buttons_on_index' => 0,
					'buttons_on_front' => 0,
					'buttons_add_to_post' => 1,
					'buttons_add_to_page' => 1,
					'buttons_add_to_attachment' => 1,
					'buttons_location_the_excerpt' => 'bottom',
					'buttons_location_the_content' => 'bottom',
					'buttons_link_css' => 1,
					'buttons_css_social' => '',
					'buttons_css_excerpt' => '',
					'buttons_css_content' => '',
					'buttons_css_shortcode' => '',
					'buttons_css_widget' => '',
					'fb_on_the_excerpt' => 0,
					'fb_on_the_content' => 0,
					'fb_on_admin_sharing' => 1,
					'fb_order' => 1,
					'fb_js_loc' => 'header',
					'fb_button' => 'like',
					'fb_markup' => 'xfbml',
					'fb_send' => 1,
					'fb_layout' => 'button_count',
					'fb_font' => 'arial',
					'fb_show_faces' => 0,
					'fb_colorscheme' => 'light',
					'fb_action' => 'like',
					'fb_type' => 'button_count',
					'gp_on_the_excerpt' => 0,
					'gp_on_the_content' => 0,
					'gp_on_admin_sharing' => 1,
					'gp_order' => 2,
					'gp_js_loc' => 'header',
					'gp_lang' => 'en-US',
					'gp_action' => 'plusone',
					'gp_size' => 'medium',
					'gp_annotation' => 'bubble',
					'gp_expandto' => '',
					'tc_enable' => 0,
					'tc_site' => '',
					'tc_desc_len' => 200,
					'tc_gal_min' => 4,
					'tc_gal_size' => 'ngfb-medium',
					'tc_photo_size' => 'ngfb-large',
					'tc_large_size' => 'ngfb-medium',
					'tc_sum_size' => 'ngfb-thumbnail',
					'tc_prod_size' => 'ngfb-medium',
					'tc_prod_def_l2' => 'Location',
					'tc_prod_def_d2' => 'Unknown',
					'twitter_on_the_excerpt' => 0,
					'twitter_on_the_content' => 0,
					'twitter_on_admin_sharing' => 1,
					'twitter_order' => 3,
					'twitter_js_loc' => 'header',
					'twitter_lang' => 'en',
					'twitter_caption' => 'title',
					'twitter_cap_len' => 140,
					'twitter_count' => 'horizontal',
					'twitter_size' => 'medium',
					'twitter_via' => 1,
					'twitter_rel_author' => 1,
					'twitter_dnt' => 1,
					'twitter_shortener' => '',
					'linkedin_on_the_excerpt' => 0,
					'linkedin_on_the_content' => 0,
					'linkedin_on_admin_sharing' => 1,
					'linkedin_order' => 4,
					'linkedin_js_loc' => 'header',
					'linkedin_counter' => 'right',
					'linkedin_showzero' => 1,
					'managewp_on_the_excerpt' => 0,
					'managewp_on_the_content' => 0,
					'managewp_on_admin_sharing' => 1,
					'managewp_order' => 5,
					'managewp_js_loc' => 'header',
					'managewp_type' => 'small',
					'stumble_on_the_excerpt' => 0,
					'stumble_on_the_content' => 0,
					'stumble_on_admin_sharing' => 1,
					'stumble_order' => 6,
					'stumble_js_loc' => 'header',
					'stumble_badge' => 1,
					'pin_on_the_excerpt' => 0,
					'pin_on_the_content' => 0,
					'pin_on_admin_sharing' => 1,
					'pin_order' => 7,
					'pin_js_loc' => 'header',
					'pin_count_layout' => 'horizontal',
					'pin_img_size' => 'ngfb-large',
					'pin_caption' => 'both',
					'pin_cap_len' => 500,
					'pin_img_url' => 'http://assets.pinterest.com/images/PinExt.png',
					'tumblr_on_the_excerpt' => 0,
					'tumblr_on_the_content' => 0,
					'tumblr_on_admin_sharing' => 1,
					'tumblr_order' => 8,
					'tumblr_js_loc' => 'footer',
					'tumblr_button_style' => 'share_1',
					'tumblr_desc_len' => 300,
					'tumblr_photo' => 1,
					'tumblr_img_size' => 'ngfb-large',
					'tumblr_caption' => 'both',
					'tumblr_cap_len' => 500,
					'inc_description' => 0,
					'inc_fb:admins' => 1,
					'inc_fb:app_id' => 1,
					'inc_og:locale' => 1,
					'inc_og:site_name' => 1,
					'inc_og:description' => 1,
					'inc_og:title' => 1,
					'inc_og:type' => 1,
					'inc_og:url' => 1,
					'inc_og:image' => 1,
					'inc_og:image:secure_url' => 1,
					'inc_og:image:width' => 1,
					'inc_og:image:height' => 1,
					'inc_og:video' => 1,
					'inc_og:video:secure_url' => 1,
					'inc_og:video:width' => 1,
					'inc_og:video:height' => 1,
					'inc_og:video:type' => 1,
					'inc_article:author' => 1,
					'inc_article:publisher' => 1,
					'inc_article:published_time' => 1,
					'inc_article:modified_time' => 1,
					'inc_article:section' => 1,
					'inc_article:tag' => 1,
					'inc_product:price:amount' => 1,
					'inc_product:price:currency' => 1,
					'inc_product:availability' => 1,
					'inc_twitter:card' => 1,
					'inc_twitter:creator' => 1,
					'inc_twitter:site' => 1,
					'inc_twitter:title' => 1,
					'inc_twitter:description' => 1,
					'inc_twitter:image' => 1,
					'inc_twitter:image:width' => 1,
					'inc_twitter:image:height' => 1,
					'inc_twitter:image0' => 1,
					'inc_twitter:image1' => 1,
					'inc_twitter:image2' => 1,
					'inc_twitter:image3' => 1,
					'inc_twitter:player' => 1,
					'inc_twitter:player:width' => 1,
					'inc_twitter:player:height' => 1,
					'inc_twitter:data1' => 1,
					'inc_twitter:label1' => 1,
					'inc_twitter:data2' => 1,
					'inc_twitter:label2' => 1,
					'inc_twitter:data3' => 1,
					'inc_twitter:label3' => 1,
					'inc_twitter:data4' => 1,
					'inc_twitter:label4' => 1,
					'options_version' => '',
					'plugin_version' => '',
					'plugin_tid' => '',
					'plugin_preserve' => 0,
					'plugin_debug' => 0,
					'plugin_filter_content' => 1,
					'plugin_filter_excerpt' => 0,
					'plugin_shortcode_ngfb' => 0,
					'plugin_ignore_small_img' => 1,
					'plugin_wistia_api' => 1,
					'plugin_add_to_post' => 1,
					'plugin_add_to_page' => 1,
					'plugin_add_to_attachment' => 1,
					'plugin_verify_certs' => 0,
					'plugin_file_cache_hrs' => 0,
					'plugin_object_cache_exp' => 3600,
					'plugin_min_shorten' => 21,
					'plugin_google_api_key' => '',
					'plugin_google_shorten' => 0,
					'plugin_bitly_login' => '',
					'plugin_bitly_api_key' => '',
					'plugin_cdn_urls' => '',
					'plugin_cdn_folders' => 'wp-content, wp-includes',
					'plugin_cdn_excl' => '',
					'plugin_cdn_not_https' => 1,
					'plugin_cdn_www_opt' => 1,
					'plugin_cm_fb_name' => 'facebook', 
					'plugin_cm_fb_label' => 'Facebook URL', 
					'plugin_cm_fb_enabled' => 1,
					'plugin_cm_gp_name' => 'gplus', 
					'plugin_cm_gp_label' => 'Google+ URL', 
					'plugin_cm_gp_enabled' => 1,
					'plugin_cm_linkedin_name' => 'linkedin', 
					'plugin_cm_linkedin_label' => 'LinkedIn URL', 
					'plugin_cm_linkedin_enabled' => 0,
					'plugin_cm_pin_name' => 'pinterest', 
					'plugin_cm_pin_label' => 'Pinterest URL', 
					'plugin_cm_pin_enabled' => 0,
					'plugin_cm_tumblr_name' => 'tumblr', 
					'plugin_cm_tumblr_label' => 'Tumblr URL', 
					'plugin_cm_tumblr_enabled' => 0,
					'plugin_cm_twitter_name' => 'twitter', 
					'plugin_cm_twitter_label' => 'Twitter @username', 
					'plugin_cm_twitter_enabled' => 1,
					'plugin_cm_yt_name' => 'youtube', 
					'plugin_cm_yt_label' => 'YouTube Channel URL', 
					'plugin_cm_yt_enabled' => 0,
					'plugin_cm_skype_name' => 'skype', 
					'plugin_cm_skype_label' => 'Skype Username', 
					'plugin_cm_skype_enabled' => 0,
					'wp_cm_aim_name' => 'aim', 
					'wp_cm_aim_label' => 'AIM', 
					'wp_cm_aim_enabled' => 1,
					'wp_cm_jabber_name' => 'jabber', 
					'wp_cm_jabber_label' => 'Jabber / Google Talk', 
					'wp_cm_jabber_enabled' => 1,
					'wp_cm_yim_name' => 'yim',
					'wp_cm_yim_label' => 'Yahoo IM', 
					'wp_cm_yim_enabled' => 1,
				),
				'site_defaults' => array(
					'options_version' => '',
					'plugin_version' => '',
					'plugin_tid' => '',
					'plugin_tid:use' => 'default',
				),
				'admin_sharing' => array(
					'fb_button' => 'share',
					'fb_type' => 'button_count',
					'gp_action' => 'share',
					'gp_size' => 'medium',
					'gp_annotation' => 'bubble',
					'gp_expandto' => '',
					'twitter_count' => 'horizontal',
					'twitter_size' => 'medium',
					'linkedin_counter' => 'right',
					'linkedin_showzero' => 1,
					'managewp_type' => 'small',
					'pin_count_layout' => 'horizontal',
					'tumblr_button_style' => 'share_1',
					'stumble_badge' => 1,
				),
				'pre' => array(
					'facebook' => 'fb', 
					'gplus' => 'gp',
					'twitter' => 'twitter',
					'linkedin' => 'linkedin',
					'managewp' => 'managewp',
					'pinterest' => 'pin',
					'stumbleupon' => 'stumble',
					'tumblr' => 'tumblr',
					'youtube' => 'yt',
					'skype' => 'skype',
				),
			),
			'wp' => array(				// wordpress
				'min_version' => '3.0',		// minimum wordpress version
				'cm' => array(
					'aim' => 'AIM',
					'jabber' => 'Jabber / Google Talk',
					'yim' => 'Yahoo IM',
				),
			),
			'css' => array(				// filter with 'ngfb_style_tabs'
				'social' => 'Buttons Style',
				'excerpt' => 'Excerpt Style',
				'content' => 'Content Style',
				'shortcode' => 'Shortcode Style',
				'widget' => 'Widget Style',
			),
			'url' => array(
				'feed' => 'http://feed.surniaulula.com/category/application/wordpress/wp-plugins/ngfb/feed/',
				'readme' => 'http://plugins.svn.wordpress.org/nextgen-facebook/trunk/readme.txt',
				'purchase' => 'http://plugin.surniaulula.com/extend/plugins/nextgen-facebook/',
				'faq' => 'http://wordpress.org/plugins/nextgen-facebook/faq/',
				'notes' => 'http://wordpress.org/plugins/nextgen-facebook/other_notes/',
				'changelog' => 'http://wordpress.org/plugins/nextgen-facebook/changelog/',
				'support' => 'http://wordpress.org/support/plugin/nextgen-facebook',
				'pro_codex' => 'http://codex.ngfb.surniaulula.com/',
				'pro_support' => 'http://support.ngfb.surniaulula.com/',
				'pro_ticket' => 'http://ticket.ngfb.surniaulula.com/',
				'pro_update' => 'http://update.surniaulula.com/extend/plugins/nextgen-facebook/update/',
			),
			'follow' => array(
				'size' => 32,
				'src' => array(
					'facebook.png' => 'https://www.facebook.com/SurniaUlulaCom',
					'gplus.png' => 'https://plus.google.com/b/112667121431724484705/112667121431724484705/posts',
					'linkedin.png' => 'https://www.linkedin.com/in/jsmoriss',
					'twitter.png' => 'https://twitter.com/surniaululacom',
					'youtube.png' => 'https://www.youtube.com/user/SurniaUlulaCom',
					'feed.png' => 'http://feed.surniaulula.com/category/application/wordpress/wp-plugins/ngfb/feed/',
				),
			),
			'form' => array(
				'max_desc_hashtags' => 10,
				'max_media_items' => 20,
				'file_cache_hours' => array( 0, 1, 3, 6, 9, 12, 24, 36, 48, 72, 168 ),
				'tooltip_class' => 'sucom_tooltip',
			),
			'head' => array(
				'min_img_width' => 200,
				'min_img_height' => 200,
				'min_desc_len' => 156,
			),
			'social' => array(
				'show_on' => array( 
					'the_content' => 'Content', 
					'the_excerpt' => 'Excerpt', 
					'admin_sharing' => 'Edit Post/Page',
				),
			),
		);
		private static $cf_filtered = false;

		public static function get_config( $idx = '' ) { 
			// remove the social sharing libs if disabled
			if ( defined( 'NGFB_SOCIAL_SHARING_DISABLE' ) && NGFB_SOCIAL_SHARING_DISABLE ) {
				unset (
					self::$cf['lib']['submenu']['social'],
					self::$cf['lib']['submenu']['style'],
					self::$cf['lib']['shortcode']['ngfb'],
					self::$cf['lib']['widget']['social'],
					self::$cf['lib']['util']['rewrite'],
					self::$cf['lib']['util']['shorten']
				);
				self::$cf['lib']['website'] = array();
			}
			if ( ! empty( $idx ) ) {
				if ( array_key_exists( $idx, self::$cf ) )
					return self::$cf[$idx];
				else return false;
			} else return self::$cf;
		}

		public static function set_constants( $plugin_filepath ) { 

			$cf = self::get_config();

			define( 'NGFB_FILEPATH', $plugin_filepath );						
			define( 'NGFB_PLUGINDIR', trailingslashit( plugin_dir_path( $plugin_filepath ) ) );
			define( 'NGFB_PLUGINBASE', plugin_basename( $plugin_filepath ) );
			define( 'NGFB_TEXTDOM', $cf['slug'] );
			define( 'NGFB_URLPATH', trailingslashit( plugins_url( '', $plugin_filepath ) ) );
			define( 'NGFB_NONCE', md5( NGFB_PLUGINDIR.'-'.$cf['version'] ) );

			/*
			 * Allow some constants to be pre-defined in wp-config.php
			 */

			if ( defined( 'NGFB_DEBUG' ) && 
				! defined( 'NGFB_HTML_DEBUG' ) )
					define( 'NGFB_HTML_DEBUG', NGFB_DEBUG );

			if ( ! defined( 'NGFB_CACHEDIR' ) )
				define( 'NGFB_CACHEDIR', NGFB_PLUGINDIR.'cache/' );

			if ( ! defined( 'NGFB_CACHEURL' ) )
				define( 'NGFB_CACHEURL', NGFB_URLPATH.'cache/' );

			if ( ! defined( 'NGFB_OPTIONS_NAME' ) )
				define( 'NGFB_OPTIONS_NAME', $cf['lca'].'_options' );

			if ( ! defined( 'NGFB_SITE_OPTIONS_NAME' ) )
				define( 'NGFB_SITE_OPTIONS_NAME', $cf['lca'].'_site_options' );

			if ( ! defined( 'NGFB_META_NAME' ) )
				define( 'NGFB_META_NAME', '_'.$cf['lca'].'_meta' );

			if ( ! defined( 'NGFB_MENU_PRIORITY' ) )
				define( 'NGFB_MENU_PRIORITY', '99.11' );

			if ( ! defined( 'NGFB_INIT_PRIORITY' ) )
				define( 'NGFB_INIT_PRIORITY', 13 );

			if ( ! defined( 'NGFB_HEAD_PRIORITY' ) )
				define( 'NGFB_HEAD_PRIORITY', 10 );

			if ( ! defined( 'NGFB_SOCIAL_PRIORITY' ) )
				define( 'NGFB_SOCIAL_PRIORITY', 100 );
			
			if ( ! defined( 'NGFB_FOOTER_PRIORITY' ) )
				define( 'NGFB_FOOTER_PRIORITY', 100 );
			
			if ( ! defined( 'NGFB_DEBUG_FILE_EXP' ) )
				define( 'NGFB_DEBUG_FILE_EXP', 300 );

			if ( ! defined( 'NGFB_CURL_USERAGENT' ) )
				define( 'NGFB_CURL_USERAGENT', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:18.0) Gecko/20100101 Firefox/18.0' );

			if ( ! defined( 'NGFB_CURL_CAINFO' ) )
				define( 'NGFB_CURL_CAINFO', NGFB_PLUGINDIR.'share/curl/cacert.pem' );

			if ( ! defined( 'NGFB_TOPICS_LIST' ) )
				define( 'NGFB_TOPICS_LIST', NGFB_PLUGINDIR.'share/topics.txt' );
		}

		public static function require_libs( $plugin_filepath ) {
			
			$cf = self::get_config();
			$plugin_dir = NGFB_PLUGINDIR;

			require_once( $plugin_dir.'lib/com/util.php' );
			require_once( $plugin_dir.'lib/com/cache.php' );
			require_once( $plugin_dir.'lib/com/notice.php' );
			require_once( $plugin_dir.'lib/com/script.php' );
			require_once( $plugin_dir.'lib/com/style.php' );
			require_once( $plugin_dir.'lib/com/webpage.php' );
			require_once( $plugin_dir.'lib/com/opengraph.php' );

			require_once( $plugin_dir.'lib/check.php' );
			require_once( $plugin_dir.'lib/util.php' );
			require_once( $plugin_dir.'lib/options.php' );
			require_once( $plugin_dir.'lib/user.php' );
			require_once( $plugin_dir.'lib/postmeta.php' );
			require_once( $plugin_dir.'lib/media.php' );
			require_once( $plugin_dir.'lib/style.php' );		// extends lib/com/style.php
			require_once( $plugin_dir.'lib/head.php' );

			if ( is_admin() ) {
				require_once( $plugin_dir.'lib/messages.php' );
				require_once( $plugin_dir.'lib/admin.php' );
				require_once( $plugin_dir.'lib/com/form.php' );
				require_once( $plugin_dir.'lib/ext/parse-readme.php' );
			} else require_once( $plugin_dir.'lib/functions.php' );

			if ( file_exists( $plugin_dir.'lib/social.php' ) &&
				( ! defined( 'NGFB_SOCIAL_SHARING_DISABLE' ) || 
					! NGFB_SOCIAL_SHARING_DISABLE ) )
						require_once( $plugin_dir.'lib/social.php' );

			if ( file_exists( $plugin_dir.'lib/opengraph.php' ) &&
				( ! defined( 'NGFB_OPEN_GRAPH_DISABLE' ) || ! NGFB_OPEN_GRAPH_DISABLE ) &&
				empty( $_SERVER['NGFB_OPEN_GRAPH_DISABLE'] ) )
					require_once( $plugin_dir.'lib/opengraph.php' );	// extends lib/com/opengraph.php

			// additional classes are loaded and extended by the pro addon construct
			if ( file_exists( $plugin_dir.'lib/pro/addon.php' ) )
				require_once( $plugin_dir.'lib/pro/addon.php' );

			add_action( 'ngfb_load_lib', array( 'NgfbConfig', 'load_lib' ), 10, 2 );
		}

		public static function load_lib( $sub, $id ) {
			if ( empty( $sub ) && ! empty( $id ) )
				$filepath = NGFB_PLUGINDIR.'lib/'.$id.'.php';
			elseif ( ! empty( self::$cf['lib'][$sub][$id] ) )
				$filepath = NGFB_PLUGINDIR.'lib/'.$sub.'/'.$id.'.php';
			else return false;
			if ( file_exists( $filepath ) )
				require_once( $filepath );
		}

	}
}

?>
