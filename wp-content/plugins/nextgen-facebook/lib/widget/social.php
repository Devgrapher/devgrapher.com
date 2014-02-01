<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'NgfbWidgetSocialSharing' ) && class_exists( 'WP_Widget' ) ) {

	class NgfbWidgetSocialSharing extends WP_Widget {

		protected $p;

		public function __construct() {
			global $ngfb;
			if ( ! is_object( $ngfb ) )
				return;
			$this->p =& $ngfb;
			$widget_name = $this->p->cf['menu'].' Social Sharing';
			$widget_class = $this->p->cf['lca'].'-widget-buttons';
			$widget_ops = array( 
				'classname' => $widget_class,
				'description' => 'The '.$this->p->cf['full'].' social sharing buttons widget.'
			);
			$this->WP_Widget( $widget_class, $widget_name, $widget_ops );
		}
	
		public function widget( $args, $instance ) {
			if ( is_feed() ) return;	// nothing to do in the feeds
			if ( ! empty( $_SERVER['NGFB_DISABLE'] ) ) return;
			if ( ! is_object( $this->p ) ) return;

			if ( is_object( $this->p->social ) && $this->p->social->is_disabled() ) {
				$this->p->debug->log( 'widget buttons skipped: buttons disabled' );
				return;
			}
			extract( $args );

			if ( $this->p->is_avail['cache']['transient'] ) {
				$sharing_url = $this->p->util->get_sharing_url();
				$cache_salt = __METHOD__.'(lang:'.get_locale().'_widget:'.$this->id.'_sharing_url:'.$sharing_url.')';
				$cache_id = $this->p->cf['lca'].'_'.md5( $cache_salt );
				$cache_type = 'object cache';
				$this->p->debug->log( $cache_type.': widget_html transient salt '.$cache_salt );
				$widget_html = get_transient( $cache_id );
				if ( $widget_html !== false ) {
					$this->p->debug->log( $cache_type.': widget_html retrieved from transient '.$cache_id );
					$this->p->debug->show_html();
					echo $widget_html;
					return;
				}
			}

			// sort enabled social buttons by their preferred order
			$sorted_ids = array();
			foreach ( $this->p->cf['opt']['pre'] as $id => $pre )
				if ( array_key_exists( $id, $instance ) && (int) $instance[$id] )
					$sorted_ids[$this->p->options[$pre.'_order'].'-'.$id] = $id;
			unset ( $id, $pre );
			ksort( $sorted_ids );

			$atts = array( 'is_widget' => 1, 'css_id' => $args['widget_id'] );
			$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

			$widget_html = '<!-- '.$this->p->cf['lca'].' '.$args['widget_id'].' begin -->';
			$widget_html .= $before_widget;
			if ( $title ) 
				$widget_html .= $before_title.$title.$after_title;
			$widget_html .= $this->p->social->get_html( $sorted_ids, $atts );
			$widget_html .= $after_widget;
			$widget_html .= '<!-- '.$this->p->cf['lca'].' '.$args['widget_id'].' end -->';

			if ( $this->p->is_avail['cache']['transient'] ) {
				set_transient( $cache_id, $widget_html, $this->p->cache->object_expire );
				$this->p->debug->log( $cache_type.': widget_html saved to transient '.$cache_id.' ('.$this->p->cache->object_expire.' seconds)');
			}
			$this->p->debug->show_html();
			echo $widget_html;
		}
	
		public function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['title'] = strip_tags( $new_instance['title'] );
			foreach ( $this->p->cf['lib']['website'] as $id => $name )
				$instance[$id] = empty( $new_instance[$id] ) ? 0 : 1;
			unset( $name, $id );
			return $instance;
		}
	
		public function form( $instance ) {
			$title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : 'Share It';
			echo "\n", '<p><label for="', $this->get_field_id( 'title' ), '">Title (Leave Blank for No Title):</label>',
				'<input class="widefat" id="', $this->get_field_id( 'title' ), 
					'" name="', $this->get_field_name( 'title' ), 
					'" type="text" value="', $title, '" /></p>', "\n";
	
			foreach ( $this->p->cf['lib']['website'] as $id => $name ) {
				$classname = $this->p->cf['cca'].'Social'.ucfirst( $id );
				if ( class_exists( $classname ) ) {
					$name = $name == 'GooglePlus' ? 'Google+' : $name;
					echo '<p><label for="', $this->get_field_id( $id ), '">', 
						'<input id="', $this->get_field_id( $id ), 
						'" name="', $this->get_field_name( $id ), 
						'" value="1" type="checkbox" ';
					if ( ! empty( $instance[$id] ) )
						echo checked( 1 , $instance[$id] );
					echo ' /> ', $name;
					switch ( $id ) {
						case 'pinterest' : echo ' (not added on indexes)'; break;
						case 'tumblr' : echo ' (shares link on indexes)'; break;
					}
					echo '</label></p>', "\n";
				}
			}
			unset( $id, $name );
		}
	}
}

?>
