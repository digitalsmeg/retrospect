var commentcount = 0;
jQuery(document).ready(function(){
	//commentcount
	jQuery(".pending-count").html(commentcount);	
});

function getUrlVars() {
	var vars = {};
	var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
	vars[key] = value;
	});
	return vars;
}
