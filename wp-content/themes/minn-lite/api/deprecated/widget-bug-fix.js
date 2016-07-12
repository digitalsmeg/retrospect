jQuery(document).ready(function ($) {
	/* Fixes issue on this trac ticket: http://core.trac.wordpress.org/ticket/14686 */
	$(document).ajaxSuccess(function (event, XMLHttpRequest, ajaxOptions) {
		var request = {}, pairs = ajaxOptions.data.split('&'), i, split, widget;
		for (i in pairs) {
			split = pairs[i].split('=');
			request[decodeURIComponent(split[0])] = decodeURIComponent(split[1]);
		}
		if (request.action && (request.action === 'save-widget')) {
			widget = $('input.widget-id[value="' + request['widget-id'] + '"]').parents('.widget');
			if (!XMLHttpRequest.responseText) wpWidgets.save(widget, 0, 1, 0);
		}
	});
});