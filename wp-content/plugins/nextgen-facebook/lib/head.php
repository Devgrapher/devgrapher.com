<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'NgfbHead' ) ) {

	class NgfbHead {

		private $p;

		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			$this->p->debug->mark();

			add_action( 'wp_head', array( &$this, 'add_header' ), NGFB_HEAD_PRIORITY );
		}

		// called by WP wp_head action
		public function add_header() {
			// add various function test results top-most in the debug log
			// hook into ngfb_is_functions to extend the default array of function names
			if ( $this->p->debug->is_on() ) {
				$is_functions = array( 
					'is_author',
					'is_archive',
					'is_category',
					'is_tag',
					'is_home',
					'is_search',
					'is_singular',
					'is_attachment',
					'is_product',
					'is_product_category',
					'is_product_tag',
				);
				$is_functions = apply_filters( $this->p->cf['lca'].'_is_functions', $is_functions );
				foreach ( $is_functions as $function ) 
					if ( function_exists( $function ) && $function() )
						$this->p->debug->log( $function.'() = true' );
			}
			if ( $this->p->is_avail['metatags'] ) {
				if ( $this->p->is_avail['opengraph'] )
					echo $this->get_header_html( $this->p->og->get_array() );
				else echo $this->get_header_html();
			} else echo "\n<!-- ".$this->p->cf['lca']." meta tags are disabled -->\n";

			if ( $this->p->debug->is_on() ) {
				$defined_constants = get_defined_constants( true );
				$defined_constants['user']['NGFB_NONCE'] = '********';
				$this->p->debug->show_html( SucomUtil::preg_grep_keys( '/^NGFB_/', $defined_constants['user'] ), 'ngfb constants' );

				$opts = $this->p->options;
				foreach ( $opts as $key => $val ) {
					switch ( $key ) {
						case ( preg_match( '/^buttons_css_/', $key ) ? true : false ):
						case ( preg_match( '/_key$/', $key ) ? true : false ):
						case 'plugin_tid':
							$opts[$key] = '********';
					}
				}
				$this->p->debug->show_html( print_r( $this->p->is_avail, true ), 'available features' );
				$this->p->debug->show_html( print_r( $this->p->check->get_active(), true ), 'active plugins' );
				$this->p->debug->show_html( null, 'debug log' );
				$this->p->debug->show_html( $opts, 'ngfb settings' );
			}
		}

		// called from add_header() and the work/header.php template
		// input array should be from transient cache
		public function get_header_html( $meta_tags = array(), $use_post = false ) {
		
			if ( ( $obj = $this->p->util->get_the_object( $use_post ) ) === false ) {
				$this->p->debug->log( 'exiting early: invalid object type' );
				return;
			}
			$post_id = empty( $obj->ID ) ? 0 : $obj->ID;
			$this->p->debug->log( 'using post_id '.$post_id );
			$author_url = '';
		
			$html = "\n<!-- ".$this->p->cf['lca']." meta tags begin -->\n";
			if ( $this->p->is_avail['aop'] )
				$html .= "<!-- updates: ".$this->p->cf['url']['pro_update']." -->\n";

			// show the array structure before the html block
			$html .= $this->p->debug->get_html( print_r( $meta_tags, true ), 'open graph array' );
			$html .= $this->p->debug->get_html( print_r( $this->p->util->get_urls_found(), true ), 'media urls found' );

			$html .= '<meta name="generator" content="'.$this->p->cf['full'].' '.$this->p->cf['version'];
			if ( $this->p->check->is_aop() ) $html .= 'L';
			elseif ( $this->p->is_avail['aop'] ) $html .= 'U';
			else $html .= 'G';
			$html .= '" />'."\n";

			/*
			 * Meta HTML Tags for Google
			 */
			$links = array();
			if ( array_key_exists( 'link:publisher', $meta_tags ) ) {
				$links['publisher'] = $meta_tags['link:publisher'];
				unset ( $meta_tags['link:publisher'] );
			} elseif ( ! empty( $this->p->options['link_publisher_url'] ) )
				$links['publisher'] = $this->p->options['link_publisher_url'];

			if ( array_key_exists( 'link:author', $meta_tags ) ) {
				$links['author'] = $meta_tags['link:author'];
				unset ( $meta_tags['link:author'] );
			} else {
				// check for single/attachment page, or admin editing page
				if ( is_singular() || $use_post !== false ) {
					if ( ! empty( $obj->post_author ) )
						$links['author'] = $this->p->user->get_author_url( $obj->post_author, 
							$this->p->options['link_author_field'] );
					elseif ( ! empty( $this->p->options['link_def_author_id'] ) )
						$links['author'] = $this->p->user->get_author_url( $this->p->options['link_def_author_id'], 
							$this->p->options['link_author_field'] );

				// check for default author info on indexes and searches
				} elseif ( ( ! ( is_singular() || $use_post !== false ) && 
					! is_search() && ! empty( $this->p->options['link_def_author_on_index'] ) && ! empty( $this->p->options['link_def_author_id'] ) )
					|| ( is_search() && ! empty( $this->p->options['link_def_author_on_search'] ) && ! empty( $this->p->options['link_def_author_id'] ) ) ) {

					$links['author'] = $this->p->user->get_author_url( $this->p->options['link_def_author_id'], 
						$this->p->options['link_author_field'] );
				}
			}
			$links = apply_filters( $this->p->cf['lca'].'_link', $links );
			foreach ( $links as $key => $val )
				if ( ! empty( $val ) )
					$html .= '<link rel="'.$key.'" href="'.$val.'" />'."\n";

			// the meta "description" html tag
			if ( ! empty( $this->p->options['inc_description'] ) ) {
				if ( ! array_key_exists( 'description', $meta_tags ) ) {
					if ( ! empty( $post_id ) && ( is_singular() || $use_post !== false ) )
						$meta_tags['description'] = $this->p->meta->get_options( $post_id, 'meta_desc' );
					if ( empty( $meta_tags['description'] ) )
						$meta_tags['description'] = $this->p->webpage->get_description( $this->p->options['meta_desc_len'], '...',
							$use_post, true, false );	// use_post = false, use_cache = true, add_hashtags = false
				}
			}
			$meta_tags = apply_filters( $this->p->cf['lca'].'_meta', $meta_tags );

			/*
			 * Print the Multi-Dimensional Array as HTML
			 */
			$this->p->debug->log( count( $meta_tags ).' meta_tags to process' );
			foreach ( $meta_tags as $first_name => $first_val ) {			// 1st-dimension array (associative)
				if ( is_array( $first_val ) ) {
					if ( empty( $first_val ) )
						$html .= $this->get_single_meta( $first_name );	// possibly show an empty tag (depends on og_empty_tags value)
					else {
						foreach ( $first_val as $second_num => $second_val ) {			// 2nd-dimension array
							if ( SucomUtil::is_assoc( $second_val ) ) {
								ksort( $second_val );
								foreach ( $second_val as $third_name => $third_val )	// 3rd-dimension array (associative)
									$html .= $this->get_single_meta( $third_name, $third_val, $first_name.':'.( $second_num + 1 ) );
							} else $html .= $this->get_single_meta( $first_name, $second_val, $first_name.':'.( $second_num + 1 ) );
						}
					}
				} else $html .= $this->get_single_meta( $first_name, $first_val );
			}
			$html .= "<!-- ".$this->p->cf['lca']." meta tags end -->\n";
			return $html;
		}

		private function get_single_meta( $name, $val = '', $cmt = '' ) {
			$meta_html = '';

			if ( empty( $this->p->options['inc_'.$name] ) ) {
				$this->p->debug->log( 'meta '.$name.' is disabled (skipped)' );
				return $meta_html;
			} elseif ( $val === -1 ) {
				$this->p->debug->log( 'meta '.$name.' is -1 (skipped)' );
				return $meta_html;
			// ignore all empty non-open graph meta tags, 
			// and open-graph meta tags as well if the option allows
			} elseif ( ( $val === '' || $val === null ) && 
				( preg_match( '/^description|fb:|twitter:/', $name ) || 
					empty( $this->p->options['og_empty_tags'] ) ) ) {

				$this->p->debug->log( 'meta '.$name.' is empty (skipped)' );
				return $meta_html;
			} elseif ( is_object( $val ) ) {
				$this->p->debug->log( 'meta '.$name.' value is an object (skipped)' );
				return $meta_html;
			}

			$charset = get_bloginfo( 'charset' );
			$val = htmlentities( $val, ENT_QUOTES, $charset, false );	// double_encode = false

			$this->p->debug->log( 'meta '.$name.' = "'.$val.'"' );
			if ( $cmt ) $meta_html .= "<!-- $cmt -->";

			// by default, echo a <meta property="" content=""> html tag
			// the description and twitter card tags are exceptions
			if ( $name == 'description' || strpos( $name, 'twitter:' ) === 0 ) {

				$meta_html .= '<meta name="'.$name.'" content="'.$val.'" />'."\n";

			} elseif ( ( $name == 'og:image' || $name == 'og:video' ) && 
				strpos( $val, 'https:' ) === 0 && ! empty( $this->p->options['inc_'.$name] ) ) {

				$http_url = preg_replace( '/^https:/', 'http:', $val );
				$meta_html .= '<meta property="'.$name.'" content="'.$http_url.'" />'."\n";

				// add an additional secure_url meta tag
				if ( $cmt ) $meta_html .= "<!-- $cmt -->";
				$meta_html .= '<meta property="'.$name.':secure_url" content="'.$val.'" />'."\n";

			} else $meta_html .= '<meta property="'.$name.'" content="'.$val.'" />'."\n";

			return $meta_html;
		}
	}
}
?>
