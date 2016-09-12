<?php
/*
  Plugin Name: Retrospect Like button
  Plugin URI: http://likebtn.com
  Description: Add a Like Button to posts - customized by Firespike LLC
  Version: 999999.0
  Author: Firepsike LLC (likebtn)
  Author URI: http://firespike.com
 */
 
 /* format returned
 lb_json({"p":9,"di":0,"e":"","is":[{"ha":"74fe34f83acf","l":1,"d":0}]})
 */

// Debug
// ini_set('display_errors', 'On');
// ini_set('error_reporting', E_ALL);

// Plugin version
define('LIKEBTN_VERSION', '999999.0');
// Current DB version
define('LIKEBTN_DB_VERSION', 7);

// i18n domain
define('LIKEBTN_I18N_DOMAIN', 'likebtn-like-button');

// LikeBtn plans
define('LIKEBTN_PLAN_FREE', 0);
define('LIKEBTN_PLAN_PLUS', 1);
define('LIKEBTN_PLAN_PRO', 2);
define('LIKEBTN_PLAN_VIP', 3);
define('LIKEBTN_PLAN_ULTRA', 4);
define('LIKEBTN_PLAN_TRIAL', 9);
//update_option('likebtn_plan', LIKEBTN_PLAN_FREE);

// Flag added to entity excerpts
define('LIKEBTN_LIST_FLAG', '_likebtn_list');

// entity names
define('LIKEBTN_ENTITY_POST', 'post');
define('LIKEBTN_ENTITY_POST_LIST', 'post'.LIKEBTN_LIST_FLAG);
define('LIKEBTN_ENTITY_PAGE', 'page');
define('LIKEBTN_ENTITY_PAGE_LIST', 'page'.LIKEBTN_LIST_FLAG);
define('LIKEBTN_ENTITY_COMMENT', 'comment');
define('LIKEBTN_ENTITY_CUSTOM_ITEM', 'custom_item');
define('LIKEBTN_ENTITY_USER', 'user'); // invisible type, used for bbPress
define('LIKEBTN_ENTITY_PRODUCT', 'product'); // WooCommerce
define('LIKEBTN_ENTITY_PRODUCT_LIST', 'product'.LIKEBTN_LIST_FLAG); // WooCommerce
define('LIKEBTN_ENTITY_BP_ACTIVITY_POST', 'bp_activity_post');
define('LIKEBTN_ENTITY_BP_ACTIVITY_UPDATE', 'bp_activity_update');
define('LIKEBTN_ENTITY_BP_ACTIVITY_COMMENT', 'bp_activity_comment');
define('LIKEBTN_ENTITY_BP_ACTIVITY_TOPIC', 'bp_activity_topic');
define('LIKEBTN_ENTITY_BP_MEMBER', 'bp_member');
define('LIKEBTN_ENTITY_BBP_POST', 'bbp_post');
define('LIKEBTN_ENTITY_BBP_USER', 'bbp_user');

// position
define('LIKEBTN_POSITION_TOP', 'top');
define('LIKEBTN_POSITION_BOTTOM', 'bottom');
define('LIKEBTN_POSITION_BOTH', 'both');

// alignment
define('LIKEBTN_ALIGNMENT_LEFT', 'left');
define('LIKEBTN_ALIGNMENT_CENTER', 'center');
define('LIKEBTN_ALIGNMENT_RIGHT', 'right');

// BuddyPress xprofile object type used in syncing
define('LIKEBTN_BP_XPROFILE_OBJECT_TYPE', 'data');

/*define('LIKEBTN_POST_VIEW_MODE_FULL', 'full');
define('LIKEBTN_POST_VIEW_MODE_EXCERPT', 'excerpt');
define('LIKEBTN_POST_VIEW_MODE_BOTH', 'both');*/

// statistics page size
define('LIKEBTN_STATISTIC_PAGE_SIZE', 50);

// show review link after this period
define('LIKEBTN_REVIEW_LINK_PERIOD', '1 month');

// item table name
define('LIKEBTN_TABLE_ITEM', 'likebtn_item');

// custom fields names
define('LIKEBTN_META_KEY_LIKES', 'Likes');
define('LIKEBTN_META_KEY_DISLIKES', 'Dislikes');
define('LIKEBTN_META_KEY_LIKES_MINUS_DISLIKES', 'Likes minus dislikes');
global $likebtn_custom_fields;
$likebtn_custom_fields = array(
    LIKEBTN_META_KEY_LIKES,
    LIKEBTN_META_KEY_DISLIKES,
    LIKEBTN_META_KEY_LIKES_MINUS_DISLIKES,
);

// default widget title length
define('LIKEBTN_WIDGET_TITLE_LENGTH', 100);
define('LIKEBTN_WIDGET_EXCERPT_LENGTH', 200);

// Main website address
define('LIKEBTN_WEBSITE_MAIN_PROTOCOL', 'http');
define('LIKEBTN_WEBSITE_DOMAIN', 'likebtn.com');

// entities for which plugin can be enabled
//global $likebtn_entities;
//$likebtn_entities = _likebtn_get_entities($likebtn_entities);

// post format: just to translate
$post_formats = array(
    'standard' => __('Standard'),
    'aside' => __('Aside'),
    'image' => __('Image'),
    'link' => __('Link'),
    'quote' => __('Quote'),
    'status' => __('Status'),
);

// post types without excerpts
global $likebtn_no_excerpts;
$likebtn_no_excerpts = array(
    LIKEBTN_ENTITY_COMMENT
);

// post types titles
global $likebtn_entity_titles;
$likebtn_entity_titles = array(
    LIKEBTN_ENTITY_POST => __('Post'),
    LIKEBTN_ENTITY_POST_LIST => __('Post List'),
    LIKEBTN_ENTITY_PAGE => __('Page'),
    LIKEBTN_ENTITY_PAGE_LIST => __('Page List'),
    LIKEBTN_ENTITY_COMMENT => __('Comments'),
    LIKEBTN_ENTITY_USER => __('User'),
    LIKEBTN_ENTITY_CUSTOM_ITEM => __('Custom Items'),
    LIKEBTN_ENTITY_PRODUCT => __('Product [WooCommerce]'),
    LIKEBTN_ENTITY_PRODUCT_LIST => __('Product List [WooCommerce]'),
    LIKEBTN_ENTITY_BP_ACTIVITY_POST => __('Activity Posts [BuddyPress]'),
    LIKEBTN_ENTITY_BP_ACTIVITY_UPDATE => __('Activity Updates [BuddyPress]'),
    LIKEBTN_ENTITY_BP_ACTIVITY_COMMENT => __('Activity Comments [BuddyPress]'),
    LIKEBTN_ENTITY_BP_ACTIVITY_TOPIC => __('Activity Topics [BuddyPress]'),
    LIKEBTN_ENTITY_BP_MEMBER => __('Member Profile [BuddyPress]'),
    LIKEBTN_ENTITY_BBP_POST => __('Forum Posts [bbPress]'),
    LIKEBTN_ENTITY_BBP_USER => __('User Profile [bbPress]'),
);

// map entities
global $likebtn_map_entities;
$likebtn_map_entities = array(
    LIKEBTN_ENTITY_BP_MEMBER => LIKEBTN_ENTITY_USER,
    LIKEBTN_ENTITY_BBP_USER => LIKEBTN_ENTITY_USER
);

// entities which are not based on posts
global $likebtn_nonpost_entities;
$likebtn_nonpost_entities = array(
    LIKEBTN_ENTITY_COMMENT,
    LIKEBTN_ENTITY_CUSTOM_ITEM,
    LIKEBTN_ENTITY_BP_ACTIVITY_POST,
    LIKEBTN_ENTITY_BP_ACTIVITY_UPDATE,
    LIKEBTN_ENTITY_BP_ACTIVITY_COMMENT,
    LIKEBTN_ENTITY_BP_ACTIVITY_TOPIC,
    LIKEBTN_ENTITY_BP_MEMBER,
    LIKEBTN_ENTITY_BBP_POST,
    LIKEBTN_ENTITY_BBP_USER
);

// bbPress post types in posts table
global $likebtn_bbp_post_types;
$likebtn_bbp_post_types = array(/*'forum',*/ 'topic', 'reply');

// languages
global $likebtn_page_sizes;
$likebtn_page_sizes = array(
    10,
    20,
    50,
    100,
    500,
    1000,
    5000,
);
global $likebtn_post_statuses;
$likebtn_post_statuses = array_reverse(get_post_statuses());

// likebtn settings
global $likebtn_settings;
$likebtn_settings = array(
    "theme" => array("default" => 'white'),
    "lang" => array("default" => "en"),
    "show_like_label" => array("default" => '1'),
    "show_dislike_label" => array("default" => '0'),
    "like_enabled" => array("default" => '1'),
    "lazy_load" => array("default" => '0'),
    "dislike_enabled" => array("default" => '1'),
    "icon_like_show" => array("default" => '1'),
    "icon_dislike_show" => array("default" => '1'),
    "site_id" => array("default" => ""),
    "group_identifier" => array("default" => ""),
    //"local_domain" => array("default" => ''),
    "domain_from_parent" => array("default" => '0'),
    //"subdirectory" => array("default" => ''),
    "item_url" => array("default" => ''),
    "share_enabled" => array("default" => '1'),
    "item_title" => array("default" => ''),
    "item_description" => array("default" => ''),
    "item_image" => array("default" => ''),
    "popup_dislike" => array("default" => '0'),
    "counter_type" => array("default" => "number"),
    "counter_clickable" => array("default" => '0'),
    "counter_show" => array("default" => '1'),
    "counter_padding" => array("default" => ''),
    "counter_zero_show" => array("default" => '0'),
    "voting_enabled" => array("default" => '1'),
    "voting_cancelable" => array("default" => '1'),
    "voting_both" => array("default" => '0'),
    "voting_frequency" => array("default" => ''),
    "addthis_pubid" => array("default" => ''),
    "addthis_service_codes" => array("default" => '', 'default_values' => array(
        'all' => 'facebook,twitter,preferred_1,preferred_2,preferred_3,preferred_4,preferred_5,compact',
        'ru' => 'vk,odnoklassniki_ru,twitter,facebook,preferred_1,preferred_2,preferred_3,compact'
    )),
    "loader_image" => array("default" => ''),
    "loader_show" => array("default" => '0'),
    "loader_image" => array("default" => ''),
    "tooltip_enabled" => array("default" => '1'),
    "tooltip_like_show_always" => array("default" => '0'),
    "tooltip_dislike_show_always" => array("default" => '0'),
    "white_label" => array("default" => '0'),
    "popup_html" => array("default" => ''),
    "popup_donate" => array("default" => ''),
    "popup_content_order" => array("default" => 'popup_share,popup_donate,popup_html'),
    "popup_disabled" => array("default" => '0'),
    "popup_position" => array("default" => 'top'),
    "popup_style" => array("default" => 'light'),
    "popup_hide_on_outside_click" => array("default" => '1'),
    "popup_on_load" => array("default" => '0'),
    "event_handler" => array("default" => ''),
    "info_message" => array("default" => '1'),
    "i18n_like" => array("default" => ''),
    "i18n_dislike" => array("default" => ''),
    "i18n_after_like" => array("default" => ''),
    "i18n_after_dislike" => array("default" => ''),
    "i18n_like_tooltip" => array("default" => ''),
    "i18n_dislike_tooltip" => array("default" => ''),
    "i18n_unlike_tooltip" => array("default" => ''),
    "i18n_undislike_tooltip" => array("default" => ''),
    "i18n_share_text" => array("default" => ''),
    "i18n_popup_close" => array("default" => ''),
    "i18n_popup_text" => array("default" => ''),
    "i18n_popup_donate" => array("default" => '')
);
// removed settings
global $likebtn_settings_deprecated;
$likebtn_settings_deprecated = array(
    'style' => array("default" => 'white'),
    'display_only' => array("default" => '0'),
    'unlike_allowed' => array("default" => '1'),
    'like_dislike_at_the_same_time' => array("default" => '0'),
    'show_copyright' => array("default" => '1')
);

// plans
global $likebtn_plans;
$likebtn_plans = array(
    LIKEBTN_PLAN_TRIAL => 'TRIAL',
    LIKEBTN_PLAN_FREE => 'FREE',
    LIKEBTN_PLAN_PLUS => 'PLUS',
    LIKEBTN_PLAN_PRO => 'PRO',
    LIKEBTN_PLAN_VIP => 'VIP',
    LIKEBTN_PLAN_ULTRA => 'ULTRA',
);

// themes
global $likebtn_styles;
$likebtn_styles = array(
    'white',
    'lightgray',
    'gray',
    'black',
    'padded',
    'drop',
    'line',
    'github',
    'transparent',
    'youtube',
    'habr',
    'heartcross',
    'plusminus',
    'google',
    'greenred',
    'large',
    'elegant',
    'disk',
    'squarespace',
    'slideshare',
    'baidu',
    'uwhite',
    'ublack',
    'uorange',
    'ublue',
    'ugreen',
    'direct',
    'homeshop'
);

// languages
global $likebtn_default_locales;
$likebtn_default_locales = array(
    'en' => array('name'    => 'English',
          'en_name' => 'English',
          'iso'     => 'en'
    ),
    'ru' => array('name'    => 'Русский',
          'en_name' => 'Russian',
          'iso'     => 'ru'
    ),
    'de' => array('name'    => 'Deutsch',
          'en_name' => 'German',
          'iso'     => 'de'
    ),
    'ja' => array('name'    => '日本語',
          'en_name' => 'Japanese',
          'iso'     => 'jp'
    ),
    'uk' => array('name'    => 'Українська мова',
          'en_name' => 'Ukrainian',
          'iso'     => 'uk'
    ),
    'kk' => array('name'    => 'Қазақ тілі',
          'en_name' => 'Kazakh',
          'iso'     => 'kk'
    ),
    'nl' => array('name'    => 'Nederlands',
          'en_name' => 'Dutch',
          'iso'     => 'nl'
    ),
    'hu' => array('name'    => 'Magyar',
          'en_name' => 'Hungarian',
          'iso'     => 'hu'
    ),
    'sv' => array('name'    => 'Svenska',
          'en_name' => 'Swedish',
          'iso'     => 'sv'
    ),
    'tr' => array('name'    => 'Türkçe',
          'en_name' => 'Turkish',
          'iso'     => 'tr'
    ),
    'es' => array('name'    => 'Español',
          'en_name' => 'Spanish',
          'iso'     => 'es'
    ),
    'ar' => array('name'    => 'العربية',
          'en_name' => 'Arabic',
          'iso'     => 'ar'
    ),
    'pt' => array('name'    => 'Português',
          'en_name' => 'Portuguese',
          'iso'     => 'pt'
    ),
    'pt_BR' => array('name'    => 'Português do Brasil',
          'en_name' => 'Portuguese (Brazil)',
          'iso'     => 'pt'
    ),
    'vi' => array('name'    => 'Tiếng Việt',
          'en_name' => 'Vietnamese',
          'iso'     => 'vi'
    ),
    'tr' => array('name'    => 'Türkçe',
          'en_name' => 'Turkish',
          'iso'     => 'tr'
    ),
    'zh_CN' => array('name'    => '简体中文',
          'en_name' => 'Chinese',
          'iso'     => 'zh'
    ),
    'cs' => array('name'    => 'Čeština',
          'en_name' => 'Czech',
          'iso'     => 'cs'
    ),
    'ne' => array('name'    => 'नेपाली',
          'en_name' => 'Nepali',
          'iso'     => 'ne'
    ),
    'fr' => array('name'     => 'Français',
          'en_name'  => 'French',
          'iso'      => 'fr'
    ),
    'it' => array('name'     => 'Italiano',
          'en_name'  => 'Italian',
          'iso'      => 'it'
    ),
    'he' => array('name'     => 'עברית',
          'en_name'  => 'Hebrew',
          'iso'      => 'he'
    ),
    'th' => array('name'     => 'ไทย',
          'en_name'       => 'Thai',
          'iso'      => 'th'
    ),
    'pl' => array('name'     => 'Polski',
          'en_name'  => 'Polish',
          'iso'      => 'pl'
    ),
);

// languages
global $likebtn_sync_intervals;
$likebtn_sync_intervals = array(
    5,
    15,
    30,
    60,
    90,
    120,
);

// LikeBtn website locales available
global $likebtn_website_locales;
$likebtn_website_locales = array(
    'en', 'ru'
);

// Form options
global $likebtn_settings_options;
$likebtn_settings_options = array(
    'likebtn_account_email' => '',
    'likebtn_account_api_key' => '',
    'likebtn_sync_inerval' => '',
    'likebtn_site_id' => ''
);
// Form entity options
global $likebtn_buttons_options;
$likebtn_buttons_options = array(
    'likebtn_show' => '1',
    'likebtn_use_settings_from' => '',
    'likebtn_post_format' => array('all'),
    'likebtn_exclude_sections' => array(),
    'likebtn_exclude_categories' => array(),
    'likebtn_allow_ids' => '',
    'likebtn_exclude_ids' => '',
    'likebtn_user_logged_in' => '',
    'likebtn_position' => LIKEBTN_POSITION_BOTTOM,
    'likebtn_alignment' => LIKEBTN_ALIGNMENT_LEFT,
    'likebtn_html_before' => '',
    'likebtn_html_after' => '',
    'likebtn_newline' => '',
    'likebtn_wrap' => '1'
);
// Internal settings
global $likebtn_internal_options;
$likebtn_internal_options = array(
    'likebtn_plan' => LIKEBTN_PLAN_TRIAL,
    'likebtn_plan_expires_in' => 0,
    'likebtn_plan_expires_on' => '',
    'likebtn_last_sync_time' => 0,
    'likebtn_last_successfull_sync_time' => 0,
    'likebtn_last_locale_sync_time' => 0,
    'likebtn_last_style_sync_time' => 0,
    'likebtn_last_plan_sync_time' => 0,
    'likebtn_last_plan_successfull_sync_time' => 0,
    'likebtn_last_sync_result' => false,
    'likebtn_last_sync_message' => '',
    'likebtn_plugin_v' => '',
    'likebtn_installation_timestamp' => '',
    'likebtn_notice_plan' => 0, // 1 = upgrade, -1 = downgrade
    'likebtn_account_data_hash' => '',
    'likebtn_admin_notices' => array(),
    'likebtn_db_version' => LIKEBTN_DB_VERSION,
    'likebtn_locales' => array(),
    'likebtn_styles' => array()
);

// Internal settings
global $likebtn_entities_config;
$likebtn_entities_config = array(
    'theme' => array(
        LIKEBTN_ENTITY_PRODUCT => array('value'=>'github'),
        LIKEBTN_ENTITY_COMMENT => array('value'=>'transparent'),
        LIKEBTN_ENTITY_BP_ACTIVITY_POST => array('value'=>'transparent'),
        LIKEBTN_ENTITY_BP_ACTIVITY_UPDATE => array('value'=>'transparent'),
        LIKEBTN_ENTITY_BP_ACTIVITY_COMMENT => array('value'=>'transparent'),
        LIKEBTN_ENTITY_BP_ACTIVITY_TOPIC => array('value'=>'transparent'),
        LIKEBTN_ENTITY_BP_MEMBER => array('value'=>'github'),
        LIKEBTN_ENTITY_BBP_USER => array('value'=>'github'),
    ),
    'popup_position' => array(
        LIKEBTN_ENTITY_BP_MEMBER => array('value'=>'bottom'),
        LIKEBTN_ENTITY_BP_ACTIVITY_POST => array('value'=>'left'),
        LIKEBTN_ENTITY_BP_ACTIVITY_UPDATE => array('value'=>'left'),
        LIKEBTN_ENTITY_BP_ACTIVITY_COMMENT => array('value'=>'left'),
        LIKEBTN_ENTITY_BP_ACTIVITY_TOPIC => array('value'=>'left')
    ),
    'likebtn_post_format' => array(
        LIKEBTN_ENTITY_BP_MEMBER => array('hide'=>true),
        LIKEBTN_ENTITY_BP_ACTIVITY_POST => array('hide'=>true),
        LIKEBTN_ENTITY_BP_ACTIVITY_UPDATE => array('hide'=>true),
        LIKEBTN_ENTITY_BP_ACTIVITY_COMMENT => array('hide'=>true),
        LIKEBTN_ENTITY_BP_ACTIVITY_TOPIC => array('hide'=>true)
    ),
    'likebtn_exclude_sections' => array(
        LIKEBTN_ENTITY_BP_MEMBER => array('hide'=>true),
        LIKEBTN_ENTITY_BP_ACTIVITY_POST => array('hide'=>true),
        LIKEBTN_ENTITY_BP_ACTIVITY_UPDATE => array('hide'=>true),
        LIKEBTN_ENTITY_BP_ACTIVITY_COMMENT => array('hide'=>true),
        LIKEBTN_ENTITY_BP_ACTIVITY_TOPIC => array('hide'=>true)
    ),
    'likebtn_exclude_categories' => array(
        LIKEBTN_ENTITY_BP_MEMBER => array('hide'=>true),
        LIKEBTN_ENTITY_BP_ACTIVITY_POST => array('hide'=>true),
        LIKEBTN_ENTITY_BP_ACTIVITY_UPDATE => array('hide'=>true),
        LIKEBTN_ENTITY_BP_ACTIVITY_COMMENT => array('hide'=>true),
        LIKEBTN_ENTITY_BP_ACTIVITY_TOPIC => array('hide'=>true)
    ),
    'likebtn_allow_ids' => array(
        LIKEBTN_ENTITY_BP_MEMBER => array('hide'=>true),
        LIKEBTN_ENTITY_BP_ACTIVITY_POST => array('hide'=>true),
        LIKEBTN_ENTITY_BP_ACTIVITY_UPDATE => array('hide'=>true),
        LIKEBTN_ENTITY_BP_ACTIVITY_COMMENT => array('hide'=>true),
        LIKEBTN_ENTITY_BP_ACTIVITY_TOPIC => array('hide'=>true)
    ),
    'likebtn_exclude_ids' => array(
        LIKEBTN_ENTITY_BP_MEMBER => array('hide'=>true),
        LIKEBTN_ENTITY_BP_ACTIVITY_POST => array('hide'=>true),
        LIKEBTN_ENTITY_BP_ACTIVITY_UPDATE => array('hide'=>true),
        LIKEBTN_ENTITY_BP_ACTIVITY_COMMENT => array('hide'=>true),
        LIKEBTN_ENTITY_BP_ACTIVITY_TOPIC => array('hide'=>true)
    ),
    'likebtn_position' => array(
        LIKEBTN_ENTITY_PRODUCT => array('hide'=>true),
        LIKEBTN_ENTITY_BP_MEMBER => array('hide'=>true),
        LIKEBTN_ENTITY_BBP_USER => array('hide'=>true)
    ),
    'likebtn_alignment' => array(
        LIKEBTN_ENTITY_PRODUCT => array('hide'=>true),
        LIKEBTN_ENTITY_BP_MEMBER => array('hide'=>true),
        LIKEBTN_ENTITY_BBP_USER => array('hide'=>true)
    ),
);

// AddThis services codes list
global $likebtn_addthis_service_codes;
$likebtn_addthis_service_codes = array(
    /*'facebook_like',
    'foursquare',
    'google_plusone',
    'pinterest',*/
    '100zakladok',
    '2tag',
    '2linkme',
    'a97abi',
    'addressbar',
    'adfty',
    'adifni',
    'advqr',
    'amazonwishlist',
    'amenme',
    'aim',
    'aolmail',
    'apsense',
    'arto',
    'azadegi',
    'baang',
    'baidu',
    'balltribe',
    'beat100',
    'biggerpockets',
    'bitly',
    'bizsugar',
    'bland',
    'blinklist',
    'blogger',
    'bloggy',
    'blogkeen',
    'blogmarks',
    'blurpalicious',
    'bobrdobr',
    'bonzobox',
    'socialbookmarkingnet',
    'bookmarkycz',
    'bookmerkende',
    'box',
    'brainify',
    'bryderi',
    'buddymarks',
    'buffer',
    'buzzzy',
    'camyoo',
    'care2',
    'foodlve',
    'chiq',
    'cirip',
    'citeulike',
    'classicalplace',
    'cleanprint',
    'cleansave',
    'cndig',
    'colivia',
    'technerd',
    'cosmiq',
    'cssbased',
    'curateus',
    'delicious',
    'digaculturanet',
    'digg',
    'diggita',
    'digo',
    'diigo',
    'domelhor',
    'dotnetshoutout',
    'douban',
    'draugiem',
    'dropjack',
    'dudu',
    'dzone',
    'efactor',
    'ekudos',
    'elefantapl',
    'email',
    'mailto',
    'embarkons',
    'evernote',
    'extraplay',
    'ezyspot',
    'stylishhome',
    'fabulously40',
    'facebook',
    'facebook_like',
    'foursquare',
    'informazione',
    'thefancy',
    'fark',
    'farkinda',
    'fashiolista',
    'favable',
    'faves',
    'favlogde',
    'favoritende',
    'favorites',
    'favoritus',
    'financialjuice',
    'flaker',
    'flipboard',
    'folkd',
    'formspring',
    'thefreedictionary',
    'fresqui',
    'friendfeed',
    'funp',
    'fwisp',
    'gamekicker',
    'gg',
    'giftery',
    'gigbasket',
    'givealink',
    'gmail',
    'govn',
    'goodnoows',
    'google',
    'googleplus',
    'googletranslate',
    'google_plusone',
    'google_plusone_share',
    'greaterdebater',
    'hackernews',
    'hatena',
    'gluvsnap',
    'hedgehogs',
    'historious',
    'hootsuite',
    'hotklix',
    'hotmail',
    'w3validator',
    'identica',
    'ihavegot',
    'indexor',
    'instapaper',
    'iorbix',
    'irepeater',
    'isociety',
    'iwiw',
    'jamespot',
    'jappy',
    'jolly',
    'jumptags',
    'kaboodle',
    'kaevur',
    'kaixin',
    'ketnooi',
    'kindleit',
    'kledy',
    'kommenting',
    'latafaneracat',
    'librerio',
    'lidar',
    'linkedin',
    'linksgutter',
    'linkshares',
    'linkuj',
    'livejournal',
    'lockerblogger',
    'logger24',
    'mymailru',
    'margarin',
    'markme',
    'mashant',
    'mashbord',
    'me2day',
    'meinvz',
    'mekusharim',
    'memonic',
    'memori',
    'mendeley',
    'meneame',
    'live',
    'misterwong',
    'misterwong_de',
    'mixi',
    'moemesto',
    'moikrug',
    'mrcnetworkit',
    'myspace',
    'myvidster',
    'n4g',
    'naszaklasa',
    'netlog',
    'netvibes',
    'netvouz',
    'newsmeback',
    'newstrust',
    'newsvine',
    'nujij',
    'odnoklassniki_ru',
    'oknotizie',
    'openthedoor',
    'oyyla',
    'packg',
    'pafnetde',
    'pdfonline',
    'pdfmyurl',
    'phonefavs',
    'pinterest',
    'pinterest_share',
    'planypus',
    'plaxo',
    'plurk',
    'pocket',
    'posteezy',
    'print',
    'printfriendly',
    'pusha',
    'qrfin',
    'qrsrc',
    'quantcast',
    'qzone',
    'reddit',
    'rediff',
    'redkum',
    'researchgate',
    'safelinking',
    'scoopat',
    'scoopit',
    'sekoman',
    'select2gether',
    'shaveh',
    'shetoldme',
    'sinaweibo',
    'skyrock',
    'smiru',
    'sodahead',
    'sonico',
    'spinsnap',
    'yiid',
    'springpad',
    'startaid',
    'startlap',
    'storyfollower',
    'studivz',
    'stuffpit',
    'stumbleupon',
    'stumpedia',
    'sulia',
    'sunlize',
    'supbro',
    'surfingbird',
    'svejo',
    'symbaloo',
    'taaza',
    'tagza',
    'tapiture',
    'taringa',
    'textme',
    'thewebblend',
    'thinkfinity',
    'thisnext',
    'thrillon',
    'throwpile',
    'toly',
    'topsitelernet',
    'transferr',
    'tuenti',
    'tulinq',
    'tumblr',
    'tvinx',
    'twitter',
    'twitthis',
    'typepad',
    'upnews',
    'urlaubswerkde',
    'viadeo',
    'virb',
    'visitezmonsite',
    'vk',
    'vkrugudruzei',
    'voxopolis',
    'vybralisme',
    'wanelo',
    'internetarchive',
    'sharer',
    'webnews',
    'webshare',
    'werkenntwen',
    'whatsapp',
    'domaintoolswhois',
    'windows',
    'wirefan',
    'wishmindr',
    'wordpress',
    'wowbored',
    'raiseyourvoice',
    'wykop',
    'xanga',
    'xing',
    'yahoobkm',
    'yahoomail',
    'yammer',
    'yardbarker',
    'yigg',
    'yookos',
    'yoolink',
    'yorumcuyum',
    'youmob',
    'yuuby',
    'zakladoknet',
    'ziczac',
    'zingme',
    'more',
    'compact',
    'preferred_1',
    'preferred_2',
    'preferred_3',
    'preferred_4',
    'preferred_5',
    'preferred_6',
    'preferred_7',
    'preferred_8'
);

// Features
global $likebtn_features;
$likebtn_features = array(
    LIKEBTN_PLAN_FREE => array(
        'max_buttons' => 1,
        'statistics' => false,
        'synchronization' => false,
        'most_liked_widget' => false,
        'sorting' => false
    ),
    LIKEBTN_PLAN_PLUS => array(
        'max_buttons' => 10,
        'statistics' => false,
        'synchronization' => false,
        'most_liked_widget' => false,
        'sorting' => false
    ),
    LIKEBTN_PLAN_PRO => array(
        'max_buttons' => 25,
        'statistics' => true,
        'synchronization' => true,
        'most_liked_widget' => true,
        'sorting' => true
    ),
    LIKEBTN_PLAN_VIP => array(
        'max_buttons' => 0,
        'statistics' => true,
        'synchronization' => true,
        'most_liked_widget' => true,
        'sorting' => true
    ),
    LIKEBTN_PLAN_ULTRA => array(
        'max_buttons' => 0,
        'statistics' => true,
        'synchronization' => true,
        'most_liked_widget' => true,
        'sorting' => true
    ),
    LIKEBTN_PLAN_TRIAL => array(
        'max_buttons' => 0,
        'statistics' => true,
        'synchronization' => true,
        'most_liked_widget' => true,
        'sorting' => true
    )
);

###############
### Backend ###
###############
// i18n function
/* function likebtn_trans($text, $params = null) {
  if (!is_array($params)) {
  $params = func_get_args();
  $params = array_slice($params, 1);
  }
  return vsprintf(__($text, LIKEBTN_I18N_DOMAIN), $params);
  } */

// initicalization
function likebtn_init() {
    _likebtn_plugin_on_load();

    load_plugin_textdomain(LIKEBTN_I18N_DOMAIN, false, dirname(plugin_basename(__FILE__)) . '/languages');

    // Process forms
    _likebtn_bulk_actions();

    if (is_admin()) {
        wp_enqueue_script('jquery');
    }
}

add_action('init', 'likebtn_init');

// add Settings link to the plugin list page
function likebtn_links($links, $file) {
    $plugin_file = basename(__FILE__);
    if (basename($file) == $plugin_file) {
       // $settings_link = '<a href="admin.php?page=likebtn_settings">' . __('Settings', LIKEBTN_I18N_DOMAIN) . '</a>';
        array_unshift($links, $settings_link);
    }
    return $links;
}

add_filter('plugin_action_links', 'likebtn_links', 10, 2);

// admin options
function likebtn_admin_menu() {
    $logo_url = _likebtn_get_public_url() . 'img/menu_icon.png';

    //add_menu_page(__('Like Buttons', LIKEBTN_I18N_DOMAIN), __('Like Buttons', LIKEBTN_I18N_DOMAIN), 'manage_options', 'likebtn_buttons', '', $logo_url);
    //add_submenu_page(  'likebtn_buttons', __('Buttons', LIKEBTN_I18N_DOMAIN) . ' ‹ ' . __('LikeBtn Like Button', LIKEBTN_I18N_DOMAIN), __('Buttons', LIKEBTN_I18N_DOMAIN), 'manage_options', 'likebtn_buttons', 'likebtn_admin_buttons');
    //add_options_page('LikeBtn Like Button', __('LikeBtn Like Button', 'likebtn'), 'activate_plugins', 'likebtn', 'likebtn_admin_content');
   // add_submenu_page(  'likebtn_buttons', __('Synchronization', LIKEBTN_I18N_DOMAIN) . ' ‹ ' . __('LikeBtn Like Button', LIKEBTN_I18N_DOMAIN), __('Synchronization', LIKEBTN_I18N_DOMAIN), 'manage_options', 'likebtn_settings', 'likebtn_admin_settings' );
   // add_submenu_page(  'likebtn_buttons', __('Statistics', LIKEBTN_I18N_DOMAIN) . ' ‹ LikeBtn Like Button', __('Statistics', LIKEBTN_I18N_DOMAIN), 'manage_options', 'likebtn_statistics', 'likebtn_admin_statistics'  );
   //add_submenu_page(  'likebtn_buttons', __('Help') . ' ‹ LikeBtn Like Button', __('Help'), 'manage_options', 'likebtn_help', 'likebtn_admin_help'  );
}

//add_action('admin_menu', 'likebtn_admin_menu');

// plugin header
function likebtn_admin_head() {
    $url_css = _likebtn_get_public_url() . 'css/admin.css?ver=' . LIKEBTN_VERSION;
    $url_js = _likebtn_get_public_url() . 'js/admin.js?ver=' . LIKEBTN_VERSION;

    echo '<link rel="stylesheet" type="text/css" href="' . $url_css . '" />';
    echo '<link rel="stylesheet" type="text/css" href="' . _likebtn_get_public_url() . 'css/jquery/tipsy.css' . '" />';
    echo '<script src="' . $url_js . '" type="text/javascript"></script>';
    echo '<script src="' . _likebtn_get_public_url() . 'js/jquery/jquery.tipsy.js" type="text/javascript"></script>';
}

add_action('admin_head', 'likebtn_admin_head');

// admin header
function likebtn_admin_header() {
    $logo_url = _likebtn_get_public_url() . 'img/logotype.png';
    $header = <<<HEADER
    <div class="wrap" id="likebtn">
        <script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

          ga('create', 'UA-37384414-15', 'auto');
          ga('send', 'pageview');
        </script>
        <script type="text/javascript"> (function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter31779816 = new Ya.Metrika({ id:31779816, clickmap:true, trackLinks:true, accurateTrackBounce:true }); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = "https://mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks");</script><noscript><div><img src="https://mc.yandex.ru/watch/31779816" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
        <h2 class="likebtn_logo">
            <a href="http://likebtn.com" target="_blank" title="LikeBtn Like Button"><img alt="" src="{$logo_url}">LikeBtn</a>
        </h2>
HEADER;
//<div id="plan_upgrade">
//                Plan:
//        </div>

    /*$installation_timestamp = get_option('likebtn_installation_timestamp');

    if ( (($installation_timestamp && strtotime(date('Y-m-d H:i:s', $installation_timestamp) . ' +' . LIKEBTN_REVIEW_LINK_PERIOD) < time())
           || !$installation_timestamp)
         && (get_option('likebtn_plan') != LIKEBTN_PLAN_FREE && get_option('likebtn_plan') != LIKEBTN_PLAN_TRIAL)
    ) {*/

    //}

    $header .= '
        <div id="poststuff">
            <div id="post-body" class="metabox-holder columns-2">
                
                <div id="postbox-container-1" class="postbox-container">
                    <div class="meta-box-sortables ui-sortable">
                        <div class="postbox">
                            <h3 class="hndle ui-sortable-handle"><span>' . __('Plan & Features', LIKEBTN_I18N_DOMAIN) . '</span></h3>
                            ' . _likebtn_sidebar_plan() . '
                        </div>
                        <div class="postbox">
                            <h3 class="hndle ui-sortable-handle"><span>' . __('Synchronization', LIKEBTN_I18N_DOMAIN) . '</span></h3>
                            <div class="inside likebtn_sidebar_inside">
                                ' . _likebtn_sidebar_synchronization() . '
                            </div>
                        </div>
                        <div class="postbox">
                            <h3 class="hndle ui-sortable-handle"><span>' . __('Follow', LIKEBTN_I18N_DOMAIN) . '</span></h3>
                            <div class="inside">
                                ' . _likebtn_sidebar_social() . '
                            </div>
                        </div>
                    </div>
                </div>
                <div id="postbox-container-2" class="postbox-container">
                    <h2 class="nav-tab-wrapper">
                        <a class="nav-tab ' . ($_GET['page'] == 'likebtn_buttons' ? 'nav-tab-active' : '') . '" href="' . admin_url() . 'admin.php?page=likebtn_buttons">' . __('Buttons', LIKEBTN_I18N_DOMAIN) . '</a>
                        <a class="nav-tab ' . ($_GET['page'] == 'likebtn_settings' ? 'nav-tab-active' : '') . '" href="' . admin_url() . 'admin.php?page=likebtn_settings">' . __('Synchronization', LIKEBTN_I18N_DOMAIN) . '</a>
                        <a class="nav-tab ' . ($_GET['page'] == 'likebtn_statistics' ? 'nav-tab-active' : '') . '" href="' . admin_url() . 'admin.php?page=likebtn_statistics">' . __('Statistics', LIKEBTN_I18N_DOMAIN) . ' <i class="premium_feature" title="PRO / VIP / ULTRA">&nbsp;</i></a>
                        <a class="nav-tab ' . ($_GET['page'] == 'likebtn_help' ? 'nav-tab-active' : '') . '" href="' . admin_url() . 'admin.php?page=likebtn_help">' . __('Help') . '</a>';

                $header .= '
                    <div id="premium_features_hint">
                        <i class="premium_feature"></i>' . __('Premium / Trial features', LIKEBTN_I18N_DOMAIN) .
                    '</div>
    ';

    $header .= '</h2>';

    echo $header;
}

// admin side panel and footer
function _likebtn_admin_footer()
{
    ?>
                    </div>
                </div>
                <br class="clear"/>
            </div>

        </div>
    <?php
}

// sidebar plan
function _likebtn_sidebar_plan()
{
    $plan_synced = false;

    if (get_option('likebtn_last_plan_successfull_sync_time')) {
        $plan_synced = true;
    }

    $likebtn_plan = get_option('likebtn_plan');
    
    $html = '
    <div class="inside likebtn_sidebar_inside">
        <div class="likebtn_sidebar_section">
            <div id="likebtn_plan_wr">
                '._likebtn_plan_html().'
            </div>'
    ;

    $html .= '
            <div id="likebtn_refresh_msg_wr" style="display:none">
                <div class="likebtn_sidebar_div_simple"></div>
                <small class="likebtn_error" id="likebtn_refresh_error" style="display:none"></small>
            </div>
        </div>';

    if ($plan_synced) {
        $features = _likebtn_get_features();

        $likebtn_alert = '';
        if ((!_likebtn_is_stat_enabled() || get_option('likebtn_last_sync_message')) && $features['statistics']) {
            $likebtn_alert = ' <i class="likebtn_ttip likebtn_alert" data-likebtn_ttip_gr="e" title="'.__('Configure Synchronization in order to use this feature', LIKEBTN_I18N_DOMAIN).'"></i>';
        }

        $html .= '
        <div class="likebtn_sidebar_div"></div>
        <div class="likebtn_sidebar_section">
            '.__('Max buttons per page', LIKEBTN_I18N_DOMAIN).': <strong><nobr>'.($features['max_buttons'] ? $features['max_buttons'] : __('Unlimited', LIKEBTN_I18N_DOMAIN)).'</strong></nobr>
        </div>
        <div class="likebtn_sidebar_div"></div>
        <div class="likebtn_sidebar_section">
            <ul class="likebtn_features">
                <li class="'.($features['statistics'] ? 'likebtn_avail' : 'likebtn_unavail').'"><span class="likebtn_ttip" title="PRO / VIP / ULTRA">'.__('Statistics', LIKEBTN_I18N_DOMAIN).'</span>'.$likebtn_alert.'</li>
                <li class="'.($features['synchronization'] ? 'likebtn_avail' : 'likebtn_unavail').'"><span class="likebtn_ttip" title="PRO / VIP / ULTRA">'.__('Synchronization', LIKEBTN_I18N_DOMAIN).'</span></li>
                <li class="'.($features['most_liked_widget'] ? 'likebtn_avail' : 'likebtn_unavail').'"><span class="likebtn_ttip" title="PRO / VIP / ULTRA">'.__('Most liked content widget', LIKEBTN_I18N_DOMAIN).'</span>'.$likebtn_alert.'</li>
                <li class="'.($features['sorting'] ? 'likebtn_avail' : 'likebtn_unavail').'"><span class="likebtn_ttip" title="PRO / VIP / ULTRA">'.__('Sorting content by likes', LIKEBTN_I18N_DOMAIN).'</span>'.$likebtn_alert.'</li>
            </ul>
        </div>
        ';
        $html .= '';
    }

    $html .= '
    </div>
    ';

    if ($plan_synced) {
        if ($likebtn_plan == LIKEBTN_PLAN_FREE || $likebtn_plan == LIKEBTN_PLAN_TRIAL) {
            $html .= '
    <div class="likebtn_upgrade_container">
        <input class="button-secondary likebtn_button_upgrade" type="button" value="'.__('Upgrade', LIKEBTN_I18N_DOMAIN).'" onclick="likebtnPopup(\''.__('http://likebtn.com/en/', LIKEBTN_I18N_DOMAIN).'#plans_pricing\')" />
    </div>
    ';
        }
    }

    return $html;
}

// sidebar syncing
function _likebtn_sidebar_synchronization()
{
    $enabled = false;
    $sync_result_html = '';

    if (_likebtn_is_stat_enabled()) {
        $enabled = true;
        $status = __('Enabled', LIKEBTN_I18N_DOMAIN);
        $status_class = 'likebtn_success';

        $last_sync = get_option('likebtn_last_sync_time');
        if ($last_sync) {
            $last_sync = ceil((time()-$last_sync) / 60);
            $last_sync_html = $last_sync.' '.__('min(s) ago', LIKEBTN_I18N_DOMAIN);

            if (get_option('likebtn_last_sync_result') == 'error') {
                if (get_option('likebtn_last_sync_message')) {
                    $sync_result_html = get_option('likebtn_last_sync_message');
                }
            }
        } else {
            $last_sync_html = __('Never', LIKEBTN_I18N_DOMAIN);
        }
    } else {
        $status = __('Disabled', LIKEBTN_I18N_DOMAIN);
        $status_class = 'likebtn_error';
    }

    if (!$sync_result_html) {
        $html = '
            <div class="likebtn_sidebar_section">
                '.__('Status', LIKEBTN_I18N_DOMAIN).': <strong class="'.$status_class.'">'.$status.'</strong>
        ';
    } else {
        // Show error in status
        $html = '
            <div class="likebtn_sidebar_section">
                '.__('Status', LIKEBTN_I18N_DOMAIN).': <strong class="likebtn_error">'.$sync_result_html.'</strong>
        ';
    }
    if (!$enabled) {
        $html .= ' <a href="'.admin_url().'admin.php?page=likebtn_settings">'.__('Edit', LIKEBTN_I18N_DOMAIN).'</a>';
    }
    
    if ($enabled) {
        $html .= '
            <div class="likebtn_sidebar_div_simple"></div>
                '.__('Last sync', LIKEBTN_I18N_DOMAIN).': <strong>'.$last_sync_html.'</strong>
        ';
        /*if ($sync_result_html) {
            $html .= '
                <div class="likebtn_sidebar_div_simple"></div>
                '.__('Error', LIKEBTN_I18N_DOMAIN).': <strong class="likebtn_error">'.$sync_result_html.'</strong>
            ';
        }*/
    }
    $html .= '</div>';

    return $html;
}

// sidebar social
function _likebtn_sidebar_social()
{
    $html =<<<HTML
<div class="likebtn_social">
    <iframe src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2FLikeBtn.LikeButton&amp;width&amp;layout=button_count&amp;action=like&amp;show_faces=false&amp;share=false&amp;height=21&amp;appId=192115980991078" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:21px; width:110px;" allowTransparency="true"></iframe>
</div>
<div class="likebtn_social">
    <script src="https://apis.google.com/js/platform.js" async defer></script>
    <div class="g-follow" data-href="https://plus.google.com/+Likebtn" data-rel="publisher"></div>
</div>
<div class="likebtn_social">
    <a href="https://twitter.com/likebtn" class="twitter-follow-button" data-show-count="true" data-show-screen-name="false" data-width="144px"></a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
</div>
HTML;

    return $html;
}

// Get plan html
function _likebtn_plan_html() {
    global $likebtn_plans;
    $plan_synced = false;
    $expires_html = '';
    $expires_on = '';

    if (get_option('likebtn_last_plan_successfull_sync_time')) {
        $plan_synced = true;
    }

    $likebtn_plan = get_option('likebtn_plan');

    if (isset($likebtn_plans[$likebtn_plan]) && $plan_synced) {
        $plan_html = '
            <a href="javascript: likebtnPopup(\''.__('http://likebtn.com/en/', LIKEBTN_I18N_DOMAIN).'?add_website='.$_SERVER['SERVER_NAME'].'#plans_pricing\'); void(0)" class="likebtn_ttip" title="'.__('Plans & Pricing', LIKEBTN_I18N_DOMAIN).'"><strong>'.$likebtn_plans[$likebtn_plan].'</strong></a> 
            <img src="'._likebtn_get_public_url().'img/refresh.gif" class="likebtn_refresh likebtn_ttip" onclick="refreshPlan(\''.__('Error occured, please try again later.', LIKEBTN_I18N_DOMAIN).__('Disable WP HTTP Compression plugin if you have it enabled.', LIKEBTN_I18N_DOMAIN).'\', \''.__('Plan data refreshed', LIKEBTN_I18N_DOMAIN).'\')" title="'.__('Refresh data', LIKEBTN_I18N_DOMAIN).'" id="likebtn_refresh_trgr"/>
            <img src="'._likebtn_get_public_url().'img/refresh_loader.gif" class="likebtn_refresh likebtn_refresh_loader likebtn_ttip" style="display:none" title="'.__('Please wait...', LIKEBTN_I18N_DOMAIN).'" id="likebtn_refresh_ldr"/> 
            <small class="likebtn_success" id="likebtn_refresh_success" style="display:none"></small>
        ';
        if ($likebtn_plan != LIKEBTN_PLAN_FREE) {
            if ((int)get_option('likebtn_last_plan_successfull_sync_time') >= time() - 86000) {
                $expires_in_option = (int)get_option('likebtn_plan_expires_in');
                $expires_in = '';
                if ($expires_in_option) {
                    $expires_in = ceil($expires_in_option / 86400);
                }
                if ($expires_in) {
                    $expires_on = get_option('likebtn_plan_expires_on');
                    if ($expires_on) {
                        $expires_on = date_i18n(get_option('date_format'), strtotime($expires_on));
                    }
                    $expires_html .= '
                        <div class="likebtn_sidebar_div_simple"></div>
                        '.__('Expires in', LIKEBTN_I18N_DOMAIN).': <strong class="likebtn_ttip" title="'.$expires_on.'">'.$expires_in.' '.__('day(s)', LIKEBTN_I18N_DOMAIN).'</strong>';
                }
            } else {
                $expires_html .= '
                    <div class="likebtn_sidebar_div_simple"></div>
                    '.__('Expires in', LIKEBTN_I18N_DOMAIN).': 
                    <strong class="likebtn_error">'.__('Unknown', LIKEBTN_I18N_DOMAIN).'</strong>
                    <i class="likebtn_help_simple" title="'.__('Enter your LikeBtn.com account data on Synchronization tab', LIKEBTN_I18N_DOMAIN).'"></i> 
                    <a href="'.admin_url().'admin.php?page=likebtn_settings">'.__('Edit', LIKEBTN_I18N_DOMAIN).'</a>
                ';
            }
        }
    } else {
        $plan_html = '
            <strong class="likebtn_error">'.
                __('Unknown', LIKEBTN_I18N_DOMAIN).'
            </strong>
            <i class="likebtn_help_simple" title="'.__('Enter your LikeBtn.com account data on Synchronization tab', LIKEBTN_I18N_DOMAIN).'"></i> 
            <a href="'.admin_url().'admin.php?page=likebtn_settings">'.__('Edit', LIKEBTN_I18N_DOMAIN).'</a>
        ';
    }

    $html = __('Plan', LIKEBTN_I18N_DOMAIN).': '.$plan_html.
        $expires_html;

    return $html;
}

// Check if statistics has been enabled on Statistics tab
function _likebtn_is_stat_enabled() {
    if (get_option('likebtn_sync_inerval') && get_option('likebtn_account_email') && 
        get_option('likebtn_account_api_key') && get_option('likebtn_site_id') &&
        get_option('likebtn_plan') >= LIKEBTN_PLAN_PRO
    ) {
        return true;
    } else {
        return false;
    }
}

// Get features availability
function _likebtn_get_features() {
    global $likebtn_features;
    $plan = get_option('likebtn_plan');

    if (isset($likebtn_features[$plan])) {
        return $likebtn_features[$plan];
    } else {
        $likebtn_features[LIKEBTN_PLAN_TRIAL];
    }
}

// http://codex.wordpress.org/Plugin_API/Action_Reference/admin_notices
function _likebtn_notice($msg, $class = 'updated')
{
    ?>
    <div class="<?php echo $class; ?> likebtn_notice">
        <p><?php echo $msg; ?></p>
    </div>
    <?php
}

// Display notice on plan downgrade
function likebtn_account_notice() {
	return false;
    if (!get_option('likebtn_last_plan_successfull_sync_time')) {
        $msg = strtr(
            __('Like Button', LIKEBTN_I18N_DOMAIN).': '.__('Please enter your LikeBtn.com account data on <a href="%url_sync%">Synchronization</a> tab.', LIKEBTN_I18N_DOMAIN), 
            array('%url_sync%'=>admin_url().'admin.php?page=likebtn_settings')
        );
        _likebtn_notice($msg, 'update-nag');
    }
}

add_action('admin_notices', 'likebtn_account_notice');

// Display notice on plan downgrade
function likebtn_plan_notice() {
    global $likebtn_plans;

    if (get_option('likebtn_notice_plan')) {
        $msg = __("Your LikeBtn plan has been switched to:", LIKEBTN_I18N_DOMAIN).' '.$likebtn_plans[get_option('likebtn_plan')];
        $class = '';
        
        if (get_option('likebtn_notice_plan') == 1) {
            // Upgrade
            $class = 'updated';
        } else {
            // Downgrade
            $class = 'error';
        }
        _likebtn_notice($msg, $class);
        update_option('likebtn_notice_plan', false);
    }
}

add_action('admin_notices', 'likebtn_plan_notice');

// Add notice
function _likebtn_add_notice($notice) {
    $notices = get_option('likebtn_admin_notices');
    if (!is_array($notices)) {
        $notices = array();
    }
    $notices[] = $notice;
    update_option('likebtn_admin_notices', $notices);
}

// Display notices
function likebtn_admin_notices() {
    $likebtn_admin_notices = get_option('likebtn_admin_notices');

    if (is_array($likebtn_admin_notices) && count($likebtn_admin_notices)) {
        foreach ($likebtn_admin_notices as $notice) {
            $class = 'updated';
            if (!empty($notice['class'])) {
                $class = $notice['class'];
            }
            _likebtn_notice($notice['msg'], $class);    
        }
        
        update_option('likebtn_admin_notices', array());
    }
}

add_action('admin_notices', 'likebtn_admin_notices');

// uninstall function called from uninstall.php
function likebtn_uninstall() {
    global $wpdb;
    global $likebtn_settings;
    global $likebtn_settings_options;
    global $likebtn_buttons_options;
    global $likebtn_internal_options;

    // Options
    $likebtn_entities = _likebtn_get_entities();

    foreach ($likebtn_settings_options as $option_name=>$option_value) {
        delete_option($option_name);
    }

    foreach ($likebtn_entities as $entity_name => $entity_title) {
        foreach ($likebtn_buttons_options as $option_name=>$option_value) {
            delete_option($option_name.'_'.$entity_name);
        }
        // settings
        foreach ($likebtn_settings as $option_name => $option_info) {
            delete_option('likebtn_settings_' . $option_name . '_' . $entity_name);
        }
    }

    foreach ($likebtn_internal_options as $option_name=>$option_value) {
        delete_option($option_name);
    }

    // Tables
    $wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . LIKEBTN_TABLE_ITEM );
}

// activation hook
function likebtn_activation_hook()
{
    // Install DB
    _likebtn_db_install();

    // Add options
    _likebtn_add_options();
}

// Add options
function _likebtn_add_options()
{
    global $likebtn_settings_options;
    global $likebtn_internal_options;

    $likebtn_entities = _likebtn_get_entities();

    // set default values for options
    foreach ($likebtn_settings_options as $option_name=>$option_value) {
        add_option($option_name, $option_value);
    }

    // set default options for entities
    foreach ($likebtn_entities as $entity_name => $entity_title) {
        _likebtn_add_default_options($entity_name);
    }

    // set default values for options
    foreach ($likebtn_internal_options as $option_name=>$option_value) {
        add_option($option_name, $option_value);
    }

    // For showing review link
    if (!get_option('likebtn_installation_timestamp')) {
        add_option('likebtn_installation_timestamp', time());
    }

    //add_option('likebtn_db_version', LIKEBTN_DB_VERSION);
}

// The latest version of DB
function _likebtn_db_install() {
    global $wpdb;

    $table_name = $wpdb->prefix . LIKEBTN_TABLE_ITEM;

    $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
        `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        `identifier` text NOT NULL,
        `identifier_hash` varchar(32) NOT NULL,
        `url` text,
        `title` text,
        `description` text,
        `image` text,
        `likes` int(11) NOT NULL DEFAULT '0',
        `dislikes` int(11) NOT NULL DEFAULT '0',
        `likes_minus_dislikes` int(11) NOT NULL DEFAULT '0',
        PRIMARY KEY (`ID`),
        UNIQUE KEY `identifier_hash` (`identifier_hash`),
        KEY `title` (`title`(1)),
        KEY `likes` (`likes`),
        KEY `dislikes` (`dislikes`),
        KEY `likes_minus_dislikes` (`likes_minus_dislikes`),
        KEY `identifier` (`identifier`(1))
    );";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// Plugin loaded
function _likebtn_plugin_on_load()
{
    //likebtn_uninstall();
    //_likebtn_add_options();

    _likebtn_db_update();
    _likebtn_update_options();

    // Run sunchronization
    require_once(dirname(__FILE__) . '/likebtn_like_button.class.php');
    $likebtn = new LikeBtnLikeButton();
    $likebtn->runSyncVotes();
}

// Update DB
function _likebtn_db_update() 
{
    require_once(dirname(__FILE__) . '/likebtn_like_button_db_update.php');

    $db_version = (int)get_option('likebtn_db_version');

    if (!$db_version) {
        // Backward compatibility
        $db_version = (int)get_option('likebtn_like_button_db_version');
    }
    //$db_version = 5;
    $db_version++;
    while (function_exists('likebtn_db_update_'.$db_version)) {
        call_user_func('likebtn_db_update_'.$db_version);
        update_option("likebtn_db_version", $db_version);
        $db_version++;
    }
}

// update options
function _likebtn_update_options() {
    $likebtn_entities = _likebtn_get_entities();

    foreach ($likebtn_entities as $entity_name => $entity_title) {

        // Set default settings for new entity
        // User lang parameter to check if option exists
        if (!get_option('likebtn_settings_lang_' . $entity_name)) {
            _likebtn_add_default_options($entity_name);
        }

        // Backward compatibility
        if (get_option('likebtn_settings_display_only_' . $entity_name)) {
            if (get_option('likebtn_settings_display_only_' . $entity_name) == '1') {
                update_option('likebtn_settings_voting_enabled_' . $entity_name, '0');
            }
            delete_option('likebtn_settings_display_only_' . $entity_name);
        }
        if (get_option('likebtn_settings_unlike_allowed_' . $entity_name)) {
            if (get_option('likebtn_settings_unlike_allowed_' . $entity_name) == '1') {
                update_option('likebtn_settings_voting_cancelable_' . $entity_name, '1');
            }
            delete_option('likebtn_settings_unlike_allowed_' . $entity_name);
        }
        if (get_option('likebtn_settings_like_dislike_at_the_same_time_' . $entity_name)) {
            if (get_option('likebtn_settings_like_dislike_at_the_same_time_' . $entity_name) == '1') {
                update_option('likebtn_settings_voting_both_' . $entity_name, '1');
            }
            delete_option('likebtn_settings_like_dislike_at_the_same_time_' . $entity_name);
        }
        if (get_option('likebtn_settings_show_copyright_' . $entity_name) === '0') {
            update_option('likebtn_settings_white_label_' . $entity_name, '1');
            delete_option('likebtn_settings_show_copyright_' . $entity_name);
        }
        if (get_option('likebtn_settings_style_' . $entity_name)) {
            update_option('likebtn_settings_theme_' . $entity_name, get_option('likebtn_settings_style_' . $entity_name));
            delete_option('likebtn_settings_style_' . $entity_name);
        }
        if (get_option('likebtn_settings_revote_period_' . $entity_name)) {
            update_option('likebtn_settings_voting_frequency_' . $entity_name, get_option('likebtn_settings_revote_period_' . $entity_name));
            delete_option('likebtn_settings_revote_period_' . $entity_name);
        }
        // Process likebtn_post_view_mode_ for Excerpt entities
        if (_likebtn_has_list_flag($entity_name)) {
            $original_name = _likebtn_cut_list_flag($entity_name);

            if (get_option('likebtn_post_view_mode_' . $entity_name) && get_option('likebtn_show_' . $original_name) == '1') {
                if (in_array(get_option('likebtn_post_view_mode_' . $entity_name), array('excerpt', 'both')) &&
                    get_option('likebtn_show_' . $entity_name) != '1')
                {
                    update_option('likebtn_show_' . $entity_name, '1');
                    update_option('likebtn_use_settings_from_' . $entity_name, $original_name);
                }
                delete_option('likebtn_post_view_mode_' . $entity_name);
            }
        }
    }
}

// set default options for post type
function _likebtn_add_default_options($entity_name) {
    global $likebtn_settings;
    global $likebtn_buttons_options;
    global $likebtn_entities_config;

    foreach ($likebtn_buttons_options as $option_name=>$option_value) {

        switch ($option_name) {
            case 'likebtn_show':
                if ($entity_name == LIKEBTN_ENTITY_COMMENT) {
                    $option_value = '0';
                }
                break;
            case 'likebtn_use_settings_from':
                // Set copy settings for list entities
                if (_likebtn_has_list_flag($entity_name)) {
                    $option_value = _likebtn_cut_list_flag($entity_name);
                }
                break;
            case 'likebtn_position':
                if ($entity_name == LIKEBTN_ENTITY_PRODUCT || $entity_name == LIKEBTN_ENTITY_PRODUCT_LIST) {
                    $option_value = LIKEBTN_POSITION_TOP;
                }
                break;
        }

        add_option($option_name.'_'.$entity_name, $option_value);
    }

    // settings
    foreach ($likebtn_settings as $option_name => $option_info) {
        $value = $option_info['default'];
        if (!empty($likebtn_entities_config[$option_name][$entity_name]['value'])) {
            $value = $likebtn_entities_config[$option_name][$entity_name]['value'];
        }
        add_option('likebtn_settings_' . $option_name . '_' . $entity_name, $value);
    }
}

register_activation_hook(__FILE__, 'likebtn_activation_hook');

// admin init hook: registering settings
function likebtn_admin_init()
{
    global $likebtn_settings_options;
    global $likebtn_settings;
    global $likebtn_buttons_options;

    // Synchronization
    foreach ($likebtn_settings_options as $option_name=>$option_value) {
        register_setting('likebtn_settings', $option_name);
    }

    // Buttons
    $entity_name = _likebtn_get_subpage();

    /*$likebtn_entities = _likebtn_get_entities();
    foreach ($likebtn_entities as $entity_name => $entity_title) {
        _likebtn_register_entity_settings($entity_name);
    }*/
    foreach ($likebtn_buttons_options as $option_name=>$option_value) {
        register_setting('likebtn_buttons', $option_name.'_'.$entity_name);
    }

    // settings
    foreach ($likebtn_settings as $option_name => $option_info) {
        register_setting('likebtn_buttons', 'likebtn_settings_' . $option_name.'_'.$entity_name);
    }

    // run sunchronization
    require_once(dirname(__FILE__) . '/likebtn_like_button.class.php');
    $likebtn = new LikeBtnLikeButton();
    $likebtn->runSyncPlan();
}

// register entity settings
/*function _likebtn_register_entity_settings($entity_name)
{
    global $likebtn_settings;
    global $likebtn_buttons_options;

    foreach ($likebtn_buttons_options as $option_name=>$option_value) {
        register_setting('likebtn_buttons', $option_name.'_'.$entity_name);
    }

    // settings
    foreach ($likebtn_settings as $option_name => $option_info) {
        register_setting('likebtn_buttons', 'likebtn_settings_' . $option_name.'_'.$entity_name);
    }
}*/

add_action('admin_init', 'likebtn_admin_init');

// admin content
function likebtn_admin_settings() {

    //global $likebtn_plans;
    global $likebtn_sync_intervals;

    // reset sync interval
    if (!get_option('likebtn_account_email') || !get_option('likebtn_account_api_key') || !get_option('likebtn_site_id')) {
        update_option('likebtn_sync_inerval', '');
    }

    // If account data has changed, refresh the plan
    $account_data_hash = md5(get_option('likebtn_account_email').get_option('likebtn_account_api_key').get_option('likebtn_site_id'));
    if (!get_option('likebtn_account_data_hash') || $account_data_hash != get_option('likebtn_account_data_hash')) {
        update_option('likebtn_account_data_hash', $account_data_hash);

        // run plan sunchronization
        require_once(dirname(__FILE__) . '/likebtn_like_button.class.php');
        $likebtn = new LikeBtnLikeButton();
        $likebtn->syncPlan();

        // run synchronization
        $likebtn->syncVotes();
    }

    likebtn_admin_header();
    ?>
    <?php /*<script type="text/javascript">
        jQuery(document).ready(function() {
            planChange(jQuery(":input[name='likebtn_plan']").val());
        });
    </script>*/ ?>
    <div class="likebtn_subpage">
        <form method="post" action="options.php">
            <?php settings_fields('likebtn_settings'); ?>
            <?php /*
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label><?php _e('Current plan', LIKEBTN_I18N_DOMAIN); ?></label></th>
                    <td>
                        <select name="likebtn_plan" onChange="planChange(this.value)">
                            <?php foreach ($likebtn_plans as $plan_id => $plan_name): ?>
                                <option value="<?php echo $plan_id; ?>" <?php if (get_option('likebtn_plan') == $plan_id): ?>selected="selected"<?php endif ?> ><?php echo $plan_name; ?></option>
                            <?php endforeach ?>
                        </select>
                        <p class="description">
                            <?php _e('Premium features are available only if your website is upgraded to the corresponding tariff plan (PLUS, PRO, VIP, ULTRA). Keep in mind that only websites upgraded to <a href="http://likebtn.com/en/#plans_pricing" target="_blank">PLUS</a> plan or higher are allowed to display more than 1 Like Button per page.', LIKEBTN_I18N_DOMAIN) ?><br/>
                            <a href="javascript:toggleToUpgrade();void(0);"><?php _e('To upgrade your website...', LIKEBTN_I18N_DOMAIN) ?></a>
                            <ol id="likebtn_to_upgrade" class="hidden">
                                <li><?php _e('Register on <a href="http://likebtn.com/en/customer.php/register/" target="_blank">LikeBtn.com</a>', LIKEBTN_I18N_DOMAIN) ?></li>
                                <li><?php _e('Add your website to your account and activate it on <a href="http://likebtn.com/en/customer.php/websites" target="_blank">Websites page</a>', LIKEBTN_I18N_DOMAIN) ?></li>
                                <li><?php _e('Upgrade your website to the desired plan.', LIKEBTN_I18N_DOMAIN) ?></li>
                            </ol>
                        </p>
                    </td>
                </tr>
            </table>
            */ ?>
            <p class="description">
                <?php _e('Enable synchronization of likes from LikeBtn.com system into your database to get the opportunity to use the following features:', LIKEBTN_I18N_DOMAIN); ?><br/>
                ● <?php _e('View statistics on Statistics tab.', LIKEBTN_I18N_DOMAIN); ?><br/>
                ● <?php _e('Sort content by likes.', LIKEBTN_I18N_DOMAIN); ?><br/>
                ● <?php _e('Use most liked content widget and shortcode.', LIKEBTN_I18N_DOMAIN); ?><br/>
            </p>
            <br/>

            <div class="postbox ">
                <h3><?php _e('LikeBtn.com Account', LIKEBTN_I18N_DOMAIN); ?></h3>
                <div class="inside">
                    <p>
                        <?php _e('To get your account data:', LIKEBTN_I18N_DOMAIN); ?>
                        <ol>
                            <li>
                                <?php echo strtr(
                                    __('Register on <a href="%url_register%">LikeBtn.com</a>', LIKEBTN_I18N_DOMAIN), 
                                    array('%url_register%'=>"javascript:likebtnPopup('".__('http://likebtn.com/en/customer.php/register/', LIKEBTN_I18N_DOMAIN)."');void(0)")); 
                                ?>
                            </li>
                            <li>
                                <?php echo strtr(
                                    __('Add your website to your account on <a href="%url_websites%">Websites</a> page.', LIKEBTN_I18N_DOMAIN), 
                                    array('%url_websites%'=>"javascript:likebtnPopup('".__('http://likebtn.com/en/customer.php/websites', LIKEBTN_I18N_DOMAIN)."');void(0)")); 
                                ?>
                            </li>
                        </ol>
                    </p>
                    <input class="button-primary likebtn_button_green" type="button" value="<?php _e('Get Account Data', LIKEBTN_I18N_DOMAIN); ?>" onclick="likebtnGetAccountData('<?php _e('http://likebtn.com/en/customer.php/register/', LIKEBTN_I18N_DOMAIN) ?>')" />
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row"><label><?php _e('E-mail', LIKEBTN_I18N_DOMAIN); ?></label></th>
                            <td>
                                <input type="text" name="likebtn_account_email" value="<?php echo get_option('likebtn_account_email') ?>" onkeyup="accountChange(this)" class="likebtn_account likebtn_input" id="likebtn_account_email_input"/><br/>
                                <p class="description"><?php _e('Your LikeBtn.com account email. Can be found on <a href="http://likebtn.com/en/customer.php/profile/edit" target="_blank">Profile</a> page', LIKEBTN_I18N_DOMAIN) ?></p>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><label><?php _e('API key', LIKEBTN_I18N_DOMAIN); ?></label></th>
                            <td>
                                <input type="text" name="likebtn_account_api_key" value="<?php echo get_option('likebtn_account_api_key') ?>" onkeyup="accountChange(this)" class="likebtn_account likebtn_input" id="likebtn_account_api_key_input" maxlength="32" /><br/>
                                <p class="description"><?php _e('Your website API key on LikeBtn.com. Can be obtained on <a href="http://likebtn.com/en/customer.php/websites" target="_blank">Websites</a> page', LIKEBTN_I18N_DOMAIN) ?></p>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><label><?php _e('Site ID', LIKEBTN_I18N_DOMAIN); ?></label></th>
                            <td>
                                <input type="text" name="likebtn_site_id" value="<?php echo get_option('likebtn_site_id') ?>" class="likebtn_input" id="likebtn_site_id_input" maxlength="24" /><br/>
                                <?php /*<span class="description"><?php _e('Enter Site ID in following cases:', LIKEBTN_I18N_DOMAIN) ?><br/>
                                ● <?php _e('Your site is local – located on a local server and is available from your local network only and NOT available from the Internet.', LIKEBTN_I18N_DOMAIN) ?><br/>
                                ● <?php _e('Your site is path-based – one of sites located in different subdirectories of one domain and you want to have statistics separate from other sites.', LIKEBTN_I18N_DOMAIN) ?><br/><br/>*/ ?>
                                <p class="description">
                                    <?php _e('Your Site ID on LikeBtn.com. Can be obtained on <a href="http://likebtn.com/en/customer.php/websites" target="_blank">Websites</a> page.', LIKEBTN_I18N_DOMAIN) ?></span>
                                </p>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">&nbsp;</th>
                            <td>
                                <input class="button-primary" type="button" value="<?php _e('Check Account Data', LIKEBTN_I18N_DOMAIN); ?>" onclick="checkAccount('<?php echo _likebtn_get_public_url() ?>img/ajax_loader.gif')" /> &nbsp;<strong class="likebtn_check_account_container"><img src="<?php echo _likebtn_get_public_url() ?>img/ajax_loader.gif" class="hidden"/></strong>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <?php if (get_option('likebtn_plan') < LIKEBTN_PLAN_PRO): ?>
                <strong class="likebtn_error">
                    <?php echo strtr(
                        __('Website tariff plan does not allow to synchronize likes into your database – <a href="%url_upgrade%">upgrade</a> your website to PRO or higher plan.', LIKEBTN_I18N_DOMAIN), 
                        array('%url_upgrade%'=>"javascript:likebtnPopup('".__('http://likebtn.com/en/', LIKEBTN_I18N_DOMAIN)."#plans_pricing');void(0)")); 
                    ?>
                </strong>
                <br/><br/>
            <?php endif ?>

            <div class="postbox ">
                <div class="inside">
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row"><label><?php _e('Synchronization Interval', LIKEBTN_I18N_DOMAIN); ?></label>
                                <i class="likebtn_help" title="<?php _e('Time interval in minutes in which fetching of vote results from LikeBtn.com into your database is being launched. When synchronization is enabled you can view Statistics, number of likes and dislikes for each post as Custom Field, sort posts by vote results, use Like Button widgets. The less the interval the heavier your database load.', LIKEBTN_I18N_DOMAIN); ?>">&nbsp;</i>
                            </th>
                            <td>
                                <select name="likebtn_sync_inerval" <?php disabled((!get_option('likebtn_account_email') || !get_option('likebtn_account_api_key'))); ?> id="likebtn_sync_inerval_input">
                                    <option value="" <?php selected('', get_option('likebtn_sync_inerval')); ?> ><?php _e('Do not fetch votes from LikeBtn.com into my database', LIKEBTN_I18N_DOMAIN) ?></option>
                                    <?php foreach ($likebtn_sync_intervals as $sync_interval): ?>
                                        <option value="<?php echo $sync_interval; ?>" <?php selected($sync_interval, get_option('likebtn_sync_inerval')); ?> ><?php echo $sync_interval; ?> <?php _e('min', LIKEBTN_I18N_DOMAIN); ?></option>
                                    <?php endforeach ?>
                                </select>
                                <br/><br/>
                                <input class="button-primary" type="button" value="<?php _e('Test sync', LIKEBTN_I18N_DOMAIN); ?>" onclick="testSync('<?php echo _likebtn_get_public_url() ?>img/ajax_loader.gif')" /> &nbsp;<strong class="likebtn_test_sync_container"><img src="<?php echo _likebtn_get_public_url() ?>img/ajax_loader.gif" class="hidden"/></strong>
                                <br/><br/>
                                <div class="liketbtn_mansync_wr">
                                    <input class="button-secondary likebtn_ttip" type="button" value="<?php _e('Run Full Sync Manually', LIKEBTN_I18N_DOMAIN); ?>" onclick="manualSync('<?php echo _likebtn_get_public_url() ?>img/ajax_loader.gif')" title="<?php _e("ATTENTION: Use this feature carefully since full synchronization may affect your website performance. If you don't experience any problems with likes synchronization better to avoid using this feature.", LIKEBTN_I18N_DOMAIN) ?>" /> &nbsp;<strong class="likebtn_manual_sync_container"><img src="<?php echo _likebtn_get_public_url() ?>img/ajax_loader.gif" class="hidden"/></strong>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <?php /*<div class="postbox likebtn_account">
                <h3><?php _e('Local domain', LIKEBTN_I18N_DOMAIN); ?></h3>
                <div class="inside">
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row">&nbsp;</th>
                            <td> ?>
                                <input type="hidden" name="likebtn_local_domain" value="<?php echo get_option('likebtn_local_domain') ?>" size="60" />
                                <?php
                                <br/>
                                <strong class="description"><?php _e('Example:', LIKEBTN_I18N_DOMAIN) ?> localdomain!50f358d30acf358d30ac000001</strong>
                                <br/><br/>
                                <span class="description"><?php _e('Specify it if your website is located on a local server and is available from your local network only and NOT available from the Internet. You can find the domain on your <a href="http://likebtn.com/en/customer.php/websites" target="_blank">Websites</a> page after adding your local website to the panel. See <a href="http://likebtn.com/en/faq#local_domain" target="_blank">FAQ</a> for more details.', LIKEBTN_I18N_DOMAIN) ?></span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>*/ ?>
            <?php /*<div class="postbox likebtn_account">
                <h3><?php _e('Website subdirectory', LIKEBTN_I18N_DOMAIN); ?></h3>
                <div class="inside">
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row">&nbsp;</th>
                            <td> ?>
                                <input type="hidden" name="likebtn_subdirectory" value="<?php echo get_option('likebtn_subdirectory') ?>" size="60" />
                                <?php
                                <br/>
                                <strong class="description"><?php _e('Example:', LIKEBTN_I18N_DOMAIN) ?> /subdirectory/</strong>
                                <br/><br/>
                                <span class="description"><?php _e('If your website is one of websites located in different subdirectories of one domain and you want to have a separate from other websites on this domain statistics, enter subdirectory (for example /subdirectory/). Required for path-based <a href="http://codex.wordpress.org/Create_A_Network" target="_blank">multisite networks</a> in which on-demand sites use paths.', LIKEBTN_I18N_DOMAIN) ?></span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>*/ ?>

            <input class="button-primary" type="submit" name="Save" value="<?php _e('Save Changes', LIKEBTN_I18N_DOMAIN); ?>" />
        </form>

    </div>
    <?php

    _likebtn_admin_footer();
}

// admin buttons
function likebtn_admin_buttons() {

    global $likebtn_styles;
    global $likebtn_default_locales;
    global $likebtn_settings;
    global $likebtn_entities_config;
    global $likebtn_addthis_service_codes;

    // Enque scripts
    wp_register_script('select2', _likebtn_get_public_url().'js/jquery/select2/select2.js', array('jquery'), LIKEBTN_VERSION, true);
    wp_register_script('select2-sortable', _likebtn_get_public_url().'js/jquery/select2/select2.sortable.js', array('select2'), LIKEBTN_VERSION, true);
    wp_register_style('select2-css', _likebtn_get_public_url().'css/jquery/select2/select2.css', false, LIKEBTN_VERSION, 'all');
    wp_register_script('likebtn-jquery-ui', _likebtn_get_public_url().'js/jquery/jquery-ui/jquery-ui.js', array('jquery'), LIKEBTN_VERSION, true);
    wp_register_style('likebtn-jquery-ui-css', _likebtn_get_public_url().'css/jquery/jquery-ui/jquery-ui.css', false, LIKEBTN_VERSION, 'all');
    wp_register_style('likebtn-addthis', _likebtn_get_public_url().'css/addthis.css', false, LIKEBTN_VERSION, 'all');
    // Select2 locale
    $blog_locale = get_locale();
    list($blog_locale_main) = explode("_", $blog_locale);
    $plugin_dir = plugin_dir_path(__FILE__);

    $select2_locale_script = '';
    if (file_exists($plugin_dir.'public/js/jquery/select2/locale/select2_locale_'.$blog_locale.'.js')) {
        $select2_locale_script = _likebtn_get_public_url().'js/jquery/select2/locale/select2_locale_'.$blog_locale.'.js';
    } else if (file_exists($plugin_dir.'public/js/jquery/select2/locale/select2_locale_'.$blog_locale_main.'.js')) {
        $select2_locale_script = _likebtn_get_public_url().'js/jquery/select2/locale/select2_locale_'.$blog_locale_main.'.js';
    }

    if ($select2_locale_script) {
        wp_register_script('select2-locale', $select2_locale_script, LIKEBTN_VERSION, true);
    }

    wp_enqueue_script('select2');
    wp_enqueue_script('select2-sortable');
    wp_enqueue_style('select2-css');
    if ($select2_locale_script) {
        wp_enqueue_script('select2-locale');
    }
    wp_enqueue_script('likebtn-jquery-ui');
    wp_enqueue_style('likebtn-jquery-ui-css');
    wp_enqueue_style('likebtn-addthis');


    $likebtn_entities = _likebtn_get_entities();

    // retrieve post formats
    $post_formats = _likebtn_get_post_formats();

    // run sunchronization
    require_once(dirname(__FILE__) . '/likebtn_like_button.class.php');
    $likebtn = new LikeBtnLikeButton();
    $likebtn->runSyncLocales();
    $likebtn->runSyncStyles();

    // Languages
    $locales = get_option('likebtn_locales');
    $languages = array();
    $languages['auto'] = array(
        'name' => __("Automatic", LIKEBTN_I18N_DOMAIN),
        'en_name' => __("Automatic", LIKEBTN_I18N_DOMAIN)
    );
    if (!$locales) {
        $locales = $likebtn_default_locales;
    }
    foreach ($locales as $locale_code => $locale_info) {
        $languages[$locale_code] = array(
            'name' => $locale_info['name'],
            'en_name' => $locale_info['en_name']
        );
    }

    // Get styles
    $styles = get_option('likebtn_styles');

    $style_options = array();
    if (!$styles) {
      // Styles have not been loaded using API yet, load default languages
      $styles = $likebtn_styles;
    }
    foreach ($styles as $style) {
      $style_options[] = $style;
    }

    // Select tab
    $subpage = _likebtn_get_subpage();
    //$entity_title = $likebtn_entities[$entity_name];

    // JS and Styles
    global $likebtn_website_locales;
    $likebtn_website_locale = substr(get_bloginfo('language'), 0, 2);
    if (!in_array($likebtn_website_locale, $likebtn_website_locales)) {
        $likebtn_website_locale = 'en';
    }

    likebtn_admin_header();
    ?>
    
    <script>(function(d, e, s) {a = d.createElement(e);m = d.getElementsByTagName(e)[0];a.async = 1;a.src = s;m.parentNode.insertBefore(a, m)})(document, 'script', '//<?php echo LIKEBTN_WEBSITE_DOMAIN; ?>/<?php echo $likebtn_website_locale ?>/js/donate_generator.js');
if (typeof(LikeBtn) != "undefined") { LikeBtn.init(); }</script>
    <?php /*
    <link rel="stylesheet" type="text/css" href="<?php echo _likebtn_get_public_url() ?>js/jquery/select2/select2.js?ver=<?php echo LIKEBTN_VERSION ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo _likebtn_get_public_url() ?>js/jquery/jquery-ui/jquery-ui.js?ver=<?php echo LIKEBTN_VERSION ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo _likebtn_get_public_url() ?>css/jquery/select2/select2.css?ver=<?php echo LIKEBTN_VERSION ?>" />
    */ ?>

    <script type="text/javascript">
        var reset_settings = [];
        reset_settings['post_view_mode'] = 'both';
        reset_settings['post_format'] = 'all';
        reset_settings['exclude_sections'] = '';
        reset_settings['exclude_categories'] = '';
        reset_settings['allow_ids'] = '';
        reset_settings['exclude_ids'] = '';
        reset_settings['position'] = 'bottom';
        reset_settings['alignment'] = 'left';
        reset_settings['user_logged_in'] = '';
    <?php foreach ($likebtn_settings as $option_name => $option_info): ?>
            reset_settings['settings_<?php echo $option_name ?>'] = '<?php echo $option_info['default'] ?>';
    <?php endforeach ?>

    <?php /*
        var likebtn_entities = [
    <?php $entity_index = 0; ?>
    <?php foreach ($likebtn_entities as $entity_name => $entity_title): ?>
            <?php $entity_index++; ?>
            '<?php echo $entity_name; ?>'<?php if ($entity_index != count($likebtn_entities)-1): ?>,<?php endif ?>
    <?php endforeach ?>
        ];
     */ ?>

        var likebtn_msg_reset = '<?php _e('Are you sure you want to reset settings for this entity?', LIKEBTN_I18N_DOMAIN); ?>';
        var likebtn_msg_identifier = '<?php _e('CUSTOM ITEM NAME', LIKEBTN_I18N_DOMAIN); ?>';

        var likebtn_path_settings_theme = '//<?php echo LIKEBTN_WEBSITE_DOMAIN; ?>/bundles/likebtnwebsite/i/theme/';
        var likebtn_path_settings_counter_type = '//<?php echo LIKEBTN_WEBSITE_DOMAIN; ?>/bundles/likebtnwebsite/i/counter/';
        var likebtn_default_settings = <?php echo json_encode(array(
            'addthis_service_codes' => $likebtn_settings['addthis_service_codes']
        )) ?>;
        var likebtn_prev_lang = '';

        jQuery(document).ready(function() {
            planChange('<?php echo get_option('likebtn_plan'); ?>');
            <?php /* if (!empty($subpage)): ?>
                likebtnGotoSubpage('<?php echo $subpage; ?>');
            <?php endif ?>
            likebtnDetectSubpage();
            */ ?>
           
            // Image dropdown
            jQuery(".image_dropdown").select2({
                formatResult: likebtnFormatSelect,
                formatSelection: likebtnFormatSelect,
                escapeMarkup: function(m) { return m; },
                minimumResultsForSearch: -1
            });
            jQuery("#settings_popup_content_order").select2({
                /*formatResult: likebtnPcoSelectResult,
                formatSelection: likebtnPcoSelectSelection,*/
                escapeMarkup: function(m) { return m; },
                dropdownCssClass: "likebtn_pco_container"
            }).select2Sortable();
            jQuery("#settings_addthis_service_codes").select2({
                formatResult: likebtnAddthisSelectResult,
                formatSelection: likebtnAddthisSelectSelection,
                escapeMarkup: function(m) { return m; },
                maximumSelectionSize: 8,
                dropdownCssClass: "likebtn_at16_conatiner"
            }).select2Sortable();

            // Radio images
            jQuery('.image_toggle').buttonset();

            displayFields();

            // Events
            jQuery("#settings_lang").change(function() {
                // Must come before displayTranslations
                displayAddthis();
                displayTranslations();
            });

            // Refresh preview
            jQuery("#settings_container :input").on("keyup change", function(event) {
                likebtnRefreshPreview("<?php echo $subpage ?>");
            });

            // Fix preview
            likebtnFixPreview();
        });
    </script>

    <div>
        <form method="post" action="options.php" onsubmit="return likebtnOnSaveButtons()" id="settings_form">
            <?php settings_fields('likebtn_buttons'); ?>
            <input type="hidden" name="likebtn_subpage" value="<?php echo $subpage; ?>" id="likebtn_entity_name_field">

            <h3 class="nav-tab-wrapper" style="padding: 0" id="likebtn_subpage_tab_wrapper">
                <?php foreach ($likebtn_entities as $tab_entity_name => $tab_entity_title): ?>
                    <a class="nav-tab likebtn_tab_<?php echo $tab_entity_name; ?> <?php echo ($subpage == $tab_entity_name ? 'nav-tab-active' : '') ?>" href="<?php echo admin_url().'admin.php?page=likebtn_buttons&likebtn_subpage='.$tab_entity_name; ?>"><img src="<?php echo _likebtn_get_public_url() ?>img/check.png" class="likebtn_ttip likebtn_show_marker <?php if (get_option('likebtn_show_' . $tab_entity_name) != '1'): ?>hidden<?php endif ?>" title="<?php _e('Like Button enabled', LIKEBTN_I18N_DOMAIN); ?>"><?php _e($tab_entity_title, LIKEBTN_I18N_DOMAIN); ?></a>
                <?php endforeach ?>
            </h3>
            <?php
            foreach ($likebtn_entities as $entity_name => $entity_title):

                // Display one entity per page
                if ($subpage != $entity_name) {
                    continue;
                }

                $excluded_sections = get_option('likebtn_exclude_sections_' . $entity_name);
                if (!is_array($excluded_sections)) {
                    $excluded_sections = array();
                }

                $excluded_categories = get_option('likebtn_exclude_categories_' . $entity_name);
                if (!is_array($excluded_categories)) {
                    $excluded_categories = array();
                }

                // just in case
                if (!is_array(get_option('likebtn_post_format_' . $entity_name))) {
                    update_option('likebtn_post_format_' . $entity_name, array('all'));
                }

                // AddThis service codes
                $value_addthis_service_codes = get_option('likebtn_settings_addthis_service_codes_' . $entity_name);
                if (!$value_addthis_service_codes) {
                    $lang = get_option('likebtn_settings_lang_' . $entity_name);
                    if (!empty($likebtn_settings['addthis_service_codes']['default_values'][$lang])) {
                        $value_addthis_service_codes = $likebtn_settings['addthis_service_codes']['default_values'][$lang];
                    } else {
                        $value_addthis_service_codes = $likebtn_settings['addthis_service_codes']['default_values']['all'];
                    }
                }

                ?>

                <div id="likebtn_subpage_wrapper_<?php echo $entity_name; ?>" class="likebtn_subpage <?php if ($subpage !== $entity_name): ?>hidden<?php endif ?>" >
                    <?php /*<h3><?php _e($entity_title, LIKEBTN_I18N_DOMAIN); ?></h3>*/ ?>
                    <div class="inside entity_tab_container">

                        <table class="form-table">
                            <tr valign="top">
                                <th scope="row"><label><?php _e('Enable Like Button', LIKEBTN_I18N_DOMAIN); ?></label></th>
                                <td>
                                    <input type="checkbox" name="likebtn_show_<?php echo $entity_name; ?>" value="1" <?php checked('1', get_option('likebtn_show_' . $entity_name)); ?> onClick="entityShowChange(this, '<?php echo $entity_name; ?>')" />
                                </td>
                            </tr>
                        </table>

                        <div id="entity_container_<?php echo $entity_name; ?>" <?php if (!get_option('likebtn_show_' . $entity_name)): ?>style="display:none"<?php endif ?>>
                            <table class="form-table" >
                                <tr valign="top">
                                    <th scope="row"><label><?php _e('Copy Settings From', LIKEBTN_I18N_DOMAIN); ?></label>
                                        <i class="likebtn_help" title="<?php _e('Choose the entity from which you want to copy settings', LIKEBTN_I18N_DOMAIN); ?>">&nbsp;</i>
                                    </th>
                                    <td>
                                        <select name="likebtn_use_settings_from_<?php echo $entity_name; ?>" onChange="userSettingsFromChange(this, '<?php echo $entity_name; ?>')">
                                            <option value="" <?php selected('', get_option('likebtn_use_settings_from_' . $entity_name)); ?> >&nbsp;</option>
                                            <?php foreach ($likebtn_entities as $use_entity_name => $use_entity_title): ?>
                                                <?php
                                                if ($use_entity_name == $entity_name) {
                                                    continue;
                                                }
                                                ?>
                                                <option value="<?php echo $use_entity_name; ?>" <?php selected($use_entity_name, get_option('likebtn_use_settings_from_' . $entity_name)); ?> ><?php _e($use_entity_title, LIKEBTN_I18N_DOMAIN); ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </td>
                                </tr>
                            </table>

                            <?php if (get_option('likebtn_show_' . $entity_name) == '1'): ?>
                                <br/>
                                <div id="preview_fixer" class="likebtn_preview_static postbox">

                                    <h3>
                                        <?php _e('Preview', LIKEBTN_I18N_DOMAIN); ?>
                                    </h3>
                                    <div class="inside">
                                        <div class="preview_container">
                                            <?php echo _likebtn_get_markup($entity_name, 'demo', array(), get_option('likebtn_use_settings_from_' . $entity_name)) ?>
                                        </div>
                                        <input class="button-primary" type="submit" name="Save" value="<?php _e('Save Changes', LIKEBTN_I18N_DOMAIN); ?>" id="likebtn_save_preview"  <?php if (get_option('likebtn_use_settings_from_' . $entity_name)): ?>style="display: none"<?php endif ?>/>

                                        <span class="support_link">
                                            ♥ <?php _e('Like it?', LIKEBTN_I18N_DOMAIN); ?>
                                            <a href="http://wordpress.org/support/view/plugin-reviews/likebtn-like-button?rate=5#postform" target="_blank">
                                                <?php _e('Support the plugin with 5 Stars', LIKEBTN_I18N_DOMAIN); ?>
                                            </a>
                                        </span>
                                    </div>
                                </div>
                            <?php endif ?>

                            <div id="use_settings_from_container_<?php echo $entity_name; ?>" <?php if (get_option('likebtn_use_settings_from_' . $entity_name)): ?>style="display:none"<?php endif ?>>
                                <div class="postbox" id="settings_container">
                                    <h3><?php _e('Settings', LIKEBTN_I18N_DOMAIN); ?></h3>
                                    <div class="inside">

                                        <table class="form-table">
                                            <tr valign="top">
                                                <th scope="row"><label><?php _e('Theme', LIKEBTN_I18N_DOMAIN); ?></label></th>
                                                <td>
                                                    <select name="likebtn_settings_theme_<?php echo $entity_name; ?>" class="image_dropdown" id="settings_theme">
                                                        <?php foreach ($style_options as $style): ?>
                                                            <option value="<?php echo $style; ?>" <?php selected($style, get_option('likebtn_settings_theme_' . $entity_name)); ?> ><?php /*echo $style;*/ ?></option>
                                                        <?php endforeach ?>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr valign="top">
                                                <th scope="row"><label><?php _e('Language', LIKEBTN_I18N_DOMAIN); ?></label></th>
                                                <td>
                                                    <select name="likebtn_settings_lang_<?php echo $entity_name; ?>" id="settings_lang">
                                                        <?php foreach ($languages as $lang_code => $lang_info): ?>
                                                            <option value="<?php echo $lang_code; ?>" <?php selected($lang_code, get_option('likebtn_settings_lang_' . $entity_name)); ?> title="<?php echo $lang_info['en_name']; ?>">[<?php echo $lang_code; ?>] <?php echo $lang_info['name']; ?></option>
                                                        <?php endforeach ?>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr valign="top">
                                                <th scope="row"><label><?php _e('Show buttons', LIKEBTN_I18N_DOMAIN); ?></label></th>
                                                <td>
                                                    <input type="checkbox" name="likebtn_settings_like_enabled_<?php echo $entity_name; ?>" value="1" <?php checked('1', get_option('likebtn_settings_like_enabled_' . $entity_name)); ?> />
                                                    <i><?php _e('Like', LIKEBTN_I18N_DOMAIN); ?></i>
                                                    &nbsp;&nbsp;
                                                    <input type="checkbox" name="likebtn_settings_dislike_enabled_<?php echo $entity_name; ?>" value="1" <?php checked('1', get_option('likebtn_settings_dislike_enabled_' . $entity_name)); ?> />
                                                    <i><?php _e('Dislike', LIKEBTN_I18N_DOMAIN); ?></i>
                                                </td>
                                            </tr>
                                            <tr valign="top">
                                                <th scope="row"><label><?php _e('Show labels', LIKEBTN_I18N_DOMAIN); ?></label></th>
                                                <td>
                                                    <input type="checkbox" name="likebtn_settings_show_like_label_<?php echo $entity_name; ?>" value="1" <?php checked('1', get_option('likebtn_settings_show_like_label_' . $entity_name)); ?> />
                                                    <i><?php _e('Like', LIKEBTN_I18N_DOMAIN); ?></i>
                                                    &nbsp;&nbsp;
                                                    <input type="checkbox" name="likebtn_settings_show_dislike_label_<?php echo $entity_name; ?>" value="1" <?php checked('1', get_option('likebtn_settings_show_dislike_label_' . $entity_name)); ?> />
                                                    <i><?php _e('Dislike', LIKEBTN_I18N_DOMAIN); ?></i>
                                                </td>
                                            </tr>
                                            <tr valign="top">
                                                 <th scope="row"><label><?php _e('Show icons', LIKEBTN_I18N_DOMAIN); ?></label></th>
                                                 <td>
                                                     <input type="checkbox" name="likebtn_settings_icon_like_show_<?php echo $entity_name; ?>" value="1" <?php checked('1', get_option('likebtn_settings_icon_like_show_' . $entity_name)); ?> />
                                                     <i><?php _e('Like', LIKEBTN_I18N_DOMAIN); ?></i>
                                                     &nbsp;&nbsp;
                                                     <input type="checkbox" name="likebtn_settings_icon_dislike_show_<?php echo $entity_name; ?>" value="1" <?php checked('1', get_option('likebtn_settings_icon_dislike_show_' . $entity_name)); ?> />
                                                     <i><?php _e('Dislike', LIKEBTN_I18N_DOMAIN); ?></i>
                                                 </td>
                                             </tr>
                                            <tr valign="top">
                                                <th scope="row"><label><?php _e('Counter type', LIKEBTN_I18N_DOMAIN); ?></label></th>
                                                <td>
                                                    <select name="likebtn_settings_counter_type_<?php echo $entity_name; ?>" class="image_dropdown" id="settings_counter_type">
                                                        <option value="number" <?php selected('number', get_option('likebtn_settings_counter_type_' . $entity_name)); ?> ><?php _e('Number', LIKEBTN_I18N_DOMAIN); ?></option>
                                                        <option value="percent" <?php selected('percent', get_option('likebtn_settings_counter_type_' . $entity_name)); ?> ><?php _e('Percent', LIKEBTN_I18N_DOMAIN); ?></option>
                                                        <option value="subtract_dislikes" <?php selected('subtract_dislikes', get_option('likebtn_settings_counter_type_' . $entity_name)); ?> ><?php _e('Subtract dislikes', LIKEBTN_I18N_DOMAIN); ?></option>
                                                        <option value="single_number" <?php selected('single_number', get_option('likebtn_settings_counter_type_' . $entity_name)); ?> ><?php _e('Single number outside', LIKEBTN_I18N_DOMAIN); ?></option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr valign="top">
                                                <th scope="row"><label><?php _e('Show tooltips', LIKEBTN_I18N_DOMAIN); ?></label></th>
                                                <td>
                                                    <input type="checkbox" name="likebtn_settings_tooltip_enabled_<?php echo $entity_name; ?>" value="1" <?php checked('1', get_option('likebtn_settings_tooltip_enabled_' . $entity_name)); ?> />
                                                </td>
                                            </tr>
                                            <tr valign="top">
                                                <th scope="row"><label><?php _e('Like button text', LIKEBTN_I18N_DOMAIN); ?></label></th>
                                                <td>
                                                    <input type="text" name="likebtn_settings_i18n_like_<?php echo $entity_name; ?>" value="<?php echo get_option('likebtn_settings_i18n_like_' . $entity_name); ?>" class="likebtn_input likebtn_placeholder" placeholder="<?php _e('Like', LIKEBTN_I18N_DOMAIN); ?>"/>
                                                </td>
                                            </tr>
                                            <tr valign="top">
                                                <th scope="row"><label><?php _e('Dislike button text', LIKEBTN_I18N_DOMAIN); ?></label></th>
                                                <td>
                                                    <input type="text" name="likebtn_settings_i18n_dislike_<?php echo $entity_name; ?>" value="<?php echo get_option('likebtn_settings_i18n_dislike_' . $entity_name); ?>" class="likebtn_input likebtn_placeholder" placeholder="<?php _e('Dislike', LIKEBTN_I18N_DOMAIN); ?>" />
                                                </td>
                                            </tr>
                                            <tr valign="top" class="plan_dependent plan_vip">
                                                <th scope="row"><label><?php _e('White-label', LIKEBTN_I18N_DOMAIN); ?> <i class="premium_feature" title="VIP / ULTRA"></i></label>
                                                    <i class="likebtn_help" title="<?php _e('No LikeBtn branding link', LIKEBTN_I18N_DOMAIN); ?>">&nbsp;</i>
                                                </th>
                                                <td>
                                                    <input type="checkbox" name="likebtn_settings_white_label_<?php echo $entity_name; ?>" value="1" <?php checked('1', get_option('likebtn_settings_white_label_' . $entity_name)); ?> />
                                                </td>
                                            </tr>
                                            <?php if (empty($likebtn_entities_config['likebtn_alignment'][$entity_name]['hide'])): ?>
                                                <tr valign="top">
                                                    <th scope="row"><label><?php _e('Horizontal alignment', LIKEBTN_I18N_DOMAIN); ?></label></th>
                                                    <td>
                                                        <input type="radio" name="likebtn_alignment_<?php echo $entity_name; ?>" value="<?php echo LIKEBTN_ALIGNMENT_LEFT; ?>" <?php if (LIKEBTN_ALIGNMENT_LEFT == get_option('likebtn_alignment_' . $entity_name) || !get_option('likebtn_alignment_' . $entity_name)): ?>checked="checked"<?php endif ?> /> <?php _e('Left'); ?>
                                                        &nbsp;&nbsp;&nbsp;
                                                        <input type="radio" name="likebtn_alignment_<?php echo $entity_name; ?>" value="<?php echo LIKEBTN_ALIGNMENT_CENTER; ?>" <?php if (LIKEBTN_ALIGNMENT_CENTER == get_option('likebtn_alignment_' . $entity_name)): ?>checked="checked"<?php endif ?> /> <?php _e('Center'); ?>
                                                        &nbsp;&nbsp;&nbsp;
                                                        <input type="radio" name="likebtn_alignment_<?php echo $entity_name; ?>" value="<?php echo LIKEBTN_ALIGNMENT_RIGHT; ?>" <?php if (LIKEBTN_ALIGNMENT_RIGHT == get_option('likebtn_alignment_' . $entity_name)): ?>checked="checked"<?php endif ?> /> <?php _e('Right'); ?>

                                                    </td>
                                                </tr>
                                            <?php endif ?>
                                            <?php if (empty($likebtn_entities_config['likebtn_position'][$entity_name]['hide'])): ?>
                                                <tr valign="top">
                                                    <th scope="row"><label><?php _e('Vertical alignment', LIKEBTN_I18N_DOMAIN); ?></label></th>
                                                    <td>
                                                        <input type="radio" name="likebtn_position_<?php echo $entity_name; ?>" value="<?php echo LIKEBTN_POSITION_TOP ?>" <?php if (LIKEBTN_POSITION_TOP == get_option('likebtn_position_' . $entity_name)): ?>checked="checked"<?php endif ?> /> <?php _e('Top of Content', LIKEBTN_I18N_DOMAIN); ?>&nbsp;&nbsp;&nbsp;
                                                        <input type="radio" name="likebtn_position_<?php echo $entity_name; ?>" value="<?php echo LIKEBTN_POSITION_BOTTOM ?>" <?php if (LIKEBTN_POSITION_BOTTOM == get_option('likebtn_position_' . $entity_name) || !get_option('likebtn_position_' . $entity_name)): ?>checked="checked"<?php endif ?> /> <?php _e('Bottom of Content', LIKEBTN_I18N_DOMAIN); ?>&nbsp;&nbsp;&nbsp;
                                                        <input type="radio" name="likebtn_position_<?php echo $entity_name; ?>" value="<?php echo LIKEBTN_POSITION_BOTH ?>" <?php if (LIKEBTN_POSITION_BOTH == get_option('likebtn_position_' . $entity_name)): ?>checked="checked"<?php endif ?> /> <?php _e('Top and Bottom', LIKEBTN_I18N_DOMAIN); ?>

                                                    </td>
                                                </tr>
                                            <?php endif ?>
                                        </table>

                                        <?php /*<div class="postbox">

                                            <h3 style="cursor:pointer" onclick="toggleCollapsable(this)" class="likebtn_collapse_trigger"><small>►</small> <?php _e('Extended Settings', LIKEBTN_I18N_DOMAIN); ?></h3>
                                            <div class="inside">
                                                <br/>*/ ?>

                                                <?php /*<p class="description">&nbsp;&nbsp;<?php _e('You can find detailed settings description on <a href="http://likebtn.com/en/#settings" target="_blank">LikeBtn.com</a>', LIKEBTN_I18N_DOMAIN); ?></p><br/>*/ ?>

                                        <br/>

                                        <h3 class="nav-tab-wrapper" style="padding: 0" id="likebtn_extset_tabs">
                                            <a class="nav-tab likebtn_tab_general nav-tab-active" href="javascript:likebtnGotoTab('general', '.likebtn_tab_extset', '#likebtn_extset_tab_', '#likebtn_extset_tabs');void(0);"><?php _e('General', LIKEBTN_I18N_DOMAIN); ?></a>

                                            <a class="nav-tab likebtn_tab_popup" href="javascript:likebtnGotoTab('popup', '.likebtn_tab_extset', '#likebtn_extset_tab_', '#likebtn_extset_tabs');void(0);"><?php _e('Popup', LIKEBTN_I18N_DOMAIN); ?></a>

                                            <a class="nav-tab likebtn_tab_voting" href="javascript:likebtnGotoTab('voting', '.likebtn_tab_extset', '#likebtn_extset_tab_', '#likebtn_extset_tabs');void(0);"><?php _e('Voting', LIKEBTN_I18N_DOMAIN); ?></a>

                                            <a class="nav-tab likebtn_tab_counter" href="javascript:likebtnGotoTab('counter', '.likebtn_tab_extset', '#likebtn_extset_tab_', '#likebtn_extset_tabs');void(0);"><?php _e('Counter', LIKEBTN_I18N_DOMAIN); ?></a>

                                            <a class="nav-tab likebtn_tab_loading" href="javascript:likebtnGotoTab('loading', '.likebtn_tab_extset', '#likebtn_extset_tab_', '#likebtn_extset_tabs');void(0);"><?php _e('Loading', LIKEBTN_I18N_DOMAIN); ?></a>

                                            <a class="nav-tab likebtn_tab_tooltips" href="javascript:likebtnGotoTab('tooltips', '.likebtn_tab_extset', '#likebtn_extset_tab_', '#likebtn_extset_tabs');void(0);"><?php _e('Tooltips', LIKEBTN_I18N_DOMAIN); ?></a>

                                            <a class="nav-tab likebtn_tab_events" href="javascript:likebtnGotoTab('events', '.likebtn_tab_extset', '#likebtn_extset_tab_', '#likebtn_extset_tabs');void(0);"><?php _e('Events', LIKEBTN_I18N_DOMAIN); ?></a>

                                            <a class="nav-tab likebtn_tab_texts" href="javascript:likebtnGotoTab('texts', '.likebtn_tab_extset', '#likebtn_extset_tab_', '#likebtn_extset_tabs');void(0);"><?php _e('Texts', LIKEBTN_I18N_DOMAIN); ?></a>
                                        </h3>

                                        <div class="postbox likebtn_tab_extset" id="likebtn_extset_tab_general">
                                            <?php /*<h3 style="cursor:pointer" onclick="toggleCollapsable(this)" class="likebtn_collapse_trigger"><small>►</small> <?php _e('General', LIKEBTN_I18N_DOMAIN); ?></h3>*/ ?>
                                            <div class="inside">
                                                <table class="form-table" >
                                                    <?php /*if (!in_array($entity_name, $likebtn_no_excerpts)): ?>
                                                        <tr valign="top">
                                                            <th scope="row"><label><?php _e('View mode', LIKEBTN_I18N_DOMAIN); ?></label></th>
                                                            <td>
                                                                <input type="radio" name="likebtn_post_view_mode_<?php echo $entity_name; ?>" value="<?php echo LIKEBTN_POST_VIEW_MODE_FULL; ?>" <?php checked(LIKEBTN_POST_VIEW_MODE_FULL, get_option('likebtn_post_view_mode_' . $entity_name)) ?> /> <?php _e('Full', LIKEBTN_I18N_DOMAIN); ?>&nbsp;&nbsp;&nbsp;
                                                                <input type="radio" name="likebtn_post_view_mode_<?php echo $entity_name; ?>" value="<?php echo LIKEBTN_POST_VIEW_MODE_EXCERPT; ?>" <?php checked(LIKEBTN_POST_VIEW_MODE_EXCERPT, get_option('likebtn_post_view_mode_' . $entity_name)) ?> /> <?php _e('Excerpt', LIKEBTN_I18N_DOMAIN); ?>&nbsp;&nbsp;&nbsp;
                                                                <input type="radio" name="likebtn_post_view_mode_<?php echo $entity_name; ?>" value="<?php echo LIKEBTN_POST_VIEW_MODE_BOTH; ?>" <?php checked(LIKEBTN_POST_VIEW_MODE_BOTH, get_option('likebtn_post_view_mode_' . $entity_name)) ?> /> <?php _e('Both', LIKEBTN_I18N_DOMAIN); ?>

                                                                <i class="likebtn_help" title="<?php _e('Choose post display modes for which you want to show the Like Button', LIKEBTN_I18N_DOMAIN); ?>"></i>
                                                            </td>
                                                        </tr>
                                                    <?php endif*/ ?>

                                                    <tr valign="top">
                                                        <th scope="row"><label><?php _e('Format', LIKEBTN_I18N_DOMAIN); ?></label>
                                                            <i class="likebtn_help" title="<?php _e('Select post formats for which you want to show the Like Button', LIKEBTN_I18N_DOMAIN); ?>">&nbsp;</i>
                                                        </th>
                                                        <td>
                                                            <input type="checkbox" name="likebtn_post_format_<?php echo $entity_name; ?>[]" value="all" <?php if (in_array('all', get_option('likebtn_post_format_' . $entity_name))): ?>checked="checked"<?php endif ?> onClick="postFormatAllChange(this, '<?php echo $entity_name; ?>')" /> <?php _e('All', LIKEBTN_I18N_DOMAIN); ?>&nbsp;&nbsp;&nbsp;
                                                            <span id="post_format_container_<?php echo $entity_name; ?>" <?php if (in_array('all', get_option('likebtn_post_format_' . $entity_name))): ?>style="display:none"<?php endif ?>>
                                                                <?php foreach ($post_formats as $post_format): ?>
                                                                    <input type="checkbox" name="likebtn_post_format_<?php echo $entity_name; ?>[]" value="<?php echo $post_format; ?>" <?php if (in_array($post_format, get_option('likebtn_post_format_' . $entity_name))): ?>checked="checked"<?php endif ?> /> <?php _e(__(ucfirst($post_format), LIKEBTN_I18N_DOMAIN)); ?>&nbsp;&nbsp;&nbsp;
                                                                <?php endforeach ?>
                                                            </span>
                                                        </td>
                                                    </tr>

                                                    <tr valign="top">
                                                        <th scope="row"><label><?php _e('Exclude on selected sections', LIKEBTN_I18N_DOMAIN); ?></label>
                                                            <i class="likebtn_help" title="<?php _e('Choose sections where you DO NOT want to show the Like Button', LIKEBTN_I18N_DOMAIN); ?>">&nbsp;</i>
                                                        </th>
                                                        <td>
                                                            <input type="checkbox" name="likebtn_exclude_sections_<?php echo $entity_name; ?>[]" value="home" <?php if (in_array('home', $excluded_sections)): ?>checked="checked"<?php endif ?> /> <?php _e('Home'); ?>&nbsp;&nbsp;&nbsp;
                                                            <input type="checkbox" name="likebtn_exclude_sections_<?php echo $entity_name; ?>[]" value="archive" <?php if (in_array('archive', $excluded_sections)): ?>checked="checked"<?php endif ?> /> <?php _e('Archive', LIKEBTN_I18N_DOMAIN); ?>&nbsp;&nbsp;&nbsp;
                                                            <input type="checkbox" name="likebtn_exclude_sections_<?php echo $entity_name; ?>[]" value="search" <?php if (in_array('search', $excluded_sections)): ?>checked="checked"<?php endif ?> /> <?php _e('Search', LIKEBTN_I18N_DOMAIN); ?>&nbsp;&nbsp;&nbsp;
                                                            <input type="checkbox" name="likebtn_exclude_sections_<?php echo $entity_name; ?>[]" value="category" <?php if (in_array('category', $excluded_sections)): ?>checked="checked"<?php endif ?> /> <?php _e('Category', LIKEBTN_I18N_DOMAIN); ?>
                                                        </td>
                                                    </tr>
                                                    <tr valign="top">
                                                        <th scope="row"><label><?php _e('Exclude in selected categories', LIKEBTN_I18N_DOMAIN); ?></label>
                                                            <i class="likebtn_help" title="<?php _e('Select categories where you DO NOT want to show the Like Button (use CTRL key to pick categories)', LIKEBTN_I18N_DOMAIN); ?>">&nbsp;</i>
                                                        </th>
                                                        <td>
                                                            <select name='likebtn_exclude_categories_<?php echo $entity_name; ?>[]' multiple="multiple" size="4" style="height:auto !important;">
                                                                <?php
                                                                $categories = get_categories();

                                                                foreach ($categories as $category) {
                                                                    $selected = (in_array($category->cat_ID, $excluded_categories)) ? 'selected="selected"' : '';
                                                                    $option = '<option value="' . $category->cat_ID . '" ' . $selected . '>';
                                                                    $option .= $category->cat_name;
                                                                    $option .= ' (' . $category->category_count . ')';
                                                                    $option .= '</option>';
                                                                    echo $option;
                                                                }
                                                                ?>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr valign="top">
                                                        <th scope="row"><label><?php _e('Allow post/page IDs', LIKEBTN_I18N_DOMAIN); ?></label>
                                                            <i class="likebtn_help" title="<?php _e('Suppose you have a post which belongs to more than one category and you have excluded one of those categories. So the Like Button will not be available for that post. Enter comma separated post ids where you want to show the Like Button irrespective of that post category being excluded.', LIKEBTN_I18N_DOMAIN); ?>">&nbsp;</i>
                                                        </th>
                                                        <td>
                                                            <input type="text"name="likebtn_allow_ids_<?php echo $entity_name; ?>" value="<?php _e(get_option('likebtn_allow_ids_' . $entity_name)); ?>" class="likebtn_input" />
                                                        </td>
                                                    </tr>
                                                    <tr valign="top">
                                                        <th scope="row"><label><?php _e('Exclude post/page IDs', LIKEBTN_I18N_DOMAIN); ?></label>
                                                            <i class="likebtn_help" title="<?php _e('Comma separated post/page IDs where you DO NOT want to show the Like Button', LIKEBTN_I18N_DOMAIN); ?>">&nbsp;</i>
                                                        </th>
                                                        <td>
                                                            <input type="text" name="likebtn_exclude_ids_<?php echo $entity_name; ?>" value="<?php _e(get_option('likebtn_exclude_ids_' . $entity_name)); ?>" class="likebtn_input" />
                                                        </td>
                                                    </tr>
                                                    <tr valign="top">
                                                        <th scope="row"><label><?php _e('User authorization', LIKEBTN_I18N_DOMAIN); ?></label>
                                                            <i class="likebtn_help" title="<?php _e('Show the Like Button when user is logged in, not logged in or show always', LIKEBTN_I18N_DOMAIN); ?>">&nbsp;</i>
                                                        </th>
                                                        <td>
                                                            <input type="radio" name="likebtn_user_logged_in_<?php echo $entity_name; ?>" value="1" <?php checked('1', get_option('likebtn_user_logged_in_' . $entity_name)) ?> /> <?php _e('Logged in', LIKEBTN_I18N_DOMAIN); ?>&nbsp;&nbsp;&nbsp;
                                                            <input type="radio" name="likebtn_user_logged_in_<?php echo $entity_name; ?>" value="0" <?php checked('0', get_option('likebtn_user_logged_in_' . $entity_name)) ?> /> <?php _e('Not logged in', LIKEBTN_I18N_DOMAIN); ?>&nbsp;&nbsp;&nbsp;
                                                            <input type="radio" name="likebtn_user_logged_in_<?php echo $entity_name; ?>" value="" <?php checked('', get_option('likebtn_user_logged_in_' . $entity_name)) ?> /> <?php _e('For all', LIKEBTN_I18N_DOMAIN); ?>

                                                        </td>
                                                    </tr>
                                                    <tr valign="top">
                                                        <th scope="row"><label><?php _e('HTML to put before', LIKEBTN_I18N_DOMAIN); ?></label>
                                                            <?php /*<i class="likebtn_help" title="<?php _e('HTML code to insert before the Like Button', LIKEBTN_I18N_DOMAIN); ?>">&nbsp;</i>*/ ?>
                                                        </th>
                                                        <td>
                                                            <textarea name="likebtn_html_before_<?php echo $entity_name; ?>" class="likebtn_input" rows="2"><?php echo get_option('likebtn_html_before_' . $entity_name); ?></textarea>
                                                        </td>
                                                    </tr>
                                                    <tr valign="top">
                                                        <th scope="row"><label><?php _e('HTML to put after', LIKEBTN_I18N_DOMAIN); ?></label>
                                                            <?php /*<i class="likebtn_help" title="<?php _e('HTML code to insert after the Like Button', LIKEBTN_I18N_DOMAIN); ?>">&nbsp;</i>*/ ?>
                                                        </th>
                                                        <td>
                                                            <textarea name="likebtn_html_after_<?php echo $entity_name; ?>" class="likebtn_input" rows="2"><?php echo get_option('likebtn_html_after_' . $entity_name); ?></textarea>
                                                        </td>
                                                    </tr>
                                                    <tr valign="top">
                                                        <th scope="row"><label><?php _e('Display on a new line', LIKEBTN_I18N_DOMAIN); ?></label>
                                                            <i class="likebtn_help" title="<?php _e("Add a 'clear:both' style to the like button container", LIKEBTN_I18N_DOMAIN); ?>">&nbsp;</i>
                                                        </th>
                                                        <td>
                                                            <input type="checkbox" name="likebtn_newline_<?php echo $entity_name; ?>" value="1" <?php checked('1', get_option('likebtn_newline_' . $entity_name)) ?> />
                                                        </td>
                                                    </tr>
                                                    <tr valign="top">
                                                        <th scope="row"><label><?php _e('Wrap button in a div', LIKEBTN_I18N_DOMAIN); ?></label>
                                                            <i class="likebtn_help" title="<?php _e("If disabled alignment and new line options have no affect", LIKEBTN_I18N_DOMAIN); ?>">&nbsp;</i>
                                                        </th>
                                                        <td>
                                                            <input type="checkbox" name="likebtn_wrap_<?php echo $entity_name; ?>" value="1" <?php checked('1', get_option('likebtn_wrap_' . $entity_name)) ?> />
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="postbox likebtn_tab_extset hidden" id="likebtn_extset_tab_popup">
                                            <?php /*<h3 style="cursor:pointer" onclick="toggleCollapsable(this)" class="likebtn_collapse_trigger"><small>►</small> <?php _e('Popup', LIKEBTN_I18N_DOMAIN); ?></h3>*/ ?>
                                            <div class="inside">
                                                <table class="form-table">
                                                    <tr valign="top" class="plan_dependent plan_vip">
                                                        <th scope="row"><label><?php _e('Disable popup', LIKEBTN_I18N_DOMAIN); ?> <i class="premium_feature" title="VIP / ULTRA"></i></label></th>
                                                        <td>
                                                            <input type="checkbox" name="likebtn_settings_popup_disabled_<?php echo $entity_name; ?>" value="1" <?php checked('1', get_option('likebtn_settings_popup_disabled_' . $entity_name)); ?> />
                                                        </td>
                                                    </tr>
                                                    <tr valign="top">
                                                        <th scope="row"><label><?php _e('Show popup on disliking', LIKEBTN_I18N_DOMAIN); ?></label></th>
                                                        <td>
                                                            <input type="checkbox" name="likebtn_settings_popup_dislike_<?php echo $entity_name; ?>" value="1" <?php checked('1', get_option('likebtn_settings_popup_dislike_' . $entity_name)); ?> />
                                                        </td>
                                                    </tr>
                                                    <tr valign="top">
                                                        <th scope="row"><label><?php _e('Show popup on button load', LIKEBTN_I18N_DOMAIN); ?></label></th>
                                                        <td>
                                                            <input type="checkbox" name="likebtn_settings_popup_on_load_<?php echo $entity_name; ?>" value="1" <?php checked('1', get_option('likebtn_settings_popup_on_load_' . $entity_name)); ?> />
                                                        </td>
                                                    </tr>
                                                    <tr valign="top">
                                                        <th scope="row"><label><?php _e('Popup position', LIKEBTN_I18N_DOMAIN); ?></label></th>
                                                        <td>
                                                            <div class="image_toggle">
                                                                <input type="radio" name="likebtn_settings_popup_position_<?php echo $entity_name; ?>" id="likebtn_settings_popup_position_<?php echo $entity_name; ?>_top" value="top" <?php checked('top', get_option('likebtn_settings_popup_position_' . $entity_name)); ?>>
                                                                <label for="likebtn_settings_popup_position_<?php echo $entity_name; ?>_top"><img src="<?php echo _likebtn_get_public_url() ?>img/popup_position/top.png" alt="<?php _e('top', LIKEBTN_I18N_DOMAIN); ?>" title="<?php _e('top', LIKEBTN_I18N_DOMAIN); ?>" /></label>

                                                                <input type="radio" name="likebtn_settings_popup_position_<?php echo $entity_name; ?>" id="likebtn_settings_popup_position_<?php echo $entity_name; ?>_right" value="right" <?php checked('right', get_option('likebtn_settings_popup_position_' . $entity_name)); ?>>
                                                                <label for="likebtn_settings_popup_position_<?php echo $entity_name; ?>_right"><img src="<?php echo _likebtn_get_public_url() ?>img/popup_position/right.png" alt="<?php _e('right', LIKEBTN_I18N_DOMAIN); ?>" title="<?php _e('right', LIKEBTN_I18N_DOMAIN); ?>" /></label>

                                                                <input type="radio" name="likebtn_settings_popup_position_<?php echo $entity_name; ?>" id="likebtn_settings_popup_position_<?php echo $entity_name; ?>_bottom" value="bottom" <?php checked('bottom', get_option('likebtn_settings_popup_position_' . $entity_name)); ?>>
                                                                <label for="likebtn_settings_popup_position_<?php echo $entity_name; ?>_bottom"><img src="<?php echo _likebtn_get_public_url() ?>img/popup_position/bottom.png" alt="<?php _e('bottom', LIKEBTN_I18N_DOMAIN); ?>" title="<?php _e('bottom', LIKEBTN_I18N_DOMAIN); ?>" /></label>

                                                                <input type="radio" name="likebtn_settings_popup_position_<?php echo $entity_name; ?>" id="likebtn_settings_popup_position_<?php echo $entity_name; ?>_left" value="left" <?php checked('left', get_option('likebtn_settings_popup_position_' . $entity_name)); ?>>
                                                                <label for="likebtn_settings_popup_position_<?php echo $entity_name; ?>_left"><img src="<?php echo _likebtn_get_public_url() ?>img/popup_position/left.png" alt="<?php _e('left', LIKEBTN_I18N_DOMAIN); ?>" title="<?php _e('left', LIKEBTN_I18N_DOMAIN); ?>" /></label>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr valign="top">
                                                        <th scope="row"><label><?php _e('Popup style', LIKEBTN_I18N_DOMAIN); ?></label></th>
                                                        <td>
                                                            <div class="image_toggle">
                                                                <input type="radio" name="likebtn_settings_popup_style_<?php echo $entity_name; ?>" id="likebtn_settings_popup_style_<?php echo $entity_name; ?>_light" value="light" <?php checked('light', get_option('likebtn_settings_popup_style_' . $entity_name)); ?>>
                                                                <label for="likebtn_settings_popup_style_<?php echo $entity_name; ?>_light"><img src="<?php echo _likebtn_get_public_url() ?>img/popup_style/light.png" alt="<?php _e('light', LIKEBTN_I18N_DOMAIN); ?>" title="<?php _e('light', LIKEBTN_I18N_DOMAIN); ?>" /></label>                                                                        
                                                                <input type="radio" name="likebtn_settings_popup_style_<?php echo $entity_name; ?>" id="likebtn_settings_popup_style_<?php echo $entity_name; ?>_dark" value="dark" <?php checked('dark', get_option('likebtn_settings_popup_style_' . $entity_name)); ?>>
                                                                <label for="likebtn_settings_popup_style_<?php echo $entity_name; ?>_dark"><img src="<?php echo _likebtn_get_public_url() ?>img/popup_style/dark.png" alt="<?php _e('dark', LIKEBTN_I18N_DOMAIN); ?>" title="<?php _e('dark', LIKEBTN_I18N_DOMAIN); ?>" /></label>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr valign="top">
                                                        <th scope="row"><label><?php _e('Hide popup when clicking outside', LIKEBTN_I18N_DOMAIN); ?></label></th>
                                                        <td>
                                                            <input type="checkbox" name="likebtn_settings_popup_hide_on_outside_click_<?php echo $entity_name; ?>" value="1" <?php checked('1', get_option('likebtn_settings_popup_hide_on_outside_click_' . $entity_name)); ?> />
                                                        </td>
                                                    </tr>
                                                    <tr valign="top" class="plan_dependent plan_plus">
                                                        <th scope="row"><label><?php _e('Show share buttons', LIKEBTN_I18N_DOMAIN); ?> <i class="premium_feature" title="PLUS / PRO / VIP / ULTRA"></i></label></th>
                                                        <td>
                                                            <input type="checkbox" name="likebtn_settings_share_enabled_<?php echo $entity_name; ?>" value="1" <?php checked('1', get_option('likebtn_settings_share_enabled_' . $entity_name)); ?> />
                                                            <?php /*<span class="description"><?php _e('Use popup_disabled option to enable/disable popup.', LIKEBTN_I18N_DOMAIN); ?></span>*/ ?>
                                                        </td>
                                                    </tr>
                                                    <tr valign="top" class="plan_dependent plan_pro">
                                                        <th scope="row"><label><?php _e('AddThis <a href="https://www.addthis.com/settings/publisher" target="_blank">Profile ID</a>', LIKEBTN_I18N_DOMAIN); ?> <i class="premium_feature" title="PLUS / PRO / VIP / ULTRA"></i></label>
                                                            <i class="likebtn_help" title="<?php _e("Enter your AddThis Profile ID to collect sharing statistics and view it on AddThis analytics page", LIKEBTN_I18N_DOMAIN); ?>">&nbsp;</i>
                                                        </th>
                                                        <td>
                                                            <input type="text" name="likebtn_settings_addthis_pubid_<?php echo $entity_name; ?>" value="<?php echo get_option('likebtn_settings_addthis_pubid_' . $entity_name); ?>" class=" likebtn_input likebtn_placeholder" placeholder="ra-511b51aa3d843ec4" />
                                                        </td>
                                                    </tr>
                                                    <tr valign="top" class="plan_dependent plan_pro">
                                                        <th scope="row">
                                                            <label><?php _e('AddThis share buttons', LIKEBTN_I18N_DOMAIN); ?> <i class="premium_feature" title="PLUS / PRO / VIP / ULTRA"></i></label>
                                                        </th>
                                                        <td>
                                                            <select id="settings_addthis_service_codes" class="likebtn_at16 likebtn_input" multiple="multiple" >
                                                                <?php foreach($likebtn_addthis_service_codes as $addthis_service_code): ?>
                                                                    <option value="<?php echo $addthis_service_code ?>"><?php echo $addthis_service_code ?></option>
                                                                <?php endforeach ?>
                                                            </select>

                                                            <input type="hidden" name="likebtn_settings_addthis_service_codes_<?php echo $entity_name; ?>" value="<?php echo $value_addthis_service_codes; ?>" class="likebtn_input" id="settings_addthis_service_codes_input"/>

                                                            <p class="description"><?php _e('<a href="http://www.addthis.com" target="_blank">AddThis</a> is the content sharing and social insights platform helping users to share your content and drive viral traffic.', LIKEBTN_I18N_DOMAIN); ?>
                                                            </p>
                                                        </td>
                                                    </tr>
                                                    <tr valign="top" class="plan_dependent plan_vip">
                                                        <th scope="row"><label><?php _e('Donate buttons', LIKEBTN_I18N_DOMAIN); ?> <i class="premium_feature" title="VIP / ULTRA"></i></label></th>
                                                        <td>
                                                            <div id="donate_wrapper">
                                                                <div id="donate_pveview" class="likebtn_input"></div>

                                                                <a href="javascript:likebtnDG('popup_donate_input', false, {width: '80%'}, {preview_container: '#donate_pveview'});void(0);" id="popup_donate_trigger"><img src="<?php echo _likebtn_get_public_url() ?>img/popup_donate.png" alt="<?php _e('Configure donate buttons', LIKEBTN_I18N_DOMAIN); ?>"></a>
                                                            </div>

                                                            <input type="hidden" name="likebtn_settings_popup_donate_<?php echo $entity_name; ?>" value="<?php echo htmlspecialchars(get_option('likebtn_settings_popup_donate_' . $entity_name)); ?>" id="popup_donate_input" class="likebtn_input"/>

                                                            <p class="description">
                                                                <?php _e('Collect donations using', LIKEBTN_I18N_DOMAIN); ?> <a href="https://www.paypal.com" target="_blank">PayPal</a>, <a href="https://bitcoin.org" target="_blank">Bitcoin</a>, <a href="https://wallet.google.com" target="_blank">Google Wallet</a>, <a href="https://money.yandex.ru" target="_blank">Yandex.Money</a>, <a href="http://www.webmoney.ru" target="_blank">Webmoney</a>, <a href="https://qiwi.ru" target="_blank">Qiwi</a>, <a href="http://smscoin.com" target="_blank">SmsCoin</a>, <a href="https://zaypay.com" target="_blank"><?php _e('Zaypay Mobile Payments', LIKEBTN_I18N_DOMAIN); ?></a>.
                                                            </p>
                                                        </td>
                                                    </tr>
                                                    <tr valign="top" class="plan_dependent plan_pro">
                                                        <th scope="row"><label><?php _e('Custom HTML', LIKEBTN_I18N_DOMAIN); ?> <i class="premium_feature" title="PRO / VIP / ULTRA"></i></label>
                                                            <i class="likebtn_help" title="<?php _e("Custom HTML to insert into the popup", LIKEBTN_I18N_DOMAIN); ?>">&nbsp;</i>
                                                        </th>
                                                        <td>
                                                            <textarea name="likebtn_settings_popup_html_<?php echo $entity_name; ?>" class="likebtn_input" rows="2"><?php echo get_option('likebtn_settings_popup_html_' . $entity_name); ?></textarea>
                                                        </td>
                                                    </tr>
                                                    <tr valign="top">
                                                        <th scope="row"><label><?php _e('Popup content order', LIKEBTN_I18N_DOMAIN); ?></label></th>
                                                        <td>
                                                            <select id="settings_popup_content_order" multiple="multiple" class="likebtn_input">
                                                                <option value="popup_donate" ><?php _e('Donate buttons', LIKEBTN_I18N_DOMAIN); ?></option>
                                                                <option value="popup_share" ><?php _e('Share buttons', LIKEBTN_I18N_DOMAIN); ?></option>
                                                                <option value="popup_html" ><?php _e('Custom HTML', LIKEBTN_I18N_DOMAIN); ?></option>
                                                            </select>

                                                            <input type="hidden" id="settings_popup_content_order_input" name="likebtn_settings_popup_content_order_<?php echo $entity_name; ?>" value="<?php echo get_option('likebtn_settings_popup_content_order_' . $entity_name); ?>" />
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="postbox likebtn_tab_extset hidden" id="likebtn_extset_tab_voting">
                                            <?php /*<h3 style="cursor:pointer" onclick="toggleCollapsable(this)" class="likebtn_collapse_trigger"><small>►</small> <?php _e('Voting', LIKEBTN_I18N_DOMAIN); ?></h3>*/ ?>
                                            <div class="inside">
                                                <table class="form-table">
                                                    <tr valign="top">
                                                        <th scope="row"><label><?php _e('Allow voting', LIKEBTN_I18N_DOMAIN); ?></label></th>
                                                        <td>
                                                            <input type="checkbox" name="likebtn_settings_voting_enabled_<?php echo $entity_name; ?>" value="1" <?php checked('1', get_option('likebtn_settings_voting_enabled_' . $entity_name)); ?> />
                                                        </td>
                                                    </tr>
                                                    <tr valign="top">
                                                        <th scope="row"><label><?php _e('Allow to cancel a vote', LIKEBTN_I18N_DOMAIN); ?></label></th>
                                                        <td>
                                                            <input type="checkbox" name="likebtn_settings_voting_cancelable_<?php echo $entity_name; ?>" value="1" <?php checked('1', get_option('likebtn_settings_voting_cancelable_' . $entity_name)); ?> />
                                                        </td>
                                                    </tr>
                                                    <tr valign="top">
                                                        <th scope="row"><label><?php _e('Allow to like and dislike at the same time', LIKEBTN_I18N_DOMAIN); ?></label></th>
                                                        <td>
                                                            <input type="checkbox" name="likebtn_settings_voting_both_<?php echo $entity_name; ?>" value="1" <?php checked('1', get_option('likebtn_settings_voting_both_' . $entity_name)); ?> />
                                                        </td>
                                                    </tr>
                                                    <tr valign="top">
                                                        <th scope="row"><label><?php _e('Voting frequency', LIKEBTN_I18N_DOMAIN); ?></label>
                                                        </th>
                                                        <td>
                                                            <select name="likebtn_settings_voting_frequency_<?php echo $entity_name; ?>">
                                                                <option value=""><?php _e('Once', LIKEBTN_I18N_DOMAIN); ?></option>
                                                                <option value="1" <?php selected('1', get_option('likebtn_settings_voting_frequency_' . $entity_name)); ?> ><?php _e('Unlimited', LIKEBTN_I18N_DOMAIN); ?> *</option>
                                                                <option value="60" <?php selected('60', get_option('likebtn_settings_voting_frequency_' . $entity_name)); ?> ><?php _e('Every minute', LIKEBTN_I18N_DOMAIN); ?> *</option>
                                                                <option value="3600" <?php selected('3600', get_option('likebtn_settings_voting_frequency_' . $entity_name)); ?> ><?php _e('Hourly', LIKEBTN_I18N_DOMAIN); ?></option>
                                                                <option value="86400" <?php selected('86400', get_option('likebtn_settings_voting_frequency_' . $entity_name)); ?> ><?php _e('Daily', LIKEBTN_I18N_DOMAIN); ?></option>
                                                                <option value="604800" <?php selected('604800', get_option('likebtn_settings_voting_frequency_' . $entity_name)); ?> ><?php _e('Weekly', LIKEBTN_I18N_DOMAIN); ?></option>
                                                                <option value="2592000" <?php selected('2592000', get_option('likebtn_settings_voting_frequency_' . $entity_name)); ?> ><?php _e('Monthly', LIKEBTN_I18N_DOMAIN); ?></option>
                                                                <option value="31536000" <?php selected('31536000', get_option('likebtn_settings_voting_frequency_' . $entity_name)); ?> ><?php _e('Annually', LIKEBTN_I18N_DOMAIN); ?></option>
                                                            </select>
                                                            <p class="description">
                                                                * <?php echo strtr(
                                                                    __('Make sure that its value is larger than <a href="%url_interval%">IP address vote interval</a> of your website.', LIKEBTN_I18N_DOMAIN), 
                                                                    array('%url_interval%'=>"javascript:likebtnPopup('".__('http://likebtn.com/en/', LIKEBTN_I18N_DOMAIN)."customer.php/websites');void(0);")
                                                                ); ?>
                                                            </p>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="postbox likebtn_tab_extset hidden" id="likebtn_extset_tab_counter">
                                            <?php /*<h3 style="cursor:pointer" onclick="toggleCollapsable(this)" class="likebtn_collapse_trigger"><small>►</small> <?php _e('Counter', LIKEBTN_I18N_DOMAIN); ?></h3>*/ ?>
                                            <div class="inside">
                                                <table class="form-table">
                                                 <tr valign="top">
                                                        <th scope="row"><label><?php _e('Show votes counter', LIKEBTN_I18N_DOMAIN); ?></label></th>
                                                        <td>
                                                            <input type="checkbox" name="likebtn_settings_counter_show_<?php echo $entity_name; ?>" value="1" <?php checked('1', get_option('likebtn_settings_counter_show_' . $entity_name)); ?> />
                                                        </td>
                                                    </tr>
                                                    <tr valign="top">
                                                        <th scope="row"><label><?php _e('Votes counter is clickable', LIKEBTN_I18N_DOMAIN); ?></label></th>
                                                        <td>
                                                            <input type="checkbox" name="likebtn_settings_counter_clickable_<?php echo $entity_name; ?>" value="1" <?php checked('1', get_option('likebtn_settings_counter_clickable_' . $entity_name)); ?> />
                                                        </td>
                                                    </tr>
                                                    <tr valign="top">
                                                        <th scope="row"><label><?php _e('Counter padding', LIKEBTN_I18N_DOMAIN); ?></label></th>
                                                        <td>
                                                            <input type="text" name="likebtn_settings_counter_padding_<?php echo $entity_name; ?>" value="<?php echo get_option('likebtn_settings_counter_padding_' . $entity_name); ?>" class="likebtn_input" />
                                                        </td>
                                                    </tr>
                                                    <tr valign="top">
                                                        <th scope="row"><label><?php _e('Show zero value in counter', LIKEBTN_I18N_DOMAIN); ?></label></th>
                                                        <td>
                                                            <input type="checkbox" name="likebtn_settings_counter_zero_show_<?php echo $entity_name; ?>" value="1" <?php checked('1', get_option('likebtn_settings_counter_zero_show_' . $entity_name)); ?> />
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>

                                        <?php /*<div class="postbox">
                                            <h3 style="cursor:pointer" onclick="toggleCollapsable(this)" class="likebtn_collapse_trigger"><small>►</small> <?php _e('Sharing', LIKEBTN_I18N_DOMAIN); ?></h3>
                                            <div class="inside hidden">
                                                <table class="form-table">

                                                </table>
                                            </div>
                                        </div>*/ ?>

                                        <div class="postbox likebtn_tab_extset hidden" id="likebtn_extset_tab_loading">
                                            <?php /*<h3 style="cursor:pointer" onclick="toggleCollapsable(this)" class="likebtn_collapse_trigger"><small>►</small> <?php _e('Loading', LIKEBTN_I18N_DOMAIN); ?></h3>*/ ?>
                                            <div class="inside">
                                                <table class="form-table">
                                                    <tr valign="top">
                                                        <th scope="row"><label><?php _e('Lazy load', LIKEBTN_I18N_DOMAIN); ?></label>
                                                            <i class="likebtn_help" title="<?php _e("If button is outside a viewport it is loaded when user scrolls to it", LIKEBTN_I18N_DOMAIN); ?>">&nbsp;</i>
                                                        </th>
                                                        <td>
                                                            <input type="checkbox" name="likebtn_settings_lazy_load_<?php echo $entity_name; ?>" value="1" <?php checked('1', get_option('likebtn_settings_lazy_load_' . $entity_name)); ?> />
                                                        </td>
                                                    </tr>
                                                    <tr valign="top">
                                                        <th scope="row"><label><?php _e('Show loader', LIKEBTN_I18N_DOMAIN); ?></label>
                                                            <i class="likebtn_help" title="<?php _e("Show loader while loading a button", LIKEBTN_I18N_DOMAIN); ?>">&nbsp;</i>
                                                        </th>
                                                        <td>
                                                            <input type="checkbox" name="likebtn_settings_loader_show_<?php echo $entity_name; ?>" value="1" <?php checked('1', get_option('likebtn_settings_loader_show_' . $entity_name)); ?> />
                                                        </td>
                                                    </tr>
                                                    <tr valign="top">
                                                        <th scope="row"><label><?php _e('Loader image', LIKEBTN_I18N_DOMAIN); ?></label>
                                                            <i class="likebtn_help" title="<?php _e("URL of the image to use as loader image (leave empty to display default image)", LIKEBTN_I18N_DOMAIN); ?>">&nbsp;</i>
                                                        </th>
                                                        <td>
                                                            <input type="text" name="likebtn_settings_loader_image_<?php echo $entity_name; ?>" value="<?php echo get_option('likebtn_settings_loader_image_' . $entity_name); ?>" class="likebtn_input" />
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="postbox likebtn_tab_extset hidden" id="likebtn_extset_tab_tooltips">
                                            <div class="inside">
                                                <table class="form-table">
                                                    <tr valign="top">
                                                        <th scope="row"><label><?php _e('Always show Like button tooltip', LIKEBTN_I18N_DOMAIN); ?></label></th>
                                                        <td>
                                                            <input type="checkbox" name="likebtn_settings_tooltip_like_show_always_<?php echo $entity_name; ?>" value="1" <?php checked('1', get_option('likebtn_settings_tooltip_like_show_always_' . $entity_name)); ?> />
                                                        </td>
                                                    </tr>
                                                    <tr valign="top">
                                                        <th scope="row"><label><?php _e('Always show Dislike button tooltip', LIKEBTN_I18N_DOMAIN); ?></label></th>
                                                        <td>
                                                            <input type="checkbox" name="likebtn_settings_tooltip_dislike_show_always_<?php echo $entity_name; ?>" value="1" <?php checked('1', get_option('likebtn_settings_tooltip_dislike_show_always_' . $entity_name)); ?> />
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>

                                        <?php /*<div class="postbox">
                                            <h3 style="cursor:pointer" onclick="toggleCollapsable(this)" class="likebtn_collapse_trigger"><small>►</small> <?php _e('Domains', LIKEBTN_I18N_DOMAIN); ?></h3>
                                            <div class="inside hidden">
                                                <table class="form-table">
                                                    <tr valign="top">
                                                        <th scope="row"><label><?php _e('Use domain of the parent window', LIKEBTN_I18N_DOMAIN); ?></label></th>
                                                        <td>
                                                            <input type="checkbox" name="likebtn_settings_domain_from_parent_<?php echo $entity_name; ?>" value="1" <?php checked('1', get_option('likebtn_settings_domain_from_parent_' . $entity_name)); ?> />
                                                            <span class="description">domain_from_parent</span>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>*/ ?>

                                        <div class="postbox likebtn_tab_extset hidden"  id="likebtn_extset_tab_events">
                                            <?php /*<h3 style="cursor:pointer" onclick="toggleCollapsable(this)" class="likebtn_collapse_trigger"><small>►</small> <?php _e('Events', LIKEBTN_I18N_DOMAIN); ?></h3>*/ ?>
                                            <div class="inside">
                                                <table class="form-table">
                                                    <tr valign="top">
                                                        <th scope="row">
                                                            <label>
                                                                <?php _e('JavaScript callback function', LIKEBTN_I18N_DOMAIN); ?></label>
                                                        </th>
                                                        <td class="description">
                                                            <input type="text" name="likebtn_settings_event_handler_<?php echo $entity_name; ?>" value="<?php _e(get_option('likebtn_settings_event_handler_' . $entity_name)); ?>" class="likebtn_input" />
                                                            <p class="description">
                                                                <?php _e('The provided function receives the event object as its single argument. The event object has the following properties:', LIKEBTN_I18N_DOMAIN); ?><br/>
                                                                <code>type</code> – <?php _e('indicates which event was dispatched:', LIKEBTN_I18N_DOMAIN); ?><br/>
                                                                ● "likebtn.loaded"<br/>
                                                                ● "likebtn.like"<br/>
                                                                ● "likebtn.unlike"<br/>
                                                                ● "likebtn.dislike"<br/>
                                                                ● "likebtn.undislike"<br/>
                                                                <code>settings</code> – <?php _e('button settings', LIKEBTN_I18N_DOMAIN); ?><br/>
                                                                <code>wrapper</code> – <?php _e('button DOM-element', LIKEBTN_I18N_DOMAIN); ?>
                                                            </p>
                                                        </td>
                                                    </tr>
                                                    <?php /*<tr valign="top">
                                                        <th scope="row"><label><?php _e('Show info messages', LIKEBTN_I18N_DOMAIN); ?></label>
                                                            <i class="likebtn_help" title="<?php _e("Show information message instead of the button when it is restricted by tariff plan", LIKEBTN_I18N_DOMAIN); ?>">&nbsp;</i>
                                                        </th>
                                                        <td>
                                                            <input type="checkbox" name="likebtn_settings_info_message_<?php echo $entity_name; ?>" value="1" <?php checked('1', get_option('likebtn_settings_info_message_' . $entity_name)); ?> />
                                                        </td>
                                                    </tr>*/ ?>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="postbox likebtn_tab_extset hidden" id="likebtn_extset_tab_texts">
                                            <?php /*<h3 style="cursor:pointer" onclick="toggleCollapsable(this)" class="likebtn_collapse_trigger"><small>►</small> <?php _e('Texts', LIKEBTN_I18N_DOMAIN); ?></h3>*/ ?>
                                            <div class="inside">
                                                <table class="form-table">
                                                    <tr valign="top">
                                                        <th scope="row"><label><?php _e('Like button text after liking', LIKEBTN_I18N_DOMAIN); ?></label></th>
                                                        <td>
                                                            <input type="text" name="likebtn_settings_i18n_after_like_<?php echo $entity_name; ?>" value="<?php echo get_option('likebtn_settings_i18n_after_like_' . $entity_name); ?>" class="likebtn_input likebtn_placeholder" placeholder="<?php _e('Like', LIKEBTN_I18N_DOMAIN); ?>"/>
                                                        </td>
                                                    </tr>
                                                    <tr valign="top">
                                                        <th scope="row"><label><?php _e('Dislike button text after disliking', LIKEBTN_I18N_DOMAIN); ?></label></th>
                                                        <td>
                                                            <input type="text" name="likebtn_settings_i18n_after_dislike_<?php echo $entity_name; ?>" value="<?php echo get_option('likebtn_settings_i18n_after_dislike_' . $entity_name); ?>" class="likebtn_input likebtn_placeholder" placeholder="<?php _e('Dislike', LIKEBTN_I18N_DOMAIN); ?>"/>
                                                        </td>
                                                    </tr>
                                                    <tr valign="top">
                                                        <th scope="row"><label><?php _e('Like button tooltip', LIKEBTN_I18N_DOMAIN); ?></label></th>
                                                        <td>
                                                            <input type="text" name="likebtn_settings_i18n_like_tooltip_<?php echo $entity_name; ?>" value="<?php echo get_option('likebtn_settings_i18n_like_tooltip_' . $entity_name); ?>" class="likebtn_input likebtn_placeholder" placeholder="<?php _e('I like this', LIKEBTN_I18N_DOMAIN); ?>"/>
                                                        </td>
                                                    </tr>
                                                    <tr valign="top">
                                                        <th scope="row"><label><?php _e('Dislike button tooltip', LIKEBTN_I18N_DOMAIN); ?></label></th>
                                                        <td>
                                                            <input type="text" name="likebtn_settings_i18n_dislike_tooltip_<?php echo $entity_name; ?>" value="<?php echo get_option('likebtn_settings_i18n_dislike_tooltip_' . $entity_name); ?>" class="likebtn_input likebtn_placeholder" placeholder="<?php _e('I dislike this', LIKEBTN_I18N_DOMAIN); ?>"/>
                                                        </td>
                                                    </tr>
                                                    <tr valign="top">
                                                        <th scope="row"><label><?php _e('Like button tooltip after liking', LIKEBTN_I18N_DOMAIN); ?></label></th>
                                                        <td>
                                                            <input type="text" name="likebtn_settings_i18n_unlike_tooltip_<?php echo $entity_name; ?>" value="<?php echo get_option('likebtn_settings_i18n_unlike_tooltip_' . $entity_name); ?>" class="likebtn_input likebtn_placeholder" placeholder="<?php _e('Unlike', LIKEBTN_I18N_DOMAIN); ?>"/>
                                                        </td>
                                                    </tr>
                                                    <tr valign="top">
                                                        <th scope="row"><label><?php _e('Dislike button tooltip after disliking', LIKEBTN_I18N_DOMAIN); ?></label></th>
                                                        <td>
                                                            <input type="text" name="likebtn_settings_i18n_undislike_tooltip_<?php echo $entity_name; ?>" value="<?php echo get_option('likebtn_settings_i18n_undislike_tooltip_' . $entity_name); ?>" class="likebtn_input likebtn_placeholder" placeholder="<?php _e('Undislike', LIKEBTN_I18N_DOMAIN); ?>"/>
                                                        </td>
                                                    </tr>
                                                    <tr valign="top">
                                                        <th scope="row"><label><?php _e('Text before share buttons', LIKEBTN_I18N_DOMAIN); ?></label></th>
                                                        <td>
                                                            <input type="text" name="likebtn_settings_i18n_share_text_<?php echo $entity_name; ?>" value="<?php echo get_option('likebtn_settings_i18n_share_text_' . $entity_name); ?>" class="likebtn_input likebtn_placeholder" placeholder="<?php _e('Would you like to share?', LIKEBTN_I18N_DOMAIN); ?>"/>
                                                        </td>
                                                    </tr>
                                                    <tr valign="top">
                                                        <th scope="row"><label><?php _e('Popup close button text', LIKEBTN_I18N_DOMAIN); ?></label></th>
                                                        <td>
                                                            <input type="text" name="likebtn_settings_i18n_popup_close_<?php echo $entity_name; ?>" value="<?php echo get_option('likebtn_settings_i18n_popup_close_' . $entity_name); ?>" class="likebtn_input likebtn_placeholder" placeholder="<?php _e('Close', LIKEBTN_I18N_DOMAIN); ?>"/>
                                                        </td>
                                                    </tr>
                                                    <tr valign="top">
                                                        <th scope="row"><label><?php _e('Popup text when sharing disabled', LIKEBTN_I18N_DOMAIN); ?></label></th>
                                                        <td>
                                                            <input type="text" name="likebtn_settings_i18n_popup_text_<?php echo $entity_name; ?>" value="<?php echo get_option('likebtn_settings_i18n_popup_text_' . $entity_name); ?>" class="likebtn_input likebtn_placeholder" placeholder="<?php _e('Glad you liked it!', LIKEBTN_I18N_DOMAIN); ?>"/>
                                                        </td>
                                                    </tr>
                                                    <tr valign="top">
                                                        <th scope="row"><label><?php _e('Text before donate buttons', LIKEBTN_I18N_DOMAIN); ?></label></th>
                                                        <td>
                                                            <input type="text" name="likebtn_settings_i18n_popup_donate_<?php echo $entity_name; ?>" value="<?php echo get_option('likebtn_settings_i18n_popup_donate_' . $entity_name); ?>" class="likebtn_input likebtn_placeholder" placeholder="<?php _e('Show gratitude in the form of a donation', LIKEBTN_I18N_DOMAIN); ?>"/>
                                                        </td>
                                                    </tr>
                                                    </tr>
                                                    <tr valign="top">
                                                        <th scope="row" colspan="2">
                                                            <a href="javascript:likebtnPopup('<?php _e('http://likebtn.com/en/translate-like-button-widget', LIKEBTN_I18N_DOMAIN); ?>');void(0);"><?php _e('Send us Translation', LIKEBTN_I18N_DOMAIN); ?></a>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                            <?php /*
                                            </div>
                                        </div>*/ ?>
                                        <div class="likebtn_reset_wrapper">
                                            <a href="javascript:likebtnToggleShortcode('likebtn_sc_wr')" class="likebtn_sc_trgr"><?php _e('Get shortcode', LIKEBTN_I18N_DOMAIN); ?></a>
                                            <input class="button-secondary" type="button" name="Reset" value="<?php _e('Reset', LIKEBTN_I18N_DOMAIN); ?>" onclick="return resetSettings('<?php echo $entity_name; ?>', reset_settings)" />
                                        </div>
                                        <div id="likebtn_sc_wr">
                                            <br/>
                                            <textarea class="likebtn_input likebtn_disabled" rows="5" id="likebtn_sc" readonly></textarea>
                                            <p class="description">
                                                <?php _e('Replace "CUSTOM ITEM NAME" with the custom unique text. The custom item name will be displayed in Statistics and in the Most liked content widget.', LIKEBTN_I18N_DOMAIN); ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>

            <input class="button-primary" type="submit" name="Save" value="<?php _e('Save Changes', LIKEBTN_I18N_DOMAIN); ?>" <?php /*if (get_option('likebtn_show_' . $entity_name) == '1'): ?>style="display: none"<?php endif*/ ?> /><br/><br/>
        </form>

    </div>
    <?php

    _likebtn_admin_footer();
}

// admin vote statistics
function likebtn_admin_statistics() {

    global $likebtn_page_sizes;
    global $likebtn_post_statuses;
    global $wpdb;

    $query_parameters = array();

    $likebtn_entities = _likebtn_get_entities(true);

    // Custom item
    $likebtn_entities[LIKEBTN_ENTITY_CUSTOM_ITEM] = __('Custom item');

    // get parameters
    $entity_name = _likebtn_statistics_entity();

    // Process bulk actions
    //_likebtn_bulk_actions($entity_name);

    // Multisite - available for super admin only
    $blogs = array();
    $blog_list = array();
    $statistics_blog_id = '';
    $prefix_prepared = $wpdb->prefix;
    if (is_multisite() && is_super_admin()) {

        global $blog_id;

        $blog_list = $wpdb->get_results("
            SELECT blog_id, domain
            FROM {$wpdb->blogs}
            WHERE site_id = '{$wpdb->siteid}'
            AND spam = '0'
            AND deleted = '0'
            AND archived = '0'
            ORDER BY blog_id
        ");

        // Place current blog on the first place
        foreach ($blog_list as $blog) {
            if ($blog->blog_id == $blog_id) {
                $blogs["{$blog->blog_id}"] = get_blog_option($blog->blog_id, 'blogname') . ' - ' . $blog->domain;
                break;
            }
        }

        foreach ($blog_list as $blog) {
            if ($blog->blog_id != $blog_id) {
                $blogs["{$blog->blog_id}"] = get_blog_option($blog->blog_id, 'blogname') . ' - ' . $blog->domain;
            }
        }

        // Add all
        $blogs['all'] = __('All Sites');

        // Determine selected blog id
        if (isset($_GET['likebtn_blog_id'])) {
            if ($_GET['likebtn_blog_id'] == 'all') {
                $statistics_blog_id = $_GET['likebtn_blog_id'];
            } else {
                // Check if blog with ID exists
                foreach ($blog_list as $blog) {
                    if ($blog->blog_id == (int)$_GET['likebtn_blog_id']) {
                        $statistics_blog_id = (int)$_GET['likebtn_blog_id'];
                        break;
                    }
                }
            }
        }

        // Prepare prefix if this is not main blog
        if ($blog_id != 1) {
            $prefix_prepared = substr($wpdb->prefix, 0, strlen($wpdb->prefix)-strlen($blog_id)-1);
        }
    }

    // add comment statuses
    $likebtn_post_statuses['0'] = __('Comment not approved', LIKEBTN_I18N_DOMAIN);
    $likebtn_post_statuses['1'] = __('Comment approved', LIKEBTN_I18N_DOMAIN);

    $sort_by = '';
    if (isset($_GET['likebtn_sort_by'])) {
        $sort_by =$_GET['likebtn_sort_by'];
    }
    if (!$sort_by) {
        $sort_by = 'likes';
    } elseif ($entity_name == LIKEBTN_ENTITY_CUSTOM_ITEM && $sort_by == 'post_id') {
        $sort_by = 'likes';
    }

    $page_size = LIKEBTN_STATISTIC_PAGE_SIZE;
    if (isset($_GET['likebtn_page_size'])) {
        $page_size = LIKEBTN_STATISTIC_PAGE_SIZE;
    }

    $post_id = '';
    if (isset($_GET['likebtn_post_id'])) {
        $post_id = trim(stripcslashes($_GET['likebtn_post_id']));
    }

    $post_title = '';
    if (isset($_GET['likebtn_post_title'])) {
        $post_title = trim(stripcslashes($_GET['likebtn_post_title']));
    }
    $post_status = '';
    if (isset($_GET['likebtn_post_status'])) {
        $post_status = trim($_GET['likebtn_post_status']);
    }

    // pagination
    require_once(dirname(__FILE__) . '/likebtn_like_button_pagination.class.php');

    $pagination_target = "admin.php?page=likebtn_statistics";
    foreach ($_GET as $get_parameter => $get_value) {
        $pagination_target .= '&' . $get_parameter . '=' . stripcslashes($get_value);
    }

    $p = new LikeBtnLikeButtonPagination();
    $p->limit($page_size); // Limit entries per page
    $p->target($pagination_target);
    //$p->currentPage(); // Gets and validates the current page
    $p->prevLabel(__('Previous', LIKEBTN_I18N_DOMAIN));
    $p->nextLabel(__('Next', LIKEBTN_I18N_DOMAIN));

    if (!isset($_GET['paging'])) {
        $p->page = 1;
    } else {
        $p->page = $_GET['paging'];
    }

    // query for limit paging
    $query_limit = "LIMIT " . ($p->page - 1) * $p->limit . ", " . $p->limit;

    // query parameters
    $query_where = '';

    // Post type
    switch ($entity_name) {
        case LIKEBTN_ENTITY_COMMENT:
        case LIKEBTN_ENTITY_CUSTOM_ITEM:
        case LIKEBTN_ENTITY_BP_ACTIVITY_POST:
        case LIKEBTN_ENTITY_BP_ACTIVITY_UPDATE:
        case LIKEBTN_ENTITY_BP_ACTIVITY_COMMENT:
        case LIKEBTN_ENTITY_BP_ACTIVITY_TOPIC:
        case LIKEBTN_ENTITY_BP_MEMBER:
        case LIKEBTN_ENTITY_BBP_POST:
        case LIKEBTN_ENTITY_BBP_USER:
            break;

        default:
            $query_where .= ' AND p.post_type = %s ';
            $query_parameters[] = $entity_name;
            break;
    }

    // Post ID
    if ($post_id) {
        switch ($entity_name) {
            case LIKEBTN_ENTITY_BP_ACTIVITY_POST:
            case LIKEBTN_ENTITY_BP_ACTIVITY_UPDATE:
            case LIKEBTN_ENTITY_BP_ACTIVITY_COMMENT:
            case LIKEBTN_ENTITY_BP_ACTIVITY_TOPIC:
            case LIKEBTN_ENTITY_BP_MEMBER:
                $query_where .= ' AND p.id = %d ';
                break;
            case LIKEBTN_ENTITY_COMMENT:
                $query_where .= ' AND p.comment_ID = %d ';
                break;
            default:
                $query_where .= ' AND p.ID = %d ';
                break;
        }
        $query_parameters[] = $post_id;
    }

    if ($post_title) {
        switch ($entity_name) {
            case LIKEBTN_ENTITY_BP_ACTIVITY_POST:
            case LIKEBTN_ENTITY_BP_ACTIVITY_UPDATE:
            case LIKEBTN_ENTITY_BP_ACTIVITY_COMMENT:
            case LIKEBTN_ENTITY_BP_ACTIVITY_TOPIC:
                $query_where .= ' AND LOWER(CONCAT(p.action, p.content)) LIKE "%%%s%%" ';
                break;
            case LIKEBTN_ENTITY_BP_MEMBER:
                $query_where .= ' AND LOWER(p.value) LIKE "%%%s%%" ';
                break;
            case LIKEBTN_ENTITY_BBP_USER:
                $query_where .= ' AND LOWER(CONCAT(p.user_login, p.display_name)) LIKE "%%%s%%" ';
                break;          
            case LIKEBTN_ENTITY_COMMENT:
                $query_where .= ' AND LOWER(p.comment_content) LIKE "%%%s%%" ';
                break;
            case LIKEBTN_ENTITY_CUSTOM_ITEM:
                $query_where .= ' AND LOWER(p.identifier) LIKE "%%%s%%" ';
                break;
            default:
                $query_where .= ' AND LOWER(p.post_title) LIKE "%%%s%%" ';
                break;
        }
        $query_parameters[] = strtolower($post_title);
    }
    if ($post_status !== '') {
        if ($entity_name == LIKEBTN_ENTITY_COMMENT) {
            $query_where .= ' AND p.comment_approved = %s ';
        } elseif ($entity_name != LIKEBTN_ENTITY_CUSTOM_ITEM) {
            $query_where .= ' AND p.post_status = %s ';
        }
        $query_parameters[] = $post_status;
    }

    // order by
    switch ($sort_by) {
        case 'dislikes':
            $query_orderby = 'ORDER BY ABS(dislikes) DESC';
            break;
        case 'likes_minus_dislikes':
            $query_orderby = 'ORDER BY ABS(likes_minus_dislikes)*SIGN(likes_minus_dislikes) DESC';
            break;
        case 'post_id':
            $query_orderby = 'ORDER BY post_id ASC';
            break;
        case 'post_title':
            $query_orderby = 'ORDER BY post_title ASC';
            break;
        case 'likes':
        default:
            $query_orderby = 'ORDER BY ABS(likes) DESC';
            $sort_by = 'likes';
            break;
        /*case 'last_updated':
            $query_orderby = 'ORDER BY pm_likes.meta_id DESC';
            break;*/
    }

    // For Multisite
    $query = '';
    if ($statistics_blog_id && $statistics_blog_id != 1 && $statistics_blog_id != 'all') {
        $prefix = "{$prefix_prepared}{$statistics_blog_id}_";
        $query = _likebtn_get_statistics_sql($entity_name, $prefix, $query_where, $query_orderby, $query_limit);
        $query_prepared = $wpdb->prepare($query, $query_parameters);
    } else if ($statistics_blog_id == 'all') {
        foreach ($blog_list as $blog) {
            if ($blog->blog_id == 1) {
                $prefix = $prefix_prepared;
            } else {
                $prefix = "{$prefix_prepared}{$blog->blog_id}_";
            }
            $query_list[] = $wpdb->prepare(_likebtn_get_statistics_sql($entity_name, $prefix, $query_where, '', '', $blog->blog_id . ' as blog_id, '), $query_parameters);
        }
        $query_prepared = ' SELECT SQL_CALC_FOUND_ROWS * from (' . implode(' UNION ', $query_list) . ") query {$query_orderby} {$query_limit} ";
    } else {
        $query = _likebtn_get_statistics_sql($entity_name, $prefix_prepared, $query_where, $query_orderby, $query_limit);
        $query_prepared = $wpdb->prepare($query, $query_parameters);
    }

    // echo "<pre>";
    // echo $query_prepared;
    // echo $wpdb->prepare($query, $query_parameters);
    // $wpdb->show_errors();
    // exit();
    $statistics = $wpdb->get_results($query_prepared);

    $total_found = 0;
    if (isset($statistics[0])) {
        $query_found_rows = "SELECT FOUND_ROWS() as found_rows";
        $found_rows = $wpdb->get_results($query_found_rows);

        $total_found = (int) $found_rows[0]->found_rows;

        $p->items($total_found);
        $p->calculate(); // Calculates what to show
        $p->parameterName('paging');
        $p->adjacents(1); // No. of page away from the current page
    }

    likebtn_admin_header();
    ?>

    <script type="text/javascript">
        var likebtn_msg_select_items = '<?php _e("Please select item(s)", LIKEBTN_I18N_DOMAIN); ?>';
        var likebtn_msg_upgrade = '<?php _e("Upgrade your website to VIP to be able to use the feature", LIKEBTN_I18N_DOMAIN); ?>';
    </script>
    <div>

        <?php if (!_likebtn_is_stat_enabled() || get_option('likebtn_last_sync_message')): ?>
            <span class="likebtn_error">
                <?php 
                    echo strtr(
                        __('Statistics not available, enable synchronization in order to view statistics:', LIKEBTN_I18N_DOMAIN), 
                        array('%url_sync%'=>admin_url().'admin.php?page=likebtn_settings')
                    );
                ?>
            </span>
            <?php /*_e('To enable statistics:', LIKEBTN_I18N_DOMAIN)*/ ?>
            <ol>
                <?php if (get_option('likebtn_plan') < LIKEBTN_PLAN_PRO): ?>
                    <li>
                        <?php echo strtr(
                            __('<a href="%url_upgrade%">Upgrade</a> your website to PRO or higher plan on LikeBtn.com.', LIKEBTN_I18N_DOMAIN), 
                            array('%url_upgrade%'=>"javascript:likebtnPopup('".__('http://likebtn.com/en/', LIKEBTN_I18N_DOMAIN)."#plans_pricing')")
                        ); ?>
                    </li>
                <?php endif ?>
                <li>
                    <?php echo strtr(
                        __('Enable synchronization on <a href="%url_sync%">Synchronization</a> tab.', LIKEBTN_I18N_DOMAIN), 
                        array('%url_sync%'=>admin_url().'admin.php?page=likebtn_settings')
                    ); ?>
                </li>
                <?php /*<li><?php _e('Set your website tariff plan in Settings.', LIKEBTN_I18N_DOMAIN); ?></li>
                <li><?php _e('Enter E-mail and API key in Settings.', LIKEBTN_I18N_DOMAIN); ?></li>
                <li><?php _e('Set Synchronization interval in Settings.', LIKEBTN_I18N_DOMAIN); ?></li>*/ ?>
                <?php /* <li><?php _e('Run Synchronization test in Settings.', LIKEBTN_I18N_DOMAIN); ?></li> */ ?>
            </ol>
        <?php else: ?>
            <p class="description">
                ● <?php _e('Keep in mind that items appear in Statistics after receiving at least one vote.', LIKEBTN_I18N_DOMAIN); ?><br/>
                ● <?php _e('Select <u>Custom item</u> content type to view votes of like buttons added using shortcode or HTML code.', LIKEBTN_I18N_DOMAIN); ?><br/>
                ● <?php _e('To edit item votes click on a number of likes/dislikes in the statistics table.', LIKEBTN_I18N_DOMAIN); ?>
            </p>
        <?php endif ?>
        <br/>
        <form action="" method="get" id="statistics_form">
            <input type="hidden" name="page" value="likebtn_statistics" />

            <?php if ($blogs): ?>
                <label><?php _e('Site', LIKEBTN_I18N_DOMAIN); ?>:</label>
                <select name="likebtn_blog_id" >
                    <?php foreach ($blogs as $blog_id_value => $blog_title): ?>
                        <option value="<?php echo $blog_id_value; ?>" <?php selected($statistics_blog_id, $blog_id_value); ?> ><?php echo $blog_title; ?></option>
                    <?php endforeach ?>
                </select>
                &nbsp;&nbsp;
            <?php endif ?>

            <label><?php _e('Content type', LIKEBTN_I18N_DOMAIN); ?>:</label>
            <select name="likebtn_entity_name" >
                <?php foreach ($likebtn_entities as $entity_name_value => $entity_title): ?>
                    <option value="<?php echo $entity_name_value; ?>" <?php selected($entity_name, $entity_name_value); ?> ><?php _e($entity_title, LIKEBTN_I18N_DOMAIN); ?></option>
                <?php endforeach ?>
            </select>
            &nbsp;&nbsp;
            <label><?php _e('Order by', LIKEBTN_I18N_DOMAIN); ?>:</label>
            <select name="likebtn_sort_by" >
                <option value="likes" <?php selected('likes', $sort_by); ?> ><?php _e('Likes', LIKEBTN_I18N_DOMAIN); ?></option>
                <option value="dislikes" <?php selected('dislikes', $sort_by); ?> ><?php _e('Dislikes', LIKEBTN_I18N_DOMAIN); ?></option>
                <option value="likes_minus_dislikes" <?php selected('likes_minus_dislikes', $sort_by); ?> ><?php _e('Likes minus dislikes', LIKEBTN_I18N_DOMAIN); ?></option>
                <option value="post_id" <?php selected('post_id', $sort_by); ?> ><?php _e('ID', LIKEBTN_I18N_DOMAIN); ?></option>
                <option value="post_title" <?php selected('post_title', $sort_by); ?> ><?php _e('Title', LIKEBTN_I18N_DOMAIN); ?></option>
                <?php /* <option value="last_updated" <?php selected('last_updated', $sort_by); ?> ><?php _e('Last updated', LIKEBTN_I18N_DOMAIN); ?></option> */ ?>
            </select>

            &nbsp;&nbsp;
            <label><?php _e('Per page', LIKEBTN_I18N_DOMAIN); ?>:</label>
            <select name="likebtn_page_size" >
                <?php foreach ($likebtn_page_sizes as $page_size_value): ?>
                    <option value="<?php echo $page_size_value; ?>" <?php selected($page_size, $page_size_value); ?> ><?php echo $page_size_value ?></option>
                <?php endforeach ?>

            </select>
            <br/><br/>
            <div class="postbox statistics_filter_container">
                <?php /*<h3><?php _e('Filter', LIKEBTN_I18N_DOMAIN); ?></h3>*/ ?>
                <div class="inside">
                    <label><?php _e('ID', LIKEBTN_I18N_DOMAIN); ?>:</label>
                    <input type="text" name="likebtn_post_id" value="<?php echo htmlspecialchars($post_id) ?>" size="5" />
                    &nbsp;&nbsp;
                    <label><?php _e('Title'); ?>:</label>
                    <input type="text" name="likebtn_post_title" value="<?php echo htmlspecialchars($post_title) ?>" size="25"/>
                    &nbsp;&nbsp;
                    <label><?php _e('Status', LIKEBTN_I18N_DOMAIN); ?>:</label>
                    <select name="likebtn_post_status" >
                        <option value=""></option>
                        <?php foreach ($likebtn_post_statuses as $post_status_value => $post_status_title): ?>
                            <option value="<?php echo $post_status_value; ?>" <?php selected($post_status, $post_status_value); ?> ><?php echo _e($post_status_title) ?></option>
                        <?php endforeach ?>
                    </select>

                    &nbsp;&nbsp;
                    <input class="button-secondary" type="button" name="reset" value="<?php _e('Reset filter', LIKEBTN_I18N_DOMAIN); ?>" onClick="jQuery('.statistics_filter_container :input[type!=button]').val('');
                jQuery('#statistics_form').submit();"/>
                </div>
            </div>

            <input class="button-primary" type="submit" name="show" value="<?php _e('View', LIKEBTN_I18N_DOMAIN); ?>" /> 
        </form>
        <br/>
        <?php _e('Total found', LIKEBTN_I18N_DOMAIN); ?>: <strong><?php echo $total_found ?></strong>
        <br/>
        <form method="post" action="" id="stats_actions_form">
            <input type="hidden" name="bulk_action" value="" id="stats_bulk_action" />
        <?php if (count($statistics) && $p->total_pages > 0): ?>
            <div class="tablenav">
                <input type="button" class="button-secondary likebtn_ttip" onclick="likebtnStatsBulkAction('reset', '<?php echo get_option('likebtn_plan') ?>', '<?php _e("The votes count can not be recovered after resetting. Are you sure you want to reset likes and dislikes for the selected item(s)?", LIKEBTN_I18N_DOMAIN); ?>')" value="<?php _e('Reset', LIKEBTN_I18N_DOMAIN); ?>" title="<?php _e('Set to zero number of likes and dislikes for selected items', LIKEBTN_I18N_DOMAIN); ?>">
                
                <input type="button" class="button-secondary likebtn_ttip" onclick="likebtnStatsBulkAction('delete', '<?php echo get_option('likebtn_plan') ?>', '<?php _e("The votes count can not be recovered after deleting. Are you sure you want to delete selected item(s) from statistics?", LIKEBTN_I18N_DOMAIN); ?>')" value="<?php _e('Delete', LIKEBTN_I18N_DOMAIN); ?>" title="<?php _e('Delete selected items from statistics: no posts, pages, comments, etc will be deleted, just their votes will be deleted from statistics', LIKEBTN_I18N_DOMAIN); ?>">

                <div class="tablenav-pages">
                    <?php echo $p->show(); ?>
                </div>
            </div>
        <?php endif ?>
        <table class="widefat" id="statistics_container">
            <thead>
                <tr>
                    <th><input type="checkbox" onclick="statisticsItemsCheckbox(this)" value="all" style="margin:0"></th>
                    <?php if ($entity_name != LIKEBTN_ENTITY_CUSTOM_ITEM): ?>
                        <th>ID<?php if ($sort_by == 'post_id'): ?>&nbsp;↓<?php endif ?></th>
                    <?php endif ?>
                    <?php if ($entity_name == LIKEBTN_ENTITY_POST): ?>
                        <th><?php _e('Featured image', LIKEBTN_I18N_DOMAIN) ?></th>
                    <?php endif ?>
                    <th width="100%"><?php _e('Title', LIKEBTN_I18N_DOMAIN) ?><?php if ($sort_by == 'post_title'): ?>&nbsp;↓<?php endif ?></th>
                    <?php if ($blogs && $statistics_blog_id == 'all'): ?>
                        <th><?php _e('Site') ?></th>
                    <?php endif ?>
                    <th><?php _e('Likes', LIKEBTN_I18N_DOMAIN) ?><?php if ($sort_by == 'likes'): ?>&nbsp;↓<?php endif ?></th>
                    <th><?php _e('Dislikes', LIKEBTN_I18N_DOMAIN) ?><?php if ($sort_by == 'dislikes'): ?>&nbsp;↓<?php endif ?></th>
                    <th><?php _e('Likes minus dislikes', LIKEBTN_I18N_DOMAIN) ?><?php if ($sort_by == 'likes_minus_dislikes'): ?>&nbsp;↓<?php endif ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($statistics as $statistics_item): ?>
                    <?php
                        // URL
                        if (!$blogs) {
                            $post_url = _likebtn_get_entity_url($entity_name, $statistics_item->post_id, $statistics_item->url);
                        } else {
                            // Multisite
                            switch ($entity_name) {
                                case LIKEBTN_ENTITY_COMMENT:
                                    $post_url = _likebtn_get_blog_comment_link($statistics_item->blog_id, $statistics_item->post_id);
                                    break;
                                case LIKEBTN_ENTITY_CUSTOM_ITEM:
                                    $post_url = $statistics_item->url;
                                    break;
                                case LIKEBTN_ENTITY_BP_ACTIVITY_POST:
                                case LIKEBTN_ENTITY_BP_ACTIVITY_UPDATE:
                                case LIKEBTN_ENTITY_BP_ACTIVITY_COMMENT:
                                case LIKEBTN_ENTITY_BP_ACTIVITY_TOPIC:
                                    $post_url = bp_activity_get_permalink($statistics_item->post_id);
                                    break;
                                default:
                                    $post_url = get_blog_permalink($statistics_item->blog_id, $statistics_item->post_id);
                                    break;
                            }
                        }
                    
                    ?>

                    <?php $statistics_item->post_title = _likebtn_prepare_title($entity_name, $statistics_item->post_title); ?>

                    <tr id="item_<?php echo $statistics_item->post_id; ?>">
                        <td><input type="checkbox" class="item_checkbox" value="<?php echo $statistics_item->post_id; ?>" name="item[]" <?php if ($blogs && $statistics_item->blog_id != $blog_id): ?>disabled="disabled"<?php endif ?>></td>
                        <?php if ($entity_name != LIKEBTN_ENTITY_CUSTOM_ITEM): ?>
                            <td><?php echo $statistics_item->post_id; ?></td>
                        <?php endif ?>
                        <?php if ($entity_name == LIKEBTN_ENTITY_POST): ?>
                            <td><?php echo get_the_post_thumbnail($statistics_item->post_id, array(32,32)); ?>&nbsp;</td>
                        <?php endif ?>
                        <td><a href="<?php echo $post_url ?>" target="_blank"><?php echo htmlspecialchars($statistics_item->post_title); ?></a></td>
                        <?php if ($blogs && $statistics_blog_id == 'all'): ?>
                            <td><?php echo get_blog_option($statistics_item->blog_id, 'blogname') ?></td>
                        <?php endif ?>
                        <td>
                            <?php if ($blogs && $statistics_item->blog_id != $blog_id): ?>
                                <?php echo $statistics_item->likes; ?>
                            <?php else: ?>
                                <a href="javascript:statisticsEdit('<?php echo $entity_name ?>', '<?php echo $statistics_item->post_id; ?>', 'like', '<?php echo get_option('likebtn_plan'); ?>', '<?php _e('Enter new value:', LIKEBTN_I18N_DOMAIN) ?>', '<?php _e('Upgrade your website plan to the ULTRA plan to use the feature', LIKEBTN_I18N_DOMAIN) ?>', '<?php _e('Error occured. Please, try again later.', LIKEBTN_I18N_DOMAIN) ?>');void(0);" title="<?php _e('Click to change', LIKEBTN_I18N_DOMAIN) ?>" class="item_like likebtn_ttip"><?php echo $statistics_item->likes; ?></a>
                            <?php endif ?>
                        </td>
                        <td>
                            <?php if ($blogs && $statistics_item->blog_id != $blog_id): ?>
                                <?php echo $statistics_item->dislikes; ?>
                            <?php else: ?>
                                <a href="javascript:statisticsEdit('<?php echo $entity_name ?>', '<?php echo $statistics_item->post_id; ?>', 'dislike', '<?php echo get_option('likebtn_plan'); ?>', '<?php _e('Enter new value:', LIKEBTN_I18N_DOMAIN) ?>', '<?php _e('Upgrade your website plan to the ULTRA plan to use the feature', LIKEBTN_I18N_DOMAIN) ?>', '<?php _e('Error occured. Please, try again later.', LIKEBTN_I18N_DOMAIN) ?>');void(0);" title="<?php _e('Click to change', LIKEBTN_I18N_DOMAIN) ?>" class="item_dislike likebtn_ttip"><?php echo $statistics_item->dislikes; ?></a>
                            <?php endif ?>
                        </td>
                        <td><?php echo $statistics_item->likes_minus_dislikes; ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
        </form>
        <?php if (count($statistics) && $p->total_pages > 0): ?>
            <div class="tablenav">
                <div class="tablenav-pages">
                    <?php echo $p->show(); ?>
                </div>
            </div>
        <?php endif ?>

    </div>

    <?php

    _likebtn_admin_footer();
}

// Get statistics entity
function _likebtn_statistics_entity()
{
    $entity_name = LIKEBTN_ENTITY_POST;
    if (!empty($_GET['likebtn_entity_name'])) {
        $entity_name = $_GET['likebtn_entity_name'];
    }
    /*if (!array_key_exists($entity_name, $likebtn_entities)) {
        $entity_name = LIKEBTN_ENTITY_POST;
    }*/
    return $entity_name;
}

// get SQL query for retrieving statistics
function _likebtn_get_statistics_sql($entity_name, $prefix, $query_where, $query_orderby, $query_limit, $query_select = 'SQL_CALC_FOUND_ROWS')
{
    global $likebtn_bbp_post_types;

    if ($entity_name == LIKEBTN_ENTITY_COMMENT) {
        // comment
        $query = "
             SELECT {$query_select}
                p.comment_ID as 'post_id',
                p.comment_content as post_title,
                pm_likes.meta_value as 'likes',
                pm_dislikes.meta_value as 'dislikes',
                pm_likes_minus_dislikes.meta_value as 'likes_minus_dislikes',
                '' as url
             FROM {$prefix}commentmeta pm_likes
             LEFT JOIN {$prefix}comments p
                 ON (p.comment_ID = pm_likes.comment_id)
             LEFT JOIN {$prefix}commentmeta pm_dislikes
                 ON (pm_dislikes.comment_id = pm_likes.comment_id AND pm_dislikes.meta_key = '" . LIKEBTN_META_KEY_DISLIKES . "')
             LEFT JOIN {$prefix}commentmeta pm_likes_minus_dislikes
                 ON (pm_likes_minus_dislikes.comment_id = pm_likes.comment_id AND pm_likes_minus_dislikes.meta_key = '" . LIKEBTN_META_KEY_LIKES_MINUS_DISLIKES . "')
             WHERE
                pm_likes.meta_key = '" . LIKEBTN_META_KEY_LIKES . "'
                {$query_where}
             {$query_orderby}
             {$query_limit}";
    } elseif ($entity_name == LIKEBTN_ENTITY_CUSTOM_ITEM) {
        // custom item
        $query = "
             SELECT {$query_select}
                p.identifier as 'post_id',
                p.identifier as 'post_title',
                p.likes,
                p.dislikes,
                p.likes_minus_dislikes,
                p.url
             FROM {$prefix}" . LIKEBTN_TABLE_ITEM . " p
             WHERE
                1 = 1
                {$query_where}
             {$query_orderby}
             {$query_limit}";    
    } elseif (in_array($entity_name, array(LIKEBTN_ENTITY_BP_ACTIVITY_POST, LIKEBTN_ENTITY_BP_ACTIVITY_UPDATE, LIKEBTN_ENTITY_BP_ACTIVITY_COMMENT, LIKEBTN_ENTITY_BP_ACTIVITY_TOPIC))) {
        // BuddyPress activity
        switch ($entity_name) {
            case LIKEBTN_ENTITY_BP_ACTIVITY_POST:
                $query_where .= " AND p.type = 'new_blog_post' ";
                break;
            case LIKEBTN_ENTITY_BP_ACTIVITY_TOPIC:
                $query_where .= " AND p.type = 'bbp_topic_create' ";
                break;
            case LIKEBTN_ENTITY_BP_ACTIVITY_COMMENT:
                $query_where .= " AND p.type = 'activity_comment' ";
                break;
            default:
                $query_where .= " AND p.type != 'new_blog_post' AND p.type != 'bbp_topic_create' AND p.type != 'activity_comment' ";
                break;
        }
        $query = "
             SELECT {$query_select}
                p.id as 'post_id',
                CONCAT( IF(p.action != '', p.action, IF(p.content !='', p.content, IF(p.primary_link != '', p.primary_link, p.type))), IF(p.content != '' && p.type != 'bbp_topic_create' && p.type != 'new_blog_post', CONCAT(': ', p.content), '') ) as 'post_title',
                pm_likes.meta_value as 'likes',
                pm_dislikes.meta_value as 'dislikes',
                pm_likes_minus_dislikes.meta_value as 'likes_minus_dislikes',
                '' as url
             FROM {$prefix}bp_activity_meta pm_likes
             LEFT JOIN {$prefix}bp_activity p
                 ON (p.id = pm_likes.activity_id)
             LEFT JOIN {$prefix}bp_activity_meta pm_dislikes
                 ON (pm_dislikes.activity_id = pm_likes.activity_id AND pm_dislikes.meta_key = '" . LIKEBTN_META_KEY_DISLIKES . "')
             LEFT JOIN {$prefix}bp_activity_meta pm_likes_minus_dislikes
                 ON (pm_likes_minus_dislikes.activity_id = pm_likes.activity_id AND pm_likes_minus_dislikes.meta_key = '" . LIKEBTN_META_KEY_LIKES_MINUS_DISLIKES . "')
             WHERE
                pm_likes.meta_key = '" . LIKEBTN_META_KEY_LIKES . "'
                {$query_where}
             {$query_orderby}
             {$query_limit}";
    } elseif ($entity_name == LIKEBTN_ENTITY_BP_MEMBER) {
        $query = "
             SELECT {$query_select}
                p.id as 'post_id',
                p.value as 'post_title',
                pm_likes.meta_value as 'likes',
                pm_dislikes.meta_value as 'dislikes',
                pm_likes_minus_dislikes.meta_value as 'likes_minus_dislikes',
                '' as url
             FROM {$prefix}bp_xprofile_meta pm_likes
             LEFT JOIN {$prefix}bp_xprofile_data p
                 ON (p.id = pm_likes.object_id)
             LEFT JOIN {$prefix}bp_xprofile_meta pm_dislikes
                 ON (pm_dislikes.object_id = pm_likes.object_id AND pm_dislikes.meta_key = '" . LIKEBTN_META_KEY_DISLIKES . "')
             LEFT JOIN {$prefix}bp_xprofile_meta pm_likes_minus_dislikes
                 ON (pm_likes_minus_dislikes.object_id = pm_likes.object_id AND pm_likes_minus_dislikes.meta_key = '" . LIKEBTN_META_KEY_LIKES_MINUS_DISLIKES . "')
             WHERE
                pm_likes.meta_key = '" . LIKEBTN_META_KEY_LIKES . "'
                AND pm_likes.object_type = '" . LIKEBTN_BP_XPROFILE_OBJECT_TYPE . "' 
                {$query_where}
             {$query_orderby}
             {$query_limit}";
    } elseif ($entity_name == LIKEBTN_ENTITY_BBP_USER) {
        // bbPress User Profile
        $query = "
             SELECT {$query_select}
                p.ID as 'post_id',
                p.display_name as 'post_title',
                pm_likes.meta_value as 'likes',
                pm_dislikes.meta_value as 'dislikes',
                pm_likes_minus_dislikes.meta_value as 'likes_minus_dislikes',
                '' as url
             FROM {$prefix}usermeta pm_likes
             LEFT JOIN {$prefix}users p
                 ON (p.ID = pm_likes.user_id)
             LEFT JOIN {$prefix}usermeta pm_dislikes
                 ON (pm_dislikes.user_id = pm_likes.user_id AND pm_dislikes.meta_key = '" . LIKEBTN_META_KEY_DISLIKES . "')
             LEFT JOIN {$prefix}usermeta pm_likes_minus_dislikes
                 ON (pm_likes_minus_dislikes.user_id = pm_likes.user_id AND pm_likes_minus_dislikes.meta_key = '" . LIKEBTN_META_KEY_LIKES_MINUS_DISLIKES . "')
             WHERE
                pm_likes.meta_key = '" . LIKEBTN_META_KEY_LIKES . "'
                {$query_where}
             {$query_orderby}
             {$query_limit}";
    } elseif ($entity_name == LIKEBTN_ENTITY_BBP_POST) {
        // bbPress Forum Post
        $query = "
             SELECT {$query_select}
                p.ID as 'post_id',
                IF(p.post_title != '', p.post_title, p.post_content) as 'post_title',
                pm_likes.meta_value as 'likes',
                pm_dislikes.meta_value as 'dislikes',
                pm_likes_minus_dislikes.meta_value as 'likes_minus_dislikes',
                '' as url
             FROM {$prefix}postmeta pm_likes
             LEFT JOIN {$prefix}posts p
                 ON (p.ID = pm_likes.post_id)
             LEFT JOIN {$prefix}postmeta pm_dislikes
                 ON (pm_dislikes.post_id = pm_likes.post_id AND pm_dislikes.meta_key = '" . LIKEBTN_META_KEY_DISLIKES . "')
             LEFT JOIN {$prefix}postmeta pm_likes_minus_dislikes
                 ON (pm_likes_minus_dislikes.post_id = pm_likes.post_id AND pm_likes_minus_dislikes.meta_key = '" . LIKEBTN_META_KEY_LIKES_MINUS_DISLIKES . "')
             WHERE
                pm_likes.meta_key = '" . LIKEBTN_META_KEY_LIKES . "'
                AND p.post_type in ('".implode("', '", $likebtn_bbp_post_types)."') 
                {$query_where}
             {$query_orderby}
             {$query_limit}";
    } else {
        // post
        $query = "
             SELECT {$query_select}
                p.ID as 'post_id',
                p.post_title,
                pm_likes.meta_value as 'likes',
                pm_dislikes.meta_value as 'dislikes',
                pm_likes_minus_dislikes.meta_value as 'likes_minus_dislikes',
                '' as url
             FROM {$prefix}postmeta pm_likes
             LEFT JOIN {$prefix}posts p
                 ON (p.ID = pm_likes.post_id)
             LEFT JOIN {$prefix}postmeta pm_dislikes
                 ON (pm_dislikes.post_id = pm_likes.post_id AND pm_dislikes.meta_key = '" . LIKEBTN_META_KEY_DISLIKES . "')
             LEFT JOIN {$prefix}postmeta pm_likes_minus_dislikes
                 ON (pm_likes_minus_dislikes.post_id = pm_likes.post_id AND pm_likes_minus_dislikes.meta_key = '" . LIKEBTN_META_KEY_LIKES_MINUS_DISLIKES . "')
             WHERE
                pm_likes.meta_key = '" . LIKEBTN_META_KEY_LIKES . "'
                {$query_where}
             {$query_orderby}
             {$query_limit}";
    }

    return $query;
}

// admin help
function likebtn_admin_help() {
    likebtn_admin_header();
    ?>
    <div>
        <iframe width="560" height="315" src="https://www.youtube.com/embed/n4gye6Blmf8" frameborder="0" allowfullscreen></iframe>
        <ul>
            <li><?php echo __('<a href="http://likebtn.com/en/wordpress-like-button-plugin" target="_blank">WordPress LikeBtn Plugin FAQ</a>', LIKEBTN_I18N_DOMAIN); ?></li>
            <li><?php echo __('<a href="http://likebtn.com/en/faq" target="_blank">LikeBtn FAQ</a>', LIKEBTN_I18N_DOMAIN); ?></li>
            <li><?php echo __('<a href="http://forum.likebtn.com/forums/34-WordPress" target="_blank">Forum</a>', LIKEBTN_I18N_DOMAIN); ?></li>
        </ul>
    </div>
    <?php
    _likebtn_admin_footer();
}

// Process bulk actions
function _likebtn_bulk_actions()
{
    $entity_name = _likebtn_statistics_entity();

    // Resettings
    if (empty($_POST['bulk_action']) || empty($_POST['item'])) {
        return false;
    }

    switch ($_POST['bulk_action']) {
        case 'reset':
            $reseted = _likebtn_reset($entity_name, $_POST['item']);
            _likebtn_add_notice(array(
                'msg' => __('Likes and dislikes for the following number of items have been successfully reseted:', LIKEBTN_I18N_DOMAIN).' '.$reseted,
            ));
            break;

        case 'delete':
            $reseted = _likebtn_delete($entity_name, $_POST['item']);
            _likebtn_add_notice(array(
                'msg' => __('The following number of items have been successfully deleted:', LIKEBTN_I18N_DOMAIN).' '.$reseted,
            ));
            break;
        
        default:
            return false;
            break;
    }

    wp_redirect($_SERVER['REQUEST_URI']);
    exit();
}

// get URL of the public folder
function _likebtn_get_public_url() {
    //$siteurl = get_option('siteurl');
    //return $siteurl . '/wp-content/plugins/' . basename(dirname(__FILE__)) . '/public/';
    return plugin_dir_url( __FILE__ ) . '/public/';
}

// Get supported by current theme Post Formats
function _likebtn_get_post_formats() {
    $post_formats = get_theme_support('post-formats');
    if (is_array($post_formats[0])) {
        $post_formats = $post_formats[0];
    } else {
        $post_formats = array();
    }

    if (!is_array($post_formats)) {
        $post_formats = array();
    }

    // append Standard format
    array_unshift($post_formats, 'standard');

    return $post_formats;
}

// Get entity types
function _likebtn_get_entities($no_list = false, $include_invisible = false) {
    /*global $likebtn_entities;

    if (count($likebtn_entities)) {
        return $likebtn_entities;
    }*/
    $likebtn_entities = array(
        LIKEBTN_ENTITY_POST => _likebtn_get_entity_title(LIKEBTN_ENTITY_POST),
        LIKEBTN_ENTITY_POST_LIST => _likebtn_get_entity_title(LIKEBTN_ENTITY_POST_LIST),
        LIKEBTN_ENTITY_PAGE => _likebtn_get_entity_title(LIKEBTN_ENTITY_PAGE),
        LIKEBTN_ENTITY_PAGE_LIST => _likebtn_get_entity_title(LIKEBTN_ENTITY_PAGE_LIST)
    );
    $post_types = get_post_types(array('public'=>true, '_builtin' => false));

    if (!empty($post_types)) {
        foreach ($post_types as $post_type) {

            // If bbPress is active miss bbPress custom post types
            if (_likebtn_is_bbp_active() && in_array($post_type, array('forum', 'topic', 'reply'))) {
                continue;
            }

            $likebtn_entities[$post_type] = _likebtn_get_entity_title($post_type);
            $likebtn_entities[$post_type.LIKEBTN_LIST_FLAG] = _likebtn_get_entity_title($post_type.LIKEBTN_LIST_FLAG);
        }
    }

    // Append BuddyPress
    if (_likebtn_is_bp_active()) {
        $likebtn_entities[LIKEBTN_ENTITY_BP_ACTIVITY_POST] = _likebtn_get_entity_title(LIKEBTN_ENTITY_BP_ACTIVITY_POST);
        $likebtn_entities[LIKEBTN_ENTITY_BP_ACTIVITY_UPDATE] = _likebtn_get_entity_title(LIKEBTN_ENTITY_BP_ACTIVITY_UPDATE);
        $likebtn_entities[LIKEBTN_ENTITY_BP_ACTIVITY_COMMENT] = _likebtn_get_entity_title(LIKEBTN_ENTITY_BP_ACTIVITY_COMMENT);
        $likebtn_entities[LIKEBTN_ENTITY_BP_ACTIVITY_TOPIC] = _likebtn_get_entity_title(LIKEBTN_ENTITY_BP_ACTIVITY_TOPIC);
        $likebtn_entities[LIKEBTN_ENTITY_BP_MEMBER] = _likebtn_get_entity_title(LIKEBTN_ENTITY_BP_MEMBER);
    }

    // Append bbPress
    if (_likebtn_is_bbp_active()) {
        $likebtn_entities[LIKEBTN_ENTITY_BBP_POST] = _likebtn_get_entity_title(LIKEBTN_ENTITY_BBP_POST);
        $likebtn_entities[LIKEBTN_ENTITY_BBP_USER] = _likebtn_get_entity_title(LIKEBTN_ENTITY_BBP_USER);
    }

    // append Comments
    $likebtn_entities[LIKEBTN_ENTITY_COMMENT] = _likebtn_get_entity_title(LIKEBTN_ENTITY_COMMENT);

    // translate entity names
    // does not work here
    /* load_plugin_textdomain(LIKEBTN_I18N_DOMAIN, false, dirname(plugin_basename(__FILE__)) . '/languages');

      foreach ($entities as $entity_name => $entity_title) {
      $entities[$entity_name] = __($entity_title, LIKEBTN_I18N_DOMAIN);
      } */

    // Remove excerpt entities
    if ($no_list) {
        foreach ($likebtn_entities as $entity_name=>$entity_title) {
            if (_likebtn_has_list_flag($entity_name)) {
                unset($likebtn_entities[$entity_name]);
            }
        }
    }

    // Add invisible
    if ($include_invisible) {
        $likebtn_entities = array_merge($likebtn_entities, array(
            LIKEBTN_ENTITY_USER => _likebtn_get_entity_title(LIKEBTN_ENTITY_USER),
        ));
    }

    return $likebtn_entities;
}

// short code
function likebtn_shortcode($args) {
    $entity_name = get_post_type();
    $entity_id = get_the_ID();

    return _likebtn_get_markup($entity_name, $entity_id, $args, '', false, false);
}

add_shortcode('likebtn', 'likebtn_shortcode');

################
###  Widget  ###
################
require_once(dirname(__FILE__) . '/likebtn_like_button_most_liked_widget.class.php');

// most liked short code
function likebtn_most_liked_widget_shortcode($args) {

    global $LikeBtnLikeButtonMostLiked;
    $options = $args;

    if (isset($options['number'])) {
        $options['number'] = (int) $options['number'];
    }
    $options['entity_name'] = array();
    if (isset($options['content_types'])) {
        $options['entity_name'] = explode(',', $options['content_types']);
    }
    return $LikeBtnLikeButtonMostLiked->widget(null, $options, false);
}

add_shortcode('likebtn_most_liked', 'likebtn_most_liked_widget_shortcode');

// get current admin subpage
function _likebtn_get_subpage()
{
    $likebtn_entities = _likebtn_get_entities();
    $subpage = LIKEBTN_ENTITY_POST;

    if (!empty($_POST['likebtn_subpage']) && array_key_exists($_POST['likebtn_subpage'], $likebtn_entities) ) {
        $subpage = $_POST['likebtn_subpage'];
    } elseif (!empty($_GET['likebtn_subpage']) && array_key_exists($_GET['likebtn_subpage'], $likebtn_entities) ) {
        $subpage = $_GET['likebtn_subpage'];
    }
    return $subpage;
}

// Save BuddyPress Member Profile votes
function _likebtn_save_bp_member_votes($entity_id, $likes, $dislikes, $likes_minus_dislikes)
{
    global $wpdb;

    if (!_likebtn_is_bp_active()) {
        return false;
    }
    $bp_xprofile = $wpdb->get_row("
        SELECT id
        FROM ".$wpdb->prefix."bp_xprofile_data
        WHERE user_id = {$entity_id}
    ");

    if (!empty($bp_xprofile)) {
        if ($likes !== null) {
            if (count(bp_xprofile_get_meta($entity_id, LIKEBTN_BP_XPROFILE_OBJECT_TYPE, LIKEBTN_META_KEY_LIKES)) > 1) {
                bp_xprofile_delete_meta($entity_id, LIKEBTN_BP_XPROFILE_OBJECT_TYPE, LIKEBTN_META_KEY_LIKES);
                bp_xprofile_add_meta($entity_id, LIKEBTN_BP_XPROFILE_OBJECT_TYPE, LIKEBTN_META_KEY_LIKES, $likes, true);
            } else {
                bp_xprofile_update_meta($entity_id, LIKEBTN_BP_XPROFILE_OBJECT_TYPE, LIKEBTN_META_KEY_LIKES, $likes);
            }
        }
        if ($dislikes !== null) {
            if (count(bp_xprofile_get_meta($entity_id, LIKEBTN_BP_XPROFILE_OBJECT_TYPE, LIKEBTN_META_KEY_DISLIKES)) > 1) {
                bp_xprofile_delete_meta($entity_id, LIKEBTN_BP_XPROFILE_OBJECT_TYPE, LIKEBTN_META_KEY_DISLIKES);
                bp_xprofile_add_meta($entity_id, LIKEBTN_BP_XPROFILE_OBJECT_TYPE, LIKEBTN_META_KEY_DISLIKES, $dislikes, true);
            } else {
                bp_xprofile_update_meta($entity_id, LIKEBTN_BP_XPROFILE_OBJECT_TYPE, LIKEBTN_META_KEY_DISLIKES, $dislikes);
            }
        }
        if ($likes_minus_dislikes !== null) {
            if (count(bp_xprofile_get_meta($entity_id, LIKEBTN_BP_XPROFILE_OBJECT_TYPE, LIKEBTN_META_KEY_LIKES_MINUS_DISLIKES)) > 1) {
                bp_xprofile_delete_meta($entity_id, LIKEBTN_BP_XPROFILE_OBJECT_TYPE, LIKEBTN_META_KEY_LIKES_MINUS_DISLIKES);
                bp_xprofile_add_meta($entity_id, LIKEBTN_BP_XPROFILE_OBJECT_TYPE, LIKEBTN_META_KEY_LIKES_MINUS_DISLIKES, $likes_minus_dislikes, true);
            } else {
                bp_xprofile_update_meta($entity_id, LIKEBTN_BP_XPROFILE_OBJECT_TYPE, LIKEBTN_META_KEY_LIKES_MINUS_DISLIKES, $likes_minus_dislikes);
            }
        }
        return true;
    }

    return false;
}

// Delete BuddyPress Member Profile votes
function _likebtn_delete_bp_member_votes($entity_id)
{
    global $wpdb;

    if (!_likebtn_is_bp_active()) {
        return false;
    }
    $bp_xprofile = $wpdb->get_row("
        SELECT id
        FROM ".$wpdb->prefix."bp_xprofile_data
        WHERE user_id = {$entity_id}
    ");

    if (!empty($bp_xprofile)) {
        bp_xprofile_delete_meta($entity_id, LIKEBTN_BP_XPROFILE_OBJECT_TYPE, LIKEBTN_META_KEY_LIKES);
        bp_xprofile_delete_meta($entity_id, LIKEBTN_BP_XPROFILE_OBJECT_TYPE, LIKEBTN_META_KEY_DISLIKES);
        bp_xprofile_delete_meta($entity_id, LIKEBTN_BP_XPROFILE_OBJECT_TYPE, LIKEBTN_META_KEY_LIKES_MINUS_DISLIKES);
        return true;
    }

    return false;
}

// Save user votes
function _likebtn_save_user_votes($entity_id, $likes, $dislikes, $likes_minus_dislikes)
{
    global $wpdb;

    $user = get_user_by('id', $entity_id); 

    if (!empty($user)) {
        if ($likes !== null) {
            if (count(get_user_meta($entity_id, LIKEBTN_META_KEY_LIKES)) > 1) {
                delete_user_meta($entity_id, LIKEBTN_META_KEY_LIKES);
                add_user_meta($entity_id, LIKEBTN_META_KEY_LIKES, $likes, true);
            } else {
                update_user_meta($entity_id, LIKEBTN_META_KEY_LIKES, $likes);
            }
        }
        if ($dislikes !== null) {
            if (count(get_user_meta($entity_id, LIKEBTN_META_KEY_DISLIKES)) > 1) {
                delete_user_meta($entity_id, LIKEBTN_META_KEY_DISLIKES);
                add_user_meta($entity_id, LIKEBTN_META_KEY_DISLIKES, $dislikes, true);
            } else {
                update_user_meta($entity_id, LIKEBTN_META_KEY_DISLIKES, $dislikes);
            }
        }
        if ($likes_minus_dislikes !== null) {
            if (count(get_user_meta($entity_id, LIKEBTN_META_KEY_LIKES_MINUS_DISLIKES)) > 1) {
                delete_user_meta($entity_id, LIKEBTN_META_KEY_LIKES_MINUS_DISLIKES);
                add_user_meta($entity_id, LIKEBTN_META_KEY_LIKES_MINUS_DISLIKES, $likes_minus_dislikes, true);
            } else {
                update_user_meta($entity_id, LIKEBTN_META_KEY_LIKES_MINUS_DISLIKES, $likes_minus_dislikes);
            }
        }
        return true;
    }

    return false;
}

// Delete user votes
function _likebtn_delete_user_votes($entity_id)
{
    global $wpdb;

    $user = get_user_by('id', $entity_id); 

    if (!empty($user)) {
        delete_user_meta($entity_id, LIKEBTN_META_KEY_LIKES);
        delete_user_meta($entity_id, LIKEBTN_META_KEY_DISLIKES);
        delete_user_meta($entity_id, LIKEBTN_META_KEY_LIKES_MINUS_DISLIKES);
        return true;
    }

    return false;
}


################
### Frontend ###
################

function _likebtn_get_markup($entity_name, $entity_id, $values = null, $use_entity_name = '', $use_entity_settings = true, $wrap = true) 
{
    global $wp_version;
    global $likebtn_map_entities;
    global $likebtn_settings_deprecated;

    $prepared_settings = array();

    if (!$use_entity_name) {
        $use_entity_name = $entity_name;
    }

    // Cut excerpt flag from entity_name
    if ($entity_id !== 'demo') {
        $entity_name = _likebtn_cut_list_flag($entity_name);
    }

    if ($values && isset($values['identifier']) && $values['identifier'] !== '') {
        $data = ' data-identifier="' . $values['identifier'] . '" ';
    } else {
        $identifier = $entity_name;
        if (!empty($likebtn_map_entities[$entity_name])) {
            $identifier = $likebtn_map_entities[$entity_name];
        }
        $data = ' data-identifier="' . $identifier . '_' . $entity_id . '" ';
    }

    // Site ID
    if (get_option('likebtn_site_id')) {
        $data .= ' data-site_id="' . get_option('likebtn_site_id') . '" ';
    }

    $likebtn_settings = _likebtn_get_all_settings();
    foreach ($likebtn_settings as $option_name => $option_info) {

        if ($values && isset($values[$option_name])) {
            // if values passed
            $option_value = $values[$option_name];
        } elseif (!$use_entity_settings && !in_array($option_name, $likebtn_settings_deprecated)) {
            // Do not use entity value - use default. Usually in shortcodes.
            $option_value = $option_info['default'];
        } else {
            $option_value = get_option('likebtn_settings_' . $option_name . '_' . $use_entity_name);
        }

        $option_value_prepared = _likebtn_prepare_option($option_name, $option_value);
        $prepared_settings[$option_name] = $option_value_prepared;

        // do not add option if it has default value
        if ((isset($likebtn_settings[$option_name]['default']) && $option_value == $likebtn_settings[$option_name]['default']) ||
            //$option_value === '' || is_bool($option_value)
            ($option_value === '' && (isset($likebtn_settings[$option_name]['default']) && $likebtn_settings[$option_name]['default'] == '0'))
        ) {
            // option has default value
        } else {
            $data .= ' data-' . $option_name . '="' . $option_value_prepared . '" ';
        }
    }

    // Add item options
    $entity = null;
    $entity_url = '';
    $entity_title = '';
    $entity_image = '';

    if ($entity_name == LIKEBTN_ENTITY_COMMENT) {
        $entity = get_comment($entity_id);
        if ($entity) {
            $entity_url = get_comment_link($entity->comment_ID);
            $entity_title = $entity->comment_content;
        }
    } else if (in_array($entity_name, array(LIKEBTN_ENTITY_BP_ACTIVITY_POST, LIKEBTN_ENTITY_BP_ACTIVITY_UPDATE, LIKEBTN_ENTITY_BP_ACTIVITY_COMMENT, LIKEBTN_ENTITY_BP_ACTIVITY_TOPIC))) {
        $entity_title = _likebtn_bp_get_activity_title($entity_id);
        if (function_exists('bp_activity_get_permalink')) {
            $entity_url = bp_activity_get_permalink($entity_id);
        }
    } else if ($entity_name == LIKEBTN_ENTITY_BP_MEMBER) {
        if (function_exists('bp_core_get_username')) {
            $entity_title = bp_core_get_username($entity_id);
        }
        if (function_exists('bp_core_get_user_domain')) {
            $entity_url = bp_core_get_user_domain($entity_id);
        }
    } else {
        $entity = get_post($entity_id);
        if ($entity) {
            $entity_url = get_permalink($entity->ID);
            $entity_title = $entity->post_title;
            $entity_image_url = wp_get_attachment_image_src(get_post_thumbnail_id($entity->ID), 'large');
            if (!empty($entity_image_url[0])) {
                $entity_image = $entity_image_url[0];
            }
        }
    }

    if ($entity_url && !$prepared_settings['item_url']) {
        $data .= ' data-item_url="' . $entity_url . '" ';
    }
    if ($entity_title && !$prepared_settings['item_title']) {
        $entity_title = strip_shortcodes($entity_title);
        $entity_title = preg_replace('/\s+/', ' ', $entity_title);
        $entity_title = htmlspecialchars($entity_title);

        $data .= ' data-item_title="' . $entity_title . '" ';
    }
    if ($entity_image && !$prepared_settings['item_image']) {
        $data .= ' data-item_image="' . $entity_image . '" ';
    }

    // Set engine and plugin info
    $data .= ' data-engine="WordPress" ';
    $data .= ' data-engine_v="' . $wp_version . '" ';
    $plugin_v = LIKEBTN_VERSION;
    if ($plugin_v) {
        $data .= ' data-plugin_v="' . $plugin_v . '" ';
    }

    $public_url = _likebtn_get_public_url();

    $markup = <<<MARKUP
<!-- LikeBtn.com BEGIN --><span class="likebtn-wrapper" {$data}></span><script>(function(d, e, s) {a = d.createElement(e);m = d.getElementsByTagName(e)[0];a.async = 1;a.src = s;m.parentNode.insertBefore(a, m)})(document, 'script', '/wp-content/plugins/likebtn-like-button/widget.js');
if (typeof(LikeBtn) != "undefined") { LikeBtn.init(); }</script><!-- LikeBtn.com END -->
MARKUP;

    // HTML before
    $html_before = '';
    if (isset($values['html_before'])) {
        $html_before = $values['html_before'];
    } elseif (get_option('likebtn_html_before_' . $use_entity_name)) {
        $html_before = get_option('likebtn_html_before_' . $use_entity_name);
    }
    if (trim($html_before)) {
        $markup = $html_before . $markup;
    }

    // HTML after
    $html_after = '';
    if (isset($values['html_after'])) {
        $html_after = $values['html_after'];
    } elseif (get_option('likebtn_html_after_' . $use_entity_name)) {
        $html_after = get_option('likebtn_html_after_' . $use_entity_name);
    }
    if (trim($html_after)) {
        $markup = $markup . $html_after;
    }

    if ($wrap) {
        if (get_option('likebtn_wrap_' . $use_entity_name) != '1') {
            $wrap = false;
        }
    }

    if ($wrap) {
        $alignment = get_option('likebtn_alignment_' . $use_entity_name);
        $newline = get_option('likebtn_newline_' . $use_entity_name);

        $style = '';

        if ($newline == '1') {
            $style .= 'clear:both;';
        }

        if ($alignment == LIKEBTN_ALIGNMENT_RIGHT) {
            $style .= 'text-align:right;';
            //$markup = '<div class="fs_likebtn_container" style="'.$style.'">' . $markup . '</div>';
        } elseif ($alignment == LIKEBTN_ALIGNMENT_CENTER) {
            $style .= 'text-align:center;';
           // $markup = '<div class="fs_likebtn_container" style="'.$style.'">' . $markup . '</div>';
        } else {
            //$markup = '<div class="fs_likebtn_container" style="'.$style.'">' . $markup . '</div>';
        }
    }

    return $markup;
}

// prepare option value
function _likebtn_prepare_option($option_name, $option_value)
{
    global $likebtn_settings;

    $option_value_prepared = $option_value;

    // do not format i18n options
    if (!strstr($option_name, 'i18n') &&
       (!isset($likebtn_settings[$option_name]) || $likebtn_settings[$option_name]['default'] !== ''))
    {
        if (is_int($option_value)) {
            if ($option_value) {
                $option_value_prepared = 'true';
            } else {
                $option_value_prepared = 'false';
            }
        }
        if ($option_value === '1') {
            $option_value_prepared = 'true';
        }
        if ($option_value === '0' || $option_value === '') {
            $option_value_prepared = 'false';
        }
    }

    // Replace quotes with &quot; to avoid XSS.
    //$option_value_prepared = str_replace('"', '&quot;', $option_value_prepared);
    $option_value_prepared = htmlspecialchars($option_value_prepared);

    return $option_value_prepared;
}

// get Entity settings
function _likebtn_get_entity_settings($entity_name) {

    global $likebtn_settings;
    $settings = array();

    foreach ($likebtn_settings as $option_name => $option_info) {
        $settings[$option_name] = get_option('likebtn_settings_' . $option_name . '_' . $entity_name);
    }
    return $settings;
}

// add Like Button to content
function likebtn_get_content($content, $callback_content_position = '') {

    global $likebtn_no_excerpts;

    if (is_feed()) {
        return $content;
    }

    // detemine entity type
    $real_entity_name = get_post_type();

    // Excerpt mode
    if ($real_entity_name == LIKEBTN_ENTITY_PAGE) {
        // page
        if (!is_page()) {
            $real_entity_name = $real_entity_name . LIKEBTN_LIST_FLAG;
        }
    } else {
        // everything else
        if (!is_single()) {
            if (!in_array($real_entity_name, $likebtn_no_excerpts)) {
                $real_entity_name = $real_entity_name . LIKEBTN_LIST_FLAG;
            }
        }
    }

    // get entity name whose settings should be copied
    $use_entity_name = get_option('likebtn_use_settings_from_' . $real_entity_name);
    if ($use_entity_name) {
        $entity_name = $use_entity_name;
    } else {
        $entity_name = $real_entity_name;
    }

    if (get_option('likebtn_show_' . $real_entity_name) != '1'
        || get_option('likebtn_show_' . $entity_name) != '1')
    {
        return $content;
    }

    $entity_id = get_the_ID();    

    // get the Posts/Pages IDs where we do not need to show like functionality
    $allow_ids = explode(",", get_option('likebtn_allow_ids_' . $entity_name));
    $exclude_ids = explode(",", get_option('likebtn_exclude_ids_' . $entity_name));
    $exclude_categories = get_option('likebtn_exclude_categories_' . $entity_name);
    $exclude_sections = get_option('likebtn_exclude_sections_' . $entity_name);

    if (empty($exclude_categories)) {
        $exclude_categories = array();
    }

    if (empty($exclude_sections)) {
        $exclude_sections = array();
    }

    // checking if section is excluded
    if ((in_array('home', $exclude_sections) && is_home()) || 
        (in_array('archive', $exclude_sections) && is_archive() && !is_category()) || 
        (in_array('search', $exclude_sections) && is_search()) || 
        (in_array('category', $exclude_sections) && is_category())
    ) {
        return $content;
    }

    // checking if category is excluded
    $categories = get_the_category();
    foreach ($categories as $category) {
        if (in_array($category->cat_ID, $exclude_categories) && !in_array($entity_id, $allow_ids)) {
            return $content;
        }
    }

    // check if post is excluded
    if (in_array($entity_id, $exclude_ids)) {
        return $content;
    }

    // check Post view mode
    /*switch (get_option('likebtn_post_view_mode_' . $entity_name)) {
        case LIKEBTN_POST_VIEW_MODE_FULL:
            if (!is_single()) {
                return $content;
            }
            break;
        case LIKEBTN_POST_VIEW_MODE_EXCERPT:
            if (is_single()) {
                return $content;
            }
            break;
    }*/

    // check Post format
    $post_format = get_post_format($entity_id);
    if (!$post_format) {
        $post_format = 'standard';
    }

    if (!in_array('all', get_option('likebtn_post_format_' . $entity_name)) &&
            !in_array($post_format, get_option('likebtn_post_format_' . $entity_name))
    ) {
        return $content;
    }

    // check user authorization
    $user_logged_in = get_option('likebtn_user_logged_in_' . $entity_name);

    if ($user_logged_in === '1' || $user_logged_in === '0') {
        if ($user_logged_in == '1' && !is_user_logged_in()) {
            return $content;
        }
        if ($user_logged_in == '0' && is_user_logged_in()) {
            return $content;
        }
    }

    $html = _likebtn_get_markup($real_entity_name, $entity_id, array(), $entity_name);

    $position = get_option('likebtn_position_' . $entity_name);

    if ($callback_content_position && function_exists($callback_content_position)) {
        $content = call_user_func($callback_content_position, $content, $html, $position);
    } else {
        if ($position == LIKEBTN_POSITION_TOP) {
            $content = $html . $content;
        } elseif ($position == LIKEBTN_POSITION_BOTTOM) {
            $content = $content . $html;
        } else {
            $content = $html . $content . $html;
        }
    }

    return $content;
}

// add Like Button to the entity (except Comment)
function likebtn_the_content($content) {
    if (get_post_type() == LIKEBTN_ENTITY_PRODUCT) {
        return $content;
    }

    $content = likebtn_get_content($content);
    return $content;
}
add_filter('the_content', 'likebtn_the_content');
add_filter('the_excerpt', 'likebtn_the_content');

// WooCommerce - top and button
function likebtn_woocommerce_product($content) {
    $content = likebtn_get_content($content);
    echo $content;
}
// WooCommerce - top
function likebtn_woocommerce_product_top($content) {
    $content = likebtn_get_content($content, '_likebtn_woocommerce_content_top');
    echo $content;
}
function _likebtn_woocommerce_content_top($content, $html, $position) {
    // WooCommerce
    if ($position == LIKEBTN_POSITION_BOTTOM) {
        return $content;
    } else {
        return $html . $content;
    }
}
// WooCommerce - bottom
function likebtn_woocommerce_after_main_content($content) {
    if (!is_single()) {
        return false;
    }
    $content = likebtn_get_content($content, '_likebtn_woocommerce_content_bottom');
    echo $content;
}
function likebtn_woocommerce_product_bottom($content) {
    $content = likebtn_get_content($content, '_likebtn_woocommerce_content_bottom');
    echo $content;
}
function _likebtn_woocommerce_content_bottom($content, $html, $position) {
    // WooCommerce
    if ($position == LIKEBTN_POSITION_TOP) {
        return $content;
    } else {
        return $html . $content;
    }
}

add_action('woocommerce_single_product_summary', 'likebtn_woocommerce_product_top', 7);
add_action('woocommerce_after_main_content', 'likebtn_woocommerce_after_main_content', 7);
add_action('woocommerce_after_shop_loop_item_title', 'likebtn_woocommerce_product_top', 7);
add_action('woocommerce_after_shop_loop_item_title', 'likebtn_woocommerce_product_bottom', 12);

// add Like Button to the Comment
function likebtn_comment_text($content) {

    global $comment;
    
    // Add like button only if comment is rendered from the list and not from sidebar widget
    if (!_likebtn_has_caller('wp_list_comments')) {
        return $content;
    }

    if (is_feed()) {
        return $content;
    }

    // detemine entity type
    $real_entity_name = LIKEBTN_ENTITY_COMMENT;

    // get entity name whose settings should be copied
    $use_entity_name = get_option('likebtn_use_settings_from_' . $real_entity_name);
    if ($use_entity_name) {
        $entity_name = $use_entity_name;
    } else {
        $entity_name = $real_entity_name;
    }

    if (get_option('likebtn_show_' . $real_entity_name) != '1'
        || get_option('likebtn_show_' . $entity_name) != '1')
    {
        return $content;
    }

    $comment_id = $comment->comment_ID;
    //$comment = get_comment($comment_id, ARRAY_A);
    $post_id = $comment->comment_post_ID;

    // get the Posts/Pages IDs where we do not need to show like functionality
    $allow_ids = explode(",", get_option('likebtn_allow_ids_' . $entity_name));
    $exclude_ids = explode(",", get_option('likebtn_exclude_ids_' . $entity_name));
    $exclude_categories = get_option('likebtn_exclude_categories_' . $entity_name);
    $exclude_sections = get_option('likebtn_exclude_sections_' . $entity_name);

    if (empty($exclude_categories)) {
        $exclude_categories = array();
    }

    if (empty($exclude_sections)) {
        $exclude_sections = array();
    }

    // checking if section is excluded
    if ((in_array('home', $exclude_sections) && is_home()) || (in_array('archive', $exclude_sections) && is_archive())) {
        return $content;
    }

    // checking if category is excluded
    $categories = get_the_category();
    foreach ($categories as $category) {
        if (in_array($category->cat_ID, $exclude_categories) && !in_array($post_id, $allow_ids)) {
            return $content;
        }
    }

    // check if post is excluded
    if (in_array($post_id, $exclude_ids)) {
        return $content;
    }

    // check Post view mode - no need
    // check Post format
    $post_format = get_post_format($post_id);
    if (!$post_format) {
        $post_format = 'standard';
    }

    if (!in_array('all', get_option('likebtn_post_format_' . $entity_name)) &&
            !in_array($post_format, get_option('likebtn_post_format_' . $entity_name))
    ) {
        return $content;
    }

    $html = _likebtn_get_markup($real_entity_name, $comment_id, array(), $entity_name);

    $position = get_option('likebtn_position_' . $entity_name);

    if ($position == LIKEBTN_POSITION_TOP) {
        $content = $html . $content;
    } elseif ($position == LIKEBTN_POSITION_BOTTOM) {
        $content = $content . $html;
    } else {
        $content = $html . $content . $html;
    }

    return $content;
}

add_filter('comment_text', 'likebtn_comment_text');

// show the Like Button in Post/Page
// if Like Button is enabled in admin for Post/Page do not show button twice
function likebtn_post($post_id = null, $values = null) {
    global $post;
    if (empty($post_id)) {
        $post_id = $post->ID;
    }

    // detemine entity type
    /*if (is_page()) {
        $entity_name = LIKEBTN_ENTITY_PAGE;
    } else {*/
    if (!empty($post->post_type)) {
        $entity_name = $post->post_type;
    } else {
        $entity_name = LIKEBTN_ENTITY_POST;
    }
    //}

    // check if the Like Button should be displayed
    // if Like Button enabled for Post or Page in Admin do not show Like Button twice
    /*if ($entity_name == LIKEBTN_ENTITY_POST && get_option('likebtn_show_' . LIKEBTN_ENTITY_POST) == '1') {
        return;
    }
    if ($entity_name == LIKEBTN_ENTITY_PAGE && get_option('likebtn_show_' . LIKEBTN_ENTITY_PAGE) == '1') {
        return;
    }*/

    // 'post' here is for the sake of backward compatibility
    $html = _likebtn_get_markup($entity_name, $post_id, $values);

    echo $html;
}

// get or echo the Like Button in comment
function likebtn_comment($comment_id = NULL, $values = null) {
    //global $comment;
    if (empty($comment_id)) {
        $comment_id = get_comment_ID();
    }

    // if Like Button enabled for Comment in Admin do not show Like Button twice
    /*if (get_option('likebtn_show_' . LIKEBTN_ENTITY_COMMENT) == '1') {
        return;
    }*/

    $html = _likebtn_get_markup(LIKEBTN_ENTITY_COMMENT, $comment_id, $values);

    echo $html;
}

// Show the Like Button in WooCommerce Product
// if Like Button is enabled in admin do not show button twice
function likebtn_woocommerce($post_id = NULL, $values = null) {
    global $post;

    if (empty($post_id)) {
        $post_id = $post->ID;
    }

    // check if the Like Button should be displayed
    // if Like Button enabled in Admin do not show Like Button twice
    /*if (get_option('likebtn_show_' . LIKEBTN_ENTITY_PRODUCT) == '1') {
        return;
    }*/

    $html = _likebtn_get_markup(LIKEBTN_ENTITY_PRODUCT, $post_id, $values);

    echo $html;
}

// test synchronization callback
function likebtn_manual_sync_callback() {

    $likebtn_account_email = '';
    if (isset($_POST['likebtn_account_email'])) {
        $likebtn_account_email = trim($_POST['likebtn_account_email']);
    }

    $likebtn_account_api_key = '';
    if (isset($_POST['likebtn_account_api_key'])) {
        $likebtn_account_api_key = trim($_POST['likebtn_account_api_key']);
    }

    $likebtn_site_id = '';
    if (isset($_POST['likebtn_site_id'])) {
        $likebtn_site_id = trim($_POST['likebtn_site_id']);
    }

    require_once(dirname(__FILE__) . '/likebtn_like_button.class.php');
    $likebtn = new LikeBtnLikeButton();
    $sync_response = $likebtn->syncVotes($likebtn_account_email, $likebtn_account_api_key, $likebtn_site_id, true);

    if ($sync_response['result'] == 'success') {
        $result_text = __('OK', LIKEBTN_I18N_DOMAIN);
    } else {
        $result_text = __('Error', LIKEBTN_I18N_DOMAIN);
    }

    $response = array(
        'result' => $sync_response['result'],
        'result_text' => $result_text,
        'message' => $sync_response['message'],
    );

    if (!DOING_AJAX) {
        define('DOING_AJAX', true);
    }
    if (ob_get_contents()) {
        ob_clean();
    }
    _likebtn_send_json($response);
}

add_action('wp_ajax_likebtn_manual_sync', 'likebtn_manual_sync_callback');

// test synchronization callback
function likebtn_test_sync_callback() {

    $likebtn_account_email = '';
    if (isset($_POST['likebtn_account_email'])) {
        $likebtn_account_email = trim($_POST['likebtn_account_email']);
    }
    
    $likebtn_account_api_key = '';
    if (isset($_POST['likebtn_account_api_key'])) {
        $likebtn_account_api_key = trim($_POST['likebtn_account_api_key']);
    }

    $likebtn_site_id = '';
    if (isset($_POST['likebtn_site_id'])) {
        $likebtn_site_id = trim($_POST['likebtn_site_id']);
    }

    require_once(dirname(__FILE__) . '/likebtn_like_button.class.php');
    $likebtn = new LikeBtnLikeButton();
    $test_response = $likebtn->testSync($likebtn_account_email, $likebtn_account_api_key, $likebtn_site_id);

    if ($test_response['result'] == 'success') {
        $result_text = __('OK', LIKEBTN_I18N_DOMAIN);
    } else {
        $result_text = __('Error', LIKEBTN_I18N_DOMAIN);
    }

    $response = array(
        'result' => $test_response['result'],
        'result_text' => $result_text,
        'message' => $test_response['message'],
    );

    if (!DOING_AJAX) {
        define('DOING_AJAX', true);
    }
    if (ob_get_contents()) {
        ob_clean();
    }
    _likebtn_send_json($response);
}

add_action('wp_ajax_likebtn_test_sync', 'likebtn_test_sync_callback');

// test synchronization callback
function likebtn_check_account_callback() {

    $likebtn_account_email = '';
    if (isset($_POST['likebtn_account_email'])) {
        $likebtn_account_email = $_POST['likebtn_account_email'];
    }

    $likebtn_account_api_key = '';
    if (isset($_POST['likebtn_account_api_key'])) {
        $likebtn_account_api_key = $_POST['likebtn_account_api_key'];
    }

    $likebtn_site_id = '';
    if (isset($_POST['likebtn_site_id'])) {
        $likebtn_site_id = trim($_POST['likebtn_site_id']);
    }

    require_once(dirname(__FILE__) . '/likebtn_like_button.class.php');
    $likebtn = new LikeBtnLikeButton();
    $test_response = $likebtn->checkAccount($likebtn_account_email, $likebtn_account_api_key, $likebtn_site_id);

    if ($test_response['result'] == 'success') {
        $result_text = __('OK', LIKEBTN_I18N_DOMAIN);
    } else {
        $result_text = __('Error', LIKEBTN_I18N_DOMAIN);
    }

    $response = array(
        'result' => $test_response['result'],
        'result_text' => $result_text,
        'message' => $test_response['message'],
    );

    if (!DOING_AJAX) {
        define('DOING_AJAX', true);
    }
    if (ob_get_contents()) {
        ob_clean();
    }
    _likebtn_send_json($response);
}

add_action('wp_ajax_likebtn_check_account', 'likebtn_check_account_callback');

// edit item callback
function likebtn_edit_item_callback() {

    $entity_name = '';
    if (isset($_POST['entity_name'])) {
        $entity_name = $_POST['entity_name'];
    }

    $entity_id = '';
    if (isset($_POST['entity_id'])) {
        $entity_id = $_POST['entity_id'];
    }

    $identifier = likebtn_get_identifier($entity_name, $entity_id);

    $type = '';
    if (isset($_POST['type'])) {
        $type = $_POST['type'];
    }

    $value = '';
    if (isset($_POST['value'])) {
        $value = $_POST['value'];
    }

    require_once(dirname(__FILE__) . '/likebtn_like_button.class.php');
    $likebtn = new LikeBtnLikeButton();
    $edit_response = $likebtn->edit($identifier, $type, $value);

    if ($edit_response['result'] == 'success') {
        $result_text = __('OK', LIKEBTN_I18N_DOMAIN);

        // Update custom fields
        if ($type == '1') {
            $likes = $value;
            $dislikes = null;
        } else {
            $dislikes = $value;
            $likes = null;
        }
        $likebtn->updateCustomFields($identifier, $likes, $dislikes);
    } else {
        $result_text = __('Error', LIKEBTN_I18N_DOMAIN);
    }

    $response = array(
        'result' => $edit_response['result'],
        'result_text' => $result_text,
        'message' => $edit_response['message'],
        'value' => $value
    );

    if (!DOING_AJAX) {
        define('DOING_AJAX', true);
    }
    if (ob_get_contents()) {
        ob_clean();
    }
    _likebtn_send_json($response);
}

add_action('wp_ajax_likebtn_edit_item', 'likebtn_edit_item_callback');

// test synchronization callback
function likebtn_refresh_plan_callback() {
    $html = '';
    $message = '';

    // run sunchronization
    require_once(dirname(__FILE__) . '/likebtn_like_button.class.php');
    $likebtn = new LikeBtnLikeButton();
    $refresh_response = $likebtn->syncPlan();

    if (empty($refresh_response['message'])) {
        $html = _likebtn_plan_html();
    }

    if (!empty($refresh_response['message'])) {
        $message = $refresh_response['message'];
    }

    $response = array(
        'html' => $html,
        'message' => $message,
        'reload' => (int)get_option('likebtn_notice_plan'),
    );

    if (!DOING_AJAX) {
        define('DOING_AJAX', true);
    }
    if (ob_get_contents()) {
        ob_clean();
    }
    _likebtn_send_json($response);
}

add_action('wp_ajax_likebtn_refresh_plan', 'likebtn_refresh_plan_callback');

// reset items likes/dislikes
function _likebtn_reset($entity_name, $items)
{
    $counter = 0;

    if (empty($entity_name) || empty($items)) {
        return false;
    }

    require_once(dirname(__FILE__) . '/likebtn_like_button.class.php');
    $likebtn = new LikeBtnLikeButton();

    // prepare an array for resettings in the CMS
    $reset_list = array();
    $reset_list['response'] = array('items'=>array());

    foreach ($items as $item_identifier) {
        if ($entity_name != LIKEBTN_ENTITY_CUSTOM_ITEM) {
            $identifier = $entity_name . '_' . $item_identifier;
        } else {
            $identifier = $item_identifier;
        }
        // reset votes in LikeBtn
        $likebtn_result = $likebtn->reset($identifier);
        $reset_list['response']['items'][] = array(
            'identifier' => $identifier,
            'likes'      => 0,
            'dislikes'   => 0
        );
        if ($likebtn_result) {
            $counter++;
        }
    }

    if ($reset_list) {
        $likebtn->updateVotes($reset_list);
    }
    return $counter;
}

// delete items
function _likebtn_delete($entity_name, $items)
{
    $counter = 0;

    if (empty($entity_name) || empty($items)) {
        return false;
    }

    require_once(dirname(__FILE__) . '/likebtn_like_button.class.php');
    $likebtn = new LikeBtnLikeButton();

    // prepare an array for resettings in the CMS
    $list = array();
    $list['response'] = array('items'=>array());

    foreach ($items as $item_identifier) {
        if ($entity_name != LIKEBTN_ENTITY_CUSTOM_ITEM) {
            $identifier = $entity_name . '_' . $item_identifier;
        } else {
            $identifier = $item_identifier;
        }
        // delete votes in LikeBtn
        $likebtn_result = $likebtn->delete($identifier);
        $list['response']['items'][] = array(
            'identifier' => $identifier
        );
        if ($likebtn_result) {
            $counter++;
        }
    }

    if ($list) {
        $likebtn->deleteVotes($list);
    }
    return $counter;
}

// get entity identifier
function likebtn_get_identifier($entity_name, $entity_id)
{
    if ($entity_name == LIKEBTN_ENTITY_CUSTOM_ITEM) {
        $identifier = $entity_id;
    } else {
        $identifier = $entity_name . '_' . $entity_id;    
    }
    
    return $identifier;
}

// get comments sorted by likes
function likebtn_comments_sorted_by_likes()
{
    // function for sorting comments by Likes
    function sort_comments_by_likes($comment_a, $comment_b)
    {
        $comment_a_meta = get_comment_meta($comment_a->comment_ID, LIKEBTN_META_KEY_LIKES);
        $comment_b_meta = get_comment_meta($comment_b->comment_ID, LIKEBTN_META_KEY_LIKES);

        $comment_a_likes = (int)$comment_a_meta[0];
        $comment_b_likes = (int)$comment_b_meta[0];

        if ($comment_a_likes > $comment_b_likes) {
            return -1;
        } elseif ($comment_a_likes < $comment_b_likes) {
            return 1;
        }
        return 0;
    }

    global $wp_query;
    $comments = $wp_query->comments;
    usort($comments, 'sort_comments_by_likes');

    return $comments;
}

// get comments sorted by dislikes
function likebtn_comments_sorted_by_dislikes()
{
    // function for sorting comments by Likes
    function sort_comments_by_dislikes($comment_a, $comment_b)
    {
        $comment_a_meta = get_comment_meta($comment_a->comment_ID, LIKEBTN_META_KEY_DISLIKES);
        $comment_b_meta = get_comment_meta($comment_b->comment_ID, LIKEBTN_META_KEY_DISLIKES);

        $comment_a_dislikes = (int)$comment_a_meta[0];
        $comment_b_dislikes = (int)$comment_b_meta[0];

        if ($comment_a_dislikes > $comment_b_dislikes) {
            return -1;
        } elseif ($comment_a_dislikes < $comment_b_dislikes) {
            return 1;
        }
        return 0;
    }

    global $wp_query;
    $comments = $wp_query->comments;
    usort($comments, 'sort_comments_by_dislikes');

    return $comments;
}

// Get the permalink for a comment on another blog.
function _likebtn_get_blog_comment_link( $blog_id, $comment_id ) {
	switch_to_blog( $blog_id );
	$link = get_comment_link( $comment_id );
	restore_current_blog();

	return $link;
}

// Converts entity name to title
function _likebtn_get_entity_title($entity_name)
{
    global $likebtn_entity_titles;

    $title = '';
    $is_excerpt = false;
    if (_likebtn_has_list_flag($entity_name)) {
        $is_excerpt = true;
    }

    if (!array_key_exists($entity_name, $likebtn_entity_titles)) {

        $entity_name = _likebtn_cut_list_flag($entity_name);

        $title = __(str_replace('_', ' ', ucfirst($entity_name)));
        if ($is_excerpt) {
            $title .= ' ' . __('List');
        }
    } else {
        $title = __($likebtn_entity_titles[$entity_name]);
    }

    return $title;
}

// Check if entity has a list flag
function _likebtn_has_list_flag($entity_name)
{
    if (strstr($entity_name, LIKEBTN_LIST_FLAG)) {
        return true;
    } else {
        return false;
    }
}

// Cut list flag from entity name
function _likebtn_cut_list_flag($entity_name)
{
    return str_replace(LIKEBTN_LIST_FLAG, '', $entity_name);
}

// Check if BuddyPress is installed and active
function _likebtn_is_bp_active()
{
    if (function_exists('bp_is_active') && bp_is_active()) {
        return true;
    } else {
        return false;
    }
}

// add Like Button to content
function _likebtn_get_content_universal($real_entity_name, $entity_id, $content = '', $wrap = true, $current_position = '', $current_alignment = array())
{
    // get entity name whose settings should be copied
    $use_entity_name = get_option('likebtn_use_settings_from_' . $real_entity_name);
    if ($use_entity_name) {
        $entity_name = $use_entity_name;
    } else {
        $entity_name = $real_entity_name;
    }

    if (get_option('likebtn_show_' . $real_entity_name) != '1'
        || get_option('likebtn_show_' . $entity_name) != '1')
    {
        return '';
    }

    // check user authorization
    $user_logged_in = get_option('likebtn_user_logged_in_' . $entity_name);

    if ($user_logged_in === '1' || $user_logged_in === '0') {
        if ($user_logged_in == '1' && !is_user_logged_in()) {
            return '';
        }
        if ($user_logged_in == '0' && is_user_logged_in()) {
            return '';
        }
    }

    $html = _likebtn_get_markup($real_entity_name, $entity_id, array(), $entity_name, true, $wrap);

    $position = get_option('likebtn_position_' . $entity_name);
    if ($current_position && $position != LIKEBTN_POSITION_BOTH && $current_position != $position) {
        return $content;
    }

    $alignment = get_option('likebtn_alignment_' . $entity_name);
    if ($current_alignment && !in_array($alignment, $current_alignment)) {
        return $content;
    }
    if ($content) {
        if ($position == LIKEBTN_POSITION_TOP) {
            $html = $html . $content;
        } elseif ($position == LIKEBTN_POSITION_BOTTOM) {
            $html = $content . $html;
        } else {
            $html = $html . $content . $html;
        }
    }

    return $html;
}

// BuddyPress member profile
function likebtn_bp_member()
{
    $content = _likebtn_get_content_universal(LIKEBTN_ENTITY_BP_MEMBER, buddypress()->displayed_user->id);
    echo $content;
}
// User profile page.
add_action('bp_before_member_header_meta', 'likebtn_bp_member');

// BuddyPress activity
function _likebtn_bp_activity($wrap = true, $position = LIKEBTN_POSITION_BOTH, $content = '') {
    $entity_id = '';

    $entity_name = _likebtn_bp_get_entity_name();

    //if ($entity_name == LIKEBTN_ENTITY_BP_ACTIVITY_UPDATE || $entity_name == LIKEBTN_ENTITY_BP_ACTIVITY_COMMENT) {
    $entity_id = bp_get_activity_id();
    /*} else {
        $entity_id = bp_get_activity_secondary_item_id();
    }*/

    /*if ($entity_name == LIKEBTN_ENTITY_BP_ACTIVITY_TOPIC) {
                    echo 'entity_id'.$entity_id;
        // Get bbPress topic id
        if (function_exists("bb_get_first_post")) {
            $post = bb_get_first_post($entity_id);
        } else {
            // We consider ppPress to be installed and enabled
            return false;
            // Get from DB
            /*global $bb_table_prefix;
            // Load bbPress config file
            @include_once(?);

            // Failed loading config file.
            if (!defined("BBDB_NAME"))
                return false;

            $connection = null;
            if (!$connection = mysql_connect(BBDB_HOST, BBDB_USER, BBDB_PASSWORD, true)){ 
                return false;
            }
            if (!mysql_selectdb(BBDB_NAME, $connection)){ 
                return false; 
            }
            $results = mysql_query("SELECT * FROM {$bb_table_prefix}posts WHERE topic_id={$entity_id} AND post_position=1", $connection);
            $post = mysql_fetch_object($results);/
        }

        if (empty($post->post_id)) {
            return false;
        }

        $entity_id = $post->post_id;
    }*/

    if (!$entity_name || !$entity_id) {
        return false;
    }

    echo _likebtn_get_content_universal($entity_name, $entity_id, $content, $wrap, $position);
}

// BuddyPress activity comment
function likebtn_bp_activity_comment($content) {

    global $activities_template;

    if (empty($activities_template) || empty($activities_template->activity) || empty($activities_template->activity->current_comment)) {
        return $content;
    }

    $entity_id = $activities_template->activity->current_comment->id;

    return _likebtn_get_content_universal(LIKEBTN_ENTITY_BP_ACTIVITY_COMMENT, $entity_id, $content);
}

// BuddyPress activity comment ajax - Read more
// bp_dtheme_get_single_activity_content()
function likebtn_bp_activity_comment_ajax($content) {

    global $activities_template;

    if (!empty($_POST['action']) && $_POST['action'] == 'get_single_activity_content' && !empty($_POST['activity_id'])) {
        // Ajax - read more
        // http://oik-plugins.eu/buddypress-a2z/oik_api/bp_activity_get_specific/
        $activity_array = bp_activity_get_specific( array(
            'activity_ids'     => $_POST['activity_id'],
            'display_comments' => 'stream'
        ) );
        $activity = ! empty( $activity_array['activities'][0] ) ? $activity_array['activities'][0] : false;
        
        if (!empty($activity) && !empty($activity->type) && $activity->type == 'activity_comment') {
            $entity_id = $_POST['activity_id'];

            return _likebtn_get_content_universal(LIKEBTN_ENTITY_BP_ACTIVITY_COMMENT, $entity_id, $content);
        }
    }

    return $content;
}

// BuddyPress activity top
function likebtn_bp_activity_top($content)
{
    return _likebtn_bp_activity(true, LIKEBTN_POSITION_TOP) . $content;
}

// BuddyPress activity bottom
function likebtn_bp_activity_bottom()
{
    return _likebtn_bp_activity(false, LIKEBTN_POSITION_BOTTOM);
}

// BuddyPress Fetches full an activity's full, non-excerpted content via a POST request.
// Used for the 'Read More' link on long activity items.
/*function likebtn_bp_get_single_activity_content($content) {
    global $activities_template;

    if (empty($activities_template) || empty($activities_template->activity) || empty($activities_template->activity->current_comment)) {
        return $content;
    }

    $entity_id = $activities_template->activity->current_comment->id;

    return _likebtn_get_content_universal(LIKEBTN_ENTITY_BP_ACTIVITY_COMMENT, $entity_id, $content);
}*/

// Activity page.
//add_action("bp_has_activities", array(&$this, "BuddyPressBeforeActivityLoop"));

// BuddyPress activity top
add_filter('bp_get_activity_action', 'likebtn_bp_activity_top');

// BuddyPress activity bottom
add_action('bp_activity_entry_meta', 'likebtn_bp_activity_bottom');

// BuddyPress activity comment
add_filter('bp_get_activity_content', 'likebtn_bp_activity_comment');
add_filter('bp_get_activity_content_body', 'likebtn_bp_activity_comment_ajax');

// BuddyPress Fetches full an activity's full, non-excerpted content via a POST request.
// Used for the 'Read More' link on long activity items.
//add_action('bp_dtheme_get_single_activity_content',       'likebtn_bp_get_single_activity_content');
//add_action('bp_legacy_theme_get_single_activity_content', 'likebtn_bp_get_single_activity_content');

// Forum topic page
add_filter('bp_has_topic_posts', 'likebtn_bp_activity');

// Get entity name of the current BuddyPress activity
// Not working for comments
function _likebtn_bp_get_entity_name()
{
    $activity_type = bp_get_activity_type();

    switch ($activity_type) {
        case 'bbp_topic_create':
            return LIKEBTN_ENTITY_BP_ACTIVITY_TOPIC;
        case 'new_blog_post':
            // Post
            return LIKEBTN_ENTITY_BP_ACTIVITY_POST;
        case 'new_member':
            return LIKEBTN_ENTITY_BP_ACTIVITY_UPDATE;
        default:
            return LIKEBTN_ENTITY_BP_ACTIVITY_UPDATE;
    }
}

// Get activity title
function _likebtn_bp_get_activity_title($id)
{
    $activity = null;
    $title = '';

    if (function_exists('bp_activity_get_specific')) {
        $activity_list = bp_activity_get_specific(array(
            'activity_ids' => $id,
            'display_comments' => true
        ));

        if (!empty($activity_list['activities']) && !empty($activity_list['activities'][0])) {
            $activity = $activity_list['activities'][0];
        }
    }
    if ($activity) {
        if ($activity->action) {
            $title = $activity->action;
        } elseif ($activity->content) {
            $title = $activity->content;
        } elseif ($activity->primary_link) {
            $title = $activity->primary_link;
        }
        if ($activity->content && $activity->type != 'bbp_topic_create') {
            $title = $title.': '.$activity->content;
        }
        $title = strip_tags($title);
    }
    return $title;
}

// Check if bbPress is installed and active
function _likebtn_is_bbp_active()
{
    if (function_exists('bbp_has_replies')) {
        return true;
    } else {
        return false;
    }
}

// bbPress
function likebtn_bbp_reply($wrap = true, $position = LIKEBTN_POSITION_BOTH, $alignment = '', $content = '') {

    // Reply to topic
    $entity = bbp_get_reply(bbp_get_reply_id());

    // Topic
    if (!is_object($entity)) {
        $entity = bbp_get_topic(bbp_get_topic_id());
    }
   

    if (!$entity) {
        return false;
    }

    return _likebtn_get_content_universal(LIKEBTN_ENTITY_BBP_POST, $entity->ID, $content, $wrap, $position, $alignment);
}

// bbPress reply top left & center
function likebtn_bbp_has_replies($has_replies)
{
    add_filter('bbp_theme_before_reply_admin_links', 'likebtn_bbp_reply_top_left');
    add_filter('bbp_theme_after_reply_admin_links', 'likebtn_bbp_reply_top_right');
    add_filter('bbp_get_reply_content', 'likebtn_bbp_reply_bottom');
    add_filter('bbp_get_reply_author_link', 'likebtn_bbp_author_link');

    return $has_replies;
}

// bbPress reply top left & center
function likebtn_bbp_reply_top_left()
{
    echo likebtn_bbp_reply(false, LIKEBTN_POSITION_TOP, array(LIKEBTN_ALIGNMENT_LEFT, LIKEBTN_ALIGNMENT_CENTER));
}

// bbPress reply top left & center
function likebtn_bbp_reply_top_right()
{
    echo likebtn_bbp_reply(false, LIKEBTN_POSITION_TOP, array(LIKEBTN_ALIGNMENT_RIGHT));
}

// bbPress reply bottom
function likebtn_bbp_reply_bottom($content)
{
    return $content.likebtn_bbp_reply(true, LIKEBTN_POSITION_BOTTOM);
}

// bbPress thread
function likebtn_bbp_author_link($author_link)
{
    global $post;

    $reply_id = bbp_get_reply_id($post->ID);
    if (bbp_is_reply_anonymous($reply_id)) {
        return $author_link;
    }
    
    $author_id = bbp_get_reply_author_id($reply_id);

    $content = _likebtn_get_content_universal(LIKEBTN_ENTITY_BBP_USER, $author_id);
    return $author_link . $content;
}

// bbPress reply bottom
function likebtn_bbp_user_profile()
{
    $content = _likebtn_get_content_universal(LIKEBTN_ENTITY_BBP_USER, bbpress()->displayed_user->ID);
    echo $content;
}


add_filter('bbp_has_replies', 'likebtn_bbp_has_replies');
add_action('bbp_template_after_user_profile', 'likebtn_bbp_user_profile');

// Add style to frontend
function likebtn_enqueue_style()
{
    $src = _likebtn_get_public_url() . 'css/style.css?ver=' . LIKEBTN_VERSION;
    wp_enqueue_style('likebtn_style', $src); 
}

// Add frontend style
add_action('wp_enqueue_scripts', 'likebtn_enqueue_style');

// Current + deprecated settings
function _likebtn_get_all_settings()
{
    global $likebtn_settings;
    global $likebtn_settings_deprecated;

    return array_merge($likebtn_settings, $likebtn_settings_deprecated);
}

// Prepare entity title
function _likebtn_prepare_title($entity_name, $title, $max = LIKEBTN_WIDGET_TITLE_LENGTH)
{
    $title = stripslashes($title);

    if (in_array($entity_name, array(LIKEBTN_ENTITY_BP_ACTIVITY_POST, LIKEBTN_ENTITY_BP_ACTIVITY_UPDATE, LIKEBTN_ENTITY_BP_ACTIVITY_COMMENT, LIKEBTN_ENTITY_BP_ACTIVITY_TOPIC))) {
        $title = strip_tags($title);
    }
    if (function_exists('qtrans_useCurrentLanguageIfNotFoundUseDefaultLanguage')) {
        $title = qtrans_useCurrentLanguageIfNotFoundUseDefaultLanguage($title);
    }
    /*if ($entity_name == LIKEBTN_ENTITY_COMMENT) {
        if (mb_strlen($title) > 30) {
            $title = mb_substr($title, 0, 30) . '...';
        }
    } else*/

    return _likebtn_shorten_title($title, $max);
}

// Shorten title
function _likebtn_shorten_title($title, $max = LIKEBTN_WIDGET_TITLE_LENGTH)
{
    if (mb_strlen($title) > $max) {
        $title = mb_substr($title, 0, $max) . '...';
    }
    return $title;
}

// Shorten title
function _likebtn_shorten_excerpt($excerpt, $max = LIKEBTN_WIDGET_EXCERPT_LENGTH)
{
    if (mb_strlen($excerpt) > $max) {
        $excerpt = mb_substr($excerpt, 0, $max) . '...';
    }
    return $excerpt;
}

// Get entity URL
function _likebtn_get_entity_url($entity_name, $entity_id, $url = '')
{
    if ($url) {
        return $url;
    }

    switch ($entity_name) {
        case LIKEBTN_ENTITY_COMMENT:
            $url = get_comment_link($entity_id);
            break;
        case LIKEBTN_ENTITY_BP_ACTIVITY_POST:
        case LIKEBTN_ENTITY_BP_ACTIVITY_UPDATE:
        case LIKEBTN_ENTITY_BP_ACTIVITY_COMMENT:
        case LIKEBTN_ENTITY_BP_ACTIVITY_TOPIC:
            if (function_exists('bp_activity_get_permalink')) {
                $url = bp_activity_get_permalink($entity_id);
            }
            break;
        case LIKEBTN_ENTITY_BP_MEMBER:
        case LIKEBTN_ENTITY_BBP_USER:
            if (function_exists('bp_core_get_user_domain')) {
                $url = bp_core_get_user_domain($entity_id);
            }
            break;
        default:
            $url = get_permalink($entity_id);
            break;
    }
    return $url;
}

// Check if function has been called by some other function
function _likebtn_has_caller($function_name)
{
    $e = new Exception;

    if (strstr($e->getTraceAsString(), $function_name.'(')) {
        return true;
    } else {
        return false;
    }
}

// Send JSON to browser
function _likebtn_send_json( $response ) {
    @header( 'Content-Type: application/json; charset=' . get_option( 'blog_charset' ) );
    echo json_encode( $response );
    if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
        wp_die();
    } else {
        die;
    }
}

// Reload current page
/*function _likebtn_reload_page()
{
    header("HTTP/1.1 302 Found");
    header("Location: ".$_SERVER['REQUEST_URI']);
    exit;
}*/