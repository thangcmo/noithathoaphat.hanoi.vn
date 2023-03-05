<?php

/**
 * Registering meta boxes
 *
 * All the definitions of meta boxes are listed below with comments.
 * Please read them CAREFULLY.
 *
 * You also should read the changelog to know what has been changed before updating.
 *
 * For more information, please visit:
 * @link http://metabox.io/docs/registering-meta-boxes/
 */
add_filter('rwmb_meta_boxes', 'product_register_meta_boxes');

/**
 * Register meta boxes
 *
 * Remember to change "your_prefix" to actual prefix in your project
 *
 * @param array $meta_boxes List of meta boxes
 *
 * @return array
 */
function product_register_meta_boxes($meta_boxes) {
    /**
     * prefix of meta keys (optional)
     * Use underscore (_) at the beginning to make keys hidden
     * Alt.: You also can make prefix empty to disable it
     */
    // Better has an underscore as last sign
    $prefix = 'product_';

    // 1st meta box
    $meta_boxes[] = array(
        'id' => 'standard',
        'title' => __('Product Images', SHORT_NAME),
        'post_types' => array('product'),
        'context' => 'normal',
        'priority' => 'high',
        'autosave' => true,
        'fields' => array(
            array(
                'id' => "{$prefix}images",
                'name' => __("Image reviews", SHORT_NAME),
                'type' => 'image_advanced',
                // Delete image from Media Library when remove it from post meta?
                // Note: it might affect other posts if you use same image for multiple posts
                'force_delete' => false,
            )
        )
    );

    return $meta_boxes;
}
