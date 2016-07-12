var pw = jQuery("<div class='pw'>Please Wait...</div>");
var wpid = post__in = 0;
var page = 1;
var loader;

jQuery(document).ready(function(){
		if(jQuery("#nootherstories").length > 0){
			jQuery("#nootherstories").parent().find(".content").css({height:"0px"});		
		}
		jQuery(".answer").on("click",function(){
		
			if(jQuery(this).find(".votebar").length == 0){
				var r = jQuery(this).attr("id").split("_");
				jQuery.post(ajaxurl, {action: 'voteit', post_id: r[1], answer_id: r[2]}, function(response) {
					jQuery(".quiz").replaceWith(response);
				});	
			}
		});
		
	
		
		
		
		jQuery(".rating").each(function(){
			var r = jQuery(this).find(".therating").val();	
			for(var a =0;a < r; a++){
				jQuery(this).find(".star").eq(a).addClass("active");
			}
		});
		jQuery("#comment").attr("placeholder","SAY ONE POSITIVE THING ABOUT THIS STORY");
		//jQuery('.sharemes').hide();
		jQuery('.shareme').on('click',function(){
			jQuery('.sharemes').css({display:"block"}).show('slow');
			jQuery(this).hide();
		});
	
		jQuery(".star[id]").on("click",function(){
			jQuery.post(ajaxurl, {action: 'rate', story_id:jQuery('#rpid').val(),rating: jQuery(this).find("a").attr("id")}, function(response) {
				if(response.match(/already/)){
				alert(response);
				} else {
					jQuery(".myRating").remove();
					jQuery("#rate1").find(".star").removeClass("active");
						jQuery("#rate1").find(".therating").val(response);
						jQuery(".rating").each(function(){
							var r = jQuery(this).find(".therating").val();	
							for(var a =0;a <= r-1; a++){
								jQuery(this).find(".star").eq(a).addClass("active");
							}
						});
				}
			});	
		});
		
		jQuery(".flaglink").find("input[type=checkbox]").on("change",function(){
			var th = jQuery(this);
			jQuery("#preason").unbind().show().on("change",function(){
				var reason = jQuery(this).find("option:selected").val();
				if(!reason){
					jQuery("#preason").hide();
					jQuery(".flaglink").find("input").attr("checked",false);	
				} else {
						jQuery("#preason").hide();
						jQuery(".flaglink").html('<span style="color:red;"><input type="checkbox" checked="" disabled="" />flagged</span>');
					jQuery.post(ajaxurl, {action: 'report', story_id: th.val(), reason: reason}, function(response) {
					
					});	
				}
		});
			
			
		});
		
		
	
		
		
		jQuery(".characterize").on("click",function(){
			var th = jQuery(this);
			var id = th.attr("name").split("_");
			var vals = [];
			id = id[1];
			jQuery(".characterize:checked").each(function(){
					vals.push(jQuery(this).val());
			});
			
			vals = vals.join(",");
			
			
			jQuery.post(ajaxurl, {action: 'characterizeit', c: vals, story_id: id}, function(response) {
				//th.replaceWith('<strong>'+th.find("option:selected").val()+'</strong>.');
			});	
		});
		
		jQuery(".sharecb").on("change",function(){
			var th = jQuery(this);
			
			
			jQuery.post(ajaxurl, {action: 'shareit', group: th.val(), story_id: jQuery('#rpid').val()}, function(response) {
				alert(response);
				th.attr("disabled",true);
			});	
		});
		
		if(jQuery(".bp-user").length > 0){
			jQuery("#primary-sidebar").remove();
			
		}
		
			jQuery("#stories_mark_as_read").on("click",function(){
				var th = jQuery(this);
				var id = th.val();
				jQuery.post(ajaxurl, {action: 'markasread',  story_id: id, value: th.attr("checked")}, function(response) {
					
				});	
			});
			
			
			
			//https://connoratherton.com/loaders
			wpid =jQuery("#wpid").val();
			page = 1;
			loader = '<div class="loaders"><div class="loader"><span>LOADING&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;STORIES</span><div class="loader-inner ball-scale-multiple"><div></div><div></div><div></div></div></div></div>';
			jQuery(".ajaxstories").html(loader);
		jQuery(".filter-sidebar").insertAfter("main").show();
		getStories();	
		jQuery(".afilter").on("click",function(){
			getStories();
		});
		jQuery(".sorter").on("change",function(){
			getStories();	
			
		});
	
});

function getStories(){
	
	jQuery(".ajaxstories").html(loader);
			var values = [];
			wpid = jQuery("#wpid").val();
			post__in = jQuery("#post__in").val();
			jQuery(".ajaxstories").html(loader);
			jQuery(".afilter:checked").each(function(){
				values.push(jQuery(this).val());
			});
			jQuery.post(ajaxurl, {action: 'filterStories', values:values,page:page,post__in:post__in,wpid:wpid,sorter: jQuery(".sorter").find("option:selected").val() }, function(response) {
				jQuery(".ajaxstories").html(response);
				jQuery(".rating").each(function(){
				var r = jQuery(this).find(".therating").val();	
				for(var a =0;a < r; a++){
					jQuery(this).find(".star").eq(a).addClass("active");
				}
			});
			});		
		}
		
		function apage(p){
			page = p;	
			getStories();
		}


	function makePDFs(id){
				jQuery.post(ajaxurl, {action: 'makePDF',post_id:id}, function(response) {
				//window.location("/pdf/s"+response);
				window.open("/pdfs/"+response, '_blank', 'fullscreen=yes'); return false;
				});	
	}
