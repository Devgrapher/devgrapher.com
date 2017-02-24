<?php
function naples_post( $atts) 
{
    ?>
<div id='2'>
    <?php
    $actual_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
    $a = shortcode_atts( array(
        'title' => 'My Title',
        'img-width'=>'500',
        'img-height'=>'500',
        'display'=>'yes',
        'title-size'=>'h2',
        'link'=>'li',
        'limit'=>'30',
        'post_number'=>'po'
    ), $atts );
    if($atts['post_number'] && strcmp ($atts['post_number'],'0')==1)
    {
        if($_POST['val'])
        {
            $j=0;
            $current=$_POST['val'];
            $next=$current+$atts['post_number'];
            $pre=$current-$atts['post_number'];
           
            
                      query_posts('category_name='.$atts['title']);
    
 if ( have_posts()) : while ( have_posts() ) : the_post();  $j++;
            
            if( $current<$j && $j<=$next)
            {
                
                       
    
?>
<script>
    var $elem=document.getElementById('2');
    $elem.style.width="90%";
    $elem.style.margin="auto";
    
    
    
</script>
<div id='1'>
    <?php
     if($atts['title-size'])
 {
   echo "<".$atts['title-size'].">".get_the_title( get_the_ID() )."</".$atts['title-size']."><br>";
 }
    else
    {
         echo "<h3>".get_the_title( get_the_ID() )."</h3><br>";
    }
if($atts['display'] && strcmp ( $atts['display'] ,'yes')==0)
{
    if($atts['img-width'] &&  $atts['img-height'])
    {
    echo get_the_post_thumbnail( get_the_ID(),array( $atts['img-width'], $atts['img-height'] ))."<br>";
    }
    else
    {
        echo get_the_post_thumbnail( get_the_ID(),array(600, 600 ))."<br>";
    }
}
    elseif($atts['display'] && strcmp ( $atts['display'] ,'no')==0)
    {
    }
    else
    {
        if($atts['img-width'] &&  $atts['img-height'])
    {
    echo get_the_post_thumbnail( get_the_ID(),array( $atts['img-width'], $atts['img-height'] ))."<br>";
    }
    else
    {
        echo get_the_post_thumbnail( get_the_ID(),array(600, 600 ))."<br>";
    }
    }
    
        ?>
    <div style="color:#484848">
        <?php
        global $userdata;
        get_currentuserinfo();
        $author_name = get_author_name( $userdata->ID ); 
       
          echo "Published on ".get_post_time( 'l, F jS, Y', get_the_ID())." By <a href=".get_author_posts_url($userdata->ID ).">".$author_name."</a><br>";
    ?>
        </div>
    <?php
  $theContent = get_the_content();
$output = preg_replace('/<img[^>]+./','', $theContent);
$output = preg_replace( '/<blockquote>.*<\/blockquote>/', '', $output );
$output = preg_replace( '|\[(.+?)\](.+?\[/\\1\])?|s', '', $output );
    if($atts['limit'])
    {
        $limit = $atts['limit'];
    }
    else
    {
        $limit=40;
    }
$content = explode(' ', $output, $limit);
array_pop($content);
$content = implode(" ",$content)."...";
echo $content."<br>";
    if($atts['link'])
    {
    echo "<a href=".get_permalink (get_the_ID())."> ".$atts['link']." >> </a>";
    }
    else
    {
     echo "<a href=".get_permalink (get_the_ID())."> Read more >> </a>";
    }
    echo "<hr>";
    ?>
</div>
<?php
            }
 endwhile; 
endif; 
     wp_reset_query(); 
            $i=0;
             query_posts('category_name='.$atts['title']);
            if ( have_posts()) : while ( have_posts() ) : the_post(); $i++;
            endwhile;
             endif;
            wp_reset_query(); 
            if($next<$i)
            {
                ?>
    <table style="border: none;">
        <tr style="border: none;">
<td style="border: none; " colspan="3"><form action="<?php $actual_link ?>" method="POST">
 <input type="hidden" name="val" value="<?php echo $pre ?>"/>
 <input type="submit" name="go" value="PREVIOUS"/>
    </form></td>
<td  style="border: none;text-align: right;" colspan="3"><form action="<?php $actual_link ?>" method="POST">
 <input type="hidden" name="val" value="<?php echo $next ?>"/>
 <input type="submit" name="go" value="NEXT"/>
    </form></td>
            </tr>
        </table>
    <?php
            }
                else
                {
                    $pre=$next-$i;
                    $pre=$pre+$atts['post_number'];
                    ?>
<form action="<?php $actual_link ?>" method="POST">
 <input type="hidden" name="val" value="<?php echo $pre ?>"/>
 <input type="submit" name="go" value="PREVIOUS"/>
</form> <?php
                }
            
        }
        else
        {
        query_posts('category_name='.$atts['title'].'&posts_per_page='.$atts['post_number']);
            
    
 if ( have_posts()) : while ( have_posts() ) : the_post(); 
    
?>
<script>
    var $elem=document.getElementById('2');
    $elem.style.width="90%";
    $elem.style.margin="auto";
    
    
    
</script>
<div id='1'>
    <?php
     if($atts['title-size'])
 {
   echo "<".$atts['title-size'].">".get_the_title( get_the_ID() )."</".$atts['title-size']."><br>";
 }
    else
    {
         echo "<h3>".get_the_title( get_the_ID() )."</h3><br>";
    }
if($atts['display'] && strcmp ( $atts['display'] ,'yes')==0)
{
    if($atts['img-width'] &&  $atts['img-height'])
    {
    echo get_the_post_thumbnail( get_the_ID(),array( $atts['img-width'], $atts['img-height'] ))."<br>";
    }
    else
    {
        echo get_the_post_thumbnail( get_the_ID(),array(600, 600 ))."<br>";
    }
}
    elseif($atts['display'] && strcmp ( $atts['display'] ,'no')==0)
    {
    }
    else
    {
        if($atts['img-width'] &&  $atts['img-height'])
    {
    echo get_the_post_thumbnail( get_the_ID(),array( $atts['img-width'], $atts['img-height'] ))."<br>";
    }
    else
    {
        echo get_the_post_thumbnail( get_the_ID(),array(600, 600 ))."<br>";
    }
    }
    
        ?>
    <div style="color:#484848">
        <?php
        global $userdata;
        get_currentuserinfo();
        $author_name = get_author_name( $userdata->ID ); 
       
          echo "Published on ".get_post_time( 'l, F jS, Y', get_the_ID())." By <a href=".get_author_posts_url($userdata->ID ).">".$author_name."</a><br>";
    ?>
        </div>
    <?php
  $theContent = get_the_content();
$output = preg_replace('/<img[^>]+./','', $theContent);
$output = preg_replace( '/<blockquote>.*<\/blockquote>/', '', $output );
$output = preg_replace( '|\[(.+?)\](.+?\[/\\1\])?|s', '', $output );
    if($atts['limit'])
    {
        $limit = $atts['limit'];
    }
    else
    {
        $limit=40;
    }
$content = explode(' ', $output, $limit);
array_pop($content);
$content = implode(" ",$content)."...";
echo $content."<br>";
    if($atts['link'])
    {
    echo "<a href=".get_permalink (get_the_ID())."> ".$atts['link']." >> </a>";
    }
    else
    {
     echo "<a href=".get_permalink (get_the_ID())."> Read more >> </a>";
    }
    echo "<hr>";
    ?>
</div>
<?php
 endwhile; 
endif; 
     wp_reset_query(); 
            $i=0;
             query_posts('category_name='.$atts['title']);
            if ( have_posts()) : while ( have_posts() ) : the_post();$i++;
            endwhile;
             endif;
            wp_reset_query(); 
            if($atts['post_number']<$i)
            {
                ?>
<form action="<?php $actual_link ?>" method="POST">
 <input type="hidden" name="val" value="<?php echo $atts['post_number'] ?>"/>
 <input type="submit" name="age" value="NEXT"/>
</form>
    <?php
            }
        }
    
    }
    else
    {
         query_posts('category_name='.$atts['title']);
    
 if ( have_posts()) : while ( have_posts() ) : the_post(); 
    
?>
<script>
    var $elem=document.getElementById('2');
    $elem.style.width="90%";
    $elem.style.margin="auto";
    
    
    
</script>
<div id='1'>
    <?php
     if($atts['title-size'])
 {
   echo "<".$atts['title-size'].">".get_the_title( get_the_ID() )."</".$atts['title-size']."><br>";
 }
    else
    {
         echo "<h3>".get_the_title( get_the_ID() )."</h3><br>";
    }
if($atts['display'] && strcmp ( $atts['display'] ,'yes')==0)
{
    if($atts['img-width'] &&  $atts['img-height'])
    {
    echo get_the_post_thumbnail( get_the_ID(),array( $atts['img-width'], $atts['img-height'] ))."<br>";
    }
    else
    {
        echo get_the_post_thumbnail( get_the_ID(),array(600, 600 ))."<br>";
    }
}
    elseif($atts['display'] && strcmp ( $atts['display'] ,'no')==0)
    {
    }
    else
    {
        if($atts['img-width'] &&  $atts['img-height'])
    {
    echo get_the_post_thumbnail( get_the_ID(),array( $atts['img-width'], $atts['img-height'] ))."<br>";
    }
    else
    {
        echo get_the_post_thumbnail( get_the_ID(),array(600, 600 ))."<br>";
    }
    }
    
        ?>
    <div style="color:#484848">
        <?php
        global $userdata;
        get_currentuserinfo();
        $author_name = get_author_name( $userdata->ID ); 
       
          echo "Published on ".get_post_time( 'l, F jS, Y', get_the_ID())." By <a href=".get_author_posts_url($userdata->ID ).">".$author_name."</a><br>";
    ?>
        </div>
    <?php
  $theContent = get_the_content();
$output = preg_replace('/<img[^>]+./','', $theContent);
$output = preg_replace( '/<blockquote>.*<\/blockquote>/', '', $output );
$output = preg_replace( '|\[(.+?)\](.+?\[/\\1\])?|s', '', $output );
    if($atts['limit'])
    {
        $limit = $atts['limit'];
    }
    else
    {
        $limit=40;
    }
$content = explode(' ', $output, $limit);
array_pop($content);
$content = implode(" ",$content)."...";
echo $content."<br>";
    if($atts['link'])
    {
    echo "<a href=".get_permalink (get_the_ID())."> ".$atts['link']." >> </a>";
    }
    else
    {
     echo "<a href=".get_permalink (get_the_ID())."> Read more >> </a>";
    }
    echo "<hr>";
    ?>
</div>
<?php
 endwhile; 
endif; 
     wp_reset_query(); 
    }
    ?>
    </div>
<?php
}
?>