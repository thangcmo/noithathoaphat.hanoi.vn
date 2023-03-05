<?php get_header(); ?>

<section id="main">
    <div class="container">
        <div class="row">
            <?php get_sidebar('left'); ?>

            <div class="col-lg-8 col-md-9 col-sm-12">
                <!--BEGIN SLIDER-->
                <?php
                $slider_id = intval(get_option('home_slider'));
                if ($slider_id > 0):
                ?>
                <div class="slider">
                    <?php echo do_shortcode('[layerslider id="' . $slider_id . '"]'); ?>
                </div>
                <?php endif; ?>
                <!--END SLIDER-->

                <div class="product-grid-container">
                    <?php if ( is_active_sidebar( 'sidebar_home' ) ) { dynamic_sidebar( 'sidebar_home' ); } ?>
                </div>
            </div>

            <?php get_sidebar(); ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>