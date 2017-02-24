<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?> 
<div class="cpsp-wrap"> 
<div class="col-md-11">
<div id="icon-options-general" class="icon32"><br/></div>
<h3><span class="glyphicon glyphicon-asterisk"></span>Add New Slides</h3>
<div id="dashboard-widgets-container">
    <div class="postbox-container" id="main-container" style="width:75%;margin-bottom: 2%;">
        <p>							
        <b><?php _e('Note', CPSP_SLIDER_SLUG) ?></b> : <?php _e('Selected Posts featured images will be displayed in slider.', CPSP_SLIDER_SLUG) ?> 
        </p>
    </div>     
</div>
<div class="cpsp-overview">

<div id="cpsp_message" class="updated" style="display:none;"></div>
<?php
global $wpdb;
$cpsp_table = $wpdb->prefix ."cpsp_slides";
$categories = cpsp_load_section_Categories();
$slides_record = array();
$slides_record = $wpdb->get_results("SELECT * FROM ".$cpsp_table);
if(count($slides_record) > 0){
?>
 <form method="post" enctype="multipart/form-data" id="ajaxform">
 <input type="hidden" name="cpsp-ajax-nonce" id="cpsp-ajax-nonce" value="<?php echo wp_create_nonce( 'cpsp-ajax-nonce' ); ?>" />
 <input type="hidden" name="num_tab_sections" id="num_tab_sections" value="<?php echo count($slides_record); ?>"/>
        <div class="form-horizontal">
       	<fieldset>
    	<legend>Add Category tabs along with post slides</legend>          
          <!-------Sections Start ---------->
          <div style="width:100%;" id="section_div">          
          <?php
		  $t = 1;
		  foreach($slides_record as $slidesData){
		  ?>
          <div class="row new_cat_block" id="new_cat_block_<?php echo $t; ?>">
          <div class="sec_name">Section <?php echo $t; ?></div>
          <div class="topdiv">
           	<div class="col-md-3">
            <label for="category">Category <?php echo $t; ?></label>
          	</div>
            <div class="col-md-5">
               <select name="category_id<?php echo $t; ?>" id= "category_id<?php echo $t; ?>" data-catid="<?php echo $t; ?>" class="form-control pcategories">
                <option value="0">Select Category</option>
                <?php
                if(count($categories) > 0){
                    foreach($categories as $categorydata)
                    {
                        //echo '<option value="'.$categorydata->cat_ID.'">'.$categorydata->cat_name.'</option>';
						
						
							if($slidesData->category_id == $categorydata->cat_ID){
								echo '<option value="'.$categorydata->cat_ID.'" selected="selected">'.$categorydata->cat_name.'</option>';
							}
							else
							{
								echo '<option value="'.$categorydata->cat_ID.'">'.$categorydata->cat_name.'</option>';
							}						
                    }
                }
                ?>
              </select>
            </div>
          </div>           
           <div class="topdiv">              
           <div class="col-md-3">
            <label for="slideposts">Select Posts</label>
           </div>           
           <div class="col-md-5">          
            <select multiple="multiple" name="slidepost_<?php echo $t; ?>[]" id="slidepost_<?php echo $t; ?>" class="form-control" data-posts-id="<?php echo $t; ?>">
            <?php
			$post_slides = array();
			if($slidesData->posts_id != "")
			{
				$post_slides = explode(",",$slidesData->posts_id);
				$post_array = cpsp_getAllPostsByCategory($slidesData->category_id);
				if(count($post_array) > 0)
				{
					foreach ($post_array as $optionvalues)
					{
						if(in_array($optionvalues->id,$post_slides)){
							echo '<option value="'.$optionvalues->id .'" selected>'. $optionvalues->title .'</option>';	
						}
						else{
							echo '<option value="'.$optionvalues->id .'">'. $optionvalues->title .'</option>';	
						}
						
					}
				}
			}
			else
			{
				?>
                <option value="select">Select</option>
                <?php
			}
			?>
            
                                  
            </select>
           <p class="cpsp_err_msg" id="slidepost_err_<?php echo $t; ?>">No Posts Found</p>      
           <p class="description">press ctrl and select multiple posts to add slides to category</p>
           
           </div>              
          </div>
          <?php
		  if($t > 1)
		  {
		  ?>
         <div id="section_buttons" style="float:right; padding:2%;">
          <input type='button' value='Remove Section' id='removeSection' class="removeSection btn btn-sm btn-danger" data-section="<?php echo $t;?>">
        </div> 
        <?php
		  } ?>
        </div><!--End block 1--> 
        <?php 
		$t++;
		} ?>      
        
         <div class="add_new_cat_tab">
        </div>       
        </div>
          <!--------- Section Ends----------->
		</fieldset>               
        </div>          
          <div class="row">         
          <div class="col-md-7 col-md-offset-2">
          	<input type='button' value='Add Section' id='addButtonsection' class="new_cat_section btn btn-sm btn-danger">		  	
            <input type="submit" name="slidesubmit" id="submit" class="btn btn-primary" value="Save"/>
          </div>
        </div>
      </form>
    <?php
}
else{	
?>
 <form method="post" enctype="multipart/form-data" id="ajaxform">
 <input type="hidden" name="cpsp-ajax-nonce" id="cpsp-ajax-nonce" value="<?php echo wp_create_nonce( 'cpsp-ajax-nonce' ); ?>" />
 <input type="hidden" name="num_tab_sections" id="num_tab_sections" value="1"/>
        <div class="form-horizontal">            
        
       <fieldset>
    	<legend>Add Category tabs along with post slides</legend>
          
          <!-------Sections Start ---------->
          <div style="width:100%;" id="section_div">
          <div class="row new_cat_block" id="new_cat_block_1">
          <div class="sec_name">Section 1</div>
          <div class="topdiv">
           	<div class="col-md-3">
            <label for="category">Category 1</label>
          	</div>
            <div class="col-md-5">
               <select name="category_id1" id= "category_id1" data-catid="1" class="form-control pcategories">
                <option value="0">Select Category</option>
                <?php
                if(count($categories) > 0){
                    foreach($categories as $categorydata)
                    {
                        echo '<option value="'.$categorydata->cat_ID.'">'.$categorydata->cat_name.'</option>';
                    }
                }
                ?>
              </select>
            </div>
          </div> 
           <div class="topdiv"> 
             
           <div class="col-md-3">
            <label for="slideposts">Select Posts</label>
           </div>
           
           <div class="col-md-5">          
            <select multiple="multiple" name="slidepost_1[]" id="slidepost_1" class="form-control" data-posts-id="1">
              <option value="select">Select</option>                       
            </select>
           <p class="cpsp_err_msg" id="slidepost_err_1">No Posts Found</p>      
           <p class="description">press ctrl and select multiple posts to add slides to category</p>
           
           </div>              
          </div>
        </div><!--End block 1-->
         <div class="add_new_cat_tab">
        </div>       
        </div>
          <!--------- Section Ends----------->
		</fieldset>
        </div>
          <div class="row">
          <div class="col-md-7 col-md-offset-2 section_buttons">
          	<input type='button' value='Add Section' id='addButtonsection' class="new_cat_section btn btn-sm btn-danger">				  	
            <input type="submit" name="slidesubmit" id="submit" class="btn btn-primary" value="Save"/>
          </div>
        </div>
      </form>
      <?php
	}
	  ?>
    </div>
  </div>
</div>