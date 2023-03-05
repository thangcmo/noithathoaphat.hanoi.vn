<?php
/**
 * The template for displaying Search Results pages
 */
get_header();
?>

<section id="main">
    <div class="container">
        <div class="row">
            <?php get_sidebar('left'); ?>

            <div class="col-lg-8 col-md-9 col-sm-12">
                <h1 class="archive-title"><?php printf(__('Search results for: "%s"', SHORT_NAME), get_search_query()); ?></h1>

                <?php
                if (have_posts()) :
                    $post_type = getRequest('post_type');
                    if($post_type == 'product'){
                        include 'template/search-product.php';
                    } else {
                        include 'template/search-post.php';
                    }

                    // Previous/next post navigation.
                    getpagenavi();

                else :
                ?>
                <p>Không có kết quả nào được tìm thấy, hãy tìm kiếm bằng một từ khoá khác.</p>
                <?php get_search_form(); ?>
                <?php endif; ?>
            </div>

            <?php get_sidebar(); ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>