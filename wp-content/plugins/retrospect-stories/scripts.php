var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
jQuery(document).ready(function(){
	addNewFilter();
	try{
		
		
		jQuery(".datepicker").datepicker({dateFormat:"yy-mm-dd", changeMonth: true, changeYear: true,  minDate: new Date(1900, 1 - 1, 1)});
		
	
			jQuery(".datepicker").change(function() {
                var selectedDate= jQuery(this).datepicker({ dateFormat: 'yy-mm-dd' }).val();
				if(jQuery(this).attr("rel") == "admin"){
					var postid = jQuery(this).attr("id").replace(/datepicker/,'');
                	jQuery.post(ajaxurl, {action: 'admin_date', post_id: postid,stories_embargo_until:selectedDate}, function(response) {
							
					});	       
				}
            });
	} catch(err){}
		
	var sv = jQuery('#totalStories').val();
	if(sv){
	jQuery("#toplevel_page_edit-post_type-stories").find('.wp-menu-name').append('<span class="awaiting-mod count-1"><span class="pending-count">'+sv+'</span></span>');
	}
	
});

function clearReport(postid,th){
	jQuery.post(ajaxurl, {action: 'clearreport', story_id: postid}, function(response) {
				th.parent().html('');
	});		
}

function requestEdit(postid,th){
	var reason = th.parent().find("input[type=text]").val();
	if(reason){
	jQuery.post(ajaxurl, {action: 'requestedit', story_id: postid, reason: reason}, function(response) {
		alert("Edit Requested.");			
	});	
	} else {
		alert("Please provide a reason.");	
	}
}

function addNewFilter(){
	jQuery('<input type="text" style="width: 46%;position:absolute" id="paregfilter" placeholder="Start typing to filter - use & to include multiple parameters..." />').insertAfter("#post-query-submit");
    
    jQuery("#paregfilter").on("keyup",function(event){
    	var find = jQuery(this).val();
        var temp = find.split("&");
         jQuery("#the-list").find("tr").show();
        jQuery("#the-list").find("tr").each(function(){
        	for(var a = 0;a < temp.length; a ++){
            	temp[a] = jQuery.trim(temp[a]);
                if(!jQuery(this).text().match(eval('/'+temp[a]+'/gi'))){
                	jQuery(this).hide();
                } else {
                    
                }
            }
        });
        if(find == ''){
         jQuery("#the-list").find("tr").show();
        }
    });
}