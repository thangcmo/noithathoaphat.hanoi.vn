<?php get_header(); ?>

<section id="main">
    <div class="container">
        <div class="row">
            <?php get_sidebar('news'); ?>

            <div class="col-lg-8 col-md-9 col-sm-12">
                <?php if ( function_exists('yoast_breadcrumb') ) { yoast_breadcrumb('<div id="breadcrumbs">','</div>'); } ?>

                <h1 class="archive-title"><?php echo single_cat_title() ?></h1>
                <div class="archive-content">
                    <?php
                    if(have_posts()):
                        while (have_posts()) : the_post();
                            get_template_part('content', get_post_format());
                        endwhile;
                    else:
                    ?>
                    <p>Không có bài viết nào được tìm thấy trong chuyên mục này.</p>
                    <?php get_search_form(); ?>
                    <?php endif; ?>
                </div>

                <?php getpagenavi();?>
            </div>

            <?php get_sidebar(); ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>