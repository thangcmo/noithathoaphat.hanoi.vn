<?php
/* ----------------------------------------------------------------------------------- */
# Create post_type
/* ----------------------------------------------------------------------------------- */
add_action('init', 'create_product_post_type');

function create_product_post_type(){
    register_post_type('product', array(
        'labels' => array(
            'name' => __('Products', SHORT_NAME),
            'singular_name' => __('Products', SHORT_NAME),
            'add_new' => __('Add new', SHORT_NAME),
            'add_new_item' => __('Add new Product', SHORT_NAME),
            'new_item' => __('New Product', SHORT_NAME),
            'edit' => __('Edit', SHORT_NAME),
            'edit_item' => __('Edit Product', SHORT_NAME),
            'view' => __('View Product', SHORT_NAME),
            'view_item' => __('View Product', SHORT_NAME),
            'search_items' => __('Search Products', SHORT_NAME),
            'not_found' => __('No Product found', SHORT_NAME),
            'not_found_in_trash' => __('No Product found in trash', SHORT_NAME),
        ),
        'public' => true,
        'show_ui' => true,
        'publicy_queryable' => true,
        'exclude_from_search' => false,
        'menu_position' => 20,
        'hierarchical' => false,
        'query_var' => true,
        'supports' => array(
            'title', 'editor', 'author', 'thumbnail', 
            //'custom-fields', 'excerpt', 'comments', 
        ),
        'rewrite' => array('slug' => 'san-pham', 'with_front' => false),
        'can_export' => true,
        'description' => __('Product description here.', SHORT_NAME),
        //'taxonomies' => array('post_tag'),
    ));
}
/* ----------------------------------------------------------------------------------- */
# Create taxonomy
/* ----------------------------------------------------------------------------------- */
add_action('init', 'create_product_taxonomies');

function create_product_taxonomies(){
    register_taxonomy('product_cat', 'product', array(
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'query_var' => true,
        'labels' => array(
            'name' => __('Product Categories', SHORT_NAME),
            'singular_name' => __('Product Categories', SHORT_NAME),
            'add_new' => __('Add new', SHORT_NAME),
            'add_new_item' => __('Add New Category', SHORT_NAME),
            'new_item' => __('New Category', SHORT_NAME),
            'search_items' => __('Search Categories', SHORT_NAME),
        ),
        'rewrite' => array('slug' => 'chuyen-muc', 'with_front' => false),
    ));
    register_taxonomy('product_price', 'product', array(
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'query_var' => true,
        'labels' => array(
            'name' => __('Product Prices', SHORT_NAME),
            'singular_name' => __('Product Prices', SHORT_NAME),
            'add_new' => __('Add New', SHORT_NAME),
            'add_new_item' => __('Add New Price', SHORT_NAME),
            'new_item' => __('New Color', SHORT_NAME),
            'search_items' => __('Search Prices', SHORT_NAME),
        ),
        'rewrite' => array('slug' => 'price', 'with_front' => false),
    ));
    register_taxonomy('product_color', 'product', array(
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'query_var' => true,
        'labels' => array(
            'name' => __('Product Colors', SHORT_NAME),
            'singular_name' => __('Product Colors', SHORT_NAME),
            'add_new' => __('Add New', SHORT_NAME),
            'add_new_item' => __('Add New Color', SHORT_NAME),
            'new_item' => __('New Color', SHORT_NAME),
            'search_items' => __('Search Colors', SHORT_NAME),
        ),
        'rewrite' => array('slug' => 'color', 'with_front' => false),
    ));
    register_taxonomy('product_factor', 'product', array(
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'query_var' => true,
        'labels' => array(
            'name' => __('Product Factors', SHORT_NAME),
            'singular_name' => __('Product Factors', SHORT_NAME),
            'add_new' => __('Add New', SHORT_NAME),
            'add_new_item' => __('Add New Factor', SHORT_NAME),
            'new_item' => __('New Factor', SHORT_NAME),
            'search_items' => __('Search Factors', SHORT_NAME),
        ),
        'rewrite' => array('slug' => 'loai', 'with_front' => false),
    ));
}

// Show filter
add_action('restrict_manage_posts','restrict_product_by_product_category');
function restrict_product_by_product_category() {
    global $wp_query, $typenow;
    if ($typenow=='product') {
        $taxonomies = array('product_cat');
        foreach ($taxonomies as $taxonomy) {
            $category = get_taxonomy($taxonomy);
            wp_dropdown_categories(array(
                'show_option_all' =>  __("$category->label"),
                'taxonomy'        =>  $taxonomy,
                'name'            =>  $taxonomy,
                'orderby'         =>  'name',
                'selected'        =>  $wp_query->query['term'],
                'hierarchical'    =>  true,
                'depth'           =>  3,
                'show_count'      =>  true, // Show # listings in parens
                'hide_empty'      =>  true, // Don't show businesses w/o listings
            ));
        }
    }
}

// Get post where filter condition

add_filter( 'posts_where' , 'products_where' );
function products_where($where) {
    if (is_admin()) {
        global $wpdb;
        
        $wp_posts = $wpdb->posts;
        $term_relationships = $wpdb->term_relationships;
        $term_taxonomy = $wpdb->term_taxonomy;

        $product_category = intval(getRequest('product_cat'));
        if ($product_category > 0) {
            $where .= " AND $wp_posts.ID IN (SELECT DISTINCT {$term_relationships}.object_id FROM {$term_relationships} 
                WHERE {$term_relationships}.term_taxonomy_id IN (
                    SELECT DISTINCT {$term_taxonomy}.term_taxonomy_id FROM {$term_taxonomy} ";
            
            if ($product_category > 0) {
                $where .= " WHERE {$term_taxonomy}.term_id = $product_category 
                                AND {$term_taxonomy}.taxonomy = 'product_cat') )";
            }
                            
//            $where = str_replace("AND 0 = 1", "", $where);
            $where = str_replace("0 = 1", "1 = 1", $where);
        }
    }
    return $where;
}
/* ----------------------------------------------------------------------------------- */
# Meta box
/* ----------------------------------------------------------------------------------- */
$product_meta_box = array(
    'id' => 'product-meta-box',
    'title' => __('Information', SHORT_NAME),
    'page' => 'product',
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
        array(
            'name' => __('Code', SHORT_NAME),
            'desc' => '',
            'id' => 'code',
            'type' => 'text',
            'std' => '',
        ),
        array(
            'name' => __('Size', SHORT_NAME),
            'desc' => '',
            'id' => 'size',
            'type' => 'text',
            'std' => '',
        ),
        array(
            'name' => __('Price', SHORT_NAME),
            'desc' => 'Example: 100000',
            'id' => 'price',
            'type' => 'text',
            'std' => '',
        ),
        array(
            'name' => __('Sale (%)', SHORT_NAME),
            'desc' => 'Example: 10',
            'id' => 'sale',
            'type' => 'text',
            'std' => '0',
        ),
        array(
            'name' => __('Guarantee', SHORT_NAME),
            'desc' => '',
            'id' => 'guarantee',
            'type' => 'text',
            'std' => '',
        ),
        array(
            'name' => __('Status', SHORT_NAME),
            'desc' => '',
            'id' => 'tinh_trang',
            'type' => 'radio',
            'std' => 'in_stock',
            'options' => array(
                'in_stock' => __('In stock', SHORT_NAME),
                'out_of_stock' => __('Out of stock', SHORT_NAME),
                'coming_soon' => __('Coming soon', SHORT_NAME),
            )
        ),
        array(
            'name' => __('Popular product', SHORT_NAME),
            'desc' => '',
            'id' => 'is_most',
            'type' => 'radio',
            'std' => '',
            'options' => array(
                '1' => 'Yes',
                '0' => 'No'
            )
        ),
));

// Add product meta box
if(is_admin()){
    add_action('admin_menu', 'product_add_box');
    add_action('save_post', 'product_add_box');
    add_action('save_post', 'product_save_data');
//    add_action('publish_product', 'product_publish_data');
}

function product_add_box(){
    global $product_meta_box;
    add_meta_box($product_meta_box['id'], $product_meta_box['title'], 'product_show_box', $product_meta_box['page'], $product_meta_box['context'], $product_meta_box['priority']);
}
/**
 * Callback function to show fields in product meta box
 * @global array $product_meta_box
 * @global Object $post
 * @global array $area_fields
 */
function product_show_box() {
    global $product_meta_box, $post;
    custom_output_meta_box($product_meta_box, $post);    
}
/**
 * Save data from product meta box
 * @global array $product_meta_box
 * @param Object $post_id
 * @return 
 */
function product_save_data($post_id) {
    global $product_meta_box;
    custom_save_meta_box($product_meta_box, $post_id);
}
/*
function product_publish_data($post_id){
    $purchases = get_post_meta($post_id, "purchases", true);
    
    if(!$purchases or $purchases == ""){
        if( ( $_POST['post_status'] == 'publish' ) && ( $_POST['original_post_status'] != 'publish' ) ) {
            update_post_meta($post_id, 'purchases', 0);
        }
    }
    
    return $post_id;
}

/***************************************************************************/

// ADD NEW COLUMN  
function product_columns_head($defaults) {
    $defaults['is_most'] = __('Nổi bật', SHORT_NAME);
    $defaults['orders'] = __('Orders', SHORT_NAME);
    return $defaults;
}

// SHOW THE COLUMN
function product_columns_content($column_name, $post_id) {
    switch ($column_name) {
        case 'is_most':
            $is_most = get_post_meta( $post_id, 'is_most', true );
            if($is_most == 1){
                echo '<a href="edit.php?update_is_most=true&post_id=' . $post_id . '&is_most=' . $is_most . '&redirect_to=' . urlencode(getCurrentRquestUrl()) . '">Yes</a>';
            }else{
                echo '<a href="edit.php?update_is_most=true&post_id=' . $post_id . '&is_most=' . $is_most . '&redirect_to=' . urlencode(getCurrentRquestUrl()) . '">No</a>';
            }
            break;
        case 'orders':
            echo '<a href="admin.php?page=nvt_orders&product_id=' . $post_id . '" target="_blank">' . __('View') . '</a>';
            break;
        default:
            break;
    }
}

// Update is most stataus
function update_product_is_most(){
    if(getRequest('update_is_most') == 'true'){
        $post_id = getRequest('post_id');
        $is_most = getRequest('is_most');
        $redirect_to = urldecode(getRequest('redirect_to'));
        if($is_most == 1){
            update_post_meta($post_id, 'is_most', 0);
        }else{
            update_post_meta($post_id, 'is_most', 1);
        }
        header("location: $redirect_to");
        exit();
    }
}

add_filter('manage_product_posts_columns', 'product_columns_head');  
add_action('manage_product_posts_custom_column', 'product_columns_content', 10, 2);  
add_filter('admin_init', 'update_product_is_most');

// Sortable columns

function sortable_product_columns( $columns ) {  
    $columns['is_most'] = 'is_most';
    return $columns;
}

function product_column_orderby( $query ) {  
    if( ! is_admin() )  
        return;  
  
    $orderby = $query->get( 'orderby');  
  
    switch ($orderby) {
        case 'is_most':
            $query->set('meta_key','is_most');  
            $query->set('orderby','meta_value_num');  
            break;
        default:
            break;
    }
}

add_filter( 'manage_edit-product_sortable_columns', 'sortable_product_columns' );  
add_action( 'pre_get_posts', 'product_column_orderby' );  

/*
# Add custom field to quick edit
//add_action( 'bulk_edit_custom_box', 'quickedit_products_custom_box', 10, 2 );
add_action('quick_edit_custom_box', 'quickedit_products_custom_box', 10, 2);

function quickedit_products_custom_box( $col, $type ) {
    if( $col != 'orders' || $type != 'product' ) {
        return;
    }
?>
    <fieldset class="inline-edit-col-right">
        <div class="inline-edit-col product-custom-fields">
            <div class="inline-edit-group">
                <label class="alignleft">
                    <span class="title">Price</span>
                    <input type="text" name="price" id="price" value="" />
                    <span class="spinner" style="display: none;"></span>
                </label>
            </div>
            <div class="inline-edit-group">
                <label class="alignleft">
                    <span class="title">Sale price</span>
                    <input type="text" name="sale_price" id="sale_price" value="" />
                    <span class="spinner" style="display: none;"></span>
                </label>
            </div>
        </div>
    </fieldset>
<?php
}

add_action('save_post', 'product_save_quick_edit_data');
 
function product_save_quick_edit_data($post_id) {
    // verify if this is an auto save routine. If it is our form has not been submitted, so we dont want
    // to do anything
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
        return $post_id;   
    // Check permissions
    if ( 'page' == $_POST['post_type'] ) {
        if ( !current_user_can( 'edit_page', $post_id ) )
            return $post_id;
    } else {
        if ( !current_user_can( 'edit_post', $post_id ) )
        return $post_id;
    }
    // OK, we're authenticated: we need to find and save the data
    $post = get_post($post_id);
    $fields = array('price', 'sale_price');
    foreach ($fields as $field) {
        if (isset($_POST[$field]) && ($post->post_type != 'revision')) {
            $meta = esc_attr($_POST[$field]);
            if ($meta)
                update_post_meta( $post_id, $field, $meta);
        }
    }
    
    return $post_id;
}

add_action( 'admin_print_scripts-edit.php', 'product_enqueue_edit_scripts' );
function product_enqueue_edit_scripts() {
   wp_enqueue_script( 'product-admin-edit', get_bloginfo( 'stylesheet_directory' ) . '/libs/js/quick_edit.js', array( 'jquery', 'inline-edit-post' ), '', true );
}
*/

//////////////////
//add extra fields to tag edit form hook
add_action('product_cat_add_form_fields', 'product_add_extra_tag_fields');
//add_action('edit_tag_form_fields', 'product_extra_tag_fields');
add_action('product_cat_edit_form_fields', 'product_extra_tag_fields');

//add extra fields to category edit form callback function
function product_add_extra_tag_fields() {
    ?>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="tag_meta_discount"><?php _e('Discount (%)', SHORT_NAME); ?></label></th>
        <td>
            <input type="text" name="tag_meta[discount]" id="tag_meta_discount" value="" /><br />
            <span class="description">Discount for all product in this category. Example: 20</span><br /><br />
        </td>
    </tr>
    <?php
}
function product_extra_tag_fields($tag) {    //check for existing featured ID
    $t_id = $tag->term_id;
    $tag_meta = get_option("tag_$t_id");
    ?>

    <tr class="form-field">
        <th scope="row" valign="top"><label for="tag_meta_discount"><?php _e('Discount (%)', SHORT_NAME); ?></label></th>
        <td>
            <input type="text" name="tag_meta[discount]" id="tag_meta_discount" value="<?php echo $tag_meta['discount'] ? $tag_meta['discount'] : ''; ?>" /><br />
            <span class="description">Discount for all product in this category. Example: 20</span>
        </td>
    </tr>
    <?php
}

//add extra fields to tag edit form hook
add_action('product_color_add_form_fields', 'product_color_add_extra_tag_fields');
//add_action('edit_tag_form_fields', 'product_extra_tag_fields');
add_action('product_color_edit_form_fields', 'product_color_extra_tag_fields');

//add extra fields to category edit form callback function
function product_color_add_extra_tag_fields() {
    ?>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="tag_meta_img"><?php _e('Color Image', SHORT_NAME); ?></label></th>
        <td>
            <input type="text" name="tag_meta[img]" id="tag_meta_img" style="width:80%;" value=""/>
            <button type="button" onclick="uploadByField('tag_meta_img')" class="button button-upload" id="upload_tag_meta_img_button" />Upload</button><br />
            <span class="description">Size: 32x32. Use full URL with http://</span><br /><br />
        </td>
    </tr>
    <?php
}
function product_color_extra_tag_fields($tag) {    //check for existing featured ID
    $t_id = $tag->term_id;
    $tag_meta = get_option("tag_$t_id");
    ?>

    <tr class="form-field">
        <th scope="row" valign="top"><label for="tag_meta_img"><?php _e('Color Image', SHORT_NAME); ?></label></th>
        <td>
            <input type="text" name="tag_meta[img]" id="tag_meta_img" style="width:84%;" value="<?php echo $tag_meta['img'] ? $tag_meta['img'] : ''; ?>">
            <button type="button" onclick="uploadByField('tag_meta_img')" class="button button-upload" id="upload_tag_meta_img_button" />Upload</button><br />
            <span class="description">Size: 32x32. Use full URL with http://</span>
        </td>
    </tr>
    <?php
}

// save extra tag extra fields hook
add_action('edited_terms', 'product_save_extra_tag_fileds');
add_action('create_term', 'product_save_extra_tag_fileds');

// save extra tag extra fields callback function
function product_save_extra_tag_fileds($term_id) {
    if (isset($_POST['tag_meta'])) {
        $t_id = $term_id;
        $tag_meta = get_option("tag_$t_id");
        $tag_keys = array_keys($_POST['tag_meta']);
        foreach ($tag_keys as $key) {
            if (isset($_POST['tag_meta'][$key])) {
                $tag_meta_value = stripslashes_deep($_POST['tag_meta'][$key]);
                if(!empty($tag_meta_value)){
                    $tag_meta[$key] = $tag_meta_value;
                }
            }
        }
        //save the option array
        update_option("tag_$t_id", $tag_meta);
    }
}

//these filters will only affect custom column, the default column will not be affected
//filter: manage_edit-{$taxonomy}_columns
function product_color_custom_column_header($columns) {
    $new_columns = array();
    $new_columns['cb'] = $columns['cb'];
    $new_columns['thumb'] = __('Image');

    unset( $columns['cb'] );

    return array_merge( $new_columns, $columns );
}

add_filter("manage_edit-product_color_columns", 'product_color_custom_column_header', 10);

function product_color_column_content($columns, $column_name, $tax_id) {
    $tag_meta = get_option("tag_$tax_id");
    //for multiple custom column, you may consider using the column name to distinguish
    if ($column_name === 'thumb') {
        $columns = '<span><img src="' . $tag_meta['img'] . '" alt="' . __('Thumbnail') . '" class="wp-post-image" /></span>';
    }
    return $columns;
}

add_action("manage_product_color_custom_column", 'product_color_column_content', 10, 3);