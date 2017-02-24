<?php
require(plugin_dir_path(__FILE__).'naples_generate.php');
function naples_add_custom_metabox()
{
    add_meta_box(
        'naples_meta',
        'Post Category',
        'naples_create',
        'postcat'
    );
    
}
add_action('add_meta_boxes','naples_add_custom_metabox');


?>