<div id="sidebar_left" class="sidebar col-md-3 col-lg-2 hidden-xs hidden-sm">
    <?php if ( is_active_sidebar( 'sidebar_page' ) ) { dynamic_sidebar( 'sidebar_page' ); } ?>
    
    <?php get_template_part('template/product-popular'); ?>
</div><!-- #sidebar -->