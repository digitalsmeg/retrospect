
jQuery(document).ready(function(){
	addNewFilter();
	jQuery("#post-5520").find(".row-title").append(" - DO NOT DELETE ");
	jQuery("#cb-select-5520").remove();
	jQuery("#post-5520").find(".row-actions").remove();
	jQuery("#post-5759").find(".row-title").append(" - DO NOT DELETE ");
	jQuery("#cb-select-5759").remove();
	jQuery("#post-5759").find(".row-actions").remove();
	try{
		jQuery("[for=ping_status]").remove();
		jQuery("[for=default_ping_status]").remove();
		jQuery(".wp-pwd").after('<p class="description indicator-hint">Hint: To strengthen your password, make it longer and use a mix of upper and lowercase letters, numbers, and symbols like ! “ ? $ ^ & ).</p>');
		if(jQuery("#post_type") == "stories"){
		jQuery('#timestamp').after( 'Your story will be published the same date as your selected prompt.');
		}
		jQuery(".wp-switch-editor.switch-tmce").trigger("click");
		jQuery("#wp-admin-bar-site-name").find("a:eq(0)").html("Back To Retrospect");
		if(jQuery("#post_type").val() == "stories"){
		jQuery("#excerpt").siblings("p").replaceWith("<p>An Excerpt is a short summary or quote that appears in story previews and can entice others to read your story.</p>");
		jQuery("#postimagediv").before('<div class="note">Your Featured Image will appear at the top of your story and with your Excerpt in story previews.</div>');
		//jQuery("#postimagediv").find(".hide-if-no-js").css({display:"block!important",visibility:"visible!important"});
		jQuery("#insert-media-button").before('<div class="note">Add Photos, Video, or Audio to your story</div>');
		jQuery("label[for='visibility-radio-password']").attr('title','Share only with readers you select.');
		jQuery("#post_status").find("option[value=pending]").remove();
		}
		jQuery(".inside > p").fadeTo(1,1);
		jQuery(".datepicker").datepicker({dateFormat:"yy-mm-dd", changeMonth: true, changeYear: true,  minDate: new Date(1900, 1 - 1, 1)});
		
	
			jQuery(".datepicker").change(function() {
                var selectedDate= jQuery(this).datepicker({ dateFormat: 'yy-mm-dd' }).val();
				if(jQuery(this).attr("rel") == "admin"){
					var postid = jQuery(this).attr("id").replace(/datepicker/,'');
                	jQuery.post(ajaxurl, {action: 'admin_date', post_id: postid,stories_embargo_until:selectedDate}, function(response) {
					window.location = '/wp-admin/edit.php?post_type=prompts';		
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
var itemCount = 0;
function addNewFilter(){
	jQuery('<input type="text" style="width: 46%;position:absolute" id="paregfilter" placeholder="Start typing to filter - use & to include multiple parameters..." />').insertAfter("#post-query-submit");
    itemCount = jQuery(".displaying-num").html();
    jQuery("#paregfilter").on("keyup",function(event){
		
    	var find = jQuery(this).val();
        var temp = find.split("&");
         jQuery("#the-list").find("tr").hide();
        jQuery("#the-list").find("tr").each(function(){
        	for(var a = 0;a < temp.length; a ++){
			
            	var m = jQuery.trim(temp[a]);
					console.log(m);
                if(jQuery(this).text().match(eval('/'+m+'/gi'))){
                	jQuery(this).show();
                }
            }
        });
        if(find == ''){
         jQuery("#the-list").find("tr").show();
		 jQuery("#pfcount").html(itemCount);
        }
		jQuery(".displaying-num").html( jQuery("#the-list").find("tr:visible").length + " items");
    });
}