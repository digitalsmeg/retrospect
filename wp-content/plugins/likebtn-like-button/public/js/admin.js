var plans={trial:9,free:0,plus:1,pro:2,vip:3,ultra:4};
var likebtn_popup;
var likebtn_popup_timer;
var likebtn_preview;
var likebtn_preview_offset;
var likebtn_preview_position = 'static';

// replace all occurences of a string
String.prototype.replaceAll = function(search, replace){
    return this.split(search).join(replace);
};

jQuery(document).ready(function(jQuery) {
    //likebtnRefreshPreview();
    likebtnApplyTooltips();
});

// Show/hide entity options
function entityShowChange(el, entity_name)
{
    if (jQuery(el).is(':checked')) {
        jQuery("#entity_container_"+entity_name).show();
        jQuery("#likebtn_subpage_tab_wrapper .likebtn_tab_"+entity_name+" .likebtn_show_marker").removeClass('hidden');
    } else {
        jQuery("#entity_container_"+entity_name).hide();
        jQuery("#likebtn_subpage_tab_wrapper .likebtn_tab_"+entity_name+" .likebtn_show_marker").addClass('hidden');
    }
}

// Show/hide options on Use settings from select
function userSettingsFromChange(el, entity_name)
{
    if (jQuery(el).val()) {
        jQuery("#use_settings_from_container_"+entity_name).hide();
        jQuery("#likebtn_save_preview").hide();
    } else {
        jQuery("#use_settings_from_container_"+entity_name).show();
        jQuery("#likebtn_save_preview").show();
    }
}

// Toggle collapable area
function toggleCollapsable(el)
{
    jQuery(el).parent().children('.inside').toggle();
}

// Toggle upgrade website instructions
function toggleToUpgrade()
{
    jQuery("#likebtn_to_upgrade").toggle();
}

// Toggle Post format container
function postFormatAllChange(el, entity_name)
{
    if (jQuery(el).is(':checked')) {
        jQuery("#post_format_container_"+entity_name).hide();
    } else {
        jQuery("#post_format_container_"+entity_name).show();
    }
}

// Account data change
function accountChange()
{
    var account_data_filled = true;
    jQuery("input.likebtn_account").each(function(index, element) {
        if (!jQuery(element).val()) {
            account_data_filled = false;
        }
    });
    if (account_data_filled) {
        jQuery(":input[name='likebtn_sync_inerval']").removeAttr('disabled');
    } else {
        jQuery(":input[name='likebtn_sync_inerval']").val('').attr('disabled', 'disabled');
    }
}

// test synchronization
function testSync(loader_src)
{
    jQuery(".likebtn_test_sync_container:first").html('<img src="' + loader_src + '" />');

    jQuery.ajax({
        type: 'POST',
        dataType: "json",
        url: ajaxurl,
        data: {
            action: 'likebtn_test_sync',
            likebtn_account_email: jQuery(":input[name='likebtn_account_email']:first").val(),
            likebtn_account_api_key: jQuery(":input[name='likebtn_account_api_key']:first").val(),
            likebtn_site_id: jQuery(":input[name='likebtn_site_id']:first").val()
        },
        success: function(response) {
            var result_text = '';
            if (typeof(response.result_text) != "undefined") {
                result_text = response.result_text;
            }
            jQuery(".likebtn_test_sync_container:first").text(result_text);
            if (typeof(response.result) == "undefined" || response.result != "success") {
                jQuery(".likebtn_test_sync_container:first").css('color', 'red');
                if (typeof(response.message) != "undefined") {
                    var text = jQuery(".likebtn_test_sync_container:first").html() + ': ' + response.message;
                    jQuery(".likebtn_test_sync_container:first").html(text);
                }
            } else {
                jQuery(".likebtn_test_sync_container:first").css('color', 'green');
            }

        },
        error: function(response) {
            jQuery(".likebtn_test_sync_container:first").html('Error occured. Disable WP HTTP Compression plugin if you have it enabled.').css('color', 'red');
        }
    });
}

// check account data
function checkAccount(loader_src)
{
    jQuery(".likebtn_check_account_container:first").html('<img src="' + loader_src + '" />');

    jQuery.ajax({
        type: 'POST',
        dataType: "json",
        url: ajaxurl,
        data: {
            action: 'likebtn_check_account',
            likebtn_account_email: jQuery(":input[name='likebtn_account_email']:first").val(),
            likebtn_account_api_key: jQuery(":input[name='likebtn_account_api_key']:first").val(),
            likebtn_site_id: jQuery(":input[name='likebtn_site_id']:first").val()
        },
        success: function(response) {
            var result_text = '';
            if (typeof(response.result_text) != "undefined") {
                result_text = response.result_text;
            }
            jQuery(".likebtn_check_account_container:first").text(result_text);
            if (typeof(response.result) == "undefined" || response.result != "success") {
                jQuery(".likebtn_check_account_container:first").css('color', 'red');
                if (typeof(response.message) != "undefined") {
                    var text = jQuery(".likebtn_check_account_container:first").html() + ': ' + response.message;
                    jQuery(".likebtn_check_account_container:first").html(text);
                }
            } else {
                jQuery(".likebtn_check_account_container:first").css('color', 'green');
            }

        },
        error: function(response) {
            jQuery(".likebtn_check_account_container:first").html('Error occured. Disable WP HTTP Compression plugin if you have it enabled.').css('color', 'red');
        }
    });
}

// full synchronization
function manualSync(loader_src)
{
    jQuery(".likebtn_manual_sync_container:first").html('<img src="' + loader_src + '" />');

    jQuery.ajax({
        type: 'POST',
        dataType: "json",
        url: ajaxurl,
        data: {
            action: 'likebtn_manual_sync',
            likebtn_account_email: jQuery(":input[name='likebtn_account_email']:first").val(),
            likebtn_account_api_key: jQuery(":input[name='likebtn_account_api_key']:first").val()
        },
        success: function(response) {
            var result_text = '';
            if (typeof(response.result_text) != "undefined") {
                result_text = response.result_text;
            }
            jQuery(".likebtn_manual_sync_container:first").text(result_text);
            if (typeof(response.result) == "undefined" || response.result != "success") {
                jQuery(".likebtn_manual_sync_container:first").css('color', 'red');
                if (typeof(response.message) != "undefined") {
                    var text = jQuery(".likebtn_manual_sync_container:first").html() + ': ' + response.message;
                    jQuery(".likebtn_manual_sync_container:first").html(text);
                }
            } else {
                jQuery(".likebtn_manual_sync_container:first").css('color', 'green');
            }

        },
        error: function(response) {
            jQuery(".likebtn_manual_sync_container:first").html('Error occured. Disable WP HTTP Compression plugin if you have it enabled.').css('color', 'red');
        }
    });
}

// enable/disable elements depending on the plan
function planChange(plan_id)
{
    // come through all plan dependent elements
    jQuery(".plan_dependent").each(function() {

        var classes;
        var class_name;
        var option_plan;
        var option_plan_id;
        var available = false;

        if (jQuery(this).attr('class')) {
            classes = jQuery(this).attr('class').split(/\s+/);

            for (var i = 0; i < classes.length; i++) {
                class_name  = classes[i];
                if (!class_name) {
                    continue;
                }
                option_plan = class_name.replace(/plan_/, '');

                if (!option_plan) {
                    continue;
                }
                if (typeof(plans[option_plan]) == "undefined") {
                    continue;
                }
                option_plan_id = plans[option_plan];

                if (plan_id >= option_plan_id) {
                    available = true;
                }
            }
        } else {
            available = true;
        }

        if (available) {
            jQuery(this).removeClass('likebtn_disabled').find(':input').removeAttr('readonly').removeClass('disabled');
            /*if (this.tagName.toUpperCase() === 'SELECT') {
                jQuery(this).removeAttr('onchange');
            }*/
        } else {
            jQuery(this).addClass('likebtn_disabled').find(':input').each(function(index, el) {
                jQuery(el).attr('readonly', 'readonly').addClass('disabled').attr('onclick', 'return false');
                /*if (this.tagName.toUpperCase() === 'SELECT') {
                    jQuery(this).attr('onchange', "this.defaultIndex=this.selectedIndex;");
                }*/
                // Disable AddThis select2
                if (el.id && el.id == 'settings_addthis_service_codes') {
                    jQuery(el)
                        .attr("disabled", "disabled")
                        .addClass('disabled')
                        .select2Sortable('destroy');
                }
                if (el.id && el.id == 'popup_donate_input') {
                    jQuery("#popup_donate_trigger").attr('href', 'javascript:void(0);');
                }
            });
        }
    });
}

// reest settings
function resetSettings(entity_name, parameters)
{
    var input;
    var default_value;

    if (!confirm(likebtn_msg_reset)) {
        return false;
    }

    for (option_name in parameters) {
        input = jQuery('#use_settings_from_container_'+entity_name+' :input[name^="likebtn_'+option_name+'_'+entity_name+'"]');

        default_value = parameters[option_name];

        if (input.attr('type') == 'checkbox') {
            jQuery('#use_settings_from_container_'+entity_name+' :input[name^="likebtn_'+option_name+'_'+entity_name+'"]').removeAttr('checked');
            jQuery('#use_settings_from_container_'+entity_name+' :input[name^="likebtn_'+option_name+'_'+entity_name+'"][value="'+default_value+'"]').attr('checked', 'checked');
        } else if(input.attr('type') == 'radio') {
            jQuery('#use_settings_from_container_'+entity_name+' :input[name^="likebtn_'+option_name+'_'+entity_name+'"][value="'+default_value+'"]').attr('checked', 'checked');
        } else {
            input.val(default_value);
        }
        input.change();
    }

    displayFields();
    likebtnRefreshPreview();
}

// select/unselect items
function statisticsItemsCheckbox(el)
{
    if (jQuery(el).is(':checked')) {
        jQuery("#statistics_container .item_checkbox").attr("checked", "checked");
    } else {
        jQuery("#statistics_container .item_checkbox").removeAttr("checked");
    }
}

// Edit statistics items
function statisticsEdit(entity_name, entity_id, type, plan, text_enter, text_upgrade, text_error)
{
    if (entity_name === '' || entity_id === '' || type === '') {
        return false;
    }

    if (typeof(plan) != "undefined" && parseInt(plan) >= plans.ultra) {
        var value = prompt(text_enter);
        if (value == null) {
            return false;
        }
    } else {
        alert(text_upgrade);
        return false;
    }

    if (type === 'like') {
        internal_type = 1;
    } else {
        internal_type = -1;
    }

    jQuery.ajax({
        url: ajaxurl,
        method: "POST",
        data: {
            action: 'likebtn_edit_item',
            entity_name: entity_name,
            entity_id: entity_id,
            type: internal_type,
            value: value
        },
        dataType: "json",
        success: function(data) {
            if (data) {
                if (data.result == "success") {
                    if (typeof(data.value) !== "undefined") {
                        jQuery("#item_"+entity_id+" .item_"+type+":first").text(data.value);
                    }
                } else {
                    if (typeof(data.message) !== "undefined") {
                        alert(data.message);
                    } else {
                        alert(text_error);
                    }
                }
            } else {
                alert(text_error);
            }
        },
        error: function(data) {
            alert(text_error);
        }
    });

    return true;
}

// Go to tab
function likebtnGotoTab(tab, content_wrapper, content_wrapper_id, wrapper_selector) {

    /*if (!jQuery("#likebtn_subpage_wrapper_"+tab).size()) {
        // Show first tab
        var tab_id = jQuery(tab_selector+":first").attr('id');
        if (tab_id) {
            tab = tab_id.replace('likebtn_subpage_wrapper_', '');
        } else {
            // Could not find first tab
            return false;
        }
    }*/

    if (tab == 'popup') {
        likebtnPreviewDonate();
    }

    // Content
    jQuery(content_wrapper).addClass('hidden');
    jQuery(content_wrapper_id+tab).removeClass('hidden');

    // Tab
    jQuery(wrapper_selector+" .nav-tab").removeClass('nav-tab-active');
    jQuery(wrapper_selector+" .nav-tab.likebtn_tab_"+tab).addClass('nav-tab-active');
}

// Show subpage
/*function likebtnGotoSubpage(subpage) {

    if (!jQuery("#likebtn_subpage_wrapper_"+subpage).size()) {
        // Show first tab
        var subpage_id = jQuery(".likebtn_subpage:first").attr('id');
        if (subpage_id) {
            subpage = subpage_id.replace('likebtn_subpage_wrapper_', '');
        } else {
            // Could not find first tab
            return false;
        }
    }

    // Content
    jQuery(".likebtn_subpage").addClass('hidden');
    jQuery("#likebtn_subpage_wrapper_"+subpage).removeClass('hidden');

    // Tab
    jQuery("#likebtn_subpage_tab_wrapper .nav-tab").removeClass('nav-tab-active');
    jQuery("#likebtn_subpage_tab_wrapper .nav-tab.likebtn_tab_"+subpage).addClass('nav-tab-active');

    jQuery("#likebtn_subpage").val(subpage);
}*/

// Detect if subpage is selected and goto it
/*function likebtnDetectSubpage()
{
    hash = window.location.hash;

    if (hash && hash.substr(0, 29) == '#likebtn_subpage_') {
        likebtnGotoSubpage(hash.substr(29));
    }
}*/

// Open popup window
function likebtnPopup(url, name, height, width)
{
    if (typeof(width) === "undefined" || !width) {
        width = 1000;
    }
    if (typeof(height) === "undefined" || !height) {
        height = 620;
    }
    if (width > jQuery(window).width()) {
        width = jQuery(window).width();
    }

    likebtn_popup = window.open(url, name, 'height='+height+',width='+width+',toolbar=0,scrollbars=yes');
    likebtn_popup.focus();

    return likebtn_popup;
}

// On Save Buttons
function likebtnOnSaveButtons()
{
    // Select2
    jQuery('#settings_popup_content_order_input').val(likebtnGetMultipleSelect2Val('#settings_popup_content_order'));

    // Do not save default addthis
    var addthis_service_codes = likebtnGetMultipleSelect2Val("#settings_addthis_service_codes");
    var addthis_service_codes_default_value = jQuery("#settings_addthis_service_codes").attr('data-default');

    if (addthis_service_codes && addthis_service_codes === addthis_service_codes_default_value) {
        addthis_service_codes = '';
    }
    jQuery('#settings_addthis_service_codes_input').val(addthis_service_codes);
    
}

// Format image dropdown
function likebtnFormatSelect(state)
{
    // optgroup
    /*if (!state.id) {
        return state.text;
    }*/
    var image_name;

    if (state.id) {
        image_name = state.id.toLowerCase();
    } else {
        return state.text;
    }
    var select_id = jQuery(state.element).parents('select:first').attr('id');
    var option_html = '<img src="' + window["likebtn_path_"+select_id] + image_name + '.png" style="border-style:none;" alt="' + image_name + '" />';

    if (typeof(state.text) !== "undefined" && state.text) {
        option_html += ' &nbsp;<span class="image_dropdown_text">-&nbsp; ' + state.text + '</span>';
    }

    return option_html;
}

// Get ordered select2 val
function likebtnGetMultipleSelect2Val(selector)
{
    var vals = [];
    var objects = jQuery(selector).select2('data');
    for (var i in objects) {
        vals.push(objects[i].id);
    }

    return vals.join();
}

// Set select2 data (keeps ordering)
function likebtnSetMultipleSelect2Val(selector, val, with_text)
{
    var vals = val.split(',');
    var data = [];
    var text;

    for (var i in vals) {
        text = '';
        val = vals[i].trim();

        if (with_text) {
            // Find text in select
            text = jQuery(selector+" option[value='"+val+"']").text();
        }
        if (!text) {
            text = val;
        }

        data.push({
            id: val,
            text: text
        });
    }

    jQuery(selector).select2('data', data);
}

// Format image dropdown
function likebtnAddthisSelectResult(state)
{
    var option_html = '<i class="likebtn_at16_' + state.text.toLowerCase() + '"></i> ' + state.text;
    return option_html;
}
// Format image dropdown
function likebtnAddthisSelectSelection(state)
{
    if (typeof(state.text) === "undefined" || !state.text) {
        return '';
    }
    var option_html = '<i class="likebtn_at16_' + state.text.toLowerCase() + '" title="' + state.text + '"></i>';
    return option_html;
}

// Display field values from settings
function displayFields()
{
    var lang = jQuery("#settings_lang").val();

    // AddThis
    if (jQuery("#settings_addthis_service_codes_input").val()) {
        likebtnSetMultipleSelect2Val('#settings_addthis_service_codes', jQuery('#settings_addthis_service_codes_input').val());
    } else {
        likebtnSetMultipleSelect2Val('#settings_addthis_service_codes', likebtnGetDefaultAddthis(lang));
    }

    // Popup content order
    likebtnSetMultipleSelect2Val('#settings_popup_content_order', jQuery('#settings_popup_content_order_input').val(), true);

    // Display donate buttons
    likebtnPreviewDonate();

    // Must come before displayTranslationsOnLoad
    displayAddthis();
    displayTranslationsOnLoad();
}

// Preview donate buttons
function likebtnPreviewDonate()
{
    if (typeof(likebtnDGGetPreview) === "undefined") {
        return false;
    }
    jQuery("#donate_pveview").html(likebtnDGGetPreview('#popup_donate_input'));
}

// Get default AddThis value
function likebtnGetDefaultAddthis(lang)
{
    if (typeof(lang) === undefined) {
        lang = jQuery("#settings_lang").val();
    }
    var default_value = likebtn_default_settings.addthis_service_codes.default_values;

    if (typeof(lang) !== "undefined" && typeof(default_value[lang]) !== "undefined") {
        return default_value[lang];
    } else {
        return default_value.all;
    }
}

// Display AddThis
function displayAddthis()
{
    var default_value;

    var lang = jQuery("#settings_lang").val();

    // On load
    if (!likebtn_prev_lang) {
        likebtn_prev_lang = lang;
    }

    default_value = likebtnGetDefaultAddthis(lang);
    prev_default_value = likebtnGetDefaultAddthis(likebtn_prev_lang);

    if (likebtnGetMultipleSelect2Val("#settings_addthis_service_codes") == prev_default_value) {
        likebtnSetMultipleSelect2Val('#settings_addthis_service_codes', default_value);
    }
    // Remember default value
    jQuery("#settings_addthis_service_codes").attr('data-default', default_value);
}

// Display translations on settings load
function displayTranslationsOnLoad(settings)
{
    var lang = jQuery("#settings_lang").val();
    // Remember lang
    likebtn_prev_lang = lang;
}

// Display translations on lang change
function displayTranslations()
{
    var lang = jQuery("#settings_lang").val();

    // Remember lang
    likebtn_prev_lang = lang;
}

// Refresh like button preview
function likebtnRefreshPreview(entity_name)
{
    var wrapper = jQuery(".likebtn_container:first .likebtn-wrapper:first");
    var properties = [];
    var property_name;
    var entity_regexp;

    if (typeof(entity_name) === "undefined") {
        entity_name = jQuery("#likebtn_entity_name_field").val();
    }
    if (!wrapper || !entity_name) {
        return false;
    }

    // Prepare field names
    jQuery("#settings_form :input").each(function(index, element) {
        var field = jQuery(element);
        var name = field.attr('name');
        var value = field.val();

        if (!name || name.indexOf('likebtn_settings_') == -1 || field.hasClass('disabled')) {
            return;
        }

        // Format value
        if (field.attr('type') == 'checkbox' && !field.is(':checked')) {
            value = '0';
        }
        // Find selected radio
        if (field.attr('type') == 'radio' && !field.is(':checked')) {
            return;
        }

        property_name = name.replace(/^likebtn_settings_/, '');

        // Cut entity name out
        entity_regexp = new RegExp("_"+likebtnEscapeRegExp(entity_name)+"$");
        property_name = property_name.replace(entity_regexp, '');

        // Fetch dynamic fields
        if (property_name == 'addthis_service_codes') {
            value = likebtnGetMultipleSelect2Val("#settings_addthis_service_codes");
        }
        if (property_name == 'popup_content_order') {
            value = likebtnGetMultipleSelect2Val('#settings_popup_content_order');
        }

        properties[property_name] = value;
    });
    
    if (typeof(LikeBtn) !== "undefined") {
        LikeBtn.apply(wrapper[0], properties, ['identifier', 'site_id']);
    }

    // Show shortcode
    likebtnShowShortcode('likebtn_sc', properties);
}

// Escape string against regular expression
function likebtnEscapeRegExp(str) {
  return str.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
}

// Fix preview container
function likebtnFixPreview()
{
    likebtn_preview = jQuery("#preview_fixer");
    var likebtn_preview_offset = likebtn_preview.offset().top - 40;

    jQuery(window).scroll(function(){
        var scroll_top = jQuery(window).scrollTop();
        
        if (likebtn_preview_position == 'fixed' && scroll_top < likebtn_preview_offset) {
            likebtn_preview
                .addClass('likebtn_preview_static')
                .removeClass('likebtn_preview_fixed')
                .width('auto');
            
            jQuery('.likebtn_subpage:first').css({paddingTop: '0'});

            likebtn_preview_position = 'static';

        } else if (likebtn_preview_position == 'static' && scroll_top >= likebtn_preview_offset) {
            likebtn_preview
                .addClass('likebtn_preview_fixed')
                .removeClass('likebtn_preview_static')
                .width(jQuery("#settings_container").width());

            jQuery('.likebtn_subpage:first').css({
                paddingTop: likebtn_preview.height() + parseInt(likebtn_preview.css('marginBottom')) + 'px'
            });

            likebtn_preview_position = 'fixed';
        }
    });    
}

// Refresh plan
function refreshPlan(msg_error, msg_success)
{
    jQuery('#likebtn_refresh_trgr').hide();
    jQuery('#likebtn_refresh_ldr').show();
    jQuery("#likebtn_refresh_msg_wr").hide();
    jQuery("#likebtn_refresh_error").hide();
    jQuery("#likebtn_refresh_success").hide();

    jQuery.ajax({
        type: 'POST',
        dataType: "json",
        url: ajaxurl,
        data: {
            action: 'likebtn_refresh_plan'
        },
        success: function(response) {
            if (typeof(response.reload) != "undefined" && response.reload) {
                location.reload(false);
            } else if (typeof(response.html) != "undefined" && response.html) {
                jQuery("#likebtn_plan_wr").html(response.html);
                jQuery("#likebtn_refresh_success").html(msg_success).show();
                jQuery("#likebtn_refresh_msg_wr").show();
            } else if (typeof(response.message) != "undefined" && response.message) {
                jQuery("#likebtn_refresh_error").html(response.message).show();
                jQuery("#likebtn_refresh_msg_wr").show();
            } else {
                jQuery("#likebtn_refresh_error").html(msg_error).show();
                jQuery("#likebtn_refresh_msg_wr").show();
            }
            jQuery('#likebtn_refresh_trgr').show();
            jQuery('#likebtn_refresh_ldr').hide();

            likebtnApplyTooltips();
        },
        error: function(response) {
            jQuery("#likebtn_refresh_error").html(msg_error).show();
            jQuery("#likebtn_refresh_msg_wr").show();
            jQuery('#likebtn_refresh_trgr').show();
            jQuery('#likebtn_refresh_ldr').hide();
        }
    });
}

// Apply tipsy tooltips
function likebtnApplyTooltips()
{
    jQuery('#likebtn .likebtn_help, #likebtn .premium_feature, #likebtn .likebtn_ttip, #likebtn .likebtn_help_simple').each(function(index, el) {
        var gravity = jQuery(el).attr('data-likebtn_ttip_gr');
        if (gravity) {
            jQuery(el).tipsy({gravity: gravity});
        } else {
            jQuery(el).tipsy({gravity: 's'});
        }
    });
}

// Get LikeBtn account data
function likebtnGetAccountData(url)
{
    // Add domain
    url += '?add_website='+window.location.hostname;

    likebtnPopup(url);
    likebtn_popup_timer = setInterval(likebtnOnGetAccountDataClose, 500);
}

// Track popup close
function likebtnOnGetAccountDataClose()
{
    if (likebtn_popup.closed) {
        clearInterval(likebtn_popup_timer);

        jQuery.ajax({
            type: 'get',
            dataType: 'jsonp',
            url: 'http://likebtn.com/en/customer.php/api',
            data: {
                action: 'account_data',
                domain: window.location.hostname
            },
            success: function(data) {
                if (data.result && data.result == 'success' && data.response) {
                    if (data.response.email) {
                        jQuery("#likebtn_account_email_input").val(data.response.email);
                    }
                    if (data.response.api_key) {
                        jQuery("#likebtn_account_api_key_input").val(data.response.api_key);
                    }
                    if (data.response.site_id) {
                        jQuery("#likebtn_site_id_input").val(data.response.site_id);
                    }
                    accountChange();
                    if (!jQuery("#likebtn_sync_inerval_input").val()) {
                        jQuery("#likebtn_sync_inerval_input").val('5');
                    }
                }
            },
            error: function(data) {
                
            }
        });
    }
}

// Statistics bulk actions
function likebtnStatsBulkAction(action, plan, msg_confirm)
{
    if (typeof(plan) != "undefined" && parseInt(plan) >= plans.vip) {

        if (jQuery("#statistics_container .item_checkbox:checked").size()) {
            if (confirm(msg_confirm)) {
                jQuery("#stats_bulk_action").val(action);
                jQuery("#stats_actions_form").submit();
            } else {
                return false;
            }
        } else {
            alert(likebtn_msg_select_items);
            return false;
        }
    } else {
        alert(likebtn_msg_upgrade);
        return false;
    }
}

// Toggle shortcode container
function likebtnToggleShortcode(id)
{
    likebtnRefreshPreview();
    jQuery('#'+id).toggle();
}

// Show shortcode
function likebtnShowShortcode(id, properties)
{
    var shortcode = '[likebtn identifier="'+likebtn_msg_identifier+'"';
    var value = '';
    for (var name in properties) {
        value = properties[name];
        // Miss default parameters
        if ((typeof(reset_settings[name]) !== undefined && reset_settings[name] === value) ||
            (typeof(reset_settings['settings_'+name]) !== undefined && reset_settings['settings_'+name] === value) ||
            (name == 'addthis_service_codes' && value == likebtnGetDefaultAddthis())
        ) {
            continue;
        }
        value = value.replaceAll('"', '&quot;');
        shortcode += ' '+name+'="'+value+'"';
    }
    shortcode += ']';

    jQuery('#'+id).val(shortcode);
}

// Show widget shortcode
function likebtnWidgetShortcode(mnemonic, no_toggle)
{
    var properties = [];
    var entity_name = [];

    jQuery("#likebtn_widget_"+mnemonic+" :input").each(function(index, element) {
        var field = jQuery(element);
        var name = field.attr('data-property');
        var value = field.val();

        if (!name || field.hasClass('disabled')) {
            return;
        }

        if (name == 'entity_name') {
            return;
        }

        // Format value
        if (field.attr('type') == 'checkbox' && !field.is(':checked')) {
            value = '0';
        }
        // Find selected radio
        if (field.attr('type') == 'radio' && !field.is(':checked')) {
            return;
        }

        properties[name] = value;
    });

    jQuery("#likebtn_widget_"+mnemonic+" :input[data-property='entity_name']:checked").each(function(index, element) {
        entity_name.push(jQuery(element).val());
    });
    properties['entity_name'] = entity_name.join(',');

    var shortcode = '[likebtn_most_liked';

    for (var name in properties) {
        value = properties[name];
        value = value.replaceAll('"', '&quot;');
        shortcode += ' '+name+'="'+value+'"';
    }
    shortcode += ']';

    jQuery('#likebtn_sc_'+mnemonic).val(shortcode);

    if (typeof(no_toggle) === "undefined" || !no_toggle) {
        jQuery('#likebtn_sc_wr_'+mnemonic).toggle();
    }
}