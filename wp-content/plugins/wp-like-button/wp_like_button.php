<?php
/*
  Plugin Name: WP Like button
  Description: WP Like button allows you to add Facebook like button to your wordpress blog.
  Author: <a href="http://crudlab.com/">CRUDLab</a>
  Version: 1.4.11
 */
ini_set('display_errors', 0);
//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
error_reporting(0);
require_once( ABSPATH . "wp-includes/pluggable.php" );
add_action('admin_menu', 'fblb_plugin_setup_menu');
//register_uninstall_hook( __FILE__, 'uninstall_hook');
register_deactivation_hook(__FILE__, 'fblb_uninstall_hook');

function caption_shortcode($atts, $content = null) {
    return '<span class="fblb_caption"></span>';
}

// Add settings link on plugin page
function crudlab_fb_like_button_settings_link($links) {
    $settings_link = '<a href="admin.php?page=facebook-like-button&edit=1">Settings</a>';
    array_unshift($links, $settings_link);
    return $links;
}

$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'crudlab_fb_like_button_settings_link');

add_shortcode('fblike', 'fb_like_button');

function fblb_uninstall_hook() {
    global $wpdb;
    $thetable = $wpdb->prefix . "fblb";
    //Delete any options that's stored also?
    $wpdb->query("DROP TABLE IF EXISTS $thetable");
}

function fblb_plugin_setup_menu() {
    global $wpdb;
    $table = $wpdb->prefix . 'fblb';
    $myrows = $wpdb->get_results("SELECT * FROM $table WHERE id = 1");
    if ($myrows[0]->status == 0) {
        add_menu_page('WP like button', 'WP like button <span id="fblb_circ" class="update-plugins count-1" style="background:#F00"><span class="plugin-count">&nbsp&nbsp</span></span>', 'manage_options', 'facebook-like-button', 'fblb_init', plugins_url("/images/ico.png", __FILE__));
    } else {
        add_menu_page('WP like button', 'WP like button <span id="fblb_circ" class="update-plugins count-1" style="background:#0F0"><span class="plugin-count">&nbsp&nbsp</span></span>', 'manage_options', 'facebook-like-button', 'fblb_init', plugins_url("/images/ico.png", __FILE__));
    }
}

add_filter('wp_head', 'fblb_header');
add_filter('the_content', 'fb_like_button');

function fblb_header() {
    $post_id = get_the_ID();
    global $wpdb;
    $table = $wpdb->prefix . 'fblb';
    $myrows = $wpdb->get_results("SELECT * FROM $table WHERE id = 1");
    $language = $myrows[0]->language;
    $status = $myrows[0]->status;
    $fb_app_id = $myrows[0]->fb_app_id;
    $fb_app_default_image = $myrows[0]->default_image;
    $fb_app_admin = explode(",", $myrows[0]->fb_app_admin);
    if ($status != 0) {
        echo '<meta property="fb:app_id" content="' . $fb_app_id . '">';
        if ($fb_app_default_image != "" && $fb_app_default_image != null) {
            echo '<meta property="og:image" content="' . $fb_app_default_image . '" />';
        }
        foreach ($fb_app_admin as $admin_id) {
            echo '<meta property="fb:admins" content="' . $admin_id . '">';
        }
        ?>
        <div id="fb-root"></div>
        <script>(function (d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id))
                    return;
                js = d.createElement(s);
                js.id = id;
                js.src = "//connect.facebook.net/<?php echo $myrows[0]->language ?>/sdk.js#xfbml=1&version=v2.0";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));</script>
        <?php
    }
}

function fb_like_button($content = NULL) {
    $post_id = get_the_ID();
    global $wpdb;
    $table = $wpdb->prefix . 'fblb';

    $myrows = $wpdb->get_results("SELECT * FROM $table WHERE id = 1");
    $beforeafter = $myrows[0]->beforeafter;
    $where_like = $myrows[0]->where_like;
    $status = $myrows[0]->status;
    $layout = $myrows[0]->layout;
    $action = $myrows[0]->action;
    $color = $myrows[0]->color;
    $display = $myrows[0]->display;
    $except_ids = $myrows[0]->except_ids;
    $language = $myrows[0]->language;
    $url = $myrows[0]->url;
    $mobile = $myrows[0]->mobile;
    $kid = $myrows[0]->kid;
    $width = $myrows[0]->width;
    $str = $content;
    $share = $myrows[0]->share;
    $faces = $myrows[0]->faces;
    $position = $myrows[0]->position;
    if ($share == 1) {
        $share = 'true';
    } else {
        $share = 'false';
    }
    if ($faces == 1) {
        $faces = 'true';
    } else {
        $faces = 'false';
    }
    if ($kid == 1) {
        $kid = 'true';
    } else {
        $kid = 'false';
    }
    if ($where_like == 'eachpage') {
        //$actual_link = get_the_permalink();
        $actual_link = get_permalink();
    } else if ($where_like == 'entiresite') {
        $actual_link = get_site_url();
    } else {
        $actual_link = $url;
    }
    if (!wp_is_mobile()) {
        $fb = '<style>.fb_iframe_widget span{width:460px !important;} .fb_iframe_widget iframe {margin: 0 !important;}        .fb_edge_comment_widget { display: none !important; }</style><div style="width:100%; text-align:' . $position . '"><div class="fb-like" style="width:' . $width . 'px; overflow: hidden !important; " data-href="' . $actual_link . '" data-colorschem"' . $color . '" data-width="' . $width . '" data-layout="' . $layout . '" data-action="' . $action . '" data-show-faces="' . $faces . '" data-share="' . $share . '" kid_directed_site="' . $kid . '"></div></div>';
    } else if ($mobile && wp_is_mobile()) {
        $fb = '<style>.fb-like {overflow: hidden !important;}</style><div style="width:100%; text-align:' . $position . '"><div class="fb-like" style="width:' . $width . 'px" data-href="' . $actual_link . '" data-colorschem"' . $color . '" data-width="' . $width . '" data-layout="' . $layout . '" data-action="' . $action . '" data-show-faces="' . $faces . '" data-share="' . $share . '" kid_directed_site="' . $kid . '"></div></div>
        <br>';
    }



    $width = $myrows[0]->width . 'px';
    if ($status == 0) {
        $str = $content;
    } else {
        if ($content == NULL) {
            $str = $fb;
        }
        if ($display & 2) {
            if (is_page() && !is_front_page()) {
                if ($beforeafter == 'before') {
                    $str = $fb . $content;
                } else {
                    $str = $content . $fb;
                }
            }
        }
        if ($display & 1) {
            if (is_front_page()) {
                if ($beforeafter == 'before') {
                    $str = $fb . $content;
                } else {
                    $str = $content . $fb;
                }
            }
        }
        if ($display & 4) {
            if (is_single()) {
                if ($beforeafter == 'before') {
                    $str = $fb . $content;
                } else {
                    $str = $content . $fb;
                }
            }
        }
    }
    $except_check = true;
    if ($display & 8) {
        @$expect_ids_arrays = split(',', $except_ids);
        foreach ($expect_ids_arrays as $id) {
            if (trim($id) == $post_id) {
                $except_check = false;
            }
        }
    }
    if ($except_check) {
        return $str;
    } else {
        return $content;
    }
}

function contains($needle, $haystack) {
    return strpos($needle, $haystack) !== false;
}

if (isset($_REQUEST['update_fblb'])) {
    global $wpdb;
    $type = '';
    $display = $_REQUEST['display'];
    $display_val = 0;
    foreach ($display as $d) {
        $display_val += @sanitize_text_field($d);
    }
    $beforeafter = @sanitize_text_field($_REQUEST['beforeafter']);
    $except_ids = (isset($_REQUEST['except_ids'])) ? $_REQUEST['except_ids'] : '';
    if ($except_ids != NULL) {
        $except_ids = implode(', ', $except_ids);
    }
    $eachpage = @sanitize_text_field($_REQUEST['eachpage']);
    $each_page_url = @sanitize_text_field($_REQUEST['each_page_url']);
    $layout = @sanitize_text_field($_REQUEST['layout']);
    $action = @sanitize_text_field($_REQUEST['action']);
    $color = @sanitize_text_field($_REQUEST['color']);
    $language = @sanitize_text_field($_REQUEST['language']);
    $width = @sanitize_text_field($_REQUEST['width']);
    $edit_id = @sanitize_text_field($_REQUEST['edit']);
    $share = @sanitize_text_field($_REQUEST['share']);
    $faces = @sanitize_text_field($_REQUEST['faces']);
    $position = @sanitize_text_field($_REQUEST['position']);
    $mobile = @sanitize_text_field($_REQUEST['mobile']);
    $kid = @sanitize_text_field($_REQUEST['kid']);
    $fb_app_id = @sanitize_text_field($_REQUEST['fb_app_id']);
    $fb_app_admin = @sanitize_text_field($_REQUEST['fb_app_admin']);
    $default_image = @sanitize_text_field($_REQUEST['fblb_default_upload_image']);
    ($edit_id == 0 || $edit_id == '') ? $edit_id = 1 : '';
    $ul = '0';
    if ($each_page_url != NULL) {
        if (!contains($each_page_url, 'http://')) {
            if (!contains($each_page_url, 'https://')) {
                $each_page_url = 'http://' . $each_page_url;
            }
        }
    }
    $user_id = $ul;
    $table = $wpdb->prefix . 'fblb';
    $data1 = array(
        'display' => $display_val,
        'width' => $width,
        'beforeafter' => $beforeafter,
        'except_ids' => $except_ids,
        'where_like' => $eachpage,
        'layout' => $layout,
        'action' => $action,
        'color' => $color,
        'language' => $language,
        'url' => $each_page_url,
        'user_id' => $user_id,
        'position' => $position,
        'mobile' => $mobile,
        'kid' => $kid,
        'active' => 1,
        'share' => $share,
        'faces' => $faces,
        'default_image' => $default_image,
        'fb_app_id' => $fb_app_id,
        'fb_app_admin' => $fb_app_admin,
        'last_modified' => current_time('mysql')
    );
    $v = $wpdb->update($table, $data1, array('id' => $edit_id));
    //header('Location:' . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']);
}

if (isset($_REQUEST['fblb_switchonoff'])) {
    global $wpdb;
    $val = $_REQUEST['fblb_switchonoff'];
    $data = array(
        'status' => $val
    );
    $table = $wpdb->prefix . 'fblb';
    if ($wpdb->update($table, $data, array('id' => 1))) {
        echo $val;
    } else {
        echo 'error';
    };
    die;
}
if (isset($_REQUEST['wplikebtn_magic_data'])) {
    $data = array();
    $args = array(
        'post_type' => 'any',
        'post_status' => 'publish',
        'posts_per_page' => -1
    );
    $posts = get_posts($args);
    foreach ($posts as $post) {
        $data[] = array('id' => $post->ID, 'name' => $post->post_title);
    }

    echo json_encode($data);
    exit();
}

function fblb_init() {
    if (!isset($_REQUEST['edit'])) {
        echo '<script>location = location+"&edit=1"</script>';
    }
    global $wpdb;
    add_filter('admin_head', 'fblb_ShowTinyMCE');
    $check = array();
    $setting = array('media_buttons' => false);
    $table = $wpdb->prefix . 'fblb';
    if (!isset($_REQUEST['edit'])) {
        header('Location:' . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'] . '&edit=1');
    }
    if (!(isset($_REQUEST['new']) || isset($_REQUEST['edit']))) {
        $myrows = $wpdb->get_results("SELECT * FROM $table WHERE id = 1");
    } else if (isset($_REQUEST['edit'])) {
        $edit_id = $_REQUEST['edit'];
        $str = "SELECT * FROM $table WHERE id = 1";
        $myrows = $wpdb->get_results($str);
    }
    $data = '';
    $data_array = array();
    if ($myrows[0]->display & 1) {
        $display[1] = 'checked';
    };
    if ($myrows[0]->display & 2) {
        $display[2] = 'checked';
    };
    if ($myrows[0]->display & 4) {
        $display[4] = 'checked';
    };
    if ($myrows[0]->display & 8) {
        $display[8] = 'checked';
    };
    $layout[$myrows[0]->layout] = ' selected="selected"';
    $action[$myrows[0]->action] = ' selected="selected"';
    $color[$myrows[0]->color] = ' selected="selected"';
    $language[$myrows[0]->language] = ' selected="selected"';
    $eachsite[$myrows[0]->where_like] = 'checked';
    $beforeafter[$myrows[0]->beforeafter] = 'checked';
    $position[$myrows[0]->position] = 'checked';
    $mobile[$myrows[0]->mobile] = 'checked';
    $kid[$myrows[0]->kid] = 'checked';
    ?>
    <div id="test-popup" class="fblb_white-popup fblb_mfp-with-anim fblb_ mfp-hide"></div>
    <div class="fblb_container">
        <div class="fblb_row">
            <div class="fblb_plugin-wrap fblb_col-md-12">
                <div class="fblb_plugin-notify">
                    <div class="fblb_forms-wrap">
                        <div class="fblb_colmain">
                            <div class="fblb_what">
                                <div class="fblb_form-types-wrap">
                                    <input type="hidden" name="fblb" value="<?php echo $notify; ?>">
                                    <div class="fblb_clearfix"></div>
                                </div>
                            </div>
                        </div>
                        <div class="fblb_col" style="width:67%; ">
                            <div class="fblb_where">
                                <form class="fblb_inline-form fblb_form-inline" method="post">
                                    <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>">
                                    <input type="hidden" name="site_url" value="<?php echo get_site_url(); ?>" id="site_url">
                                    <div class="fblb_control-group">
                                        <label class="fblb_control-label">Settings</label>
                                        <table border="0" width="100%">
                                            <tr>
                                                <td style="width: 160px; vertical-align: top;text-align: right; padding-right: 15px;">
                                                    <label style="margin-top:8px;">Where to display? </label>
                                                </td>
                                                <td>
                                                    <div class="fblb_form-group">
                                                        <input type="checkbox" id="display1" name="display[]" <?php echo @$display['1']; ?> value="1" class="fblb_form-control fblb_check" style="float:left"><label for="display1">Homepage</label>
                                                        <input type="checkbox" id="display2" name="display[]" <?php echo @$display['2']; ?> value="2" class="fblb_form-control fblb_check" style="float:left"><label for="display2">All pages</label>
                                                        <input type="checkbox" id="display4" name="display[]" <?php echo @$display['4']; ?> value="4" class="fblb_form-control fblb_check" style="float:left"><label for="display4">All posts</label>
                                                        <input type="checkbox" id="display8" onchange="if (this.checked) {
                                                                    jQuery('.fblb_exclude').show(200)
                                                                } else {
                                                                    jQuery('.fblb_exclude').hide(200)
                                                                }" name="display[]" <?php echo @$display['8']; ?> value="8" class="fblb_form-control fblb_check" style="float:left"><label for="display8">Exclude following pages and posts</label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: top;width: 160px; padding-top: 10px;text-align: right; padding-right: 15px;">
                                                    <label class="fblb_exclude" style="display:<?php
                                                if ($myrows[0]->display & 8) {
                                                    echo 'block';
                                                } else {
                                                    echo 'none';
                                                }
                                                ?>">Exclude Page/Post</label>
                                                </td>
                                                <td>
                                                    <div class="fblb_form-group fblb_exclude" style="display:<?php
                                                if ($myrows[0]->display & 8) {
                                                    echo 'block';
                                                } else {
                                                    echo 'none';
                                                }
                                                ?>">
                                                        <div id="magicsuggest" value="[<?php echo $myrows[0]->except_ids; ?>]" name="except_ids[]" style="width:auto !important; background: #fff; border: thin solid #cccccc;"></div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <label style="margin-top:8px;">Enable like button for mobile (Responsive layouts) </label>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td style="width: 160px; vertical-align: top;text-align: right; padding-right: 15px;">
                                                </td>
                                                <td>
                                                    <div class="fblb_form-group fblb_beforeafter">
                                                        <input type="radio" id="mobile0" name="mobile" <?php echo @$mobile['0']; ?> value="0" class="fblb_form-control" style="float:left"><label style="float:left" for="mobile0">No</label>
                                                        <input type="radio" id="mobile1" name="mobile" <?php echo @$mobile['1']; ?> value="1" class="fblb_form-control" style="float:left"><label style="float:left" for="mobile1">Yes</label>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan="2">
                                                    <hr>
                                                </td>
                                            </tr>
                                            <tr class="fblb_manual">
                                                <td style="width: 160px; text-align: right; padding-right: 15px;">
                                                    <label>App ID </label>
                                                </td>
                                                <td>
                                                    <div class="fblb_form-group">   
                                                        <input type="text" placeholder="" onblur="cfb()" name="fb_app_id" id="fb_app_id"  value="<?php echo @$myrows[0]->fb_app_id; ?>" class="fblb_form-control" style="width:40%; float: left; margin-top: -10px; margin-right: 10px;">
                                                        <a href="http://developers.facebook.com/setup/" target="_blank" title="Register Your Site on Facebook">Create new App ID</a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="fblb_manual">
                                                <td style="width: 160px; text-align: right; padding-right: 15px;">
                                                    <label>Admin ID </label>
                                                </td>
                                                <td>
                                                    <div class="fblb_form-group">   
                                                        <input type="text" placeholder="" onblur="cfb()" name="fb_app_admin" id="fb_app_admin"  value="<?php echo @$myrows[0]->fb_app_admin; ?>" class="fblb_form-control" style="width:40%; float: left; margin-top: -10px; margin-right: 10px;">
                                                        <label>User ID or Username who has access to insights.(Comma separated)</label>
                                                    </div>
                                                </td>
                                            </tr>                                            
                                            <tr class="fblb_manual">
                                                <td style="width: 160px; text-align: right; padding-right: 15px;">
                                                    <label>Kid Directed Site?</label>
                                                </td>
                                                <td>
                                                    <div class="fblb_form-group">   
                                                        <div class="fblb_form-group fblb_beforeafter">
                                                            <input type="radio" id="kid0" name="kid" <?php echo @$kid['0']; ?> value="0" class="fblb_form-control" style="float:left"><label style="float:left" for="kid0">No</label>
                                                            <input type="radio" id="kid1" name="kid" <?php echo @$kid['1']; ?> value="1" class="fblb_form-control" style="float:left"><label style="float:left" for="kid1">Yes</label>
                                                            <img src="<?php echo plugins_url("/images/help.png", __FILE__) ?>" style="float:left; margin-top: -10px" help_kid="" title="help_kid">
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>                                       
                                            <tr class="fblb_manual">
                                                <td style="width: 160px; text-align: right; padding-right: 15px;">
                                                    <label>Default Image</label>
                                                </td>
                                                <td>
                                                    <div class="fblb_form-group">   
    <?php
    $image_preview_status = "";
    if (@$myrows[0]->default_image == null || @$myrows[0]->default_image == "") {
        $image_preview_status = "display:none;";
    }
    ?>
                                                        <img src="<?php echo @$myrows[0]->default_image; ?>" style="max-height:70px;<?php echo $image_preview_status; ?>" id="fblb_default_image_preview"> &nbsp;<a href="javascript://void()" onclick="jQuery('#fblb_default_upload_image').val('');
                                                                jQuery('#fblb_default_image_preview').hide();
                                                                jQuery(this).hide();" id="fblb_default_image_preview_remove" style="<?php echo $image_preview_status; ?>">Remove</a><br>
    <?php
    /* if(@$myrows[0]->default_image != null && @$myrows[0]->default_image != ""){
      echo '<br>';
      } */
    //} 
    ?>
                                                        <input type="hidden" name="fblb_default_upload_image" id="fblb_default_upload_image" value="<?php echo @$myrows[0]->default_image; ?>">
                                                        <a href="#" id="fblbutton_default_upload_image_button">Select/Upload Image</a> Default image that will show on Facebook if article/post doesn't have thumbnail, leave empty to use post thumbnail.
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan="2">
                                                    <hr>
                                                </td>
                                            </tr>
                                            <tr class="fblb_manual">
                                                <td style="width: 160px; text-align: right; padding-right: 15px;">
                                                    <label>Shortcode </label>
                                                </td>
                                                <td>
                                                    <div class="fblb_form-group">   
                                                        Use shortcode <input style="width:80px;" type="text" value="[fblike]" onClick="this.setSelectionRange(0, this.value.length);"> to display like button
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="fblb_manual">
                                                <td style="width: 160px; text-align: right; padding-right: 15px; vertical-align: top;">
                                                    <label style="margin-top:10px;">Code Snippet </label>
                                                </td>
                                                <td>
                                                    <div class="fblb_form-group">
                                                        <span>
                                                            Also, you can use following code and paste it in theme files.<br>
                                                            For instance, add following code to header or footer.php to display like button
                                                        </span>
                                                        <input type="text"  onClick="this.setSelectionRange(0, this.value.length);" name="code_snippet" value="<?php echo("<?php echo fb_like_button(); ?>"); ?>" class="fblb_form-control">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <hr>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 160px;">&nbsp;</td>
                                                <td>
                                                    <div class="fblb_form-group fblb_beforeafter">
                                                        <input type="radio" id="before" name="beforeafter" <?php echo @$beforeafter['before']; ?> value="before" class="fblb_form-control" style="float:left"><label style="float:left" for="before">Before</label>
                                                        <input type="radio" id="after" name="beforeafter" <?php echo @$beforeafter['after']; ?> value="after" class="fblb_form-control" style="float:left"><label style="float:left" for="after">After</label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 160px; padding-top: 11px; vertical-align: top;text-align: right; padding-right: 15px;">
                                                    <label>What to like?</label>
                                                </td>
                                                <td>
                                                    <div class="fblb_form-group">
                                                        <table class="fblb_eachpage">
                                                            <tr>
                                                                <td>
                                                                    <input onchange="cfb();" type="radio" id="eachpage" name="eachpage" <?php echo @$eachsite['eachpage']; ?> value="eachpage" class="fblb_form-control" style="float:left"><label style="float:left; font-weight: normal;" for="eachpage">Each page/post will have its own like button</label>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <input onchange="cfb();" type="radio" id="entiresite" name="eachpage" <?php echo @$eachsite['entiresite']; ?> value="entiresite" class="fblb_form-control" style="float:left"><label style="float:left; font-weight: normal;" for="entiresite">Entire Site</label>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <input onchange="cfb();" type="radio" id="url" name="eachpage" <?php echo @$eachsite['url']; ?> value="url" class="fblb_form-control" style="float:left"><label style="float:left" > <input type="text" placeholder="URL to like" onblur="cfb()" name="each_page_url" id="url_text"  value="<?php echo @$myrows[0]->url; ?>" class="fblb_form-control"></label>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>                                                                        
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 160px; text-align: right; padding-right: 15px;">
                                                    <label>Language</label>
                                                </td>
                                                <td>
                                                    <div class="fblb_form-group">
                                                        <select class="fblb_form-control" name="language" id="language">
                                                            <option <?php echo @$language['af_ZA']; ?> value='af_ZA'>Afrikaans</option>
                                                            <option <?php echo @$language['ar_AR']; ?> value='ar_AR'>Arabic</option>
                                                            <option <?php echo @$language['az_AZ']; ?> value='az_AZ'>Azerbaijani</option>
                                                            <option <?php echo @$language['be_BY']; ?> value='be_BY'>Belarusian</option>
                                                            <option <?php echo @$language['bg_BG']; ?> value='bg_BG'>Bulgarian</option>
                                                            <option <?php echo @$language['bn_IN']; ?> value='bn_IN'>Bengali</option>
                                                            <option <?php echo @$language['bs_BA']; ?> value='bs_BA'>Bosnian</option>
                                                            <option <?php echo @$language['ca_ES']; ?> value='ca_ES'>Catalan</option>
                                                            <option <?php echo @$language['cs_CZ']; ?> value='cs_CZ'>Czech</option>
                                                            <option <?php echo @$language['cx_PH']; ?> value='cx_PH'>Cebuano</option>
                                                            <option <?php echo @$language['cy_GB']; ?> value='cy_GB'>Welsh</option>
                                                            <option <?php echo @$language['da_DK']; ?> value='da_DK'>Danish</option>
                                                            <option <?php echo @$language['de_DE']; ?> value='de_DE'>German</option>
                                                            <option <?php echo @$language['el_GR']; ?> value='el_GR'>Greek</option>
                                                            <option <?php echo @$language['en_GB']; ?> value='en_GB'>English (UK)</option>
                                                            <option <?php echo @$language['en_PI']; ?> value='en_PI'>English (Pirate)</option>
                                                            <option <?php echo @$language['en_UD']; ?> value='en_UD'>English (Upside Down)</option>
                                                            <option <?php echo @$language['en_US']; ?> value='en_US'>English (US)</option>
                                                            <option <?php echo @$language['eo_EO']; ?> value='eo_EO'>Esperanto</option>
                                                            <option <?php echo @$language['es_ES']; ?> value='es_ES'>Spanish (Spain)</option>
                                                            <option <?php echo @$language['es_LA']; ?> value='es_LA'>Spanish</option>
                                                            <option <?php echo @$language['et_EE']; ?> value='et_EE'>Estonian</option>
                                                            <option <?php echo @$language['eu_ES']; ?> value='eu_ES'>Basque</option>
                                                            <option <?php echo @$language['fa_IR']; ?> value='fa_IR'>Persian</option>
                                                            <option <?php echo @$language['fb_LT']; ?> value='fb_LT'>Leet Speak</option>
                                                            <option <?php echo @$language['fi_FI']; ?> value='fi_FI'>Finnish</option>
                                                            <option <?php echo @$language['fo_FO']; ?> value='fo_FO'>Faroese</option>
                                                            <option <?php echo @$language['fr_CA']; ?> value='fr_CA'>French (Canada)</option>
                                                            <option <?php echo @$language['fr_FR']; ?> value='fr_FR'>French (France)</option>
                                                            <option <?php echo @$language['fy_NL']; ?> value='fy_NL'>Frisian</option>
                                                            <option <?php echo @$language['ga_IE']; ?> value='ga_IE'>Irish</option>
                                                            <option <?php echo @$language['gl_ES']; ?> value='gl_ES'>Galician</option>
                                                            <option <?php echo @$language['gn_PY']; ?> value='gn_PY'>Guarani</option>
                                                            <option <?php echo @$language['gu_IN']; ?> value='gu_IN'>Gujarati</option>
                                                            <option <?php echo @$language['he_IL']; ?> value='he_IL'>Hebrew</option>
                                                            <option <?php echo @$language['hi_IN']; ?> value='hi_IN'>Hindi</option>
                                                            <option <?php echo @$language['hr_HR']; ?> value='hr_HR'>Croatian</option>
                                                            <option <?php echo @$language['hu_HU']; ?> value='hu_HU'>Hungarian</option>
                                                            <option <?php echo @$language['hy_AM']; ?> value='hy_AM'>Armenian</option>
                                                            <option <?php echo @$language['id_ID']; ?> value='id_ID'>Indonesian</option>
                                                            <option <?php echo @$language['is_IS']; ?> value='is_IS'>Icelandic</option>
                                                            <option <?php echo @$language['it_IT']; ?> value='it_IT'>Italian</option>
                                                            <option <?php echo @$language['ja_JP']; ?> value='ja_JP'>Japanese</option>
                                                            <option <?php echo @$language['ja_KS']; ?> value='ja_KS'>Japanese (Kansai)</option>
                                                            <option <?php echo @$language['jv_ID']; ?> value='jv_ID'>Javanese</option>
                                                            <option <?php echo @$language['ka_GE']; ?> value='ka_GE'>Georgian</option>
                                                            <option <?php echo @$language['kk_KZ']; ?> value='kk_KZ'>Kazakh</option>
                                                            <option <?php echo @$language['km_KH']; ?> value='km_KH'>Khmer</option>
                                                            <option <?php echo @$language['kn_IN']; ?> value='kn_IN'>Kannada</option>
                                                            <option <?php echo @$language['ko_KR']; ?> value='ko_KR'>Korean</option>
                                                            <option <?php echo @$language['ku_TR']; ?> value='ku_TR'>Kurdish</option>
                                                            <option <?php echo @$language['la_VA']; ?> value='la_VA'>Latin</option>
                                                            <option <?php echo @$language['lt_LT']; ?> value='lt_LT'>Lithuanian</option>
                                                            <option <?php echo @$language['lv_LV']; ?> value='lv_LV'>Latvian</option>
                                                            <option <?php echo @$language['mk_MK']; ?> value='mk_MK'>Macedonian</option>
                                                            <option <?php echo @$language['ml_IN']; ?> value='ml_IN'>Malayalam</option>
                                                            <option <?php echo @$language['mn_MN']; ?> value='mn_MN'>Mongolian</option>
                                                            <option <?php echo @$language['mr_IN']; ?> value='mr_IN'>Marathi</option>
                                                            <option <?php echo @$language['ms_MY']; ?> value='ms_MY'>Malay</option>
                                                            <option <?php echo @$language['nb_NO']; ?> value='nb_NO'>Norwegian (bokmal)</option>
                                                            <option <?php echo @$language['ne_NP']; ?> value='ne_NP'>Nepali</option>
                                                            <option <?php echo @$language['nl_NL']; ?> value='nl_NL'>Dutch</option>
                                                            <option <?php echo @$language['nn_NO']; ?> value='nn_NO'>Norwegian (nynorsk)</option>
                                                            <option <?php echo @$language['pa_IN']; ?> value='pa_IN'>Punjabi</option>
                                                            <option <?php echo @$language['pl_PL']; ?> value='pl_PL'>Polish</option>
                                                            <option <?php echo @$language['ps_AF']; ?> value='ps_AF'>Pashto</option>
                                                            <option <?php echo @$language['pt_BR']; ?> value='pt_BR'>Portuguese (Brazil)</option>
                                                            <option <?php echo @$language['pt_PT']; ?> value='pt_PT'>Portuguese (Portugal)</option>
                                                            <option <?php echo @$language['ro_RO']; ?> value='ro_RO'>Romanian</option>
                                                            <option <?php echo @$language['ru_RU']; ?> value='ru_RU'>Russian</option>
                                                            <option <?php echo @$language['si_LK']; ?> value='si_LK'>Sinhala</option>
                                                            <option <?php echo @$language['sk_SK']; ?> value='sk_SK'>Slovak</option>
                                                            <option <?php echo @$language['sl_SI']; ?> value='sl_SI'>Slovenian</option>
                                                            <option <?php echo @$language['sq_AL']; ?> value='sq_AL'>Albanian</option>
                                                            <option <?php echo @$language['sr_RS']; ?> value='sr_RS'>Serbian</option>
                                                            <option <?php echo @$language['sv_SE']; ?> value='sv_SE'>Swedish</option>
                                                            <option <?php echo @$language['sw_KE']; ?> value='sw_KE'>Swahili</option>
                                                            <option <?php echo @$language['ta_IN']; ?> value='ta_IN'>Tamil</option>
                                                            <option <?php echo @$language['te_IN']; ?> value='te_IN'>Telugu</option>
                                                            <option <?php echo @$language['tg_TJ']; ?> value='tg_TJ'>Tajik</option>
                                                            <option <?php echo @$language['th_TH']; ?> value='th_TH'>Thai</option>
                                                            <option <?php echo @$language['tl_PH']; ?> value='tl_PH'>Filipino</option>
                                                            <option <?php echo @$language['tr_TR']; ?> value='tr_TR'>Turkish</option>
                                                            <option <?php echo @$language['uk_UA']; ?> value='uk_UA'>Ukrainian</option>
                                                            <option <?php echo @$language['ur_PK']; ?> value='ur_PK'>Urdu</option>
                                                            <option <?php echo @$language['uz_UZ']; ?> value='uz_UZ'>Uzbek</option>
                                                            <option <?php echo @$language['vi_VN']; ?> value='vi_VN'>Vietnamese</option>
                                                            <option <?php echo @$language['zh_CN']; ?> value='zh_CN'>Simplified Chinese (China)</option>
                                                            <option <?php echo @$language['zh_HK']; ?> value='zh_HK'>Traditional Chinese (Hong Kong)</option>
                                                            <option <?php echo @$language['zh_TW']; ?> value='zh_TW'>Traditional Chinese (Taiwan)</option>

                                                        </select>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 160px; text-align: right; padding-right: 15px;">
                                                    <label>Width</label>
                                                </td>
                                                <td>
                                                    <input onblur="if (!isNumeric(this.value)) {
                                                                alert('Only digits allowed');
                                                                this.focus();
                                                            }" type="text" id="width" placeholder="" style="width:93%; float:left" name="width" value="<?php echo $myrows[0]->width; ?>" class="fblb_form-control">
                                                    <img src="<?php echo plugins_url("/images/help.png", __FILE__) ?>" style="float:right" help="" title="help">
                                                </td>
                                            </tr>

                                            <tr>
                                                <td style="width: 160px; text-align: right; padding-right: 15px;"><label>Position</label></td>
                                                <td>
                                                    <div class="fblb_form-group fblb_beforeafter">
                                                        <input onchange="cfb();" type="radio" id="left" name="position" <?php echo @$position['left']; ?> value="left" class="fblb_form-control" style="float:left;font-weight: normal"><label style="float:left;font-weight: normal" for="left">Left</label>
                                                        <input onchange="cfb();" type="radio" id="middle" name="position" <?php echo @$position['center']; ?> value="center" class="fblb_form-control" style="float:left;font-weight: normal"><label style="float:left;font-weight: normal" for="middle">Center</label>
                                                        <input onchange="cfb();" type="radio" id="right" name="position" <?php echo @$position['right']; ?> value="right" class="fblb_form-control" style="float:left"><label style="float:left;font-weight: normal" for="right">Right</label>
                                                    </div>
                                                </td>
                                            </tr>

                                        </table>
                                        <hr>
                                        <table width="100%">
                                            <tr>
                                                <td colspan="2">
                                                    <label class="fblb_control-label">Preview</label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label style="padding-right:15px; float: right">Layout</label>
                                                </td>
                                                <td>
                                                    <div class="fblb_form-group">
                                                        <select class="fblb_form-control" name="layout" id="layout" onchange="cfb();">
                                                            <option <?php echo @$layout['standard']; ?> value="standard">standard</option>
                                                            <option <?php echo @$layout['box_count']; ?> value="box_count" >box_count</option>
                                                            <option <?php echo @$layout['button_count']; ?> value="button_count">button_count</option>
                                                            <option <?php echo @$layout['button']; ?> value="button">button</option>
                                                        </select>
                                                    </div>
                                                </td>
                                                <td>
                                                    <label style="padding-right:15px; float: right">Action Type</label>
                                                </td>
                                                <td>
                                                    <div class="fblb_form-group">
                                                        <select class="fblb_form-control" name="action" id="action" onchange="cfb();">
                                                            <option <?php echo @$action['like']; ?>  value="like" >like</option>
                                                            <option <?php echo @$action['recommend']; ?>  value="recommend" >recommend</option>
                                                        </select>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <label style="padding-right:15px; float: right">Color</label>
                                                </td>
                                                <td>
                                                    <div class="fblb_form-group">
                                                        <select class="fblb_form-control" name="color" id="color" onchange="cfb();">
                                                            <option <?php echo @$color['light']; ?> value="light">light</option>
                                                            <option <?php echo @$color['dark']; ?> value="dark">dark</option>
                                                        </select>
                                                    </div>
                                                </td>
                                                <td>&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <div class="fblb_form-group" id="wpfblikebutton_faces" style="text-align: center;">
                                                        <input onchange=" cfb()" <?php if ($myrows[0]->faces == 1) {
        echo 'checked';
    } ?> type="checkbox" style="float:left" value="1" name="faces" id="faces"><label style="float:left; line-height: 10px; padding-left: 10px;" for="faces">Show Friends' Faces</label>
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td> 
                                                    <div class="fblb_form-group" style="text-align: center">
                                                        <input onchange=" cfb()" <?php if ($myrows[0]->share == 1) {
                                                        echo 'checked';
                                                    } ?>  type="checkbox" style="float:left" value="1" name="share" id="share"><label style="float:left; line-height: 10px; padding-left: 10px;" for="share">Include Share Button</label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <div id="u_0_18" class="fblb_preview" style="text-align: <?php echo $myrows[0]->position; ?>"></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <hr>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"><button type="submit" name="update_fblb" class="fblb_btn fblb_btn-primary">Save Settings</button></td>
                                                <td colspan="2">
                                                    <div class="fblb_form-group fblb_switch" style="float: right;">
    <?php
    $img = '';
    if ($myrows[0]->status == 0) {
        $img = 'off.png';
    } else {
        $img = 'on.png';
    }
    ?>
                                                        <img onclick="fblb_switchonoff(this)" src="<?php echo plugins_url('/images/' . $img, __FILE__); ?>"> 
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" style="text-align: left;">If you enjoy our plugin, please give it 5 stars. [<a href="https://wordpress.org/support/view/plugin-reviews/wp-like-button" target="_blank">Rate the plugin</a>] .</td>    

                                            </tr>
                                        </table>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="fblb_col fblb_col-adv" style="width:25%;">
                            <div class="fblb_where">
                                <h2 style="text-align:left; line-height: 28px;">   
                                    <a href="http://crudlab.com" target="_blank">CRUDLab</a> has following plugins for you:
                                </h2>
                                <hr>

                                <div>
                                    <div style="font-weight: bold;font-size: 20px; margin-top: 10px;">
                                        CRUDLab Facebook Like Box
                                    </div>
                                    <div style="margin-top:10px; margin-bottom: 8px;">
                                        CRUDLab Facebook Like Box allows you to add Facebook like box to your wordpress blog. It allows webmasters to promote their Pages and embed a simple feed of content from a Page into their WordPress sites.
                                    </div>
                                    <div style="text-align: center;">
                                        <a href="https://wordpress.org/plugins/crudlab-facebook-like-box/" target="_blank" class="fblb_btn fblb_btn-success" style="width:90%; margin-top:5px; margin-bottom: 5px; ">Download</a>
                                    </div>
                                </div>
                                <hr>
                                <div>
                                    <div style="font-weight: bold;font-size: 20px; margin-top: 10px;">
                                        Jazz Popups
                                    </div>
                                    <div style="margin-top:10px; margin-bottom: 8px;">
                                        Jazz Popups allow you to add special announcement, message or offers in form of text, image and video.
                                    </div>
                                    <div style="text-align: center;">
                                        <a href="https://wordpress.org/plugins/jazz-popups/" target="_blank" class="fblb_btn fblb_btn-success" style="width:90%; margin-top:5px; margin-bottom: 5px; ">Download</a>
                                    </div>
                                </div>
                                <hr>
                                <div>
                                    <div style="font-weight: bold;font-size: 20px; margin-top: 10px;">
                                        WP Tweet Plus
                                    </div>
                                    <div style="margin-top:10px; margin-bottom: 8px;">
                                        WP Tweet Plus allows you to add tweet button on your wordpress blog. You can add tweet Button homepage, specific pages and posts.
                                    </div>
                                    <div style="text-align: center;">
                                        <a href="https://wordpress.org/plugins/wp-tweet-plus/" target="_blank" class="fblb_btn fblb_btn-success" style="width:90%; margin-top:5px; margin-bottom: 5px; ">Download</a>
                                    </div>
                                </div>
                            </div>
                            <div class="fblb_where" style="margin-top:15px;">
                                <span>
                                    Your donation helps us make great products
                                </span>
                                <a href="https://www.paypal.com/cgi-bin/webscr?business=billing@purelogics.net&cmd=_xclick" target="_blank">
                                    <img style="width:100%;" src="<?php echo plugins_url('/images/donate.png', __FILE__); ?>">
                                </a>
                            </div>
                        </div>
                        <div class="fblb_col fblb_col-adv">

                        </div>
                        <div class="fblb_clearfix"></div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    </form> 
    <?php
}

//-------------------------------------- database --------------------
global $wpfblike_db_version;
$wpfblike_db_version = '6';

function fblb_install() {
    global $wpdb;
    global $wpfblike_db_version;

    $table_name = $wpdb->prefix . 'fblb';

    $charset_collate = $wpdb->get_charset_collate();

    $wpdb->query("DROP TABLE IF EXISTS $table_name");

    // status: 1=active, 0 unactive
    // display: 1=all other page, 2= home page, 3=all pages

    $sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
                display int, 
                width int,
                beforeafter varchar (25),
                except_ids varchar(255),
                where_like varchar (50),
                layout varchar (50),
                action varchar (50),
                color varchar (50),
                position varchar (50),
                language varchar (50),
                fb_app_id varchar (100),
                fb_app_admin varchar (100),
                url varchar (255),
                default_image varchar (500),
                status int, 
		mobile int,
		kid int,
                user_id int,
                active int,
                share int,
                faces int,
		created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		last_modified datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		UNIQUE KEY id (id)
	) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);

    add_option('wpfblike_db_version', $wpfblike_db_version);
    update_option('wpfblike_db_version', $wpfblike_db_version);
    
    if(get_option('fblb_install') == false){
        add_option('fblb_install', strtotime( "now" ));
    }else{
        update_option('fblb_install', strtotime( "now" ));
    }
}

function myplugin_update_db_check() {
    global $wpfblike_db_version;
    if (get_site_option('wpfblike_db_version') != $wpfblike_db_version) {
        fblb_install();
        fblb_install_data();
    }
}

add_action('plugins_loaded', 'myplugin_update_db_check');

function fblb_install_data() {
    global $wpdb;

    $type = '0';
    $radio_value = 'text';
    $data = 'Congratulations, you just completed the installation. Welcome to Jazz Popup!';

    $table_name = $wpdb->prefix . 'fblb';

    $ul = '0';
    $user_id = $ul;
    $table = $wpdb->prefix . 'fblb';
    $myrows = $wpdb->get_results("SELECT * FROM $table WHERE id = 1");
    if ($myrows == NULL) {
        $wpdb->insert(
                $table_name, array(
            'created' => current_time('mysql'),
            'last_modified' => current_time('mysql'),
            'status' => 1,
            'display' => 3,
            'width' => 55,
            'except_ids' => '',
            'user_id' => $user_id,
            'active' => 1,
            'share' => 1,
            'faces' => 1,
            'mobile' => 1,
            'kid' => 0,
            'position' => 'center',
            'beforeafter' => 'before',
            'where_like' => 'eachpage',
            'layout' => 'box_count',
            'action' => 'like',
            'color' => 'light',
            'language' => 'en_US',
            'url' => ''
                )
        );
    }
}

register_activation_hook(__FILE__, 'fblb_install');
register_activation_hook(__FILE__, 'fblb_install_data');

//--------------------------------------------------------------------
function fblb_my_enqueue($hook) {
    //only for our special plugin admin page
    wp_register_style('fblb_css', plugins_url('/css/fblb_style.css', __FILE__));
    wp_enqueue_style('fblb_css');
    wp_register_style('fblb_magicsuggest-min', plugins_url('/css/magicsuggest-min.css', __FILE__));
    wp_enqueue_style('fblb_magicsuggest-min');
    wp_register_style('fblb_jquery-ui', plugins_url('/css/jquery-ui.css', __FILE__));
    wp_enqueue_style('fblb_jquery-ui');
}

add_action('admin_enqueue_scripts', 'fblb_my_enqueue');
add_action('admin_enqueue_scripts', 'fblb_my_admin_scripts');
add_action('admin_notices', 'fblb_plugin_banner');

function fblb_my_admin_scripts() {
    if (isset($_GET['page']) && $_GET['page'] == 'facebook-like-button') {
        wp_enqueue_media();
        wp_register_script('my-admin-js', plugins_url('/js/custom.js', __FILE__), array('jquery'));
        wp_enqueue_script('my-admin-js');
        wp_register_script('fblb_magicsuggest', plugins_url('/js/magicsuggest-min.js', __FILE__), array('jquery'));
        wp_enqueue_script('fblb_magicsuggest');
        wp_enqueue_script('jquery-ui-tooltip');
    }
}

function fblb_plugin_banner() {
    global $hook_suffix;
    if ('plugins.php' == $hook_suffix) {
        $banner_url = '//ps.w.org/wp-like-button/assets/icon-128x128.png';
        //echo get_option('fblb_install')." - ".strtotime('-1 week');
        if (get_option('fblb_install') != null  && strtotime('-1 week') < get_option('fblb_install'))
            {
        ?>

        <div class="updated" style="padding: 0; margin: 0; border: none; background: none;">
            <div class="wp-like-button-banner_on_plugin_page">
                <div class="icon">
                    <img title="" src="<?php echo esc_attr($banner_url); ?>" alt="" />
                </div>						
                <div class="text">
                    <strong><?php echo 'Thank you for installing WP Like button by CRUDLab'; ?> plugin!</strong><br />
        <?php echo "Let's get started"; ?>: 
                    <a target="_blank" href="<?php echo 'admin.php?page=facebook-like-button&edit=1'; ?>"> Configure Settings</a> 					
                </div>
                
		<form action="" method="post">
                    <button class="notice-dismiss bws_hide_settings_notice" title="<?php echo 'Close'; ?>"></button>
			<input type="hidden" name="bws_hide_settings_notice_<?php echo $plugin_options_name; ?>" value="hide" />
		</form>
            </div>
        </div>              
    <?php
        }
    }
}
?>