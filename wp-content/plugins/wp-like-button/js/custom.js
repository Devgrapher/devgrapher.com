jQuery(document).ready(function($){
 
 
    var custom_uploader;
 
 
    $('#fblbutton_default_upload_image_button').click(function(e) {
 
        e.preventDefault();
 
        //If the uploader object has already been created, reopen the dialog
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }
 
        //Extend the wp.media object
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });
 
        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader.on('select', function() {
            attachment = custom_uploader.state().get('selection').first().toJSON();
            $('#fblb_default_upload_image').val(attachment.url);
            $('#fblb_default_image_preview').attr({src:attachment.url});
            $('#fblb_default_image_preview').show();
            $('#fblb_default_image_preview_remove').show();
            $('#img-msg').css("display", "block");
        });
 
        //Open the uploader dialog
        custom_uploader.open();
 
    });
 
 
});

jQuery(document).ready(function ($) {
    var current_url = window.location;
    var loc = window.location.href,
            index = loc.indexOf('#');

    if (index > 0) {
        current_url = loc.substring(0, index);
    }
    var magic_url = current_url + '&wplikebtn_magic_data=1';
    console.log(magic_url);
    $('#magicsuggest').magicSuggest({
        data: magic_url ,
        ajaxConfig: {
            xhrFields: {
                withCredentials: true,
            }
        }
    });
    $( document ).tooltip({
        content: function() {
        var element = $( this );
            if ( element.is( "[help]" ) ) {
                return '<table class="_4-ss _5k9x"><thead><tr><th>Layout</th><th>Default Sizes</th></tr></thead><tbody class="_5m37" id="u_0_1n"><tr class="row_0"><td><p><code>standard</code></p></td><td><p>Minimum width: 225 pixels.<br> Default width: 450 pixels.<br> Height: 35 pixels (without photos) or 80 pixels (with photos).</p></td></tr><tr class="row_1 _5m29"><td><p><code>box_count</code></p></td><td><p>Minimum width: 55 pixels.<br> Default width: 55 pixels.<br> Height: 65 pixels.</p></td></tr><tr class="row_2"><td><p><code>button_count</code></p></td><td><p>Minimum width: 90 pixels.<br> Default width: 90 pixels.<br> Height: 20 pixels.</p></td></tr><tr class="row_3 _5m29"><td><p><code>button</code></p></td><td><p>Minimum width: 47 pixels.<br> Default width: 47 pixels.<br> Height: 20 pixels.</p></td></tr></tbody></table>';
            }
            if ( element.is( "[help_kid]" ) ) {
                return 'If your web site or online service, or a portion of your service, is directed to children under 13 you must enable this';
            }
        }
        
    });
});


jQuery(document).ready(function ($) {
    
    $('#magicsuggest').magicSuggest({
        // [...] // configuration options
    });
    cfb();
});
jQuery(function () {
    cfb();
});
function fblb_switchonoff(val) {
    var path = jQuery(val).attr("src");
    var file = path.split('/').pop();
    var file2 = path.split(file);
    console.log(file2[0]);
    var on = '';
    var off = '';
    if (file == 'on.png') {
        on = true;
    } else {
        off = true;
    }
    if (off)
    {
        jQuery.post('', {'fblb_switchonoff': 1}, function (e) {
            if (e == 'error') {
                error('error');
            } else {
                jQuery('#fblb_circ').css("background", "#0f0");
                jQuery(val).attr("src", file2[0] + 'on.png');
            }
        });
    }
    if (on) {
        jQuery.post('', {'fblb_switchonoff': 0}, function (e) {
            if (e == 'error') {
                error('error');
            } else {
                jQuery('#fblb_circ').css("background", "#f00");
                jQuery(val).attr("src", file2[0] + 'off.png');
            }
        });
    }
    //alert(val);
}



window.fbAsyncInit = function () {
    FB.init({
        appId: '1577857539092932',
        xfbml: true,
        version: 'v2.1'
    });
};

(function (d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {
        return;
    }
    js = d.createElement(s);
    js.id = id;
    var language = jQuery('#language').val();
    if(language == '' || language == null){language = 'en_US';}
    js.src = "//connect.facebook.net/"+language+"/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
function isNumeric(value) {
    var bool = isNaN(+value);
    bool = bool || (value.indexOf('.') != -1);
    bool = bool || (value.indexOf(",") != -1);
    return !bool;
};
function cfb() {

    var width = jQuery('#width').val();
    if (width < 1) {
        width = 500;
        jQuery('#width').val(width);
    }
    var position = 'center';
    var layout = jQuery('#layout').val();
    var action = jQuery('#action').val();
    var color = jQuery('#color').val();
    var share = jQuery('#share').is(':checked');
    var faces = jQuery('#faces').is(':checked');
    if(layout == 'standard'){jQuery('#wpfblikebutton_faces').show();}else{jQuery('#wpfblikebutton_faces').hide();}
    
    if(layout == 'standard' && width < 225){
        width = 450;
        jQuery('#width').val(width);
    }else if(layout == 'box_count' && width < 55){
        width = 55;
        jQuery('#width').val(width);
    }else if(layout == 'button_count' && width < 90){
        width = 90;
        jQuery('#width').val(width);
    }else if(layout == 'button' && width < 47){
        width = 47;
        jQuery('#width').val(width);
    }
    
    if(share){share = true;}else{share=false;}
    var url = 'http://facebook.com';
    if(jQuery('#entiresite').is(':checked')){url = jQuery('#site_url').val();}
    if(jQuery('#url').is(':checked')){url = jQuery('#url_text').val();}
    if(jQuery('#left').is(':checked')){position = jQuery('#left').val();}
    if(jQuery('#right').is(':checked')){position = jQuery('#right').val();}
    if(jQuery('#center').is(':checked')){position = jQuery('#center').val();}
    console.log(url);
    var token = url.indexOf('http://');
    if(token == -1){token = url.indexOf('https://');}
    if(token == -1){url = 'http://'+url;}
    console.log(position);
    var data = '<div class="fb-like" data-href="'+url+'" data-width="' + width + '" data-layout="' + layout + '" data-action="' + action + '" data-show-faces="'+faces+'" data-share="'+share+'" data-colorscheme="'+color+'"  ></div>';
    jQuery('#u_0_18').html(data);console.log(token);
    jQuery('.fblb_preview').css("text-align", position);
    FB.XFBML.parse();
    return false;
}

 