<div id="sidebar_left" class="sidebar col-md-3 col-lg-2 hidden-xs hidden-sm">
    <div class="widget">
        <h3 class="widget-title"><?php _e('Categories', SHORT_NAME); ?></h3>
        <div class="widget-content">
            <ul class="sidebar-categories">
                <?php
                $term_id = get_queried_object_id();
                wp_list_categories(array(
                    'title_li' => '',
                    'hide_empty' => 0,
                    'current_category' => $term_id,
                ));
                ?>
            </ul>
        </div>
    </div>
    
    <?php get_template_part('template/product-popular'); ?>
    
    <?php if ( is_active_sidebar( 'sidebar_left' ) ) { dynamic_sidebar( 'sidebar_left' ); } ?>
</div><!-- #sidebar -->