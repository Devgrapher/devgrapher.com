<?php
/*
  Plugin Name: Category Specific RSS Menu
  Version: v2.0
  Plugin URI: https://www.tipsandtricks-hq.com/wordpress-plugin-for-category-specific-rss-feed-subscription-menu-325
  Author: Tips and Tricks HQ, Ruhul Amin
  Author URI: https://www.tipsandtricks-hq.com/
  Description: A simple Wordpress plugin to add category specific RSS subscription menu into your posts, pages, sidebars.
  License: GPLv2 or later
 */

//Plugin Slug - csr

if (!defined('ABSPATH')){
    exit;//Exit if accessed directly
}

define('CAT_SPEC_RSS_FOLDER', dirname(plugin_basename(__FILE__)));
define('CAT_SPEC_RSS_URL', get_option('siteurl') . '/wp-content/plugins/' . CAT_SPEC_RSS_FOLDER);

$category_specific_rss_version = 2.0;

include_once('csrss-misc-functions.php');
include_once('simple_html_dom.php');

add_option('rss_category_1_name', 'All Topics');
add_option('rss_category_1_link', get_option('siteurl') . '/?feed=rss2');
add_option('rss_widget_title_name', 'Category Specific RSS');
add_option('cat_rss_all_cat', '1');
add_option('cat_rss_all_aut', '0');
add_option('cat_rss_show_post_count', '1');
add_option('cat_rss_show_image_on_left', '1');
add_option('cat_rss_custom', '0');

function wp_category_specific_rss_handler($atts) {
    return show_rss_menu($atts);
}

function show_category_rss_for_all_cats() {  // generate HTML for all categories

    $output = '<div class="cat_specific_rss">';

    if (get_option('cat_rss_show_image_on_left') == '1'){
        $output .= '<ul class="cat_show_image">';
    }
    else{
        $output .= '<ul>';
    }

    if (get_option('cat_rss_show_post_count') == '1') {
        $output .= wp_list_categories('echo=0&orderby=name&show_count=1&title_li=');
    } else {
        $output .= wp_list_categories('echo=0&orderby=name&title_li=');
    }
    $output .= '</ul></div>';
    return $output;
}

function show_author_rss_for_all_auts() {    // generate HTML for all authors

    $output = '<div class="cat_specific_rss">';

    if (get_option('cat_rss_show_image_on_left') == '1')
        $output .= '<ul class="cat_show_image">';
    else
        $output .= '<ul>';


    if (get_option('cat_rss_show_post_count') == '1') {
        $output .= wp_list_authors('echo=0&orderby=name&optioncount=1&title_li=');
    } else {
        $output .= wp_list_authors('echo=0&orderby=name&title_li=');
    }
    $output .= '</ul></div>';
    return $output;
}

function show_rss_menu($atts=array()) {
    $show_all_cat = get_option('cat_rss_all_cat');
    $show_all_aut = get_option('cat_rss_all_aut');
    $show_all_custom = get_option('cat_rss_custom');

    if ($show_all_cat == '1') {                                  //Categories
        // Display Categories and RSS feed for all categories
        $html = show_category_rss_for_all_cats();           // Grab HTML for Caregories
        $html = csr_str_get_html($html);                        // Transform HTML into DOM
        $es = $html->find('li a');
        foreach ($html->find('a') as $e) {
            $e->href .= 'feed/';
        }

        $output = $html;            //Transform DOM object into String
    }

    if ($show_all_aut == '1') {                                //Authors
        // Display Categories and RSS feed for all categories
        $html = show_author_rss_for_all_auts();           // Grab HTML for Authors
        $html = csr_str_get_html($html);                   // Transform HTML into DOM
        $es = $html->find('li a');
        foreach ($html->find('a') as $e) {
            $e->href .= 'feed/';
        }

        $output .= $html;            //Transform DOM object into String
    }

    if ($show_all_custom == '1') {
        $output .= show_specific_categories();
    }

    return $output;
}

function show_specific_categories() {
    $show_count = get_option('cat_rss_show_post_count');

    $rss_cat_1_name_value = get_option('rss_category_1_name');
    $rss_cat_1_link_value = get_option('rss_category_1_link');

    $rss_cat_2_name_value = get_option('rss_category_2_name');
    $rss_cat_2_link_value = get_option('rss_category_2_link');

    $rss_cat_3_name_value = get_option('rss_category_3_name');
    $rss_cat_3_link_value = get_option('rss_category_3_link');

    $rss_cat_4_name_value = get_option('rss_category_4_name');
    $rss_cat_4_link_value = get_option('rss_category_4_link');

    $rss_cat_5_name_value = get_option('rss_category_5_name');
    $rss_cat_5_link_value = get_option('rss_category_5_link');

    $rss_cat_6_name_value = get_option('rss_category_6_name');
    $rss_cat_6_link_value = get_option('rss_category_6_link');

    $rss_cat_7_name_value = get_option('rss_category_7_name');
    $rss_cat_7_link_value = get_option('rss_category_7_link');

    $rss_cat_8_name_value = get_option('rss_category_8_name');
    $rss_cat_8_link_value = get_option('rss_category_8_link');


    $output = '<div class="cat_specific_rss">';

    if (get_option('cat_rss_show_image_on_left') == '1')
        $output .= '<ul class="cat_show_image">';
    else
        $output .= '<ul>';

    // Display configured categories and their RSS menu
    if (!empty($rss_cat_1_name_value) && !empty($rss_cat_1_link_value)) {
        $output .= '<li>';
        $cat_obj = get_term_by('name', $rss_cat_1_name_value, 'category');
        $output .= " <a href=\"$rss_cat_1_link_value\">$rss_cat_1_name_value</a>";
        if ($show_count == '1' && $cat_obj->count) {
            $output .= ' (' . $cat_obj->count . ')';
        }
        $output .= '</li>';
    }

    if (!empty($rss_cat_2_name_value) && !empty($rss_cat_2_link_value)) {
        $output .= '<li>';
        $cat_obj = get_term_by('name', $rss_cat_2_name_value, 'category');
        $output .= " <a href=\"$rss_cat_2_link_value\">$rss_cat_2_name_value</a>";
        if ($show_count == '1' && $cat_obj->count) {
            $output .= ' (' . $cat_obj->count . ')';
        }
        $output .= '</li>';
    }

    if (!empty($rss_cat_3_name_value) && !empty($rss_cat_3_link_value)) {
        $output .= '<li>';
        $cat_obj = get_term_by('name', $rss_cat_3_name_value, 'category');
        $output .= " <a href=\"$rss_cat_3_link_value\">$rss_cat_3_name_value</a>";
        if ($show_count == '1' && $cat_obj->count) {
            $output .= ' (' . $cat_obj->count . ')';
        }
        $output .= '</li>';
    }
    if (!empty($rss_cat_4_name_value) && !empty($rss_cat_4_link_value)) {
        $output .= '<li>';
        $cat_obj = get_term_by('name', $rss_cat_4_name_value, 'category');
        $output .= " <a href=\"$rss_cat_4_link_value\">$rss_cat_4_name_value</a>";
        if ($show_count == '1' && $cat_obj->count) {
            $output .= ' (' . $cat_obj->count . ')';
        }
        $output .= '</li>';
    }
    if (!empty($rss_cat_5_name_value) && !empty($rss_cat_5_link_value)) {
        $output .= '<li>';
        $cat_obj = get_term_by('name', $rss_cat_5_name_value, 'category');
        $output .= " <a href=\"$rss_cat_5_link_value\">$rss_cat_5_name_value</a>";
        if ($show_count == '1' && $cat_obj->count) {
            $output .= ' (' . $cat_obj->count . ')';
        }
        $output .= '</li>';
    }
    if (!empty($rss_cat_6_name_value) && !empty($rss_cat_6_link_value)) {
        $output .= '<li>';
        $cat_obj = get_term_by('name', $rss_cat_6_name_value, 'category');
        $output .= " <a href=\"$rss_cat_6_link_value\">$rss_cat_6_name_value</a>";
        if ($show_count == '1' && $cat_obj->count) {
            $output .= ' (' . $cat_obj->count . ')';
        }
        $output .= '</li>';
    }
    if (!empty($rss_cat_7_name_value) && !empty($rss_cat_7_link_value)) {
        $output .= '<li>';
        $cat_obj = get_term_by('name', $rss_cat_7_name_value, 'category');
        $output .= " <a href=\"$rss_cat_7_link_value\">$rss_cat_7_name_value</a>";
        if ($show_count == '1' && $cat_obj->count) {
            $output .= ' (' . $cat_obj->count . ')';
        }
        $output .= '</li>';
    }
    if (!empty($rss_cat_8_name_value) && !empty($rss_cat_8_link_value)) {
        $output .= '<li>';
        $cat_obj = get_term_by('name', $rss_cat_8_name_value, 'category');
        $output .= " <a href=\"$rss_cat_8_link_value\">$rss_cat_8_name_value</a>";
        if ($show_count == '1' && $cat_obj->count) {
            $output .= ' (' . $cat_obj->count . ')';
        }
        $output .= '</li>';
    }

    $output .= '</ul></div>';
    return $output;
}

function category_specific_rss_process($content) { //what does this do?
    if (strpos($content, "<!-- category_specific_rss_menu -->") !== FALSE) {
        $content = preg_replace('/<p>\s*<!--(.*)-->\s*<\/p>/i', "<!--$1-->", $content);
        $content = str_replace('<!-- category_specific_rss_menu -->', show_rss_menu(), $content);
    }
    return $content;
}

// Displays Category Specific RSS Options menu
function add_category_specific_rss_option_page() {
    if (function_exists('add_options_page')) {
        add_options_page('Category Specific RSS', 'Category Specific RSS', 'manage_options', __FILE__, 'category_specific_option_page');
    }
}

function category_specific_option_page() {

    global $category_specific_rss_version;

    if (isset($_POST['info_update'])) {
        echo '<div id="message" class="updated fade"><p><strong>';

        update_option('rss_widget_title_name', (string) $_POST["rss_widget_title_name"]);

        update_option('cat_rss_all_cat', ($_POST['cat_rss_all_cat'] == '1') ? '1' : '-1' );
        update_option('cat_rss_all_aut', ($_POST['cat_rss_all_aut'] == '1') ? '1' : '-1' );
        update_option('cat_rss_show_post_count', ($_POST['cat_rss_show_post_count'] == '1') ? '1' : '-1' );
        update_option('cat_rss_show_image_on_left', ($_POST['cat_rss_show_image_on_left'] == '1') ? '1' : '-1' );

        update_option('cat_rss_custom', ($_POST['cat_rss_custom'] == '1') ? '1' : '-1' );

        update_option('rss_category_1_name', (string) $_POST["rss_category_1_name"]);
        update_option('rss_category_1_link', (string) $_POST["rss_category_1_link"]);

        update_option('rss_category_2_name', (string) $_POST["rss_category_2_name"]);
        update_option('rss_category_2_link', (string) $_POST["rss_category_2_link"]);

        update_option('rss_category_3_name', (string) $_POST["rss_category_3_name"]);
        update_option('rss_category_3_link', (string) $_POST["rss_category_3_link"]);

        update_option('rss_category_4_name', (string) $_POST["rss_category_4_name"]);
        update_option('rss_category_4_link', (string) $_POST["rss_category_4_link"]);

        update_option('rss_category_5_name', (string) $_POST["rss_category_5_name"]);
        update_option('rss_category_5_link', (string) $_POST["rss_category_5_link"]);

        update_option('rss_category_6_name', (string) $_POST["rss_category_6_name"]);
        update_option('rss_category_6_link', (string) $_POST["rss_category_6_link"]);

        update_option('rss_category_7_name', (string) $_POST["rss_category_7_name"]);
        update_option('rss_category_7_link', (string) $_POST["rss_category_7_link"]);

        update_option('rss_category_8_name', (string) $_POST["rss_category_8_name"]);
        update_option('rss_category_8_link', (string) $_POST["rss_category_8_link"]);

        echo 'Options Updated!';
        echo '</strong></p></div>';
    }
    ?>

    <div class=wrap>
        <div id="poststuff"><div id="post-body">

                <h2>Category Specific RSS Menu v <?php echo $category_specific_rss_version; ?></h2>

                <p>For detailed documentation, information and updates, please visit the plugin page using the following link:<br />
                    <a href="https://www.tipsandtricks-hq.com/wordpress-plugin-for-category-specific-rss-feed-subscription-menu-325" target="_blank">https://www.tipsandtricks-hq.com</a></p>

                <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
                    <input type="hidden" name="info_update" id="info_update" value="true" />

                    <div class="postbox">
                        <h3><label for="title">Usage Details</label></h3>
                        <div class="inside">
                            <p>You can use this plugin to add category specific RSS items into your posts, pages, sidebars.</p>
                            <p>First, select some options from the following section. Then use one of the following methods to show the RSS feed menu on you site's front-end.</p>
                            <ol>
                                <li>Add the <strong>Category Specific RSS</strong> Widget to your sidebar from the Widget menu</li>
                                <li>Add the shortcode <strong>[category_specific_rss_menu]</strong> to your posts or pages</li>
                                <li>Call the function from template files: <strong>&lt;?php echo show_rss_menu(); ?&gt;</strong><br /></li>
                            </ol>
                        </div></div>

                    <div class="postbox">
                        <h3><label for="title">Category Specific RSS Menu Options</label></h3>
                        <div class="inside">

                            <strong>Category Specific RSS Widget Title</strong>
                            <input name="rss_widget_title_name" type="text" size="30" value="<?php echo get_option('rss_widget_title_name'); ?>"/>
                            <br /><br />


                            <strong>Show Category RSS for All Categories :</strong>
                            <input name="cat_rss_all_cat" type="checkbox"<?php if (get_option('cat_rss_all_cat') != '-1') echo ' checked="checked"'; ?> value="1"/>
                            <i> If checked the plugin will display category specific RSS for all categories.</i>
                            <br /><br />

                            <strong>Show Author RSS for All Authors :</strong>
                            <input name="cat_rss_all_aut" type="checkbox"<?php if (get_option('cat_rss_all_aut') != '-1') echo ' checked="checked"'; ?> value="1"/>
                            <i> If checked the plugin will display category specific RSS for all categories.</i>
                            <br /><br />

                            <strong>Show Post Count for Each Category or Author:</strong>
                            <input name="cat_rss_show_post_count" type="checkbox"<?php if (get_option('cat_rss_show_post_count') != '-1') echo ' checked="checked"'; ?> value="1"/>
                            <i> Toggles the display of the current count of posts in each category.</i>
                            <br /><br />

                            <strong>Show RSS Feed Image:</strong>
                            <input name="cat_rss_show_image_on_left" type="checkbox"<?php if (get_option('cat_rss_show_image_on_left') != '-1') echo ' checked="checked"'; ?> value="1"/>
                            <i> If checked the RSS feed image will be displayed on the left side of the category name.</i>
                            <br /><br />		



                            <strong>Show Custom RSS feeds below:</strong>
                            <input name="cat_rss_custom"  type="checkbox"<?php if (get_option('cat_rss_custom') != '-1') echo ' checked="checked"'; ?> value="1"/>
                            <i> If checked the plugin will display the custom categorys listed below, if they are filled out. Custom Feeds can be entered here. If feed names correspond to catagories names, a post count will be displayed if the option is chosen from above.</i>
                            <br /><br />



                            <table width="100%" border="0" cellspacing="0" cellpadding="6">

                                <tr valign="top"><td width="15%" align="left">
                                        <strong>Category 1 Name: </strong>
                                    </td><td align="left">
                                        <input name="rss_category_1_name" type="text" size="20" value="<?php echo get_option('rss_category_1_name'); ?>"/>
                                        <strong>RSS Link: </strong><input name="rss_category_1_link" type="text" size="40" value="<?php echo get_option('rss_category_1_link'); ?>"/>
                                    </td></tr>

                                <tr valign="top"><td width="15%" align="left">
                                        <strong>Category 2 Name: </strong>
                                    </td><td align="left">
                                        <input name="rss_category_2_name" type="text" size="20" value="<?php echo get_option('rss_category_2_name'); ?>"/>
                                        <strong>RSS Link: </strong><input name="rss_category_2_link" type="text" size="40" value="<?php echo get_option('rss_category_2_link'); ?>"/>
                                    </td></tr>

                                <tr valign="top"><td width="15%" align="left">
                                        <strong>Category 3 Name: </strong>
                                    </td><td align="left">
                                        <input name="rss_category_3_name" type="text" size="20" value="<?php echo get_option('rss_category_3_name'); ?>"/>
                                        <strong>RSS Link: </strong><input name="rss_category_3_link" type="text" size="40" value="<?php echo get_option('rss_category_3_link'); ?>"/>
                                    </td></tr>

                                <tr valign="top"><td width="15%" align="left">
                                        <strong>Category 4 Name: </strong>
                                    </td><td align="left">
                                        <input name="rss_category_4_name" type="text" size="20" value="<?php echo get_option('rss_category_4_name'); ?>"/>
                                        <strong>RSS Link: </strong><input name="rss_category_4_link" type="text" size="40" value="<?php echo get_option('rss_category_4_link'); ?>"/>
                                    </td></tr>

                                <tr valign="top"><td width="15%" align="left">
                                        <strong>Category 5 Name: </strong>
                                    </td><td align="left">
                                        <input name="rss_category_5_name" type="text" size="20" value="<?php echo get_option('rss_category_5_name'); ?>"/>
                                        <strong>RSS Link: </strong><input name="rss_category_5_link" type="text" size="40" value="<?php echo get_option('rss_category_5_link'); ?>"/>
                                    </td></tr>

                                <tr valign="top"><td width="15%" align="left">
                                        <strong>Category 6 Name: </strong>
                                    </td><td align="left">
                                        <input name="rss_category_6_name" type="text" size="20" value="<?php echo get_option('rss_category_6_name'); ?>"/>
                                        <strong>RSS Link: </strong><input name="rss_category_6_link" type="text" size="40" value="<?php echo get_option('rss_category_6_link'); ?>"/>
                                    </td></tr>

                                <tr valign="top"><td width="15%" align="left">
                                        <strong>Category 7 Name: </strong>
                                    </td><td align="left">
                                        <input name="rss_category_7_name" type="text" size="20" value="<?php echo get_option('rss_category_7_name'); ?>"/>
                                        <strong>RSS Link: </strong><input name="rss_category_7_link" type="text" size="40" value="<?php echo get_option('rss_category_7_link'); ?>"/>
                                    </td></tr>

                                <tr valign="top"><td width="15%" align="left">
                                        <strong>Category 8 Name: </strong>
                                    </td><td align="left">
                                        <input name="rss_category_8_name" type="text" size="20" value="<?php echo get_option('rss_category_8_name'); ?>"/>
                                        <strong>RSS Link: </strong><input name="rss_category_8_link" type="text" size="40" value="<?php echo get_option('rss_category_8_link'); ?>"/>
                                    </td></tr>

                            </table>
                        </div></div>

                    <strong>Note:</strong><br />
                    RSS Link of a particular category is usually the category URL followed by "/?feed=rss2" or "/feed" without the Quotes.<br />
                    e.g. http://www.tipsandtricks-hq.com/category/web-development/wordpress/?feed=rss2<br />
                    or http://www.tipsandtricks-hq.com/category/web-development/wordpress/feed

                    <div class="submit">
                        <input type="submit" name="info_update" class="button-primary" value="<?php _e('Update options'); ?> &raquo;" />
                    </div>

                </form>

                <div style="background: none repeat scroll 0 0 #FFF6D5;border: 1px solid #D1B655;color: #3F2502;margin: 10px 0;padding: 5px 5px 5px 10px;text-shadow: 1px 1px #FFFFFF;">	
                    <p>If you like this plugin then give it a <a href="http://wordpress.org/support/view/plugin-reviews/category-specific-rss-feed-menu" target="_blank">good rating</a></p>
                </div>

            </div></div>
    </div><?php
}

function show_category_rss_widget($args) {
    extract($args);
    $rss_widget_title_name_value = get_option('rss_widget_title_name');

    echo $before_widget;
    echo $before_title . $rss_widget_title_name_value . $after_title;
    echo show_rss_menu();
    echo $after_widget;
}

function category_rss_widget_control() {
    ?>
    <p>
    <? _e("Set the Plugin Settings from the Settings menu"); ?>
    </p>
    <?php
}

function widget_category_spec_rss_init() {
    $widget_options = array('classname' => 'widget_cat_spec_rss', 'description' => __("Display Category Specific RSS Menu."));
    wp_register_sidebar_widget('category_rss_widgets', __('Category Specific RSS'), 'show_category_rss_widget', $widget_options);
    wp_register_widget_control('category_rss_widgets', __('Category Specific RSS'), 'category_rss_widget_control');
}

function wp_category_specific_rss_head_content() {
    echo '<link type="text/css" rel="stylesheet" href="' . CAT_SPEC_RSS_URL . '/wp_cat_rss_style.css" />' . "\n";
}

add_filter('the_content', 'category_specific_rss_process');

add_action('init', 'widget_category_spec_rss_init');
add_action('wp_head', 'wp_category_specific_rss_head_content');
add_action('admin_menu', 'add_category_specific_rss_option_page');

add_shortcode('category_specific_rss_menu', 'wp_category_specific_rss_handler');
add_shortcode('tag_specific_rss_menu', 'wp_tag_specific_rss_handler');
add_shortcode('tag_specific_rss_cloud', 'wp_tag_specific_rss_cloud_handler');

add_shortcode('post_specific_tag_rss', 'post_specific_tag_rss_handler');