<?php

# Custom support_online post type
add_action('init', 'create_support_online_post_type');

function create_support_online_post_type(){
    register_post_type('support', array(
        'labels' => array(
            'name' => __('Supports', SHORT_NAME),
            'singular_name' => __('Supports', SHORT_NAME),
            'add_new' => __('Add new', SHORT_NAME),
            'add_new_item' => __('Add new Support', SHORT_NAME),
            'new_item' => __('New Support', SHORT_NAME),
            'edit' => __('Edit', SHORT_NAME),
            'edit_item' => __('Edit Support', SHORT_NAME),
            'view' => __('View Support', SHORT_NAME),
            'view_item' => __('View Support', SHORT_NAME),
            'search_items' => __('Search Supports', SHORT_NAME),
            'not_found' => __('No Support found', SHORT_NAME),
            'not_found_in_trash' => __('No Support found in trash', SHORT_NAME),
        ),
        'public' => true,
        'show_ui' => true,
        'publicy_queryable' => true,
        'exclude_from_search' => false,
        'menu_position' => 20,
        'hierarchical' => false,
        'query_var' => true,
        'supports' => array(
            'title', 
            //'editor', 'comments', 'author', 'excerpt', 'thumbnail', 'custom-fields'
        ),
        'rewrite' => array('slug' => 'support', 'with_front' => false),
        'can_export' => true,
        'description' => __('Support description here.', SHORT_NAME)
    ));
}

# support_online meta box
$support_online_meta_box = array(
    'id' => 'support_online-meta-box',
    'title' => __('Information', SHORT_NAME),
    'page' => 'support',
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
        array(
            'name' => __('Phone number', SHORT_NAME),
            'desc' => '',
            'id' => 'phone',
            'type' => 'text',
            'std' => '',
        ),
        array(
            'name' => __('Email address', SHORT_NAME),
            'desc' => '',
            'id' => 'email',
            'type' => 'text',
            'std' => '',
        ),
        array(
            'name' => 'Yaoo ID',
            'desc' => '',
            'id' => 'yahoo_id',
            'type' => 'text',
            'std' => '',
        ),
        array(
            'name' => 'Skype ID',
            'desc' => '',
            'id' => 'skype_id',
            'type' => 'text',
            'std' => '',
        ),
        array(
            'name' => __('Order', SHORT_NAME),
            'desc' => '',
            'id' => 'order',
            'type' => 'text',
            'std' => '1',
        ),
));

// Add support_online meta box
if(is_admin()){
    add_action('admin_menu', 'support_online_add_box');
    add_action('save_post', 'support_online_add_box');
    add_action('save_post', 'support_online_save_data');
}

function support_online_add_box(){
    global $support_online_meta_box;
    add_meta_box($support_online_meta_box['id'], $support_online_meta_box['title'], 'support_online_show_box', $support_online_meta_box['page'], $support_online_meta_box['context'], $support_online_meta_box['priority']);
}

// Callback function to show fields in support_online meta box
function support_online_show_box() {
    global $support_online_meta_box, $post;
    custom_output_meta_box($support_online_meta_box, $post);
}

// Save data from support_online meta box
function support_online_save_data($post_id) {
    global $support_online_meta_box;
    custom_save_meta_box($support_online_meta_box, $post_id);
}