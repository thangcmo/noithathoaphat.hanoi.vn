<div id="sidebar" class="sidebar col-lg-2 hidden-xs hidden-sm hidden-md">
    <div class="widget">
        <h3 class="widget-title"><?php _e('Contact with us', SHORT_NAME); ?></h3>
        <div class="widget-content">
            <?php
            $supports = new WP_Query(array(
                'post_type' => 'support',
                'posts_per_page' => -1,
                'order' => 'ASC',
                'orderby' => 'meta_value_num',
                'meta_key' => 'order',
            ));
            while ($supports->have_posts()) : $supports->the_post();
                $yahoo_id = get_post_meta(get_the_ID(), 'yahoo_id', true);
                $skype_id = get_post_meta(get_the_ID(), 'skype_id', true);
                $email = get_post_meta(get_the_ID(), 'email', true);
                $phone = get_post_meta(get_the_ID(), 'phone', true);
            ?>
            <div class="support">
                <div class="title"><?php the_title() ?></div>
                <div class="t_center mb10">
                    <?php if(!empty($yahoo_id)): ?>
                    <a href="ymsgr:sendim?<?php echo $yahoo_id ?>" class="yahoo">
                        <img src="http://opi.yahoo.com/online?u=<?php echo $yahoo_id ?>&amp;m=g&amp;t=1&amp;l=us" alt="<?php echo $yahoo_id ?>" border="0" />
                    </a>
                    <?php
                    endif;
                    if(!empty($skype_id)):
                    ?>
                    <a href="skype:<?php echo $skype_id ?>?chat" title="Skype: <?php echo $skype_id ?>" class="skype">
                        <img src="<?php bloginfo('stylesheet_directory'); ?>/images/skype.png" alt="<?php echo $skype_id ?>" border="0" />
                    </a>
                    <?php endif; ?>
                    <a><img alt="Viber" src="<?php bloginfo('stylesheet_directory'); ?>/images/viber.png" /></a>
                    <a><img alt="Zalo" src="<?php bloginfo('stylesheet_directory'); ?>/images/zalo.png" /></a>
                </div>
                <?php if(!empty($phone)): ?>
                <div class="hotline"><?php echo $phone ?></div>
                <?php
                endif;
                if(!empty($email)):
                ?>
                <div class="email"><a href="mailto:<?php echo $email ?>"><?php echo $email ?></a></div>
                <?php endif; ?>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
    
    <?php get_template_part('template/product-most-view'); ?>
    
    <?php if ( is_active_sidebar( 'sidebar' ) ) { dynamic_sidebar( 'sidebar' ); } ?>
</div><!-- #sidebar -->