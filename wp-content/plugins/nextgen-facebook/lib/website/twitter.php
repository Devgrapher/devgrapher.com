<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'NgfbAdminSocialTwitter' ) && class_exists( 'NgfbAdminSocial' ) ) {

	class NgfbAdminSocialTwitter extends NgfbAdminSocial {

		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			$this->p->debug->mark();
		}

		public function get_rows() {
			$ret = array();
			
			$ret[] = $this->p->util->th( 'Show Button in', 'short' ).'<td>'.
			( $this->show_on_checkboxes( 'twitter', $this->p->cf['social']['show_on'] ) ).'</td>';

			$ret[] = $this->p->util->th( 'Preferred Order', 'short' ).'<td>'.
			$this->form->get_select( 'twitter_order', 
				range( 1, count( $this->p->admin->submenu['social']->website ) ), 'short' ).'</td>';

			$ret[] = $this->p->util->th( 'JavaScript in', 'short' ).'<td>'.
			$this->form->get_select( 'twitter_js_loc', $this->js_locations ).'</td>';

			$ret[] = $this->p->util->th( 'Default Language', 'short' ).'<td>'.
			$this->form->get_select( 'twitter_lang', SucomUtil::get_lang( 'twitter' ) ).'</td>';

			$ret[] = $this->p->util->th( 'Count Position', 'short' ).'<td>'.
			$this->form->get_select( 'twitter_count', array( 'none' => '', 
			'horizontal' => 'Horizontal', 'vertical' => 'Vertical' ) ).'</td>';

			$ret[] = $this->p->util->th( 'Button Size', 'short' ).'<td>'.
			$this->form->get_select( 'twitter_size', array( 'medium' => 'Medium', 'large' => 'Large' ) ).'</td>';

			$ret[] = $this->p->util->th( 'Tweet Text', 'short' ).'<td>'.
			$this->form->get_select( 'twitter_caption', $this->captions ).'</td>';

			$ret[] = $this->p->util->th( 'Text Length', 'short' ).'<td>'.
			$this->form->get_input( 'twitter_cap_len', 'short' ).' Characters or less</td>';

			$ret[] = $this->p->util->th( 'Do Not Track', 'short', null,
			'Disable tracking for Twitter\'s tailored suggestions and tailored ads.' ).
			'<td>'.$this->form->get_checkbox( 'twitter_dnt' ).'</td>';

			$ret[] = $this->p->util->th( 'Add via @username', 'short', null,
			'Append the website\'s @username (entered on the ' .
			$this->p->util->get_admin_url( 'general#sucom-tab_pub_twitter', 'General / Twitter' ).' settings tab) to the Tweet.
			The website\'s @username will also be displayed and recommended for following after the Post / Page is shared.' ).
			( $this->p->check->is_aop() == true ? '<td>'.$this->form->get_checkbox( 'twitter_via' ).'</td>' :
			'<td class="blank">'.$this->form->get_fake_checkbox( 'twitter_via' ).'</td>' );

			$ret[] = $this->p->util->th( 'Recommend Author', 'short', null, 
			'Recommend following the Author\'s Twitter @username (from their profile) after sharing. 
			If the \'<em>Add via @username</em>\' option (above) is also checked, the Website\'s @username will be suggested first.' ).
			( $this->p->check->is_aop() == true ? '<td>'.$this->form->get_checkbox( 'twitter_via' ).'</td>' :
			'<td class="blank">'.$this->form->get_fake_checkbox( 'twitter_rel_author' ).'</td>' );

			$shorteners = array( '' => 'none', 'bitly' => 'Bit.ly', 'googl' => 'Goo.gl' );
			$ret[] = $this->p->util->th( 'Shorten URLs with', 'short', null, '
			If you select a URL shortening service here, <strong>you must also enter its API credentials</strong>
			on the '.$this->p->util->get_admin_url( 'advanced#sucom-tab_plugin_shorten', 'Advanced settings page' ).',
			under the API Keys tab.' ).
			( $this->p->check->is_aop() == true ?  '<td>'.$this->form->get_select( 'twitter_shortener', $shorteners, 'medium' ).
			'&nbsp;&nbsp;[ '.$this->p->util->get_admin_url( 'advanced#sucom-tab_plugin_apikeys', 'API Keys' ).' ]' :
			'<td class="blank">'.$this->form->get_hidden( 'twitter_shortener' ).$this->p->options['twitter_shortener'] ).'</td>';

			return $ret;
		}
	}
}

if ( ! class_exists( 'NgfbSocialTwitter' ) && class_exists( 'NgfbSocial' ) ) {

	class NgfbSocialTwitter {

		protected $p;

		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			$this->p->debug->mark();
		}

		public function get_html( $atts = array(), $opts = array() ) {
			if ( empty( $opts ) ) 
				$opts =& $this->p->options;
			global $post; 
			$prot = empty( $_SERVER['HTTPS'] ) ? 'http:' : 'https:';
			$use_post = empty( $atts['is_widget'] ) || is_singular() || is_admin() ? true : false;
			$source_id = $this->p->util->get_source_id( 'twitter', $atts );
			$atts['add_page'] = array_key_exists( 'add_page', $atts ) ? $atts['add_page'] : true;
			$long_url = empty( $atts['url'] ) ? 
				$this->p->util->get_sharing_url( $use_post, $atts['add_page'], $source_id ) : 
				apply_filters( $this->p->cf['lca'].'_sharing_url', $atts['url'], 
					$use_post, $atts['add_page'], $source_id );
			$short_url = apply_filters( $this->p->cf['lca'].'_shorten_url', 
				$long_url, $opts['twitter_shortener'] );

			if ( array_key_exists( 'tweet', $atts ) )
				$atts['caption'] = $atts['tweet'];

			if ( ! array_key_exists( 'caption', $atts ) ) {
				if ( ! empty( $post->ID ) && $use_post == true ) 
					$atts['caption'] = $this->p->meta->get_options( $post->ID, 'twitter_desc' );

				if ( empty( $atts['caption'] ) ) {
					$cap_len = $this->p->util->tweet_max_len( $long_url );	// tweet_max_len() shortens -- don't shorten twice
					$atts['caption'] = $this->p->webpage->get_caption( $opts['twitter_caption'], $cap_len, $use_post );
				}
			}

			if ( ! array_key_exists( 'lang', $atts ) )
				$atts['lang'] = empty( $opts['twitter_lang'] ) ? 'en' : $opts['twitter_lang'];
			$atts['lang'] = apply_filters( $this->p->cf['lca'].'_lang', $atts['lang'], SucomUtil::get_lang( 'twitter' ) );

			if ( ! array_key_exists( 'via', $atts ) ) {
				if ( ! empty( $opts['twitter_via'] ) && 
					$this->p->check->is_aop() )
						$atts['via'] = preg_replace( '/^@/', '', $opts['tc_site'] );
				else $atts['via'] = '';
			}

			if ( ! array_key_exists( 'related', $atts ) ) {
				if ( ! empty( $opts['twitter_rel_author'] ) && 
					$this->p->check->is_aop() && ! empty( $post ) && $use_post == true )
						$atts['related'] = preg_replace( '/^@/', '', 
							get_the_author_meta( $opts['plugin_cm_twitter_name'], $post->author ) );
				else $atts['related'] = '';
			}

			// hashtags are included in the caption instead
			if ( ! array_key_exists( 'hashtags', $atts ) )
				$atts['hashtags'] = '';

			if ( ! array_key_exists( 'dnt', $atts ) ) 
				$atts['dnt'] = $opts['twitter_dnt'] ? 'true' : 'false';

			$html = '<!-- Twitter Button --><div '.$this->p->social->get_css( 'twitter', $atts ).'><a href="'.$prot.'//twitter.com/share" class="twitter-share-button" data-lang="'. $atts['lang'].'" data-url="'.$short_url.'" data-counturl="'.$long_url.'" data-text="'.$atts['caption'].'" data-via="'.$atts['via'].'" data-related="'.$atts['related'].'" data-hashtags="'.$atts['hashtags'].'" data-count="'.$opts['twitter_count'].'" data-size="'.$opts['twitter_size'].'" data-dnt="'.$atts['dnt'].'"></a></div>';

			$this->p->debug->log( 'returning html ('.strlen( $html ).' chars)' );

			return $html;
		}
		
		public function get_js( $pos = 'id' ) {
			$this->p->debug->mark();
			$prot = empty( $_SERVER['HTTPS'] ) ? 'http:' : 'https:';
			$js_url = $this->p->util->get_cache_url( $prot.'//platform.twitter.com/widgets.js' );

			return '<script type="text/javascript" id="twitter-script-'.$pos.'">'.$this->p->cf['lca'].'_insert_js( "twitter-script-'.$pos.'", "'.$js_url.'" );</script>';
		}
	}
}
?>
