<?php get_header(); ?>

<section id="main">
    <div class="container">
        <div class="row">
            <?php get_sidebar('news'); ?>

            <div class="col-lg-8 col-md-9 col-sm-12">
                <?php
                // Breadcrumbs
                if ( function_exists('yoast_breadcrumb') ) { yoast_breadcrumb('<div id="breadcrumbs">','</div>'); }

                // Start the Loop.
                while (have_posts()) : the_post();

                    /*
                     * Include the post format-specific template for the content. If you want to
                     * use this in a child theme, then include a file called called content-___.php
                     * (where ___ is the post format) and that will be used instead.
                     */
                    get_template_part('content', get_post_format());

                    // Previous/next post navigation.
                    ppo_post_nav();

                    $terms = get_the_category();
                    $terms_id = array();
                    foreach ($terms as $term) {
                        array_push($terms_id, $term->term_id);
                    }
                    $loop = new WP_Query(array(
                        'post_type' => 'post',
                        'cat' => $terms_id,
                        'post__not_in' => array(get_the_ID()),
                    ));
                    if($loop->found_posts > 0):
                ?>
                    <div class="related-posts">
                        <h2 class="title"><?php _e('Related Posts', SHORT_NAME) ?></h2>
                        <ul>
                            <?php while ($loop->have_posts()) : $loop->the_post(); ?>
                            <li><a href="<?php the_permalink() ?>" title="<?php the_title() ?>"  rel="bookmark"><?php the_title() ?></a></li>
                            <?php endwhile; ?>
                        </ul>
                    </div>
                <?php
                    endif;

                    // If comments are open or we have at least one comment, load up the comment template.
                    if (comments_open() || get_comments_number()) {
                        comments_template();
                    }
                endwhile;
                ?>
            </div>

            <?php get_sidebar(); ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>