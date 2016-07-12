<?php

class LikeBtnLikeButtonMostLikedWidget extends WP_Widget {

    // Default thumbnail size
    const TUMBNAIL_SIZE = 32;
    // Default number of items to show
    const NUMBER_OF_ITEMS = 5;

    public static $instance_default = array(
        'title' => '',
        'entity_name' => array(LIKEBTN_ENTITY_POST),
        'number' => self::NUMBER_OF_ITEMS,
        'order' => 'likes',
        'time_range' => 'all',
        'thumbnail_size' => 'thumbnail',
        'show_likes' => '',
        'show_dislikes' => '',
        'show_dislikes' => '',
        'show_thumbnail' => '1',
        'show_excerpt' => '',
        'show_date' => ''
    );

    function LikeBtnLikeButtonMostLikedWidget() {
        load_plugin_textdomain(LIKEBTN_I18N_DOMAIN, false, dirname(plugin_basename(__FILE__)) . '/languages');
        $widget_ops = array('description' => __('A list of most liked posts, comments, etc', LIKEBTN_I18N_DOMAIN));
        parent::WP_Widget(false, $name = __('(LikeBtn) Most Liked Content', LIKEBTN_I18N_DOMAIN), $widget_ops);
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance, $output = true) {
        global $LikeBtnLikeButtonMostLiked;
        $html = $LikeBtnLikeButtonMostLiked->widget($args, $instance);
        if (!empty($output)) {
            echo $html;
        } else {
            return $html;
        }
    }

    /*function update($new_instance, $old_instance) {
        if ($new_instance['title'] == '') {
            $new_instance['title'] = __('Most Liked Content', LIKEBTN_I18N_DOMAIN);
        }

        return $new_instance;
    }*/

    function form($instance) {
        global $likebtn_entities;

        $instance = LikeBtnLikeButtonMostLikedWidget::prepareInstance($instance);

        $widget_mnemonic = time()+mt_rand(0, 10000000);

        $likebtn_entities = _likebtn_get_entities(true);

        // Custom item
        $likebtn_entities[LIKEBTN_ENTITY_CUSTOM_ITEM] = __('Custom item');

        $order_list = array(
            'likes' => __('Likes', LIKEBTN_I18N_DOMAIN),
            'dislikes' => __('Dislikes', LIKEBTN_I18N_DOMAIN),
            'likes_minus_dislikes' => __('Likes minus dislikes', LIKEBTN_I18N_DOMAIN)
        );

        $thumbnail_size_list = array(
            'thumbnail' => __('Thumbnail', LIKEBTN_I18N_DOMAIN),
            'medium' => __('Medium', LIKEBTN_I18N_DOMAIN),
            'large' => __('Large', LIKEBTN_I18N_DOMAIN),
            'full' => __('Full size', LIKEBTN_I18N_DOMAIN),
        );

        $time_range_list = array(
            'all' => __('All time', LIKEBTN_I18N_DOMAIN),
            '1' => __('1 day', LIKEBTN_I18N_DOMAIN),
            '2' => __('2 days', LIKEBTN_I18N_DOMAIN),
            '3' => __('3 days', LIKEBTN_I18N_DOMAIN),
            '7' => __('1 week', LIKEBTN_I18N_DOMAIN),
            '14' => __('2 weeks', LIKEBTN_I18N_DOMAIN),
            '21' => __('3 weeks', LIKEBTN_I18N_DOMAIN),
            '1m' => __('1 month', LIKEBTN_I18N_DOMAIN),
            '2m' => __('2 months', LIKEBTN_I18N_DOMAIN),
            '3m' => __('3 months', LIKEBTN_I18N_DOMAIN),
            '6m' => __('6 months', LIKEBTN_I18N_DOMAIN),
            '1y' => __('1 year', LIKEBTN_I18N_DOMAIN)
        );
        
        // Normalize instance
        if (!isset($instance['title'])) {
            $instance['title'] = __('Most Liked Content', LIKEBTN_I18N_DOMAIN);
        }
        if (empty($instance['entity_name']) || !is_array($instance['entity_name'])) {
            $instance['entity_name'] = self::$instance_default['entity_name'];
        }
        if (empty($instance['number']) || (int)$instance['number'] < 1) {
            $instance['number'] = self::$instance_default['number'];
        }
        if (empty($instance['order'])) {
            $instance['order'] = self::$instance_default['order'];
        }
        if (empty($instance['time_range'])) {
            $instance['time_range'] = self::$instance_default['time_range'];
        }
        if (empty($instance['thumbnail_size'])) {
            $instance['thumbnail_size'] = self::$instance_default['thumbnail_size'];
        }
        /*if (empty($instance['show_likes'])) {
            $instance['show_likes'] = '';
        }
        if (empty($instance['show_dislikes'])) {
            $instance['show_dislikes'] = '';
        }
        if (empty($instance['show_thumbnail'])) {
            $instance['show_thumbnail'] = '1';
        }
        if (empty($instance['show_excerpt'])) {
            $instance['show_excerpt'] = '';
        }
        if (empty($instance['show_date'])) {
            $instance['show_date'] = '';
        }*/

        ?>
        <div id="likebtn_widget_<?php echo $widget_mnemonic; ?>">
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', LIKEBTN_I18N_DOMAIN); ?>:</label>
                <input class="widefat" type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" data-property="title" />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('entity_name'); ?>"><?php _e('Items to show', LIKEBTN_I18N_DOMAIN); ?>:</label><br/>
                <?php /*<select name="<?php echo $this->get_field_name('entity_name'); ?>[]" id="<?php echo $this->get_field_id('entity_name'); ?>" multiple="multiple" size="6" style="height:auto !important;">
                    <?php foreach ($likebtn_entities as $entity_name_value => $entity_title): ?>
                        <option value="<?php echo $entity_name_value; ?>" <?php echo (in_array($entity_name_value, $instance['entity_name']) ? 'selected="selected"' : ''); ?> ><?php _e($entity_title, LIKEBTN_I18N_DOMAIN); ?></option>
                    <?php endforeach ?>
                </select>*/ ?>
                <?php foreach ($likebtn_entities as $entity_name_value => $entity_title): ?>
                    <input type="checkbox" name="<?php echo $this->get_field_name('entity_name'); ?>[]" id="<?php echo $this->get_field_id('entity_name'); ?>" value="<?php echo $entity_name_value; ?>" <?php echo (in_array($entity_name_value, $instance['entity_name']) ? 'checked="checked"' : ''); ?> data-property="entity_name" /> <?php _e($entity_title, LIKEBTN_I18N_DOMAIN); ?><br/>
                <?php endforeach ?>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of items to show:', LIKEBTN_I18N_DOMAIN); ?></label>
                <input type="text" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" value="<?php echo $instance['number']; ?>" size="3" data-property="number" />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('order'); ?>"><?php _e('Order by:', LIKEBTN_I18N_DOMAIN); ?></label>
                <select name="<?php echo $this->get_field_name('order'); ?>" id="<?php echo $this->get_field_id('order'); ?>" data-property="order" >
                    <?php foreach ($order_list as $order_value => $order_name): ?>
                        <option value="<?php echo $order_value; ?>" <?php selected($order_value, $instance['order']); ?> ><?php _e($order_name, LIKEBTN_I18N_DOMAIN); ?></option>
                    <?php endforeach ?>
                </select>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('time_range'); ?>"><?php _e('Time range:', LIKEBTN_I18N_DOMAIN); ?></label>
                <select name="<?php echo $this->get_field_name('time_range'); ?>" id="<?php echo $this->get_field_id('time_range'); ?>" data-property="time_range" >
                    <?php foreach ($time_range_list as $time_range_value => $time_range_name): ?>
                        <option value="<?php echo $time_range_value; ?>" <?php selected($time_range_value, $instance['time_range']); ?> ><?php _e($time_range_name, LIKEBTN_I18N_DOMAIN); ?></option>
                    <?php endforeach ?>
                </select>
            </p>
            <p>
                <input class="checkbox" type="checkbox" id="<?php echo $this->get_field_id('show_likes'); ?>" name="<?php echo $this->get_field_name('show_likes'); ?>" value="1" <?php checked($instance['show_likes']); ?> data-property="show_likes" />
                <label for="<?php echo $this->get_field_id('show_likes'); ?>"><?php _e('Display likes count', LIKEBTN_I18N_DOMAIN); ?></label>
            </p>
            <p>
                <input class="checkbox" type="checkbox" id="<?php echo $this->get_field_id('show_dislikes'); ?>" name="<?php echo $this->get_field_name('show_dislikes'); ?>" value="1" <?php checked($instance['show_dislikes']); ?> data-property="show_dislikes" />
                <label for="<?php echo $this->get_field_id('show_dislikes'); ?>"><?php _e('Display dislikes count', LIKEBTN_I18N_DOMAIN); ?></label>
            </p>
            <p>
                <input class="checkbox" type="checkbox" <?php checked($instance['show_thumbnail']); ?> id="<?php echo $this->get_field_id('show_thumbnail'); ?>" name="<?php echo $this->get_field_name('show_thumbnail'); ?>" value="1" data-property="show_thumbnail" />
                <label for="<?php echo $this->get_field_id('show_thumbnail'); ?>"><?php _e('Display featured image', LIKEBTN_I18N_DOMAIN); ?></label>
                <select name="<?php echo $this->get_field_name('thumbnail_size'); ?>" id="<?php echo $this->get_field_id('thumbnail_size'); ?>" data-property="thumbnail_size" >
                    <?php foreach ($thumbnail_size_list as $thumbnail_size_value => $thumbnail_size_name): ?>
                        <option value="<?php echo $thumbnail_size_value; ?>" <?php selected($thumbnail_size_value, $instance['thumbnail_size']); ?> ><?php _e($thumbnail_size_name, LIKEBTN_I18N_DOMAIN); ?></option>
                    <?php endforeach ?>
                </select>
            </p>
            <p>
                <input class="checkbox" type="checkbox" <?php checked($instance['show_excerpt']); ?> id="<?php echo $this->get_field_id('show_excerpt'); ?>" name="<?php echo $this->get_field_name('show_excerpt'); ?>" value="1" data-property="show_excerpt" />
                <label for="<?php echo $this->get_field_id('show_excerpt'); ?>"><?php _e('Display excerpt', LIKEBTN_I18N_DOMAIN); ?></label>
            </p>
            <p>
                <input class="checkbox" type="checkbox" <?php checked($instance['show_date']); ?> id="<?php echo $this->get_field_id('show_date'); ?>" name="<?php echo $this->get_field_name('show_date'); ?>" value="1" data-property="show_date" />
                <label for="<?php echo $this->get_field_id('show_date'); ?>"><?php _e('Display item date', LIKEBTN_I18N_DOMAIN); ?></label>
            </p>
            <p>
                <a href="javascript:likebtnWidgetShortcode('<?php echo $widget_mnemonic; ?>')"><?php _e('Get shortcode', LIKEBTN_I18N_DOMAIN); ?></a>
            </p>
            <p id="likebtn_sc_wr_<?php echo $widget_mnemonic; ?>" class="likebtn_sc_wr">
                <textarea class="likebtn_input likebtn_disabled" rows="5" id="likebtn_sc_<?php echo $widget_mnemonic; ?>" readonly="readonly"></textarea>
            </p>
            <input type="hidden" id="wti-most-submit" name="wti-submit" value="1" />
        </div>
        <script type="text/javascript">
            jQuery(document).ready(function() {
                jQuery("#likebtn_widget_<?php echo $widget_mnemonic ?> :input").on("keyup change", function(event) {
                    likebtnWidgetShortcode('<?php echo $widget_mnemonic ?>', true);
                });
            });
        </script>
        <?php
    }

    // Set default values
    public static function prepareInstance($instance)
    {
        foreach (self::$instance_default as $field => $default_value) {
            if (!isset($instance[$field])) {
                if ($field == 'title') {
                    $instance['title'] = __('Most Liked Content', LIKEBTN_I18N_DOMAIN);
                } else {
                    $instance[$field] = '';
                }
            }
        }
        return $instance;
    }

    /*public static function getField($instance, $field)
    {
        return isset($instance[$field]) ? $instance[$field] : '';
    }*/

}

class LikeBtnLikeButtonMostLiked {

    const PLUGIN_NAME = 'likebtn-like-button';
    const TEMPLATE = 'most-liked-widget.php';

    function LikeBtnLikeButtonMostLiked() {
        add_action('widgets_init', array(&$this, 'init'));
    }

    function init() {
        register_widget("LikeBtnLikeButtonMostLikedWidget");
    }

    function widget($args, $instance = array()) {
        global $wpdb;
        global $likebtn_nonpost_entities;
        global $likebtn_bbp_post_types;

        $has_posts = false;
        $post_types_count = 0;

        if (is_array($args)) {
            extract($args);
        }

        $instance = LikeBtnLikeButtonMostLikedWidget::prepareInstance($instance);

        if (is_array($instance)) {
            extract($instance);
        }

        /*$title = '';
        if (isset($instance['title'])) {
            $title = $instance['title'];
        }
        $show_thumbnail = '';
        if (isset($instance['show_thumbnail'])) {
            $show_thumbnail = $instance['show_thumbnail'];
        }
        $show_excerpt = '';
        if (isset($instance['show_excerpt'])) {
            $show_excerpt = $instance['show_excerpt'];
        }
        $show_date = '';
        if (isset($instance['show_date'])) {
            $show_date = $instance['show_date'];
        }
        $show_likes = '';
        if (isset($instance['show_likes'])) {
            $show_likes = $instance['show_likes'];
        }
        $show_dislikes = '';
        if (isset($instance['show_dislikes'])) {
            $show_dislikes = $instance['show_dislikes'];
        }

        // validate parameters
        if ($show_thumbnail == 'true') {
            $show_thumbnail = '1';
        }
        if ($show_date == 'true') {
            $show_date = '1';
        }
        if ($show_likes == 'true') {
            $show_likes = '1';
        }
        if ($show_dislikes == 'true') {
            $show_dislikes = '1';
        }*/

        if (empty($instance['entity_name'])) {
            $instance['entity_name'] = array(LIKEBTN_ENTITY_POST);
        }

        foreach ($instance['entity_name'] as $entity_index => $entity_name) {
            $instance['entity_name'][$entity_index] = str_replace("'", '', trim($entity_name));

            if (!in_array($entity_name, $likebtn_nonpost_entities)) {
                $has_posts = true;
            }
        }

        $query_limit = '';
        if (isset($instance['number']) && (int) $instance['number'] > 0) {
            $query_limit = "LIMIT " . (int) $instance['number'];
        }

        // getting the most liked content
        $query = '';

        // Posts
        if ($has_posts) {
            $query_post_types = "'" . implode("','", $instance['entity_name']) . "'";
            $query .= "
                 SELECT
                    p.ID as 'post_id',
                    p.post_title,
                    p.post_date,
                    CONVERT(pm_likes.meta_value, UNSIGNED INTEGER) as 'likes',
                    CONVERT(pm_dislikes.meta_value, UNSIGNED INTEGER) as 'dislikes',
                    CONVERT(pm_likes_minus_dislikes.meta_value, UNSIGNED INTEGER) as 'likes_minus_dislikes',
                    p.post_type,
                    p.post_mime_type,
                    '' as url
                 FROM {$wpdb->prefix}postmeta pm_likes
                 LEFT JOIN {$wpdb->prefix}posts p
                     ON (p.ID = pm_likes.post_id)
                 LEFT JOIN {$wpdb->prefix}postmeta pm_dislikes
                     ON (pm_dislikes.post_id = pm_likes.post_id AND pm_dislikes.meta_key = '" . LIKEBTN_META_KEY_DISLIKES . "')
                 LEFT JOIN {$wpdb->prefix}postmeta pm_likes_minus_dislikes
                     ON (pm_likes_minus_dislikes.post_id = pm_likes.post_id AND pm_likes_minus_dislikes.meta_key = '" . LIKEBTN_META_KEY_LIKES_MINUS_DISLIKES . "')
                 WHERE
                    pm_likes.meta_key = '" . LIKEBTN_META_KEY_LIKES . "'
                    AND p.post_status = 'publish'
                    AND p.post_type in ({$query_post_types}) ";
            if (!empty($instance['time_range']) && $instance['time_range'] != 'all') {
                $query .= " AND post_date >= '" . $this->timeRangeToDateTime($instance['time_range']) . "'";
            }
            $post_types_count++;
        }

        // Comments
        if (in_array(LIKEBTN_ENTITY_COMMENT, $instance['entity_name'])) {
            if ($post_types_count > 0) {
                $query .= " UNION ";
            }
            $query .= "
                 SELECT
                    p.comment_ID as 'post_id',
                    p.comment_content as post_title,
                    p.comment_date as 'post_date',
                    CONVERT(pm_likes.meta_value, UNSIGNED INTEGER) as 'likes',
                    CONVERT(pm_dislikes.meta_value, UNSIGNED INTEGER) as 'dislikes',
                    CONVERT(pm_likes_minus_dislikes.meta_value, UNSIGNED INTEGER) as 'likes_minus_dislikes',
                    '".LIKEBTN_ENTITY_COMMENT."' as post_type,
                    '' as post_mime_type,
                    '' as url
                 FROM {$wpdb->prefix}commentmeta pm_likes
                 LEFT JOIN {$wpdb->prefix}comments p
                    ON (p.comment_ID = pm_likes.comment_id)
                 LEFT JOIN {$wpdb->prefix}commentmeta pm_dislikes
                    ON (pm_dislikes.comment_id = pm_likes.comment_id AND pm_dislikes.meta_key = '" . LIKEBTN_META_KEY_DISLIKES . "')
                 LEFT JOIN {$wpdb->prefix}commentmeta pm_likes_minus_dislikes
                     ON (pm_likes_minus_dislikes.comment_id = pm_likes.comment_id AND pm_likes_minus_dislikes.meta_key = '" . LIKEBTN_META_KEY_LIKES_MINUS_DISLIKES . "')
                 WHERE
                    pm_likes.meta_key = '" . LIKEBTN_META_KEY_LIKES . "' 
                    AND p.comment_approved = 1 ";
            if (!empty($instance['time_range']) && $instance['time_range'] != 'all') {
                $query .= " AND comment_date >= '" . $this->timeRangeToDateTime($instance['time_range']) . "'";
            }
            $post_types_count++;
        }

        // Custom items
        if (in_array(LIKEBTN_ENTITY_CUSTOM_ITEM, $instance['entity_name'])) {
            if ($post_types_count > 0) {
                $query .= " UNION ";
            }
            $query_post_types = "'" . implode("','", $instance['entity_name']) . "'";
            $query .= "
                 SELECT
                    p.ID as 'post_id',
                    p.identifier as 'post_title',
                    '' as 'post_date',
                    p.likes,
                    p.dislikes,
                    p.likes_minus_dislikes,
                    '".LIKEBTN_ENTITY_CUSTOM_ITEM."' as 'post_type',
                    '' as 'post_mime_type',
                    url
                 FROM {$wpdb->prefix}".LIKEBTN_TABLE_ITEM." p
                 WHERE
                    1 = 1 ";
            $post_types_count++;
        }

        // BuddyPress Member
        if (in_array(LIKEBTN_ENTITY_BP_MEMBER, $instance['entity_name'])) {
            if ($post_types_count > 0) {
                $query .= " UNION ";
            }
            $query .= "
                 SELECT
                    p.ID as 'post_id',
                    p.display_name as post_title,
                    p.user_registered as 'post_date',
                    CONVERT(pm_likes.meta_value, UNSIGNED INTEGER) as 'likes',
                    CONVERT(pm_dislikes.meta_value, UNSIGNED INTEGER) as 'dislikes',
                    CONVERT(pm_likes_minus_dislikes.meta_value, UNSIGNED INTEGER) as 'likes_minus_dislikes',
                    '" . LIKEBTN_ENTITY_BP_MEMBER . "' as post_type,
                    '' as post_mime_type,
                    '' as url
                 FROM {$wpdb->prefix}bp_xprofile_meta pm_likes
                 LEFT JOIN {$wpdb->prefix}users p
                    ON (p.ID = pm_likes.object_id AND pm_likes.object_type = '" . LIKEBTN_BP_XPROFILE_OBJECT_TYPE . "')
                 LEFT JOIN {$wpdb->prefix}bp_xprofile_meta pm_dislikes
                    ON (pm_dislikes.object_id = pm_likes.object_id AND pm_dislikes.object_type = '" . LIKEBTN_BP_XPROFILE_OBJECT_TYPE . "' AND pm_dislikes.meta_key = '" . LIKEBTN_META_KEY_DISLIKES . "')
                 LEFT JOIN {$wpdb->prefix}bp_xprofile_meta pm_likes_minus_dislikes
                    ON (pm_likes_minus_dislikes.object_id = pm_likes.object_id AND pm_likes_minus_dislikes.object_type = '" . LIKEBTN_BP_XPROFILE_OBJECT_TYPE . "' AND pm_likes_minus_dislikes.meta_key = '" . LIKEBTN_META_KEY_LIKES_MINUS_DISLIKES . "')
                 WHERE
                    pm_likes.meta_key = '" . LIKEBTN_META_KEY_LIKES . "' 
                    AND p.user_status = 0 ";
            if (!empty($instance['time_range']) && $instance['time_range'] != 'all') {
                $query .= " AND p.user_registered >= '" . $this->timeRangeToDateTime($instance['time_range']) . "'";
            }
            $post_types_count++;
        }

        // BuddyPress Activities
        if (in_array(LIKEBTN_ENTITY_BP_ACTIVITY_POST, $instance['entity_name']) ||
            in_array(LIKEBTN_ENTITY_BP_ACTIVITY_UPDATE, $instance['entity_name']) ||
            in_array(LIKEBTN_ENTITY_BP_ACTIVITY_COMMENT, $instance['entity_name']) ||
            in_array(LIKEBTN_ENTITY_BP_ACTIVITY_TOPIC, $instance['entity_name'])
        ) {
            if ($post_types_count > 0) {
                $query .= " UNION ";
            }
            $query .= "
                SELECT 
                    p.id as 'post_id',
                    CONCAT( IF(p.action != '', p.action, IF(p.content !='', p.content, IF(p.primary_link != '', p.primary_link, p.type))), IF(p.content != '' && p.type != 'bbp_topic_create' && p.type != 'new_blog_post', CONCAT(': ', p.content), '') ) as 'post_title',
                    p.date_recorded as 'post_date',
                    CONVERT(pm_likes.meta_value, UNSIGNED INTEGER) as 'likes',
                    CONVERT(pm_dislikes.meta_value, UNSIGNED INTEGER) as 'dislikes',
                    CONVERT(pm_likes_minus_dislikes.meta_value, UNSIGNED INTEGER) as 'likes_minus_dislikes',
                    IF (p.type = 'bbp_topic_create',
                        '" . LIKEBTN_ENTITY_BP_ACTIVITY_TOPIC . "',
                        IF (p.type = 'new_blog_post',
                            '" . LIKEBTN_ENTITY_BP_ACTIVITY_POST . "',
                            '" . LIKEBTN_ENTITY_BP_ACTIVITY_UPDATE . "'
                        )
                    ) as post_type,
                    '' as post_mime_type,
                    '' as url
                 FROM {$wpdb->prefix}bp_activity_meta pm_likes
                 LEFT JOIN {$wpdb->prefix}bp_activity p
                     ON (p.id = pm_likes.activity_id)
                 LEFT JOIN {$wpdb->prefix}bp_activity_meta pm_dislikes
                     ON (pm_dislikes.activity_id = pm_likes.activity_id AND pm_dislikes.meta_key = '" . LIKEBTN_META_KEY_DISLIKES . "')
                 LEFT JOIN {$wpdb->prefix}bp_activity_meta pm_likes_minus_dislikes
                     ON (pm_likes_minus_dislikes.activity_id = pm_likes.activity_id AND pm_likes_minus_dislikes.meta_key = '" . LIKEBTN_META_KEY_LIKES_MINUS_DISLIKES . "')
                 WHERE
                    pm_likes.meta_key = '" . LIKEBTN_META_KEY_LIKES . "' 
                    AND p.hide_sitewide = 0
                    AND p.is_spam = 0 ";
            if (!empty($instance['time_range']) && $instance['time_range'] != 'all') {
                $query .= " AND p.user_registered >= '" . $this->timeRangeToDateTime($instance['time_range']) . "'";
            }
            $post_types_count++;
        }

        // bbPress Post
        if (in_array(LIKEBTN_ENTITY_BBP_POST, $instance['entity_name'])) {
            if ($post_types_count > 0) {
                $query .= " UNION ";
            }
            $query .= "
                 SELECT
                    p.ID as 'post_id',
                    p.post_title,
                    p.post_date,
                    CONVERT(pm_likes.meta_value, UNSIGNED INTEGER) as 'likes',
                    CONVERT(pm_dislikes.meta_value, UNSIGNED INTEGER) as 'dislikes',
                    CONVERT(pm_likes_minus_dislikes.meta_value, UNSIGNED INTEGER) as 'likes_minus_dislikes',
                    p.post_type,
                    p.post_mime_type,
                    '' as url
                 FROM {$wpdb->prefix}postmeta pm_likes
                 LEFT JOIN {$wpdb->prefix}posts p
                     ON (p.ID = pm_likes.post_id)
                 LEFT JOIN {$wpdb->prefix}postmeta pm_dislikes
                     ON (pm_dislikes.post_id = pm_likes.post_id AND pm_dislikes.meta_key = '" . LIKEBTN_META_KEY_DISLIKES . "')
                 LEFT JOIN {$wpdb->prefix}postmeta pm_likes_minus_dislikes
                     ON (pm_likes_minus_dislikes.post_id = pm_likes.post_id AND pm_likes_minus_dislikes.meta_key = '" . LIKEBTN_META_KEY_LIKES_MINUS_DISLIKES . "')
                 WHERE
                    pm_likes.meta_key = '" . LIKEBTN_META_KEY_LIKES . "'
                    AND p.post_type in ('".implode("', '", $likebtn_bbp_post_types)."') 
                    AND p.post_status = 'publish' ";
            if (!empty($instance['time_range']) && $instance['time_range'] != 'all') {
                $query .= " AND post_date >= '" . $this->timeRangeToDateTime($instance['time_range']) . "'";
            }
            $post_types_count++;
        }

        // bbPress User
        if (in_array(LIKEBTN_ENTITY_BBP_USER, $instance['entity_name'])) {
            if ($post_types_count > 0) {
                $query .= " UNION ";
            }
            $query .= "
                 SELECT
                    p.ID as 'post_id',
                    p.display_name as post_title,
                    p.user_registered as 'post_date',
                    CONVERT(pm_likes.meta_value, UNSIGNED INTEGER) as 'likes',
                    CONVERT(pm_dislikes.meta_value, UNSIGNED INTEGER) as 'dislikes',
                    CONVERT(pm_likes_minus_dislikes.meta_value, UNSIGNED INTEGER) as 'likes_minus_dislikes',
                    '" . LIKEBTN_ENTITY_BBP_USER . "' as post_type,
                    '' as post_mime_type,
                    '' as url
                 FROM {$wpdb->prefix}usermeta pm_likes
                 LEFT JOIN {$wpdb->prefix}users p
                    ON (p.ID = pm_likes.user_id)
                 LEFT JOIN {$wpdb->prefix}usermeta pm_dislikes
                    ON (pm_dislikes.user_id = pm_likes.user_id AND pm_dislikes.meta_key = '" . LIKEBTN_META_KEY_DISLIKES . "')
                 LEFT JOIN {$wpdb->prefix}usermeta pm_likes_minus_dislikes
                    ON (pm_likes_minus_dislikes.user_id = pm_likes.user_id AND pm_likes_minus_dislikes.meta_key = '" . LIKEBTN_META_KEY_LIKES_MINUS_DISLIKES . "')
                 WHERE
                    pm_likes.meta_key = '" . LIKEBTN_META_KEY_LIKES . "' 
                    AND p.user_status = 0 ";
            if (!empty($instance['time_range']) && $instance['time_range'] != 'all') {
                $query .= " AND p.user_registered >= '" . $this->timeRangeToDateTime($instance['time_range']) . "'";
            }
            $post_types_count++;
        }

        if ($post_types_count > 1) {
            $query = "SELECT * FROM (".$query. ") main_query";;
        }

        $query .= "
            ORDER BY ";
        switch ($instance['order']) {
            default:
            case 'likes':
                $query .= "likes";
                break;

            case 'dislikes':
                $query .= "dislikes";
                break;

            case 'likes_minus_dislikes':
                $query .= "likes_minus_dislikes";
                break;
        }
        $query .= " DESC";

        $query .= " {$query_limit}";

// echo "<pre>";
// print_r($query);
// exit();
        $posts = $wpdb->get_results($query);

        $post_loop = array();

        if (count($posts) > 0) {
            foreach ($posts as $i=>$db_post) {
                $post = array(
                    'id' => $db_post->post_id,
                    'type' => $db_post->post_type,
                    'post_mime_type' => $db_post->post_mime_type,
                    'title' => '',
                    'link' => '',
                    'likes' => '',
                    'dislikes' => '',
                    'date' => '',
                    'excerpt' => '',
                );

                // Title
                $post['title'] = _likebtn_prepare_title($db_post->post_type, $db_post->post_title);

                // Link
                $post['link'] = _likebtn_get_entity_url($db_post->post_type, $db_post->post_id, $db_post->url);

                $post['likes'] = $db_post->likes;
                $post['dislikes'] = $db_post->dislikes;

                if ($show_date) {
                    $post['date'] = strtotime($db_post->post_date);
                }

                if ($show_excerpt) {
                    if ($db_post->post_type == 'comment') {
                        $post['excerpt'] = get_comment_excerpt($db_post->post_id);
                    } elseif (!in_array($db_post->post_type, $likebtn_nonpost_entities)) {
                        $get_post = get_post($db_post->post_id);
                        $post['excerpt'] = $get_post->post_excerpt;
                        if (!$post['excerpt']) {
                            $post['excerpt'] = $get_post->post_content;
                        }
                        if ($post['excerpt']) {
                            $post['excerpt'] = strip_tags($post['excerpt']);
                            //$post['excerpt'] = apply_filters('get_the_excerpt', $post['excerpt']);
                            $post['excerpt'] = _likebtn_shorten_excerpt($post['excerpt']);
                        }
                    }
                }

                // For bbPress replies
                if (!$post['title']) {
                    $post['title'] = _likebtn_shorten_title($post['excerpt'], LIKEBTN_WIDGET_TITLE_LENGTH);
                }

                $post_loop[$i] = $post;
            }
        }

        // Get and include the template we're going to use
        ob_start();
		include( $this->getTemplateHierarchy(self::TEMPLATE) );
        $result = ob_get_contents();
        ob_get_clean();

        return $result;
    }

    function timeRangeToDateTime($range) {
        $day = 0;
        $month = 0;
        $year = 0;
        switch ($range) {
            case "1":
                $day = 1;
                break;
            case "2":
                $day = 2;
                break;
            case "3":
                $day = 3;
                break;
            case "7":
                $day = 7;
                break;
            case "14":
                $day = 14;
                break;
            case "21":
                $day = 21;
                break;
            case "1m":
                $month = 1;
                break;
            case "2m":
                $month = 2;
                break;
            case "3m":
                $month = 3;
                break;
            case "6m":
                $month = 6;
                break;
            case "1y":
                $year = 1;
                break;
        }

        $now_date_time = strtotime(date('Y-m-d H:i:s'));
        $range_timestamp = mktime(date('H', $now_date_time), date('i', $now_date_time), date('s', $now_date_time), date('m', $now_date_time) - $month, date('d', $now_date_time) - $day, date('Y', $now_date_time) - $year);

        return date('Y-m-d H:i:s', $range_timestamp);
    }

	/**
	 * Loads theme files in appropriate hierarchy:
     * 1) child theme,
	 * 2) parent template
     * 3) plugin resources
     *
     * Will look in the PLUGIN_NAME directory in a theme
	 **/
	public function getTemplateHierarchy( $template ) {

		if ( $theme_file = locate_template( array( self::PLUGIN_NAME . '/' . $template ) ) ) {
			$file = $theme_file;
		} else {
			$file = 'templates/' . $template;
		}

		return $file;
	}

}

$LikeBtnLikeButtonMostLiked = new LikeBtnLikeButtonMostLiked();