 jQuery(document).ready(function($){
	 
	$(function() {
         
        // Add Color Picker to all inputs that have 'color-field' class
        $( '.cpsp-color-picker' ).wpColorPicker();
         
    });
	
	$('.pcategories').live('change', function (e) {
		var optionSelected = $("option:selected", this);
		var attrid = $(this).attr('data-catid');
		//alert(attrid);
    	var cat_id = this.value;		
		nonce = $('#cpsp-ajax-nonce').val();
		//alert(cat_id);
		$.ajax({
		type: "POST",
		dataType : "html",
		url: myAjax.ajaxurl,		
		data : {action: "cpsp_LoadPosts", category : cat_id,nonce: nonce},		
		success: function(response){
			//alert(response);
			if(response != 0)
			{
				$("#slidepost_"+attrid).html(response);
				$("#slidepost_err_"+attrid).hide();
			}
			else
			{				
				$("#slidepost_err_"+attrid).show();
				$("#slidepost_"+attrid).html('');
			}
			
			
		}
		});
	});
	
	 
 	/*************************************** New Add Slider starts here ***********************************************/
 	var add_block  = $(".add_new_cat_tab"); //Fields wrapper
    var new_category_section      = $(".new_cat_section"); //Add button ID
	
	 $(new_category_section).click(function(e){ //on add input button click
        e.preventDefault();
		
		var vcounter = parseInt(($(".new_cat_block").length)) + 1; 
		nonce = $('#cpsp-ajax-nonce').val();
		$.ajax({
         type : "post",
         dataType : "html",
         url : myAjax.ajaxurl,
         data : {action: "cpsp_loadCategories", nonce: nonce, "counter_id": vcounter },
         success: function(response) {			
         
			$(add_block).append('<div class="row new_cat_block" style="margin-top:2%;" id="new_cat_block_'+vcounter+'"><div class="sec_name">Section '+vcounter+'</div><div class="topdiv"><div class="col-md-3"><label for="category">Category '+vcounter+'</label></div><div class="col-md-5">'+response+'</div></div><div class="topdiv"><div class="col-md-3"><label for="slideposts">Select Posts</label></div><div class="col-md-5"><select multiple="multiple" name="slidepost_'+vcounter+'[]" id="slidepost_'+vcounter+'" class="form-control" data-posts-id="'+vcounter+'"><option value="select">Select</option></select><p class="cpsp_err_msg" id="slidepost_err_'+vcounter+'">No Posts Found</p><p class="description">press ctrl and select multiple posts to add slides to category</p></div></div> <div id="section_buttons" style="float:right; padding:2%;"><input type="button" value="Remove Section" id="removeSection" class="removeSection btn btn-sm btn-danger" data-section='+vcounter+'></div></div>');
		
			//update number of tab sections field	
			$('#num_tab_sections').val(vcounter);
         }
      });          
            
       
    });
	
	<!---------------- Remove sections remove button ----------------------->	
	
	 $(document).on("click","#removeSection", function(e){ //user click on remove text
	
        e.preventDefault(); 	
			
		var counter = parseInt(($(".new_cat_block").length));  
		var sec_number = $(this).attr('data-section'); 
		//alert(sec_number);
		var sec_catid = $('#category_id'+sec_number+' option').filter(":selected").val();
		//alert('catid>>>>>'+sec_catid);
		nonce = $('#cpsp-ajax-nonce').val();	
		if(counter > 1)
        {
			
			if(sec_catid == 0)
			{
				$('#new_cat_block_' + sec_number).hide('slow', function(){ $('#new_cat_block_' + sec_number).remove(); });	
				//update number of tab sections field	
				var minus_cnt = counter - 1;	
				$('#num_tab_sections').val(minus_cnt);  		
			}
			else{			
			
				if(confirm("Sure you want to delete this slide?"))
				{
					
				$.ajax({
				type: "POST",
				url: myAjax.ajaxurl,		
				data : {action: "cpspRemoveSlideSection", id : sec_number, category : sec_catid,nonce: nonce},		
				success: function(response){
					
					var res = response.trim();
					
					if((res == "Removed") || (res == "empty-slide")) {
									
						$('#new_cat_block_' + sec_number).hide('slow', function(){ $('#new_cat_block_' + sec_number).remove(); });	
						//update number of tab sections field	
						var minus_cnt = counter - 1;	
						$('#num_tab_sections').val(minus_cnt); 
						window.location="?page=category_posts_slider_pro-new";
							
					}			
					else {
					   alert("Unable to remove this slide");
					}
					
				}
				});
				
				}
			return false;
			}
        }
        else{
            alert('Not Allowed');
        }
		
    });
	
	$("#ajaxform").submit(function(e)
	{
    var postData = $(this).serialize();
	
	nonce = cpsp_ajax.cpsp_slider_nonce;
	
	$.ajax({
				type: "POST",				
				url: cpsp_ajax.ajaxurl,
				dataType: "json",					
				data : {action: "cpsp_save_slides", SubmittedData : postData, nonce: nonce},		
				success: function(response){
					
					//alert(response.message);
					//$('#cpsp_message').html(response);
					if(response.message != null)
					{
						alert('Saved');
						$('#cpsp_message').html(response.message).show();
						$('html, body').animate({
							scrollTop: $(".cpsp-wrap").offset().top
						}, 2000);
						}
					
					//$("#cpsp_message").attr("tabindex",-1).focus();
					
				},
				 error: function(jqXHR, textStatus, errorThrown) 
				{
					//if fails    
					alert(textStatus);  
				}
				});	
 
    e.preventDefault(); //STOP default action
   
});
	
});