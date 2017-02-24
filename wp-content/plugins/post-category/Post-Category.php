<?php
/*

Plugin Name:Post Category
Description:Use Shortcode  to Loop Post From specific Categorie.
Version: 1.0
Author: Youssef Bouhalba
Author URI:https://plus.google.com/u/0/101358955779720224466
Plugin URI: https://wordpress.org/plugins/Post-Category/
License: GPLv2
*/
if(!defined('ABSPATH'))
{
    exit;
}
require(plugin_dir_path(__FILE__).'inc/naplespost.php');
add_shortcode('postcat', 'naples_post');
require(plugin_dir_path(__FILE__).'inc/naples_register_post.php');
require(plugin_dir_path(__FILE__).'inc/naples_post_fields.php');

?>