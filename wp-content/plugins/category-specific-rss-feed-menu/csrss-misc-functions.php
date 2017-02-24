<?php

function wp_tag_specific_rss_handler($args)
{
    $rss_image = CAT_SPEC_RSS_URL.'/rss_small_icon.png';
    $tags = get_tags();
    $html = '<div class="tag_specific_rss">';
    $html .= '<ul>';
    foreach ($tags as $tag) {
        $html .= '<li>';
        $tag_link = get_tag_link($tag->term_id);

        $html .= '<a title="Subscribe to the '.$tag->name.' feed" href="'.$tag_link.'/feed/"><img src="'.$rss_image.'" alt="tag feed" /></a>';//Tag feed link
        $html .= ' <a href="'.$tag_link.'">'.$tag->name.'</a>';//Tag link

        $html .= '</li>';
    }
    $html .= '</ul>';
    $html .= '</div>';
    return $html;
}

function wp_tag_specific_rss_cloud_handler($args)
{
    $rss_image = CAT_SPEC_RSS_URL.'/rss_small_icon.png';
    $tags = get_tags();
    $html = '<div class="tag_specific_rss_cloud">';

    foreach ($tags as $tag) {
	$tag_link = get_tag_link( $tag->term_id );
	$tag_feed_link = $tag_link.'feed/';	
	$html .= "<a href='{$tag_feed_link}' title='{$tag->name} Tag' class='{$tag->slug} tag_cloud_rss_item'>{$tag->name}</a> ";
    }
    $html .= '</div>';
    return $html;
}

function post_specific_tag_rss_handler($args=array())
{
    if(isset($args['post_id'])){
        $post_id = $args['post_id'];
    }else{
        global $post;
        $post_id = $post->ID;
    }
    
    $rss_image = CAT_SPEC_RSS_URL.'/rss_small_icon.png';
    
    $tags = wp_get_post_tags($post_id);
    $html = '<div class="post_specific_tag_rss">';
    $html .= '<ul>';
    foreach ($tags as $tag) {
        $html .= '<li>';
        $tag_link = get_tag_link($tag->term_id);

        $html .= '<a title="Subscribe to the '.$tag->name.' feed" href="'.$tag_link.'/feed/"><img src="'.$rss_image.'" alt="tag feed" /></a>';//Tag feed link
        $html .= ' <a href="'.$tag_link.'">'.$tag->name.'</a>';//Tag link

        $html .= '</li>';
    }
    $html .= '</ul>';
    $html .= '</div>';
    return $html;
}