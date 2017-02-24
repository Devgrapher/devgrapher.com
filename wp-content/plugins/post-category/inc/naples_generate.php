<?php

function naples_create($post)
{
    wp_nonce_field(basename( __FILE__),'naples_post_nonce');
    $naples_stored_meta=get_post_meta($post->ID);
  $args = array(
	'type'                     => 'post',
	'child_of'                 => 0,
	'parent'                   => '',
	'orderby'                  => 'name',
	'order'                    => 'ASC',
	'hide_empty'               => 1,
	'hierarchical'             => 1,
	'exclude'                  => '',
	'include'                  => '',
	'number'                   => '',
	'taxonomy'                 => 'category',
	'pad_counts'               => false 

); 

    ?>
<div>
    <span style="float:right">By <a href='https://plus.google.com/u/0/101358955779720224466'>Youssef BOUHALBA</a></span>
    <table>
        
       
    <tr>
        <td>Categorie Names:</td>
        <td>
            <select name="category" id='cat'>
                <?php
    $categories = get_categories( $args );
    foreach ($categories as $category) {
	echo "<option value=".$category->cat_name.">".$category->cat_name ."</option>";
    }
  
    ?>
               
             </select>
        </td>
    </tr>
    <tr>
        <td>Ttitle size:</td>
        <td>
            <select name="nom" id='t'>
             
               <option value="h1">H1 </option>
               <option value="h2">H2</option>
               <option value="h3">H3</option>
               <option value="h4">H4 </option>
               
             </select>
        </td>
        </tr>
        <tr>
            <td>Display Image:</td><td><input id='d1' type="radio" name="img" value="yes" checked>YES 
                                       <input id='d2' type="radio" name="img" value="no">No</td>
        </tr>
        <tr>
        <td>Image Width:</td> <td> <input id='w' name="ww" atype="number" step="1" value="500" min="200" max="1024"> </td>
            </tr>
         <tr>
        <td>Image Height:</td> <td> <input id='h' name="hh" type="number" step="1" value="300" min="200" max="1024"> </td>
            </tr>
        <tr>
        <td>Posts Number:</td> <td> <input id='n' name="nn" type="number" step="1" value="0" min="0" max="100"> </td>  
            <td>0 Value to display all post in one Page </td>
       
    
          <tr>
        <td>word Description Per Post:</td> <td> <input id='c' name="cc" type="number" step="1" value="20" min="2" max="500"> </td>
            </tr>
          <tr>
        <td>Read More Edited:</td> <td> <input id='r' name="rr" type="text" Value="Read More"> </td>
            </tr>
        <tr>
            <td></td><td><input id='sbmt' type="button" value="Greate Shortcout"></td>
        </tr>
        
        
    </table>
    <hr>
   <span style="color:red;font-size:20px">Copy Shortcout to Any Page</span>
    <hr>
    <input type="text" id='short' name="shortt" value="" size="100">

</div>
<script>
    var title_size=document.getElementById('t');
     var imgw=document.getElementById('w');
    var imgh=document.getElementById('h');
    var postn=document.getElementById('n');
     var cc=document.getElementById('c');
    var read=document.getElementById('r');
    var img1=document.getElementById('d1');
     var img2=document.getElementById('d2');
    var cat=document.getElementById('cat');
    var cout=document.getElementById('short');
    <?php if(!empty($naples_stored_meta['nom'])) 
    {
        if(strcmp($naples_stored_meta['nom'][0],'h1')==0)
        {
            ?>
            title_size[0].selected=true;
    
    <?php
        }
            else if(strcmp($naples_stored_meta['nom'][0],'h2')==0)
            {
                ?>
                
                title_size[1].selected=true;
    <?php
            }
        else if(strcmp($naples_stored_meta['nom'][0],'h3')==0)
            {
            ?>
                title_size[2].selected=true;
    <?php
            }
        else
        {
            ?>
            title_size[3].selected=true;
    <?php
        }
    }
 
    ?>
    <?php 
    if(!empty($naples_stored_meta['ww']))
    {
        ?>
    imgw.value= "<?php echo $naples_stored_meta['ww'][0];?>";
    <?php
    }
          if(!empty($naples_stored_meta['hh']))
    {
        ?>
    imgh.value= "<?php echo $naples_stored_meta['hh'][0];?>";
    <?php
              
    }
 if(!empty($naples_stored_meta['nn']))
    {
        ?>
    postn.value= "<?php echo $naples_stored_meta['nn'][0];?>";
    <?php
       }
 if(!empty($naples_stored_meta['cc']))
    {
        ?>
    cc.value= "<?php echo $naples_stored_meta['cc'][0];?>";
    <?php
    }
        if(!empty($naples_stored_meta['rr']))
    {
        ?>
    read.value= "<?php echo $naples_stored_meta['rr'][0];?>";
    <?php
    } 
    if(!empty($naples_stored_meta['img'])) 
    {
       
        if($naples_stored_meta['img'][0]=='yes')
        {
        
        ?>
        img1.checked=true;
    <?php
    }
        if($naples_stored_meta['img'][0]=='no')
        {
        
        ?>
        img2.checked=true;
    <?php
    }
     
    }
    if(!empty($naples_stored_meta['shortt'])) 
    {
        
           ?>
       cout.value='[postcat title='+'"'+"<?php echo $naples_stored_meta['category'][0]?>"+'"';
       cout.value+=' title-size='+'"'+title_size.value+'"';
    <?php
       if(img1.checked)
       {
           ?>
           cout.value+=' display='+'"'+img1.value+'"';
           cout.value+=' img-width='+'"'+imgw.value+'"';
            cout.value+=' img-height='+'"'+imgh.value+'"';
    <?php
       }
        else
        {
            ?>
       cout.value+=' display='+'"'+img2.value+'"';
        
        <?php
        }
        ?>
        cout.value+=' post_number='+'"'+postn.value+'"';
        cout.value+=' limit='+'"'+cc.value+'"';
        cout.value+=' link='+'"'+read.value+'"]';
    <?php
    }
        
   
    ?>
    cout.style.background="#c4eeff";
   
   cout.style.margin= "0px 0px 0px 50px";
    
    var submit=document.getElementById('sbmt');
    submit.style.background="#c4eeff";
    submit.onclick=function ()
    {
       cout.value='[postcat title='+'"'+cat.value+'"';
       cout.value+=' title-size='+'"'+title_size.value+'"';
       if(img1.checked)
       {
           cout.value+=' display='+'"'+img1.value+'"';
           cout.value+=' img-width='+'"'+imgw.value+'"';
            cout.value+=' img-height='+'"'+imgh.value+'"';
       }
        else
        {
       cout.value+=' display='+'"'+img2.value+'"';
        }
        cout.value+=' post_number='+'"'+postn.value+'"';
        cout.value+=' limit='+'"'+cc.value+'"';
        cout.value+=' link='+'"'+read.value+'"]';
        
            
        
    }
</script>
<?php
}
function naples_meta_save($post_id)
{
    $is_autosave=wp_is_post_autosave($post_id);
    $is_revision=wp_is_post_revision($post_id);
    $is_valid_nonce=(isset($_POST['naples_post_nonce'])&& wp_verify_nonce($_POST['naples_post_nonce'],basename( __FILE__ ))) ? 'true':'false';
    if( $is_autosave || $is_revision || !$is_valid_nonce)
    {
        return;
    }
    if(isset($_POST['nom']))
    {
        update_post_meta($post_id,'nom',sanitize_text_field($_POST['nom']));
    }
    if(isset($_POST['ww']))
    {
        update_post_meta($post_id,'ww',sanitize_text_field($_POST['ww']));
    }
    if(isset($_POST['hh']))
    {
        update_post_meta($post_id,'hh',sanitize_text_field($_POST['hh']));
    }
    if(isset($_POST['nn']))
    {
        update_post_meta($post_id,'nn',sanitize_text_field($_POST['nn']));
    }
    if(isset($_POST['cc']))
    {
        update_post_meta($post_id,'cc',sanitize_text_field($_POST['cc']));
    }
      if(isset($_POST['rr']))
    {
        update_post_meta($post_id,'rr',sanitize_text_field($_POST['rr']));
    }
    if(isset($_POST['img']))
    {
        update_post_meta($post_id,'img',sanitize_text_field($_POST['img']));
    }
  
     if(isset($_POST['shortt']))
    {
        update_post_meta($post_id,'shortt',sanitize_text_field($_POST['shortt']));
    }
    if(isset($_POST['category']))
    {
        update_post_meta($post_id,'category',sanitize_text_field($_POST['category']));
    }
                

        
   
}
add_action('save_post','naples_meta_save');
?>