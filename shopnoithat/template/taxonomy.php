<?php
get_header();
$term = get_queried_object();
?>

<section id="main">
    <div class="container">
        <div class="row">
            <?php get_sidebar('left'); ?>

            <div class="col-lg-8 col-md-9 col-sm-12">
                <?php if ( function_exists('yoast_breadcrumb') ) { yoast_breadcrumb('<div id="breadcrumbs">','</div>'); } ?>

                <div class="product-grid-container">
                    <section class="widget">
                        <div class="widget-description"><?php echo term_description( $term->term_id, $taxonomy ) ?></div>
                        <h2 class="widget-title"><?php echo single_cat_title() ?></h2>
                        <div class="widget-toolbar">
                            <div class="sortable">
                                <strong><?php _e('Sort by') ?>: </strong>
                                <a rel="nofollow" href="<?php echo get_term_link($term, $taxonomy); ?>?orderby=title&order=ASC"><span data-i18n="search.nameAZ">Tên (A-Z)</span></a> | 
                                <a rel="nofollow" href="<?php echo get_term_link($term, $taxonomy); ?>?orderby=title&order=DESC"><span data-i18n="search.nameZA">Tên (Z-A)</span></a> | 
                                <a rel="nofollow" href="<?php echo get_term_link($term, $taxonomy); ?>?orderby=price&order=DESC"><span data-i18n="search.priceDESC">Giá giảm dần</span></a> | 
                                <a rel="nofollow" href="<?php echo get_term_link($term, $taxonomy); ?>?orderby=price&order=ASC"><span data-i18n="search.priceASC">Giá tăng dần</span></a>
                            </div>
                            <div class="filterby">
                                <strong><?php _e('Filter by') ?></strong>
                                <?php wp_dropdown_categories( array(
                                    'show_option_all' => __('-- Select a price --', SHORT_NAME),
                                    'taxonomy' => 'product_price',
                                    'hide_empty' => 0,
                                    'hierarchical' => true,
                                    'name' => 'pricefilter',
                                    'selected' => intval(getRequest('pricefilter')),
                                ) ); ?>
                            </div>
                        </div>
                        <div class="widget-content">
                            <div class="row">
                                <?php
                                if(have_posts()):
                                    $count = 1;
                                    while (have_posts()) : the_post();
                                        get_template_part('template/product-item');
                                        if($count % 4 == 0) echo '<div class="clearfix"></div>';
                                        $count++;
                                    endwhile;
                                else:
                                ?>
                                <div class="col-sm-12">
                                    <p>Không có sản phẩm nào được tìm thấy trong chuyên mục này.</p>
                                    <?php get_search_form(); ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </section>
                </div>

                <?php getpagenavi();?>
            </div>

            <?php get_sidebar(); ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>