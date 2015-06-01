<?php

class APSC_Manage
{

	var $AuthorUrl = 'http://gqevu6bsiz.chicappa.jp/';

	function __construct() {

		if( !is_network_admin() && is_admin() ) {

			$this->init();

		}

	}

	function init() {
		
		global $APSC;
		
		add_filter( 'plugin_action_links_' . trailingslashit( $APSC->PluginSlug ) . $APSC->PluginSlug  . '.php' , array( $this , 'plugin_action_links' ) );
		add_action( 'admin_menu' , array( $this , 'admin_menu' ) );
		add_action( 'admin_notices' , array( $this , 'admin_notices' ) );
		
	}

	function plugin_action_links( $links ) {

		global $APSC;
		
		$link = sprintf( '<a href="%1$s">%2$s</a>' , self_admin_url( 'admin.php?page=' . $APSC->PageSlug ) , __( 'Settings' ) );
		$support_link = sprintf( '<a href="%1$s" target="_blank">%2$s</a>' , 'http://wordpress.org/support/plugin/' . $APSC->PluginSlug , __( 'Support Forums' ) );
		array_unshift( $links, $link , $support_link  );

		return $links;

	}

	function admin_menu() {

		global $APSC;

		$cap = 'administrator';
		$view_func = array( $this , 'views' );

		add_menu_page( sprintf( __( '%1$s %2$s for %3$s %4$s' , $APSC->ltd ) , __( 'Customize' ) ,  __( 'Sort' , $APSC->ltd ) , __( 'Home' ) , __( 'Archives' ) ) , __( 'Archive Posts Sort Customize' , $APSC->ltd ) , $cap , $APSC->PageSlug , $view_func );
		add_submenu_page( $APSC->PageSlug , sprintf( __( '%1$s %2$s for %3$s %4$s' , $APSC->ltd ) , __( 'Customize' ) ,  __( 'Sort' , $APSC->ltd ) , __( 'Categories' ) , __( 'Archives' ) ) , __( 'Categories' ) , $cap , $APSC->Record['cat'] , $view_func );
		add_submenu_page( $APSC->PageSlug , sprintf( __( '%1$s %2$s for %3$s %4$s' , $APSC->ltd ) , __( 'Customize' ) ,  __( 'Sort' , $APSC->ltd ) , __( 'Tags' ) , __( 'Archives' ) ) , __( 'Tags' ) , $cap , $APSC->Record['tag'] , $view_func );

		$APSC_Lists = new APSC_Lists();
		
		$taxonomies = $APSC_Lists->get_custom_taxonomies();
		if( !empty( $taxonomies ) ) {
			foreach( $taxonomies as $tax_name => $tanoxomy ) {
				add_submenu_page( $APSC->PageSlug , sprintf( __( '%1$s %2$s for %3$s %4$s' , $APSC->ltd ) , __( 'Customize' ) ,  __( 'Sort' , $APSC->ltd ) , $tanoxomy->label , __( 'Archives' ) ) , $tanoxomy->label , $cap , $APSC->Record['ct_' . $tax_name] , $view_func );
			}
		}

		add_submenu_page( $APSC->PageSlug , sprintf( __( '%1$s %2$s for %3$s %4$s' , $APSC->ltd ) , __( 'Customize' ) ,  __( 'Sort' , $APSC->ltd ) , __( 'Search' ) , __( 'Archives' ) ) , __( 'Search' ) , $cap , $APSC->Record['search'] , $view_func );
		add_submenu_page( $APSC->PageSlug , sprintf( __( '%1$s %2$s for %3$s %4$s' , $APSC->ltd ) , __( 'Customize' ) ,  __( 'Sort' , $APSC->ltd ) , __( 'Monthly' ) , __( 'Archives' ) ) , sprintf( '%1$s ( %2$s )' , __( 'Archives' ) , __( 'Monthly' , $APSC->ltd ) ) , $cap , $APSC->Record['monthly'] , $view_func );

	}

	function admin_notices() {

		global $APSC;

		if( !empty( $_GET[$APSC->MsgQ] ) ) {
			
			if( $_GET[$APSC->MsgQ] == 'update' or $_GET[$APSC->MsgQ] == 'delete' ) {
				
				$msg = __( 'Settings saved.' );

			} elseif( $_GET[$APSC->MsgQ] == 'donated' ) {

				$msg = __( 'Thank you for your donation.' , $APSC->ltd );

			}
			
			if( !empty( $msg ) ) {
				
				printf( '<div class="updated"><p><strong>%s</strong></p></div>' , $msg );
				
			}

		}
		
	}

	function get_view_dir() {

		global $APSC;

		$dir = $APSC->Dir . trailingslashit( $APSC->Path['view'] );
		
		return $dir;
		
	}

	function get_asset_url() {

		global $APSC;

		$dir = $APSC->Url . trailingslashit( $APSC->Path['asset'] );
		
		return $dir;
		
	}

	function views() {
		
		global $plugin_page;
		global $APSC;
		
		if( $plugin_page == $APSC->PageSlug ) {
			
			include_once $this->get_view_dir() . 'default.php';
			
		} elseif( $plugin_page == $APSC->Record['cat'] ) {
		
			include_once $this->get_view_dir() . 'category.php';

		} elseif( $plugin_page == $APSC->Record['tag'] ) {
		
			include_once $this->get_view_dir() . 'tag.php';

		} elseif( $plugin_page == $APSC->Record['search'] ) {
		
			include_once $this->get_view_dir() . 'search.php';

		} elseif( $plugin_page == $APSC->Record['monthly'] ) {
		
			include_once $this->get_view_dir() . 'monthly.php';

		} elseif( strstr( $plugin_page , $APSC->ltd . '_ct_' ) ) {
			
			include_once $this->get_view_dir() . 'custom_taxonomies.php';

		}
		
		add_filter( 'in_admin_footer' , array( $this , 'display_donation' ) );
		add_filter( 'admin_footer_text' , array( $this , 'admin_footer_text' ) );
		
	}

	function get_query_taxonomy() {
		
		global $plugin_page;
		global $APSC;

		$APSC_Lists = new APSC_Lists();

		$taxonomy = str_replace( $APSC->ltd . '_ct_' , '' , $plugin_page );
		
		return get_taxonomy( $taxonomy );
		
	}

	function is_donation() {

		global $APSC;

		$check = false;
		$key = get_option( $APSC->ltd . '_donated' );
		if( !empty( $key ) && $APSC->DonateKey == $key ) {
			$check = true;
		}

		return $check;

	}

	function display_donation() {

		global $APSC;

		if( !$this->is_donation() ) {

			printf( '<div class="error"><p><strong>%s</strong></p></div>' , __( 'Please consider a donate if you are satisfied with this plugin.' , $APSC->ltd ) );

		}

	}

	function admin_footer_text() {

		global $APSC;
		
		$url = $this->get_author_url( false , 'footer' );
		$text = sprintf( '<img src="%1$s" width="18" /> Plugin Developer: <a href="%2$s" target="_blank">%3$s</a>' , $this->get_gravatar_src( 18 ) , $url , 'gqevu6bsiz' );

		return $text;

	}

	function get_author_url( $slug = false , $part = false ) {
		
		global $APSC;

		$url = $this->AuthorUrl;

		if( !empty( $slug ) ) {

			if( $slug == 'donation' ) {

				$url .= 'please-donation/';

			} elseif( $slug == 'contact' ) {

				$url .= 'contact-us/';

			} elseif( $slug == 'linebreak' ) {

				$url .= 'line-break-first-and-end/';

			} elseif( $slug == 'translation' ) {

				$url .= 'please-translation/';

			}

		}
		
		$url .= $this->get_query_url( $part );

		return $url;

	}

	function get_query_url( $part = false ) {

		global $APSC;

		$url = '?utm_source=use_plugin&utm_medium=' . $part . '&utm_content=' . $APSC->ltd . '&utm_campaign=' . str_replace( '.' , '_' , $APSC->Ver );

		return $url;

	}

	function get_gravatar_src( $size = 40 ) {
		
		global $APSC;

		$img_src = $APSC->Schema . 'www.gravatar.com/avatar/7e05137c5a859aa987a809190b979ed4?s=' . $size;

		return $img_src;

	}

}
?>