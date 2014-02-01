<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'NgfbMedia' ) ) {

	class NgfbMedia {

		private $p;

		public $data_tags_preg = '(img)';
		public $data_attr_preg = '(data-wp-pid)';

		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			$this->p->debug->mark();
			$this->add_image_sizes();

			// prevent image_downsize() from lying about image width and height
			if ( is_admin() )
				add_filter( 'editor_max_image_size', array( &$this, 'editor_max_image_size' ), 10, 3 );

			add_filter( 'wp_get_attachment_image_attributes', array( &$this, 'add_attachment_image_attributes' ), 10, 2 );
			add_filter( 'get_image_tag', array( &$this, 'add_image_tag' ), 10, 6 );
		}

		private function add_image_sizes() {
			foreach ( array( 'thumbnail', 'medium', 'large' ) as $size_name ) {
				$size_info = $this->get_size_info( $size_name );
				add_image_size( $this->p->cf['lca'].'-'.$size_name, 
					$size_info['width'], $size_info['height'], $size_info['crop'] );
			}
			add_image_size( $this->p->cf['lca'].'-opengraph', 
				$this->p->options['og_img_width'], $this->p->options['og_img_height'], 
				( empty( $this->p->options['og_img_crop'] ) ? false : true ) );
		}

		// note that $size_name can be a string or an array()
		public function editor_max_image_size( $max_sizes = array(), $size_name = '', $context = '' ) {
			// allow only our sizes to exceed the editor width
			if ( is_string( $size_name ) &&
				strpos( $size_name, $this->p->cf['lca'].'-' ) !== false )
					$max_sizes = array( 0, 0 );
			return $max_sizes;
		}

		// $attr = apply_filters( 'wp_get_attachment_image_attributes', $attr, $attachment );
		public function add_attachment_image_attributes( $attr, $attach ) {
			$attr['data-wp-pid'] = $attach->ID;
			return $attr;
		}

		// $html = apply_filters( 'get_image_tag', $html, $id, $alt, $title, $align, $size );
		public function add_image_tag( $html, $id, $alt, $title, $align, $size ) {
			if ( strpos( $html, ' data-wp-pid=' ) === false )
				$html = preg_replace( '/ *\/?>/', ' data-wp-pid="'.$id.'"$0', $html );
			return $html;
		}

		public function get_size_info( $size_name = 'thumbnail' ) {
			global $_wp_additional_image_sizes;
			if ( is_integer( $size_name ) ) return;
			if ( is_array( $size_name ) ) return;
			if ( isset( $_wp_additional_image_sizes[$size_name]['width'] ) )
				$width = intval( $_wp_additional_image_sizes[$size_name]['width'] );
			else $width = get_option( $size_name.'_size_w' );
			if ( isset( $_wp_additional_image_sizes[$size_name]['height'] ) )
				$height = intval( $_wp_additional_image_sizes[$size_name]['height'] );
			else $height = get_option( $size_name.'_size_h' );
			if ( isset( $_wp_additional_image_sizes[$size_name]['crop'] ) )
				$crop = intval( $_wp_additional_image_sizes[$size_name]['crop'] );
			else $crop = get_option( $size_name.'_crop' ) == 1 ? 1 : 0;
			return array( 'width' => $width, 'height' => $height, 'crop' => $crop );
		}

		public function num_remains( &$arr, $num = 0 ) {
			$remains = 0;
			if ( ! is_array( $arr ) ) return false;
			if ( $num > 0 && $num >= count( $arr ) )
				$remains = $num - count( $arr );
			return $remains;
		}

		public function get_post_images( $num = 0, $size_name = 'thumbnail', $post_id, $check_dupes = true ) {
			$this->p->debug->args( array( 'num' => $num, 'size_name' => $size_name, 'post_id' => $post_id, 'check_dupes' => $check_dupes ) );
			$og_ret = array();
			$num_remains = $this->num_remains( $og_ret, $num );
			$og_ret = array_merge( $og_ret, $this->get_meta_image( $num_remains, $size_name, $post_id, $check_dupes ) );
			if ( ! $this->p->util->is_maxed( $og_ret, $num ) ) {
				$num_remains = $this->num_remains( $og_ret, $num );
				$og_ret = array_merge( $og_ret, $this->get_featured( $num_remains, $size_name, $post_id, $check_dupes ) );
			}
			if ( ! $this->p->util->is_maxed( $og_ret, $num ) ) {
				$num_remains = $this->num_remains( $og_ret, $num );
				$og_ret = array_merge( $og_ret, $this->get_attached_images( $num_remains, $size_name, $post_id, $check_dupes ) );
			}
			return $og_ret;
		}

		public function get_featured( $num = 0, $size_name = 'thumbnail', $post_id, $check_dupes = true ) {
			$this->p->debug->args( array( 'num' => $num, 'size_name' => $size_name, 'post_id' => $post_id, 'check_dupes' => $check_dupes ) );
			$og_ret = array();
			$og_image = array();
			if ( ! empty( $post_id ) && $this->p->is_avail['postthumb'] == true && has_post_thumbnail( $post_id ) ) {
				$pid = get_post_thumbnail_id( $post_id );

				list( $og_image['og:image'], $og_image['og:image:width'], $og_image['og:image:height'], 
					$og_image['og:image:cropped'] ) = $this->get_attachment_image_src( $pid, $size_name, $check_dupes );

				if ( ! empty( $og_image['og:image'] ) )
					$this->p->util->push_max( $og_ret, $og_image, $num );
			}
			return apply_filters( $this->p->cf['lca'].'_og_featured', $og_ret, $num, $size_name, $post_id, $check_dupes );
		}

		public function get_first_attached_image_id( $post_id ) {
			if ( ! empty( $post_id ) ) {
				$images = get_children( array( 'post_parent' => $post_id, 'post_type' => 'attachment', 'post_mime_type' => 'image' ) );
				$attach = reset( $images );
				if ( ! empty( $attach->ID ) )
					return $attach->ID;
			}
			return;
		}

		public function get_attachment_image( $num = 0, $size_name = 'thumbnail', $attach_id, $check_dupes = true ) {
			$this->p->debug->args( array( 'num' => $num, 'size_name' => $size_name, 'attach_id' => $attach_id, 'check_dupes' => $check_dupes ) );
			$og_ret = array();
			if ( ! empty( $attach_id ) ) {
				if ( wp_attachment_is_image( $attach_id ) ) {	// since wp 2.1.0 
					$og_image = array();
					list( $og_image['og:image'], $og_image['og:image:width'], $og_image['og:image:height'],
						$og_image['og:image:cropped'] ) = $this->get_attachment_image_src( $attach_id, $size_name, $check_dupes );
					if ( ! empty( $og_image['og:image'] ) &&
						$this->p->util->push_max( $og_ret, $og_image, $num ) )
							return $og_ret;
				} else $this->p->debug->log( 'attachment id '.$attach_id.' is not an image' );
			}
			return $og_ret;
		}

		public function get_attached_images( $num = 0, $size_name = 'thumbnail', $post_id, $check_dupes = true ) {
			$this->p->debug->args( array( 'num' => $num, 'size_name' => $size_name, 'post_id' => $post_id, 'check_dupes' => $check_dupes ) );
			$og_ret = array();
			if ( ! empty( $post_id ) ) {
				$images = get_children( array( 'post_parent' => $post_id, 'post_type' => 'attachment', 'post_mime_type' => 'image') );
				if ( is_array( $images ) )
					$attach_ids = array();
					foreach ( $images as $attach ) {
						if ( ! empty( $attach->ID ) )
							$attach_ids[] = $attach->ID;
					}
					rsort( $attach_ids, SORT_NUMERIC ); 
					$this->p->debug->log( 'found '.count( $attach_ids ).' attached images for post id '.$post_id );
					$attach_ids = apply_filters( $this->p->cf['lca'].'_attached_image_ids', $attach_ids, $post_id );
					foreach ( $attach_ids as $pid ) {
						$og_image = array();
						list( $og_image['og:image'], $og_image['og:image:width'], $og_image['og:image:height'],
							$og_image['og:image:cropped'] ) = $this->get_attachment_image_src( $pid, $size_name, $check_dupes );
						if ( ! empty( $og_image['og:image'] ) &&
							$this->p->util->push_max( $og_ret, $og_image, $num ) )
								break;	// end foreach and apply filters
					}
			}
			return apply_filters( $this->p->cf['lca'].'_og_attached_images', $og_ret, $num, $size_name, $post_id, $check_dupes );
		}

		public function get_attachment_image_src( $pid, $size_name = 'thumbnail', $check_dupes = true ) {
			$this->p->debug->args( array( 'pid' => $pid, 'size_name' => $size_name, 'check_dupes' => $check_dupes ) );
			$size_info = $this->get_size_info( $size_name );
			$img_url = '';
			$img_width = -1;
			$img_height = -1;
			$img_cropped = empty( $size_info['crop'] ) ? 0 : 1;
			$ret_empty = array( $img_url, $img_width, $img_height, $img_cropped );	// default values

			if ( $this->p->is_avail['media']['ngg'] === true && strpos( $pid, 'ngg-' ) === 0 ) {
				if ( ! empty( $this->p->addons['ngg'] ) )
					return $this->p->addons['ngg']->get_image_src( $pid, $size_name, $check_dupes );
				else {
					if ( is_admin() )
						$this->p->notice->err( 'NextGEN Gallery support is not available - image id '.$pid.' ignored.' ); 
					else $this->p->debug->log( 'ngg support is not available - image id '.$attr_value.' ignored' ); 
					return $ret_empty; 
				}
			} else {
				if ( ! wp_attachment_is_image( $pid ) ) {
					$this->p->debug->log( 'exiting early: attachment '.$pid.' is not an image' ); 
					return $ret_empty; 
				}

				list( $img_url, $img_width, $img_height ) = apply_filters( $this->p->cf['lca'].'_image_downsize', 
					image_downsize( $pid, $size_name ), $pid, $size_name );
				$this->p->debug->log( 'image_downsize() = '.$img_url.' ('.$img_width.'x'.$img_height.')' );
	
				// make sure the returned image size matches the size we requested, if not then possibly resize the image
				// we do this because image_downsize() does not always return accurate image sizes
				if ( ! empty( $this->p->options['og_img_resize'] ) && 
					strpos( $size_name, $this->p->cf['lca'].'-' ) !== false ) {
	
					// get the actual image sizes from the metadata array
					$img_meta = wp_get_attachment_metadata( $pid );
	
					// are our intermediate image sizes correct in the metadata array?
					if ( empty( $img_meta['sizes'][$size_name] ) ) {
						$this->p->debug->log( $size_name.' size not defined in the image meta' );
						$is_accurate_width = false;
						$is_accurate_height = false;
					} else {
						$is_accurate_width = ! empty( $img_meta['sizes'][$size_name]['width'] ) &&
							$img_meta['sizes'][$size_name]['width'] == $size_info['width'] ? true : false;
						$is_accurate_height = ! empty( $img_meta['sizes'][$size_name]['height'] ) &&
							$img_meta['sizes'][$size_name]['height'] == $size_info['height'] ? true : false;
					}

					// just in case
					if ( empty( $img_meta['width'] ) || empty( $img_meta['height'] ) ) {
						$this->p->debug->log( 'wp_get_attachment_metadata() returned empty original image sizes' );
	
					// if the original (full size) image is too small, get the full size image URL instead
					} elseif ( $img_meta['width'] < $size_info['width'] && $img_meta['height'] < $size_info['height'] ) {
	
						$this->p->debug->log( 'original image ('.$img_meta['width'].'x'.$img_meta['height'].
							') smaller than '.$size_name.' ('.$size_info['width'].'x'.$size_info['height'].
							( empty( $size_info['crop'] ) ? '' : ' cropped' ).') - fetching "full" image size url' );
	
						list( $img_url, $img_width, $img_height ) = apply_filters( $this->p->cf['lca'].'_image_downsize',
							image_downsize( $pid, 'full' ), $pid, 'full' );
						$this->p->debug->log( 'image_downsize() = '.$img_url.' ('.$img_width.'x'.$img_height.')' );
	
					// wordpress returns image sizes based on the information in the metadata array
					// check to see if our intermediate image sizes are correct in the metadata array
					} elseif ( ( empty( $size_info['crop'] ) && ( ! $is_accurate_width && ! $is_accurate_height ) ) ||
						( ! empty( $size_info['crop'] ) && ( ! $is_accurate_width || ! $is_accurate_height ) ) ) {
	
						$this->p->debug->log( 'image metadata ('.
							( empty( $img_meta['sizes'][$size_name]['width'] ) ? 0 : $img_meta['sizes'][$size_name]['width'] ).'x'.
							( empty( $img_meta['sizes'][$size_name]['height'] ) ? 0 : $img_meta['sizes'][$size_name]['height'] ).') does not match '.
							$size_name.' ('.$size_info['width'].'x'.$size_info['height'].( empty( $size_info['crop'] ) ? '' : ' cropped' ).')' );
	
						$fullsizepath = get_attached_file( $pid );
						$this->p->debug->log( 'calling image_make_intermediate_size()' );
						$resized = image_make_intermediate_size( $fullsizepath, $size_info['width'], $size_info['height'], $size_info['crop'] );
						$this->p->debug->log( 'image_make_intermediate_size() reported '.( $resized === false ? 'failure' : 'success' ) );
	
						// update the image metadata 
						if ( $resized !== false ) {
							$img_meta['sizes'][$size_name] = $resized;
							wp_update_attachment_metadata( $pid, $img_meta );
							// request the image size again to validate
							list( $img_url, $img_width, $img_height ) = apply_filters( $this->p->cf['lca'].'_image_downsize',
								image_downsize( $pid, $size_name ), $pid, $size_name );
							$this->p->debug->log( 'image_downsize() = '.$img_url.' ('.$img_width.'x'.$img_height.')' );
						}
					}
				}
				if ( empty( $img_url ) )
					$this->p->debug->log( 'exiting early: returned image_downsize() url is empty' );
			}
			if ( empty( $img_url ) )
				return $ret_empty;

			if ( ! empty( $this->p->options['plugin_ignore_small_img'] ) ) {
				$is_sufficient_width = $img_width >= $size_info['width'] ? true : false;
				$is_sufficient_height = $img_height >= $size_info['height'] ? true : false;

				if ( ( empty( $size_info['crop'] ) && ( ! $is_sufficient_width && ! $is_sufficient_height ) ) ||
					( ! empty( $size_info['crop'] ) && ( ! $is_sufficient_width || ! $is_sufficient_height ) ) ) {

					if ( is_admin() )
						$this->p->notice->err( 'Image id '.$pid.' rejected - '.$img_url.
							' ('.$img_width.'x'.$img_height.') too small for '.$size_name.
							' ('.$size_info['width'].'x'.$size_info['height'].
							( empty( $size_info['crop'] ) ? '' : ' cropped' ).').' );

					$this->p->debug->log( 'exiting early: returned image dimensions are smaller than'.
						' ('.$size_info['width'].'x'.$size_info['height'].
						( empty( $size_info['crop'] ) ? '' : ' cropped' ).')' );

					return $ret_empty;
				}
			}

			if ( ! empty( $img_url ) && 
				( $check_dupes == false || $this->p->util->is_uniq_url( $img_url ) ) )
					return array( apply_filters( $this->p->cf['lca'].'_rewrite_url', $img_url ), $img_width, $img_height, $img_cropped );

			return $ret_empty;
		}

		public function get_meta_image( $num = 0, $size_name = 'thumbnail', $post_id, $check_dupes = true ) {
			$this->p->debug->args( array( 'num' => $num, 'size_name' => $size_name, 'post_id' => $post_id, 'check_dupes' => $check_dupes ) );
			$og_ret = array();
			$og_image = array();

			if ( empty( $post_id ) )	// post id must be > 0 to have post meta
				return $og_ret;

			$pid = $this->p->meta->get_options( $post_id, 'og_img_id' );
			$pre = $this->p->meta->get_options( $post_id, 'og_img_id_pre' );
			$img_url = $this->p->meta->get_options( $post_id, 'og_img_url' );

			if ( $pid > 0 ) {
				$pid = $pre === 'ngg' ? 'ngg-'.$pid : $pid;
				$this->p->debug->log( 'found custom meta image id = '.$pid );
				list( $og_image['og:image'], $og_image['og:image:width'], $og_image['og:image:height'], 
					$og_image['og:image:cropped'] ) = $this->get_attachment_image_src( $pid, $size_name, $check_dupes );

			} elseif ( ! empty( $img_url ) ) {
				$this->p->debug->log( 'found custom meta image url = "'.$img_url.'"' );
				list( $og_image['og:image'], $og_image['og:image:width'], $og_image['og:image:height'], 
					$og_image['og:image:cropped'] ) = array( $img_url, -1, -1, -1 );
			}

			if ( ! empty( $og_image['og:image'] ) &&
				$this->p->util->push_max( $og_ret, $og_image, $num ) )
					return $og_ret;
			return $og_ret;
		}

		public function get_default_image( $num = 0, $size_name = 'thumbnail', $check_dupes = true ) {
			$this->p->debug->args( array( 'num' => $num, 'size_name' => $size_name, 'check_dupes' => $check_dupes ) );
			$og_ret = array();
			$og_image = array();

			$pid = empty( $this->p->options['og_def_img_id'] ) ? '' : $this->p->options['og_def_img_id'];
			$pre = empty( $this->p->options['og_def_img_id_pre'] ) ? '' : $this->p->options['og_def_img_id_pre'];
			$url = empty( $this->p->options['og_def_img_url'] ) ? '' : $this->p->options['og_def_img_url'];

			if ( $pid === '' && $url === '' ) {
				$this->p->debug->log( 'exiting early: no default image defined' );
				return $og_ret;
			}

			if ( $pid > 0 ) {
				$pid = $pre === 'ngg' ? 'ngg-'.$pid : $pid;
				list( $og_image['og:image'], $og_image['og:image:width'], $og_image['og:image:height'],
					$og_image['og:image:cropped'] ) = $this->get_attachment_image_src( $pid, $size_name, $check_dupes );
			}

			if ( empty( $og_image['og:image'] ) && ! empty( $url ) ) {
				$og_image = array();	// clear all array values
				$og_image['og:image'] = $url;
				$this->p->debug->log( 'using default img url = '.$og_image['og:image'] );
			}

			if ( ! empty( $og_image['og:image'] ) && 
				$this->p->util->push_max( $og_ret, $og_image, $num ) )
					return $og_ret;
			return $og_ret;
		}

		public function get_content_images( $num = 0, $size_name = 'thumbnail', $use_post = true, $check_dupes = true, $content = null ) {
			$this->p->debug->args( array( 'num' => $num, 'size_name' => $size_name, 'use_post' => $use_post, 'check_dupes' => $check_dupes, 'content' => strlen( $content ).' chars' ) );
			$og_ret = array();
			$size_info = $this->get_size_info( $size_name );

			// allow custom content to be passed
			if ( empty( $content ) )
				$content = $this->p->webpage->get_content( $use_post );

			if ( empty( $content ) ) { 
				$this->p->debug->log( 'exiting early: empty post content' ); 
				return $og_ret; 
			}

			// check div|a|img html tags for ngg images
			if ( $this->p->is_avail['media']['ngg'] === true ) {
				if ( ! empty( $this->p->addons['ngg'] ) ) {
					$og_ret = $this->p->addons['ngg']->get_content_images( $num, $size_name, $use_post, $check_dupes, $content );
					if ( $this->p->util->is_maxed( $og_ret, $num ) )
						return $og_ret;
				}
			}

			// img attributes in order of preference
			if ( preg_match_all( '/<('.$this->data_tags_preg.'[^>]*? '.$this->data_attr_preg.'=[\'"]([0-9]+)[\'"]|'.
				'(img)[^>]*? (data-share-src|src)=[\'"]([^\'"]+)[\'"])[^>]*>/s', 
				$content, $match, PREG_SET_ORDER ) ) {
				$this->p->debug->log( count( $match ).' x matching <img/> html tag(s) found' );
				foreach ( $match as $img_num => $img_arr ) {
					$tag_value = $img_arr[0];
					if ( empty( $img_arr[5] ) ) {
						$tag_name = $img_arr[2];	// img
						$attr_name = $img_arr[3];	// data-wp-pid
						$attr_value = $img_arr[4];	// id
					} else {
						$tag_name = $img_arr[5];	// img
						$attr_name = $img_arr[6];	// data-share-src|src
						$attr_value = $img_arr[7];	// url
					}
					$this->p->debug->log( 'match '.$img_num.': '.$tag_name.' '.$attr_name.'="'.$attr_value.'"' );
					$og_image = array();
					switch ( $attr_name ) {
						case 'data-wp-pid' :
							list( $og_image['og:image'], $og_image['og:image:width'], $og_image['og:image:height'], 
								$og_image['og:image:cropped'] ) = $this->get_attachment_image_src( $attr_value, $size_name, false );
							break;
						default :
							// prevent duplicates by silently ignoring ngg images (already processed by the ngg addon)
							if ( $this->p->is_avail['media']['ngg'] === true && ! empty( $this->p->addons['ngg'] ) &&
								( strpos( $tag_value, " class='ngg-" ) !== false || 
									preg_match( '/^'.$this->p->addons['ngg']->img_src_preg.'$/', $attr_value ) ) ) {
								break;
							}
	
							// recognize gravatar images in the content
							if ( preg_match( '/^https?:\/\/([^\.]+\.)?gravatar\.com\/avatar\/[a-zA-Z0-9]+/', $attr_value, $match) ) {
								$og_image['og:image'] = $match[0].'?s='.$size_info['width'].'&d=404&r=G';
								$og_image['og:image:width'] = $size_info['width'];
								$og_image['og:image:height'] = $size_info['width'];
								break;
							}

							$og_image = array(
								'og:image' => $attr_value,
								'og:image:width' => -1,
								'og:image:height' => -1,
							);

							// try and get the width and height from the image attributes
							if ( ! empty( $og_image['og:image'] ) ) {
								if ( preg_match( '/ width=[\'"]?([0-9]+)[\'"]?/i', $tag_value, $match) ) 
									$og_image['og:image:width'] = $match[1];
								if ( preg_match( '/ height=[\'"]?([0-9]+)[\'"]?/i', $tag_value, $match) ) 
									$og_image['og:image:height'] = $match[1];
							}

							$is_sufficient_width = $og_image['og:image:width'] >= $size_info['width'] ? true : false;
							$is_sufficient_height = $og_image['og:image:height'] >= $size_info['height'] ? true : false;

							// make sure the image width and height are large enough
							if ( $attr_name == 'data-share-src' || 
								( $attr_name == 'src' && empty( $this->p->options['plugin_ignore_small_img'] ) ) ||
								( $attr_name == 'src' && $size_info['crop'] === 1 && $is_sufficient_width && $is_sufficient_height ) ||
								( $attr_name == 'src' && $size_info['crop'] !== 1 && ( $is_sufficient_width || $is_sufficient_height ) ) ) {

								// data-share-src attribute used and/or image size is acceptable
								// check for relative urls, just in case
								$og_image['og:image'] = $this->p->util->fix_relative_url( $og_image['og:image'] );

							} else {
								if ( is_admin() && $this->p->debug->is_on() === true )
									$this->p->notice->err( 'Image '.$og_image['og:image'].' rejected: width / height missing or too small for '.$size_name.'.' );
								$this->p->debug->log( 'image rejected: width / height attributes missing or too small for '.$size_name.' size.' );
								$og_image = array();
							}
							break;
					}
					if ( ! empty( $og_image['og:image'] ) && 
						( $check_dupes === false || $this->p->util->is_uniq_url( $og_image['og:image'] ) ) )
							if ( $this->p->util->push_max( $og_ret, $og_image, $num ) )
								return $og_ret;
				}
				return $og_ret;
			}
			$this->p->debug->log( 'no matching <img/> html tag(s) found' );
			return $og_ret;
		}

		// called by TwitterCard class to build the Gallery Card
		public function get_gallery_images( $num = 0, $size_name = 'large', $want_this = 'gallery', $check_dupes = false ) {
			$this->p->debug->args( array( 'num' => $num, 'size_name' => $size_name, 'want_this' => $want_this, 'check_dupes' => $check_dupes ) );
			global $post;
			$og_ret = array();
			if ( $want_this == 'gallery' ) {
				if ( empty( $post ) ) { 
					$this->p->debug->log( 'exiting early: empty post object' ); 
					return $og_ret;
				} elseif ( empty( $post->post_content ) ) { 
					$this->p->debug->log( 'exiting early: empty post content' ); 
					return $og_ret;
				}
				if ( preg_match( '/\[(gallery)[^\]]*\]/im', $post->post_content, $match ) ) {
					$shortcode_type = strtolower( $match[1] );
					$this->p->debug->log( '['.$shortcode_type.'] shortcode found' );
					switch ( $shortcode_type ) {
						case 'gallery' :
							$content = do_shortcode( $match[0] );
							$content = preg_replace( '/\['.$shortcode_type.'[^\]]*\]/', '', $content );	// prevent loops, just in case
							// provide the expanded content and extract images
							$og_ret = array_merge( $og_ret, 
								$this->p->media->get_content_images( $num, $size_name, null, $check_dupes, $content ) );
							if ( ! empty( $og_ret ) ) 
								return $og_ret;		// return immediately and ignore any other type of image
							break;
					}
				} else $this->p->debug->log( '[gallery] shortcode not found' );
			}
			// check for ngg gallery
			if ( $this->p->is_avail['media']['ngg'] === true && ! empty( $this->p->addons['ngg'] ) ) {
				$og_ret = $this->p->addons['ngg']->get_gallery_images( $num , $size_name, $want_this, $check_dupes );
				if ( $this->p->util->is_maxed( $og_ret, $num ) )
					return $og_ret;
			}
			$this->p->util->slice_max( $og_ret, $num );
			return $og_ret;
		}

		public function get_meta_video( $num = 0, $post_id, $check_dupes = true ) {
			$this->p->debug->args( array( 'num' => $num, 'post_id' => $post_id, 'check_dupes' => $check_dupes ) );
			$og_ret = array();
			if ( empty( $post_id ) ) 	// post id must be > 0 to have post meta
				return $og_ret;

			$video_url = $this->p->meta->get_options( $post_id, 'og_vid_url' );

			if ( ! empty( $video_url ) && 
				( $check_dupes == false || $this->p->util->is_uniq_url( $video_url ) ) ) {
				$this->p->debug->log( 'found custom meta video url = "'.$video_url.'"' );
				$og_video = $this->get_video_info( $video_url );
				if ( empty( $og_video ) )	// fallback to custom video URL
					$og_video['og:video'] = $video_url;
				if ( $this->p->util->push_max( $og_ret, $og_video, $num ) ) 
					return $og_ret;
			}
			return $og_ret;
		}

		/* Purpose: Check the content for generic <iframe|embed/> html tags. Apply ngfb_content_videos filter for more specialized checks. */
		public function get_content_videos( $num = 0, $use_post = true, $check_dupes = true ) {
			$this->p->debug->args( array( 'num' => $num, 'check_dupes' => $check_dupes ) );
			$og_ret = array();
			$content = $this->p->webpage->get_content( $use_post );
			if ( empty( $content ) ) { 
				$this->p->debug->log( 'exiting early: empty post content' ); 
				return $og_ret; 
			}
			// detect standard iframe/embed tags - use the ngfb_content_videos filter for custom html5/javascript methods
			if ( preg_match_all( '/<(iframe|embed)[^>]*? src=[\'"]([^\'"]+\/(embed|video)\/[^\'"]+)[\'"][^>]*>/i', $content, $match_all, PREG_SET_ORDER ) ) {
				$this->p->debug->log( count( $match_all ).' x video <iframe|embed/> html tag(s) found' );
				foreach ( $match_all as $media ) {
					$this->p->debug->log( '<'.$media[1].'/> html tag found = '.$media[2] );
					$embed_url = $media[2];
					if ( ! empty( $embed_url ) &&
						( $check_dupes == false || $this->p->util->is_uniq_url( $embed_url ) ) ) {
						$embed_width = preg_match( '/ width=[\'"]?([0-9]+)[\'"]?/i', $media[0], $match) ? $match[1] : -1;
						$embed_height = preg_match( '/ height=[\'"]?([0-9]+)[\'"]?/i', $media[0], $match) ? $match[1] : -1;
						$og_video = $this->get_video_info( $embed_url, $embed_width, $embed_height );
						if ( ! empty( $og_video ) && 
							$this->p->util->push_max( $og_ret, $og_video, $num ) ) 
								return $og_ret;
					}
				}
			} else $this->p->debug->log( 'no <iframe|embed/> html tag(s) found' );

			$filter_name = $this->p->cf['lca'].'_content_videos';
			if ( has_filter( $filter_name ) ) {
				$this->p->debug->log( 'applying filter '.$filter_name ); 
				// should return an array of arrays
				if ( ( $match_all = apply_filters( $this->p->cf['lca'].'_content_videos', false, $content ) ) !== false ) {
					if ( is_array( $match_all ) ) {
						$this->p->debug->log( count( $match_all ).' x videos returned by '.$filter_name.' filter' );
						foreach ( $match_all as $media ) {
							if ( ! empty( $media[0] ) && 
								( $check_dupes == false || $this->p->util->is_uniq_url( $media[0] ) ) ) {
								$og_video = $this->get_video_info( $media[0], $media[1], $media[2] );	// url, width, height
								if ( ! empty( $og_video ) && 
									$this->p->util->push_max( $og_ret, $og_video, $num ) ) 
										return $og_ret;
							}
						}
					} else $this->p->debug->log( $filter_name.' filter did not return false or an array' ); 
				}
			}
			return $og_ret;
		}

		private function get_video_info( $embed_url, $embed_width = 0, $embed_height = 0 ) {
			if ( empty( $embed_url ) ) 
				return array();
			$prot = empty( $this->p->options['og_vid_https'] ) ? 'http:' : 'https:';
			$og_video = array(
				'og:video' => '',
				'og:video:type' => 'application/x-shockwave-flash',
				'og:video:width' => $embed_width,
				'og:video:height' => $embed_height,
				'og:image' => '',
				'og:image:width' => -1,
				'og:image:height' => -1,
			);
			/*
			 * YouTube video API
			 */
			if ( preg_match( '/^.*(youtube\.com|youtube-nocookie\.com|youtu\.be)\/(watch\?v=)?([^\?\&\#]+)(\?(list)=([^\?\&\#]+)|.*)$/', $embed_url, $match ) ) {

				$vid_name = false;
				$list_name = false;

				if ( ! empty( $match[6] ) && $match[5] === 'list' ) {
					$list_name = $match[6];
					$api_url = $prot.'//gdata.youtube.com/feeds/api/playlists/'.$list_name;
					if ( $match[3] !== 'videoseries' ) {
						$og_video['og:video'] = $prot.'//www.youtube.com/v/'.$vid_name.'?list='.$list_name;
						$og_video['og:video:embed_url'] = 'https://www.youtube.com/embed/'.$vid_name.'?list='.$list_name;
						$og_video['og:image'] = $prot.'//img.youtube.com/vi/'.$match[3].'/0.jpg';
					}
				} elseif ( ! empty( $match[3] ) ) {
					$vid_name = preg_replace( '/^.*\//', '', $match[3] );
					$api_url = $prot.'//gdata.youtube.com/feeds/api/videos?q='.$vid_name.'&max-results=1&format=5';
					$og_video['og:video'] = $prot.'//www.youtube.com/v/'.$vid_name;
					$og_video['og:video:embed_url'] = 'https://www.youtube.com/embed/'.$vid_name;
					$og_video['og:image'] = $prot.'//img.youtube.com/vi/'.$vid_name.'/0.jpg';
				}

				if ( empty( $api_url ) ) {
					$this->p->debug->log( 'youtube api url is empty - no video or playlist names found' );

				} elseif ( function_exists( 'simplexml_load_string' ) ) {

					$xml = @simplexml_load_string( $this->p->cache->get( $api_url, 'raw', 'transient' ) );

					if ( ! empty( $xml ) ) {
						$this->p->debug->log( 'setting og:video and og:image from youtube api xml' );
						if ( ! empty( $xml->entry[0] ) ) {
							$media = $xml->entry[0]->children( 'media', true );
							$content = $media->group->content[0]->attributes();

							if ( $content['type'] == 'application/x-shockwave-flash' )
								$og_video['og:video'] = (string) $content['url'];

							$og_video['og:video'] .= $list_name !== false ? 
								( strpos( $og_video['og:video'], '?' ) !== false ? 
									'&' : '?' ).'list='.$list_name : '';
	
							// find the largest thumbnail available
							foreach ( $media->group->thumbnail as $thumb ) {
								$thumb_attr = $thumb->attributes();
								if ( ! empty( $thumb_attr['width'] ) ) {
									$thumb_url = (string) $thumb_attr['url'];
									$thumb_width = (string) $thumb_attr['width'];
									$thumb_height = (string) $thumb_attr['height'];
									if ( empty( $og_video['og:image:width'] ) || $thumb_width > $og_video['og:image:width'] ) {
										list( $og_video['og:image'], $og_video['og:image:width'], $og_video['og:image:height'] ) = 
											array( $thumb_url, $thumb_width, $thumb_height );
									}
								}
							}
							// determine video name from preview image url for open graph parsing
							if ( $vid_name === false && ! empty( $og_video['og:image'] ) )
								$vid_name = preg_match( '/^.*\/([^\/]+)\/[^\/]+\.[a-z]+$/', $og_video['og:image'], $match ) ? 
									$match[1] : false;

						} else $this->p->debug->log( 'entry missing from returned xml' );
					} else $this->p->debug->log( 'returned xml is empty' );
				} else $this->p->debug->log( 'simplexml_load_string function is missing' );

				// the google youtube api does not provide the video width / height (seriously), 
				// so get them from the youtube opengraph meta tags if / when missing
				if ( $vid_name !== false && ! empty( $og_video['og:video'] ) && 
					( $og_video['og:video:width'] <= 0 || $og_video['og:video:height'] <= 0 ) ) {

					$og_fetch = $prot.'//www.youtube.com/watch?v='.$vid_name;
					$this->p->debug->log( 'fetching missing video width / height from '.$og_fetch );
					if ( ( $og_html = $this->p->cache->get( $og_fetch, 'raw', 'transient' ) ) !== false ) {
						$og_meta = $this->p->og->parse( $og_html );
						$og_video['og:video:width'] = $og_meta['og:video:width'];
						$og_video['og:video:height'] = $og_meta['og:video:height'];
					}
				}
			/*
			 * Vimeo video API
			 */
			} elseif ( preg_match( '/^.*(vimeo\.com)\/(.*\/)?([^\/\?\&\#]+).*$/', $embed_url, $match ) ) {

				$vid_name = preg_replace( '/^.*\//', '', $match[3] );
				$og_video['og:video'] = $prot.'//vimeo.com/moogaloop.swf?clip_id='.$vid_name;
				$og_video['og:video:embed_url'] = 'https://player.vimeo.com/video/'.$vid_name;
				$api_url = $prot.'//vimeo.com/api/oembed.xml?url=http%3A//vimeo.com/'.$vid_name;

				if ( function_exists( 'simplexml_load_string' ) ) {

					$xml = @simplexml_load_string( $this->p->cache->get( $api_url, 'raw', 'transient' ) );

					if ( ! empty( $xml->thumbnail_url ) ) {
						$this->p->debug->log( 'setting og:video and og:image from vimeo api xml' );
						$og_video['og:image'] = (string) $xml->thumbnail_url;
						$og_video['og:image:width'] = $og_video['og:video:width'] = (string) $xml->thumbnail_width;
						$og_video['og:image:height'] = $og_video['og:video:height'] = (string) $xml->thumbnail_height;

					} else $this->p->debug->log( 'thumbnail_url missing from returned xml' );
				} else $this->p->debug->log( 'simplexml_load_string function is missing' );
			}
			/*
			 * Other video APIs
			 */
			$og_video = apply_filters( $this->p->cf['lca'].'_video_info', $og_video, $embed_url, $embed_width, $embed_height );

			$this->p->debug->log( 'video = '.$og_video['og:video'].' ('.$og_video['og:video:width'].'x'.$og_video['og:video:height'].')' );
			$this->p->debug->log( 'image = '.$og_video['og:image'].' ('.$og_video['og:image:width'].'x'.$og_video['og:image:height'].')' );

			if ( empty( $og_video['og:video'] ) ) {
				unset ( 
					$og_video['og:video'],
					$og_video['og:video:type'],
					$og_video['og:video:width'],
					$og_video['og:video:height']
				);
			}

			if ( empty( $og_video['og:video'] ) && empty( $og_video['og:image'] ) ) 
				return array();
			else return $og_video;
		}
	}
}

?>
