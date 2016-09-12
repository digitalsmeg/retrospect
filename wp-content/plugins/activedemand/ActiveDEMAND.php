<?php

/**
 * Plugin Name: ActiveDEMAND
 * Plugin URI: https://www2.activedemand.com/s/Gnf5n
 * Description: Adds the <a href="https://www2.activedemand.com/s/SW5nU">ActiveDEMAND</a> tracking script to your website. Add custom popups, use shortcodes to embed webforms and dynamic website content.
 * Version: 0.1.25
 * Author: JumpDEMAND Inc.
 * Author URI: https://www2.activedemand.com/s/SW5nU
 * License:GPL-2.0+
 * License URI:http://www.gnu.org/licenses/gpl-2.0.txt
 */

define('ACTIVEDEMAND_VER','0.1.25');

//--------------- AD update path --------------------------------------------------------------------------
function activedemand_update(){
    $key='activedemand_version';
    $version=  get_option($key);
    
    if(ACTIVEDEMAND_VER===$version) return;
    activedemand_plugin_activation();
    update_option($key, ACTIVEDEMAND_VER);
}
add_action('init', 'activedemand_update');


//--------------- AD Server calls -------------------------------------------------------------------------

function activedemand_getHTML($url, $timeout, $args = array())
{

    $fields_string = activedemand_field_string($args);

    if (in_array('curl', get_loaded_extensions())) {
        $ch = curl_init($url . "?" . $fields_string);  // initialize curl with given url
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]); // set  useragent
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // write the response to a variable
        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // follow redirects if any
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout); // max. seconds to execute
        curl_setopt($ch, CURLOPT_FAILONERROR, 1); // stop when it encounters an error
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);//force IP4
        $result = curl_exec($ch);
        curl_close($ch);
    } elseif (function_exists('file_get_contents')){
        $result = file_get_contents($url);
    }

    return $result;
}

function activedemand_postHTML($url, $args, $timeout)
{
    $fields_string = activedemand_field_string($args);

    if (in_array('curl', get_loaded_extensions())) {
        $ch = curl_init($url); // initialize curl with given url
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]); // set  useragent
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // write the response to a variable
       // curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // follow redirects if any
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout); // max. seconds to execute
        curl_setopt($ch, CURLOPT_FAILONERROR, 1); // stop when it encounters an error
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);//force IP4
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        $result = curl_exec($ch);
        if ($result === false) {
            error_log('ActiveDEMAND Web Form error: ' . curl_error($ch));
        }

        curl_close($ch);
    }


    return $result;
}

//enqueue jQuery for popup purposes
add_action('wp_enqueue_scripts', 'activedemand_scripts');

function activedemand_scripts(){
    wp_enqueue_script('jquery');
}
/**
 * Adds Active Demand popups if API Key isset and activedemand_server_showpopups is true
 * 
 * @param string $content
 * @return string $content with popup prefix
 */

function activedemand_process_popup($content=''){
    $options = get_option('activedemand_options_field');
    $show=  get_option('activedemand_server_showpopups');

    if (is_array($options) && array_key_exists('activedemand_appkey', $options) && $show) {
        $popup=activedemand_getHTML("https://api.activedemand.com/v1/smart_blocks/popups" , 10);
        
        $script="<script>jQuery(document).ready(function(){"
                . "jQuery('body').prepend(".  json_encode($popup).");"
                . "});</script>";

        return $script.$content;
    }else{
        return $content;
    }
}

function activedemand_frag_cache_popups($content=''){
    if(defined('W3TC_DYNAMIC_SECURITY')&& function_exists('w3_instance')){
        $prefix= '<!-- mfunc '.W3TC_DYNAMIC_SECURITY.' '
            . 'echo activedemand_process_popup(); -->'
            . '<!-- /mfunc '.W3TC_DYNAMIC_SECURITY.' -->';
        return $prefix.$content;
    }else{
        return activedemand_process_popup($content);
    }
}

add_filter('the_content','activedemand_frag_cache_popups');

function activedemand_field_string($args)
{

    $options = get_option('activedemand_options_field');
    $fields_string="";
    if (array_key_exists('activedemand_appkey', $options)) {
        $activedemand_appkey = $options["activedemand_appkey"];
    } else {
        $activedemand_appkey = "";
    }

    if ("" != $activedemand_appkey) {

        $cookievalue = activedemand_get_cookie_value();
        $url="http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        if(isset($_SERVER['HTTP_REFERER']))
        {
            $referrer=$_SERVER['HTTP_REFERER'];
        }
        else
        {
            $referrer="";
        }
        if ($cookievalue != "") {
            $fields = array(
                'api-key' => $activedemand_appkey,
                'activedemand_session_guid' => activedemand_get_cookie_value(),
                'url' => $url,
                'ip_address' => activedemand_get_ip_address(),
                'referer' => $referrer
            );
        } else {
            $fields = array(
                'api-key' => $activedemand_appkey,
                'url' =>$url,
                'ip_address' => activedemand_get_ip_address(),
                'referer' => $referrer
            );

        }
        if (is_array($args)) {
            $fields = array_merge($fields, $args);
        }
        $fields_string = http_build_query($fields);
    }

    return $fields_string;
}

function activedemand_get_cookie_value()
{

    $cookieValue = "";
    if (false == strpos($_SERVER['REQUEST_URI'], "wp-admin")) {

        //not editing an options page etc.


        if (isset($_COOKIE['activedemand_session_guid'])) {
            $cookieValue = $_COOKIE['activedemand_session_guid'];
        } else {
            $urlParms = $_SERVER['HTTP_HOST'];
            if (NULL != $urlParms) {
                $cookieValue = activedemand_get_GUID();
                $basedomain = activedemand_get_basedomain();
                //get around header warning
                set_error_handler(function() { /* ignore errors */ });
                setcookie('activedemand_session_guid', $cookieValue, time() + (60 * 60 * 24 * 365 * 10), "/", $basedomain);
                restore_error_handler();
            }


        }
    }

    return $cookieValue;
}

function activedemand_get_basedomain()
{
    $result = "";

    $urlParms = $_SERVER['HTTP_HOST'];
    if (NULL != $urlParms) {
        $result= str_replace('www.', "", $urlParms);
    }
    return $result;
}

// create a session if one doesn't exist
function activedemand_get_GUID()
{
    if (function_exists('com_create_guid')) {
        return com_create_guid();
    } else {
        mt_srand((double)microtime() * 10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = substr($charid, 0, 8) . $hyphen
            . substr($charid, 8, 4) . $hyphen
            . substr($charid, 12, 4) . $hyphen
            . substr($charid, 16, 4) . $hyphen
            . substr($charid, 20, 12);
        return $uuid;
    }
}


// get the ip address
function activedemand_get_ip_address()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

//--------------- Admin Menu -------------------------------------------------------------------------
function activedemand_menu()
{
    global $activedemand_plugin_hook;
    $activedemand_plugin_hook = add_options_page('ActiveDEMAND options', 'ActiveDEMAND', 'manage_options', 'activedemand_options', 'activedemand_plugin_options');
    add_action('admin_init', 'register_activedemand_settings');

}

function register_activedemand_settings()
{
    register_setting('activedemand_options', 'activedemand_options_field');
    register_setting('activedemand_options', 'activedemand_server_showpopups');
}


function activedemand_enqueue_scripts()
{
    wp_enqueue_script('ActiveDEMAND-Track', 'https://activedemand-static.s3.amazonaws.com/public/javascript/jquery.tracker.compiled.js.gz');
}


function activedemand_admin_enqueue_scripts()
{
    global $pagenow;

    if ('post.php' == $pagenow || 'post-new.php' == $pagenow) {
        wp_enqueue_script('jquery-ui-dialog');
        wp_enqueue_style('wp-jquery-ui-dialog');

    }
}


function activedemand_process_form_shortcode($atts, $content = null)
{

    //[activedemand_form id='123']

    $id = "";
    //$id exists after this call.
    extract(shortcode_atts(array('id' => ''), $atts));
    $options = get_option('activedemand_options_field');


    $activedemand_ignore_form_style=false;

    if (array_key_exists('activedemand_ignore_form_style', $options)) {
        $activedemand_ignore_form_style = $options['activedemand_ignore_form_style'];
    }

    $form_str = "";
    if (is_numeric($id)) {
        $form_str = activedemand_getHTML("https://api.activedemand.com/v1/forms/" . $id, 10, array('form_id' => $id,'exclude_css' => $activedemand_ignore_form_style,'basedomain'=>activedemand_get_basedomain()));

    }


    if ($form_str != "") {
        //replace \n to ensure WP auto format does not mess with our CSS
        $form_str = str_replace("\n", "", $form_str);

    }
    return $form_str;
}

function activedemand_process_block_shortcode($atts, $content = null)
{
//[activedemand_block id='123']

    $id = "";
    //$id exists after this call.
    extract(shortcode_atts(array('id' => ''), $atts));
    $options = get_option('activedemand_options_field');


    if (array_key_exists('activedemand_appkey', $options)) {
        $activedemand_appkey = $options['activedemand_appkey'];
    }

    $block_str = "";

    if (is_numeric($id)) {
        $block_str = activedemand_getHTML("https://api.activedemand.com/v1/smart_blocks/" . $id, 10, array('block_id' => $id));

    }

    if ($block_str != "") {
        //replace \n to ensure WP auto format does not mess with our CSS
        $block_str = str_replace("\n", "", $block_str);

    }

    return $block_str;
}

function activedemand_plugin_action_links($links, $file)
{
    static $this_plugin;

    if (!$this_plugin) {
        $this_plugin = plugin_basename(__FILE__);
    }

    if ($file == $this_plugin) {
        $settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=activedemand_options">Settings</a>';
        array_unshift($links, $settings_link);
    }

    return $links;
}

function activedemand_stale_cart_form($form_xml=NULL){
    if(!isset($form_xml)){
        $url = "https://api.activedemand.com/v1/forms.xml";
        $str = activedemand_getHTML($url, 10);
        $form_xml = simplexml_load_string($str);
    }
    $options = get_option('activedemand_options_field');
    $activedemand_form_id = isset($options["activedemand_woocommerce_stalecart_form_id"])?
    $options["activedemand_woocommerce_stalecart_form_id"]: 0;
    $hours=isset($options['woocommerce_stalecart_hours']) ? $options['woocommerce_stalecart_hours'] : 2 ;
    
    ?>
                    <tr valign="top">
                    <th scope="row">WooCommerce Carts:</th>
                    <td><?php
                        echo "<select name=\"activedemand_options_field[activedemand_woocommerce_stalecart_form_id]\">";
                        echo "<option value='0'";
                        if (0 == $activedemand_form_id) echo "selected='selected'";
                        echo ">Do Nothing</option>";
                        foreach ($form_xml->children() as $child) {
                            echo "<option value='";
                            echo $child->id;
                            echo "'";
                            if ($child->id == $activedemand_form_id) echo "selected='selected'";
                            echo ">Submit To Form: ";
                            echo $child->name;
                            echo "</option>";
                        }
                        echo "</select>";

                            ?>
                            <div style="font-size: small;"><strong>Note:</strong> The selected ActiveDEMAND Form must
                                have <strong>[First
                                    Name]</strong>-<strong>[Last Name]</strong>-<strong>[Email
                                    Address*]</strong>-<strong>[Product Data]</strong>
                                     as the first 4 fields. 
                                     Ensure that the [Product Data] field is a textarea.
                            </div>
                            <br/>

                        Send Stale carts to ActiveDEMAND after it has sat for:<br>
                        <input type="number" min="1" 
                               name="activedemand_options_field[woocommerce_stalecart_hours]" 
                               value="<?php echo $hours;?>"> hours
                    </td>
<?php
    
}

function activedemand_plugin_options(){
    $woo_commerce_installed=false;

    $options = get_option('activedemand_options_field');
    $form_xml="";


    if (!array_key_exists('activedemand_appkey', $options)) {
        $options['activedemand_appkey'] = "";
    }

    $activedemand_appkey = $options['activedemand_appkey'];

    if (!array_key_exists('activedemand_ignore_form_style', $options)) {
        $options['activedemand_ignore_form_style'] = 0;
    }
    if (!array_key_exists('activedemand_defer_script', $options)) {
        $options['activedemand_defer_script'] = 0;
    }

    if (array_key_exists('activedemand_woo_commerce_order_form_id', $options)) {
        $activedemand_woo_commerce_order_form_id = $options["activedemand_woo_commerce_order_form_id"];

    } else {
        $activedemand_woo_commerce_order_form_id = 0;
    }

    if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {

        $woo_commerce_installed = true;
    }
    if (array_key_exists('activedemand_woo_commerce_use_status', $options)) {


        $activedemand_woo_commerce_use_status = $options["activedemand_woo_commerce_use_status"];
        if (!array_key_exists("pending", $activedemand_woo_commerce_use_status)) {
            $activedemand_woo_commerce_use_status["pending"] = FALSE;
        }
        if (!array_key_exists("processing", $activedemand_woo_commerce_use_status)) {
            $activedemand_woo_commerce_use_status["processing"] = FALSE;
        }
        if (!array_key_exists("on-hold", $activedemand_woo_commerce_use_status)) {
            $activedemand_woo_commerce_use_status["on-hold"] = FALSE;
        }
        if (!array_key_exists("completed", $activedemand_woo_commerce_use_status)) {
            $activedemand_woo_commerce_use_status["completed"] = FALSE;
        }
        if (!array_key_exists("refunded", $activedemand_woo_commerce_use_status)) {
            $activedemand_woo_commerce_use_status["refunded"] = FALSE;
        }
        if (!array_key_exists("cancelled", $activedemand_woo_commerce_use_status)) {
            $activedemand_woo_commerce_use_status["cancelled"] = FALSE;
        }
        if (!array_key_exists("failed", $activedemand_woo_commerce_use_status)) {
            $activedemand_woo_commerce_use_status["failed"] = FALSE;
        }


    } else {

        $activedemand_woo_commerce_use_status = array(
            "pending" => FALSE,
            "processing" => FALSE,
            "on-hold" => FALSE,
            "completed" => TRUE,
            "refunded" => FALSE,
            "cancelled" => FALSE,
            "failed" => FALSE

        );

        $options["activedemand_woo_commerce_use_status"] = $activedemand_woo_commerce_use_status;
    }
    update_option('activedemand_options_field', $options);

    ?>


    <div class="wrap">
        <img src="<?php echo get_base_url() ?>/images/ActiveDEMAND-Transparent.png"/>

        <h1>Settings</h1>
        <?php if ("" == $activedemand_appkey || !isset($activedemand_appkey)) { ?>
            <h2>Your ActiveDEMAND Account</h2><br/>
            You will require an <a href="http://1jp.cc/s/vaiXT">ActiveDEMAND</a> account to use this plugin. With an
                                                                                 ActiveDEMAND account you will be able
                                                                                 to:<br/>
            <ul style="list-style-type:circle;  margin-left: 50px;">
                <li>Build Webforms for your pages, posts, sidebars, etc</li>
                <li>Build Dynamic Content Blocks for your pages, posts, sidebars, etc</li>
                <ul style="list-style-type:square;  margin-left: 50px;">
                    <li>Dynamically swap content based on GEO-IP data</li>
                    <li>Automatically change banners based on campaign duration</li>
                    <li>Stop showing forms to people who have already subscribed</li>
                </ul>
                <li>Deploy Popups and Subscriber bars</li>
                <li>Automatically send emails to those who fill out your web forms</li>
                <li>Automatically send emails to you when a form is filled out</li>
                <li>Send email campaigns to your subscribers</li>
                <li>Build your individual blog posts and have them automatically be posted on a schedule</li>
                <li>Bulk import blog posts and have them post on a defined set of times and days</li>
            </ul>

            <div>
                <h3>To sign up for your ActiveDEMAND account, click <a
                        href="http://1jp.cc/s/SW5nU"><strong>here</strong></a>
                </h3>

                <p>
                    You will need to enter your application key in order to enable the form shortcodes. Your can find
                    your
                    ActiveDEMAND API key in your account settings:

                </p>

                <p>
                    <img src="<?php echo get_base_url() ?>/images/Screenshot2.png"/>
                </p>
            </div>
        <?php } ?>
        <form method="post" action="options.php">
            <?php
            wp_nonce_field('update-options');
            settings_fields('activedemand_options');
            ?>

            <h3>ActiveDEMAND Plugin Options</h3>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">ActiveDEMAND API Key</th>
                    <td><input style="width:400px" type='text' name="activedemand_options_field[activedemand_appkey]"
                               value="<?php echo $activedemand_appkey; ?>"/></td>
                </tr>
                <?php if ("" != $activedemand_appkey) {
                    //get Forms
                    $url = "https://api.activedemand.com/v1/forms.xml";
                    $str = activedemand_getHTML($url, 10);
                    $form_xml = simplexml_load_string($str);

                    //get Blocks
                    $url = "https://api.activedemand.com/v1/smart_blocks.xml";
                    $str = activedemand_getHTML($url, 10);
                    $block_xml = simplexml_load_string($str);
                    
                    $show_popup=get_option('activedemand_server_showpopups', FALSE);
                ?>    
                    <tr valign="top">
                        <th scope="row">Enable Popup Pre-Loading?</th>
                        <td><input type="checkbox" name="activedemand_server_showpopups" value="1" 
                            <?php checked($show_popup,1);?> /></td></tr>
                
                <?php } ?>
                
                <?php if ("" != $form_xml) { ?>
                    <tr valign="top">
                        <th scope="row">Style Forms in WordPress</th>
                        <td>
                            <input type="checkbox" name="activedemand_options_field[activedemand_ignore_form_style]"
                                   value="1" <?php checked($options['activedemand_ignore_form_style'], 1); ?> />
                        </td>
                    </tr>
                    
                <?php } ?>
                <tr valign="top">
                    <th scope="row">Defer Script Loading?</th>
                    <td>
                        <input type="checkbox" name="activedemand_options_field[activedemand_defer_script]"
                               value="1" <?php checked($options['activedemand_defer_script'], 1); ?> />
                    </td>
                </tr>
                <?php
                if ($woo_commerce_installed && "" != $form_xml) {
                ?>
                <tr valign="top">
                    <th scope="row">On WooCommerce Order:</th>
                    <td><?php
                        echo "<select name=\"activedemand_options_field[activedemand_woo_commerce_order_form_id]\">";
                        echo "<option value='0'";
                        if ("0" == $activedemand_woo_commerce_order_form_id) echo "selected='selected'";
                        echo ">Do Nothing</option>";
                        foreach ($form_xml->children() as $child) {
                            echo "<option value='";
                            echo $child->id;
                            echo "'";
                            if ($child->id == $activedemand_woo_commerce_order_form_id) echo "selected='selected'";
                            echo ">Submit To Form: ";
                            echo $child->name;
                            echo "</option>";
                        }
                        echo "</select>";

                        if (0 != $activedemand_woo_commerce_order_form_id) {
                            ?>
                            <div style="font-size: small;"><strong>Note:</strong> The selected ActiveDEMAND Form must
                                have <strong>[First
                                    Name]</strong>-<strong>[Last Name]</strong>-<strong>[Email
                                    Address*]</strong>-<strong>[Order
                                    Value]</strong>-<strong>[Order State Change]</strong>-<strong>[Order ID]</strong> as
                                the
                                first 6 fields. Ensure that only the [Email Address*] field is required.
                            </div>
                            <br/>
                            Submit Forms to ActiveDEMAND when an WooCommerce order status changes to:
                            <style type="text/css">
                                table.wootbl th {

                                    padding: 5px;
                                }

                                table.wootbl td {

                                    padding: 5px;
                                }
                            </style>
                            <table class="wootbl" style="margin-left: 25px">
                                <tr>
                                    <th>Pending</th>
                                    <td><input type="checkbox"
                                               name="activedemand_options_field[activedemand_woo_commerce_use_status][pending]"
                                               value="1" <?php checked($activedemand_woo_commerce_use_status['pending'], 1); ?> />
                                    </td>
                                </tr>
                                <tr>
                                    <th>Processing</th>
                                    <td><input type="checkbox"
                                               name="activedemand_options_field[activedemand_woo_commerce_use_status][processing]"
                                               value="1" <?php checked($activedemand_woo_commerce_use_status['processing'], 1); ?> />
                                    </td>
                                </tr>
                                <tr>
                                    <th>On Hold</th>
                                    <td><input type="checkbox"
                                               name="activedemand_options_field[activedemand_woo_commerce_use_status][on-hold]"
                                               value="1" <?php checked($activedemand_woo_commerce_use_status['on-hold'], 1); ?> />
                                    </td>
                                </tr>
                                <tr>
                                    <th>Completed</th>
                                    <td><input type="checkbox"
                                               name="activedemand_options_field[activedemand_woo_commerce_use_status][completed]"
                                               value="1" <?php checked($activedemand_woo_commerce_use_status['completed'], 1); ?> />
                                    </td>
                                </tr>
                                <tr>
                                    <th>Refunded</th>
                                    <td><input type="checkbox"
                                               name="activedemand_options_field[activedemand_woo_commerce_use_status][refunded]"
                                               value="1" <?php checked($activedemand_woo_commerce_use_status['refunded'], 1); ?> />
                                    </td>
                                </tr>
                                <tr>
                                    <th>Cancelled</th>
                                    <td><input type="checkbox"
                                               name="activedemand_options_field[activedemand_woo_commerce_use_status][cancelled]"
                                               value="1" <?php checked($activedemand_woo_commerce_use_status['cancelled'], 1); ?> />
                                    </td>
                                </tr>
                                <tr>
                                    <th>Failed</th>
                                    <td><input type="checkbox"
                                               name="activedemand_options_field[activedemand_woo_commerce_use_status][failed]"
                                               value="1" <?php checked($activedemand_woo_commerce_use_status['failed'], 1); ?> />
                                    </td>
                                </tr>

                            </table>
                        <?php } ?>
                    </td>
                    <?php 
                    activedemand_stale_cart_form($form_xml);
                        } ?>

                </tr>
                <tr>
                    <td></td>
                    <td>
                        <p class="submit">
                            <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>"/>
                        </p>
                    </td>
                </tr>
            </table>
        </form>

        <?php if ("" != $activedemand_appkey) { ?>
            <div>

                <h2>Using ActiveDEMAND Web Forms and Dynamic Content Blocks</h2>

                <p> The <a href="http://1jp.cc/s/SW5nU">ActiveDEMAND</a> plugin adds a
                    tracking script to your
                    WordPress
                    pages. This plugin offers the ability to use web form and content block shortcodes on your pages,
                    posts, and
                    sidebars
                    that
                    will render an ActiveDEMAND Web Form/Dynamic Content block. This allows you to maintain your dynamic
                    content, form styling, and
                    configuration
                    within
                    ActiveDEMAND.
                </p>

                <p>
                    In your visual editor, look for the 'Insert ActiveDEMAND Shortcode' button:<br/>
                    <img
                        src="<?php echo get_base_url() ?>/images/Screenshot3.png"/>.
                </p>
                <table>
                    <tr>
                        <td style="padding:15px;vertical-align: top;">
                            <?php if ("" != $form_xml) { ?>
                                <h3>Available Web Form Shortcodes</h3>

                                <style scoped="scoped" type="text/css">
                                    table#shrtcodetbl {
                                        border: 1px solid black;
                                    }

                                    table#shrtcodetbl tr {
                                        background-color: #ffffff;
                                    }

                                    table#shrtcodetbl tr:nth-child(even) {
                                        background-color: #eeeeee;
                                    }

                                    table#shrtcodetbl tr td {
                                        padding: 10px;

                                    }

                                    table#shrtcodetbl th {
                                        color: white;
                                        background-color: black;
                                        padding: 10px;
                                    }
                                </style>
                                <table id="shrtcodetbl" style="width:100%">
                                    <tr>
                                        <th>Form Name</th>
                                        <th>Shortcode</th>
                                    </tr>
                                    <?php
                                    foreach ($form_xml->children() as $child) {
                                        echo "<tr><td>";
                                        echo $child->name;
                                        echo "</td>";
                                        echo "<td>[activedemand_form id='";
                                        echo $child->id;
                                        echo "']</td>";
                                    }
                                    ?>
                                </table>


                            <?php } else { ?>
                                <h2>No Web Forms Configured</h2>
                                <p>To use the ActiveDEMAND web form shortcodes, you will first have to add some Web
                                    Forms
                                    to
                                    your
                                    account in ActiveDEMAND. Once you do have Web Forms configured, the available
                                    shortcodes
                                    will
                                    be
                                    displayed here.</p>

                            <?php } ?>
                        </td>
                        <td style="padding:15px;vertical-align: top;">
                            <?php if ("" != $block_xml) { ?>
                                <h3>Available Dynamic Content Block Shortcodes</h3>

                                <style scoped="scoped" type="text/css">
                                    table#shrtcodetbl {
                                        border: 1px solid black;
                                    }

                                    table#shrtcodetbl tr {
                                        background-color: #ffffff;
                                    }

                                    table#shrtcodetbl tr:nth-child(even) {
                                        background-color: #eeeeee;
                                    }

                                    table#shrtcodetbl tr td {
                                        padding: 10px;

                                    }

                                    table#shrtcodetbl th {
                                        color: white;
                                        background-color: black;
                                        padding: 10px;
                                    }
                                </style>
                                <table id="shrtcodetbl" style="width:100%">
                                    <tr>
                                        <th>Block Name</th>
                                        <th>Shortcode</th>
                                    </tr>
                                    <?php
                                    foreach ($block_xml->children() as $child) {
                                        echo "<tr><td>";
                                        echo $child->name;
                                        echo "</td>";
                                        echo "<td>[activedemand_block id='";
                                        echo $child->id;
                                        echo "']</td>";
                                    }
                                    ?>
                                </table>


                            <?php } else { ?>
                                <h2>No Dynamic Blocks Configured</h2>
                                <p>To use the ActiveDEMAND Dynamic Content Block shortcodes, you will first have to add
                                    some Dynamic Content Blocks
                                    to
                                    your
                                    account in ActiveDEMAND. Once you do have Dynamic Blocks configured, the available
                                    shortcodes
                                    will
                                    be
                                    displayed here.</p>

                            <?php } ?>
                        </td>
                    </tr>
                </table>
            </div>
        <?php } ?>
    </div>
    <?php
}

function activedemand_plugin_help($text, $screen_id, $screen)
{
    global $activedemand_plugin_hook;
    if ($screen_id == $activedemand_plugin_hook) {

        $text = "<h5>Need help with the ActiveDEMAND plugin?</h5>";
        $text .= "<p>Check out the documentation and support forums for help with this plugin.</p>";
        $text .= "<a href=\"http://wordpress.org/extend/plugins/activedemand/installation/\">Documentation</a><br /><a href=\"http://wordpress.org/tags/activedemand?forum_id=10\">Support forums</a><br /><a href=\"https://support.activedemand.com\">ActiveDEMAND Support portal</a>";

    }
    return $text;
}

function get_base_url()
{
    return plugins_url(null, __FILE__);
}

function activedemand_register_tinymce_javascript($plugin_array)
{
    $plugin_array['activedemand'] = plugins_url('/js/tinymce-plugin.js', __FILE__);
    return $plugin_array;
}


function activedemand_buttons()
{
    add_filter("mce_external_plugins", "activedemand_add_buttons");
    add_filter('mce_buttons', 'activedemand_register_buttons');
}

function activedemand_add_buttons($plugin_array)
{
    $plugin_array['activedemand'] = get_base_url() . '/includes/activedemand-plugin.js';
    return $plugin_array;
}

function activedemand_register_buttons($buttons)
{
    array_push($buttons, 'insert_form_shortcode');
    return $buttons;
}


function add_editor()
{

    global $pagenow;

    // Add html for shortcodes popup
    if ('post.php' == $pagenow || 'post-new.php' == $pagenow) {
        include 'partials/tinymce-editor.php';
    }

}

function activedemand_clean_url($url)
{
    $options = get_option('activedemand_options_field');

    $defer_script = FALSE;

    if(FALSE!=$options)
    {
        if (array_key_exists('activedemand_defer_script', $options)) {
            $defer_script = $options['activedemand_defer_script'];
        }
    }


    if (FALSE === strpos($url, 'jquery.tracker.compiled.js.gz')) { // not our file
        return $url;
    }

    if (TRUE == $defer_script) {
        // Must be a ', not "!
        return "$url' defer='defer";
    }
    return $url;

}

//Constant used to track stale carts
define('AD_CARTTIMEKEY', 'ad_last_cart_update');

/**
 * Adds cart timestamp to usermeta
 */
function activedemand_woocommerce_cart_update(){
    $user_id=get_current_user_id();
    update_user_meta($user_id, AD_CARTTIMEKEY, time());
}
add_action('woocommerce_cart_updated', 'activedemand_woocommerce_cart_update');

/**
 * Deletes timestamp from current user meta
 */
function activedemand_woocommerce_cart_emptied(){
    $user_id=get_current_user_id();
    delete_user_meta($user_id, AD_CARTTIMEKEY);
}
add_action('woocommerce_cart_emptied', 'activedemand_woocommerce_cart_emptied');

/**Periodically scans, and sends stale carts to active demand
 * 
 * @global object $wpdb
 * 
 * @uses activedemand_send_stale_carts function to process and send
 */

function activedemand_woocommerce_scan_stale_carts(){
    global $wpdb;
    $options=get_option('activedemand_options_field');
    $hours=$options['woocommerce_stalecart_hours'];
    
    
    $stale_secs=$hours*60*60;
    
    $carts=$wpdb->get_results('SELECT * FROM '.$wpdb->usermeta.' WHERE meta_key='.AD_CARTTIMEKEY);
    
    $stale_carts=array();
    $i=0;
    foreach($carts as $cart){
        if((time()-(int) $cart->meta_value)>$stale_secs){
            $stale_carts[$i]['user_id']=$cart->user_id;
            $stale_carts[$i]['cart']=  get_user_meta($cart->user_id, '_woocommerce_persistent_cart', TRUE);
        }
    }
    activedemand_send_stale_carts($stale_carts);    
}

add_action('activedemand_hourly', 'activedemand_woocommerce_scan_stale_carts');

register_activation_hook(__FILE__,'activedemand_plugin_activation');

function activedemand_plugin_activation(){
    if(!wp_next_scheduled('activedemand_hourly')) wp_schedule_event(time(), 'hourly', 'activedemand_hourly');
}

register_deactivation_hook(__FILE__, 'activedemand_plugin_deactivation');

function activedemand_plugin_deactivation(){
    wp_clear_scheduled_hook('activedemand_hourly');
}

/**Processes and send stale carts
 * Delete the timestamp so carts are only used once
 * 
 * @param array $stale_carts
 * 
 * @used-by activedemand_woocommerce_scan_stale_carts
 * @uses function _activedemand_send_stale cart to send each cart individually
 */
function activedemand_send_stale_carts($stale_carts){
    foreach($stale_carts as $cart){
        $form_data=array();
        $user=new WP_User($cart['user_id']);
        
        $form_data['first_name'] = $user->user_firstname;
        $form_data['last_name'] = $user->user_lastname;
        $form_data['email_address'] = $user->user_email;
        
        $products=$cart['cart']['cart'];
        $form_data['product_data']='';
        
        foreach($products as $product){
            $product_name=get_the_title($product['product_id']);
            $form_data['product_data'].="Product Name: $product_name \n"
                    . "Product price: ".$product['price'].'\n'
                    . 'Product Qty: '.$product['quantity'].'\n'
                    . 'Total: '.$product['line_total'].'\n\n';
        }
        _activedemand_send_stale_cart($form_data);
        delete_user_meta($user->ID, AD_CARTTIMEKEY);
    }
}

/**Sends individual carts to activedemand form
 * 
 * @param array $form_data
 */
function _activedemand_send_stale_cart($form_data){
    $options = get_option('activedemand_options_field');
    $activedemand_form_id = $options["activedemand_woocommerce_stalecart_form_id"];
    
    $form_str = activedemand_getHTML("https://api.activedemand.com/v1/forms/fields.xml", 10, array('form_id' => $activedemand_form_id));
    $form_xml = simplexml_load_string($form_str);
    
    
    if ($form_xml->children()->count() >= 4) {
        $fields = array();
        $i = 0;
        foreach ($form_xml->children() as $child) {

            if (!array_key_exists(urlencode($child->key), $fields)) {
                $fields[urlencode($child->key)] = array();
            }
            
            switch ($i) {
                case 0:
                    array_push($fields[urlencode($child->key)], $form_data['first_name']);
                    break;
                case 1:
                    array_push($fields[urlencode($child->key)], $form_data['last_name']);
                    break;
                case 2:
                    array_push($fields[urlencode($child->key)], $form_data['email_address']);
                    break;
                case 3:
                    array_push($fields[urlencode($child->key)], $form_data['product_data']);
                    break;
            }

                        $i++;
        }
    activedemand_postHTML("https://api.activedemand.com/v1/forms/" . $activedemand_form_id, $fields, 5);
    }                
}

function activedemand_woocommerce_order_status_changed($order_id, $order_status_old, $order_status_new)
{
    //post that this person has reviewed their account page.

    $options = get_option('activedemand_options_field');
    if (array_key_exists('activedemand_appkey', $options)) {
        $activedemand_appkey = $options["activedemand_appkey"];
    }

    if (array_key_exists('activedemand_woo_commerce_use_status', $options)) {
        $activedemand_woo_commerce_use_status = $options["activedemand_woo_commerce_use_status"];
    } else {
        $activedemand_woo_commerce_use_status = array('none' => 'none');
    }

    if (array_key_exists('activedemand_woo_commerce_order_form_id', $options)) {
        $activedemand_woo_commerce_order_form_id = $options["activedemand_woo_commerce_order_form_id"];

    } else {
        $activedemand_woo_commerce_order_form_id = "0";
    }

    $execute_form_submit = ("" != $activedemand_appkey) && ("0" != $activedemand_woo_commerce_order_form_id) && ("" != $activedemand_woo_commerce_order_form_id) && array_key_exists($order_status_new, $activedemand_woo_commerce_use_status);
    if ($execute_form_submit) {
        $execute_form_submit = $activedemand_woo_commerce_use_status[$order_status_new];
    }


    //we need an email address and a form ID
    if ($execute_form_submit) {
        $order = new WC_Order($order_id);
        $user_id = (int)$order->get_user_id();

        if (0 == $user_id) {
            $first_name = $order->billing_first_name;
            $last_name = $order->billing_last_name;
            $email_address = $order->billing_email;

        } else {
            $guest = FALSE;

            $current_user = get_userdata($user_id);
            $first_name = $current_user->user_firstname;
            $last_name = $current_user->user_lastname;
            $email_address = $current_user->user_email;

        }


        if (("" != $email_address) && ('0' != $activedemand_woo_commerce_order_form_id)) {

            $form_str = $form_str = activedemand_getHTML("https://api.activedemand.com/v1/forms/fields.xml", 10, array('form_id' => $activedemand_woo_commerce_order_form_id));
            $form_xml = simplexml_load_string($form_str);


            if ("" != $form_xml) {

                if ($form_xml->children()->count() >= 6) {
                    $fields = array();
                    $i = 0;
                    foreach ($form_xml->children() as $child) {

                        if (!array_key_exists(urlencode($child->key), $fields)) {
                            $fields[urlencode($child->key)] = array();
                        }
                        switch ($i) {
                            case 0:
                                array_push($fields[urlencode($child->key)], $first_name);
                                break;
                            case 1:
                                array_push($fields[urlencode($child->key)], $last_name);
                                break;
                            case 2:
                                array_push($fields[urlencode($child->key)], $email_address);
                                break;
                            case 3:
                                array_push($fields[urlencode($child->key)], $order->get_total());
                                break;
                            case 4:
                                array_push($fields[urlencode($child->key)], $order_status_new);
                                break;
                            case 5:
                                array_push($fields[urlencode($child->key)], $order_id);
                                break;
                        }

                        $i++;


                    }


                    activedemand_postHTML("https://api.activedemand.com/v1/forms/" . $activedemand_woo_commerce_order_form_id, $fields, 5);

                }
            } else {
//                error_log("no form fields");
            }


            //$order_status_new;


        }


    } else {
        //      error_log("Not Processing ADForm Submit");
    }//execute form submit


}


if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {


    $options = get_option('activedemand_options_field');

    //check to see if we have an API key, if we do not, zero integration is possible

    $activedemand_appkey = "";

    if (is_array($options) && array_key_exists('activedemand_appkey', $options)) {
        $activedemand_appkey = $options["activedemand_appkey"];

    }


    if ("" != $activedemand_appkey) {

        add_action('woocommerce_order_status_changed', 'activedemand_woocommerce_order_status_changed', 10, 3);
    }

}

function activedemand_prefilter_sc($content){
    if(!defined('W3TC_DYNAMIC_SECURITY') || !function_exists('w3_instance')) return $content;
    
    $output=$content;
    $shortcodes=array('activedemand_form','activedemand_block','fakecode');
    
    foreach($shortcodes as $sc){
        $output=preg_replace("/\[$sc(.*?)?\]/", "<!-- mfunc ".W3TC_DYNAMIC_SECURITY.' echo do_shortcode($0); -->'
                . '<!-- /mfunc '.W3TC_DYNAMIC_SECURITY.' -->', $content);   
    }
    
    return $output;
}

add_filter('the_content', 'activedemand_prefilter_sc',1);
add_filter('widget_text', 'activedemand_prefilter_sc');

//defer our script loading
add_filter('clean_url', 'activedemand_clean_url', 11, 1);
add_filter('contextual_help', 'activedemand_plugin_help', 10, 3);
add_action('wp_enqueue_scripts', 'activedemand_enqueue_scripts');

add_action('admin_enqueue_scripts', 'activedemand_admin_enqueue_scripts');


add_shortcode('activedemand_form', 'activedemand_process_form_shortcode');
add_shortcode('activedemand_block', 'activedemand_process_block_shortcode');
add_action('admin_menu', 'activedemand_menu');
add_filter('plugin_action_links', 'activedemand_plugin_action_links', 10, 2);


//widgets
// add new buttons
add_action('init', 'activedemand_buttons');
add_action('in_admin_footer', 'add_editor');

?>
