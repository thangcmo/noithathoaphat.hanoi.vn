<div id="sidebar_left" class="sidebar col-md-3 col-lg-2 hidden-xs hidden-sm">
    <div class="widget">
        <h3 class="widget-title"><?php
        $term_id = get_queried_object_id();
        $parent = $term_id;
        
        if(is_tax('product_factor')){
            $term = get_queried_object();
            if($term->parent > 0){ // Level 2
                $parent = $term->parent;
            }
            $term_parent = get_term($parent, 'product_factor');
            if($term_parent->parent > 0){ // Level 3
                $parent = $term_parent->parent;
                $term_parent2 = get_term($parent, 'product_factor');
                echo $term_parent2->name;
            } else { // Level 1
                echo $term_parent->name;
            }
        } else {
            _e('Product Categories', SHORT_NAME);
        }
        ?></h3>
        <div class="widget-content">
            <ul class="sidebar-categories">
                <?php
                $args = array(
                    'title_li' => '',
                    'hide_empty' => 0,
                );
                if(is_tax('product_factor')){
                    $args['taxonomy'] = 'product_factor';
                    $args['current_category'] = (is_tax('product_factor', $term_id)) ? $term_id : 0;
                    $args['child_of'] = $parent;
                } else {
                    $args['taxonomy'] = 'product_cat';
                    $args['current_category'] = (is_tax('product_cat', $term_id)) ? $term_id : 0;
                }
                wp_list_categories($args);
                ?>
            </ul>
        </div>
    </div>
    
    <?php get_template_part('template/product-popular'); ?>
    
    <?php if ( is_active_sidebar( 'sidebar_left' ) ) { dynamic_sidebar( 'sidebar_left' ); } ?>
</div><!-- #sidebar -->