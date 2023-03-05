<?php
/*
  Template Name: Báo giá
 */
get_header(); ?>

<section id="main">
    <div class="container">
        <div class="row">
            <?php get_sidebar('left'); ?>

            <div class="col-lg-8 col-md-9 col-sm-12">
                <h1 class="archive-title mb20">Báo giá tổng hợp</h1>

                <form id="search_price" method="get" action="<?php echo get_page_link(get_option(SHORT_NAME . "_pagePriceResult")); ?>">
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <input class="form-control" type="text" name="s" value="" placeholder="Nhập từ khóa tìm kiếm" />
                        </div>
                        <div class="form-group col-sm-6">
                            <?php wp_dropdown_categories( array(
                                'show_option_all' => __('-- Select a category --', SHORT_NAME),
                                'taxonomy' => 'product_cat',
                                'hide_empty' => 0,
                                'hierarchical' => true,
                                'name' => 'pcat',
                                'selected' => intval(getRequest('pcat')),
                                'class' => 'form-control'
                            ) ); ?>
                        </div>
                        <div class="form-group col-sm-6">
                            <?php wp_dropdown_categories( array(
                                'show_option_all' => __('-- Select a brand --', SHORT_NAME),
                                'taxonomy' => 'product_factor',
                                'hide_empty' => 0,
                                'hierarchical' => true,
                                'name' => 'factor',
                                'selected' => intval(getRequest('factor')),
                                'class' => 'form-control'
                            ) ); ?>
                        </div>
                        <div class="form-group col-sm-6">
                            <?php wp_dropdown_categories( array(
                                'show_option_all' => __('-- Select a price --', SHORT_NAME),
                                'taxonomy' => 'product_price',
                                'hide_empty' => 0,
                                'hierarchical' => true,
                                'name' => 'price',
                                'selected' => intval(getRequest('price')),
                                'class' => 'form-control'
                            ) ); ?>
                        </div>
                    </div>
                    <input type="submit" value="<?php _e('Search', SHORT_NAME) ?>" class="btn btn-primary" />
                </form>
            </div>

            <?php get_sidebar(); ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>