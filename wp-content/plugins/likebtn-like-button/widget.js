

var LikeBtn = {
    vote: function (i,p) {
        jQuery.post("/wp-admin/admin-ajax.php",{action: "fsvote", i: i, post: p},function(data){
			if(data == "error"){ alert("You have to be registered and login to like a story.");return false;}
			var temp = data.split(":");
			
			if(jQuery(".lb-voted").is(".lb-like")){
					if(i == 1){
						jQuery(".lb-voted").removeClass("lb-voted");
					
					} else {
						jQuery(".lb-voted").removeClass("lb-voted");
						jQuery(".lb-dislike").addClass("lb-voted");	
					}
					
			} else if(jQuery(".lb-voted").is(".lb-dislike")){ 
				if(i == -1){
					jQuery(".lb-voted").removeClass("lb-voted");
				} else {
					jQuery(".lb-voted").removeClass("lb-voted");
					jQuery(".lb-like").addClass("lb-voted");
				}
				
			} else {
				jQuery(".lb-voted").removeClass("lb-voted");
				if(i == 1){
					jQuery(".lb-like").addClass("lb-voted");	
				} else {
					jQuery(".lb-dislike").addClass("lb-voted");	
				}
			}
			jQuery(".lb-like").find(".lb-count").attr("data-count",temp[0]);
			jQuery(".lb-like").find(".lb-count").html(temp[0]);
			jQuery(".lb-dislike").find(".lb-count").attr("data-count",temp[1]);
			jQuery(".lb-dislike").find(".lb-count").html(temp[1]);
			
			
			
		});
    }
}
jQuery(".fs_likebtn_container:eq(0)").each(function(){
	jQuery.post("/wp-admin/admin-ajax.php",{action: "likebutton", post: jQuery(this).attr("id")},function(data){
		jQuery(".fs_likebtn_container:eq(0)").html(data);
			if(jQuery(".fs_likebtn_container:eq(0)").attr("voted") == "1"){
				jQuery(".lb-like").addClass("lb-voted");	
			} else if(jQuery(".fs_likebtn_container:eq(0)").attr("voted") =="-1"){
				jQuery(".lb-dislike").addClass("lb-voted");	
			}
			
		
		
	});
});