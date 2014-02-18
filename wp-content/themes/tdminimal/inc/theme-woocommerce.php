<?php 

global $woo_options;

/**
 * WooCommerce Support
 */
function woocommerce_support() {
	add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'woocommerce_support' );

/**
 * WooCommerce Overrides
 */
if ( class_exists( 'woocommerce' ) ) { 
	// Disable WooCommerce styles
	if ( version_compare( WOOCOMMERCE_VERSION, "2.1" ) >= 0 ) {
		add_action( 'wp_enqueue_scripts', 'woo_wc_dequeue_styles' );
	} else {
		define( 'WOOCOMMERCE_USE_CSS', false );
	}
	
	function woo_wc_dequeue_styles() {
		wp_dequeue_style( 'woocommerce_frontend_styles_layout' );
		wp_dequeue_style( 'woocommerce_frontend_styles_smallscreen' );
		wp_dequeue_style( 'woocommerce_frontend_styles' );
	}
	
	/**
	 * Remove Products Tabs from single_product_summary section
	 */
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);

	/**
	 * Remove Upsell items from single_product_summary section
	 */
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15);
	remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
	
	// Handle cart in header fragment for ajax add to cart
	function header_add_to_cart_fragment( $fragments ) {
		global $woocommerce;
		ob_start();
		woocommerce_cart_link();
		$fragments['a.cart-parent'] = ob_get_clean();
		return $fragments;
	}
	add_filter( 'add_to_cart_fragments', 'header_add_to_cart_fragment' );
	
	// Remove Page title
	add_filter( 'woocommerce_show_page_title', '__return_false' );
	
	// Header Cart Link
	function woocommerce_cart_link() {
		global $woocommerce;
		$output = '<div class="header-cart-container"><div class="header-cart-button">';
		$output .= '<a href="'.$woocommerce->cart->get_cart_url().'" title="'.__("View your shopping cart", "tdminimal").'" class="cart-parent">';
		$output .= '<i class="fa fa-shopping-cart"></i><span class="count-items">' . $woocommerce->cart->get_cart_contents_count() . '</span>';
		$output .= '</a></div>';
		
		if( class_exists( 'WC_Widget_Cart' ) ) {
			ob_start();
			the_widget('WC_Widget_Cart');
			$output .= '<div class="cart-current-items clearfix">';
			$output .= ob_get_contents();
			$output .= '</div>';
			ob_end_clean();
		}
		
		$output .= '</div>';
		echo $output;
	}
	
	// Change columns in related products output to 4 and move below the product summary
	if (!function_exists('woocommerce_output_related_products')) {
		function woocommerce_output_related_products() {
			$related_products_args = array(
             								'posts_per_page' => 4,
             								'columns'        => 1,
            								'orderby'        => 'rand'
         							);
			woocommerce_related_products( $related_products_args );
		}
	}
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
	add_action( 'woocommerce_after_single_product', 'woocommerce_output_related_products', 20);
}
?>