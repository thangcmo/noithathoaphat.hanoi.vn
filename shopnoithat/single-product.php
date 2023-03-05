<?php
get_header();
setPostViews(get_the_ID());
?>

<section id="main">
    <div class="container">
        <div class="row">
            <?php get_sidebar('left'); ?>

            <div class="col-lg-8 col-md-9 col-sm-12">
                <?php if ( function_exists('yoast_breadcrumb') ) { yoast_breadcrumb('<div id="breadcrumbs">','</div>'); } ?>

                <?php while (have_posts()) : the_post(); ?>
                <article id="product-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="http://schema.org/Product">
                    <div class="product-view">
                        <div class="product-essential">
                            <div class="product-name show-small">
                                <h2 class="product-title"><?php the_title(); ?></h2>
                            </div>
                            <div class="product-img-box">
                                <div class="product-image product-image-zoom">
                                    <a class="fancybox" id="main-image" title="" href="<?php get_image_url(); ?>">
                                        <img id="image" src="<?php get_image_url(); ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" itemprop="image" />
                                    </a>
                                </div>

                                <div class="more-views">
                                    <ul>
                                        <?php
                                        $images = rwmb_meta( 'product_images', array(
                                            'type' => 'image_advanced',
                                            'size' => '80x80'
                                        ));
                                        foreach ($images as $image) {
                                        ?>
                                        <li>
                                            <a id="thumbnail-image" href="<?php echo $image['full_url']; ?>" onclick="swap(this); return false;" title="<?php echo $image['title']; ?>">
                                                <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['title']; ?>" />
                                            </a>
                                        </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                                <script>
                                    function swap(link) {
                                        document.getElementById('image').src = link.href;
                                        document.getElementById('main-image').href = link.href;
                                    }
                                </script>
                            </div><!--/.product-img-box-->

                            <div class="product-shop">
                                <div class="product-main-info">
                                    <div class="product-name hide-small">
                                        <h1 class="product-title" itemprop="name"><?php the_title(); ?></h1>
                                    </div>
                                    <p class="product-code">
                                        <?php _e('Product Code', SHORT_NAME) ?>: <span itemprop="sku"><?php echo get_post_meta(get_the_ID(), 'code', true) ?></span>
                                    </p>
                                    <p class="product-size">
                                        <?php _e('Size', SHORT_NAME) ?>: <?php echo get_post_meta(get_the_ID(), 'size', true) ?>
                                    </p>
                                    <p>
                                        <strong>Liên hệ hotline, zalo, viber: <a href="tel:<?php echo get_option(SHORT_NAME . "_hotline"); ?>"><span class="t_red"><?php echo get_option(SHORT_NAME . "_hotline"); ?></span></a></strong>
                                    </p>
                                    <div style="border-bottom:1px solid #cccccc; height:1px;margin-bottom: 10px">&nbsp;</div>
                                    <p class="categories">
                                        <?php _e('Categories', SHORT_NAME) ?>: <?php the_terms(get_the_ID(), 'product_cat') ?>
                                    </p>
                                    <p class="brand">
                                        <?php _e('Brand', SHORT_NAME) ?>: <span itemprop="brand"><?php
                                        echo "Hoà Phát";
                                        /*$factors = get_the_terms(get_the_ID(), 'product_factor');
                                        foreach ($factors as $factor) {
                                            if(in_array($factor->term_id, array(10,11,12,13,125))){
                                                echo '<a href="'.  get_term_link($factor, 'product_factor').'" title="'.$factor->name.'">'.$factor->name.'</a>';
                                                break;
                                            }
                                        }*/
                                    ?></span>
                                    </p>
                                    <p class="guarantee">
                                        <?php _e('Guarantee', SHORT_NAME) ?>: <?php echo get_post_meta(get_the_ID(), 'guarantee', true) ?>
                                    </p>
                                    <span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                                        <div class="status">
                                            <?php
                                            _e('Status: ', SHORT_NAME);
                                            $tinh_trang = get_post_meta(get_the_ID(), 'tinh_trang', true);
                                            $status = array(
                                                'in_stock' => __('<span class="t_green">In stock</span>', SHORT_NAME),
                                                'out_of_stock' => __('<span class="t_red">Out of stock</span>', SHORT_NAME),
                                                'coming_soon' => __('<span class="t_orange">Coming soon</span>', SHORT_NAME),
                                            );
                                            echo $status[$tinh_trang];
                                            ?>
                                        </div>
                                        <div class="price-box">
                                            <?php
                                            $discount = floatval(get_post_meta(get_the_ID(), "sale", true));
                                            $old_price = floatval(get_post_meta(get_the_ID(), "price", true));
                                            $price = $old_price * $discount / 100;
                                            if($price > 0){
                                            ?>
                                            <p class="old-price">
                                                <span class="price">
                                                    Chưa VAT: <?php echo number_format($old_price, 0, ',', '.'); ?> đ
                                                </span>
                                            </p>
                                            <p class="special-price">
                                                <span class="price">
                                                    Chưa VAT: <span itemprop="price"><?php echo number_format($price, 0, ',', '.'); ?></span> đ
                                                </span>
                                            </p>
                                            <!--<p class="price-vat">
                                                <span class="price">
                                                    Có VAT 10%: <?php //echo number_format(($price + $price * 0.1), 0, ',', '.'); ?> đ
                                                </span>
                                            </p>-->
                                            <?php } else { ?>
                                                <?php if($old_price > 0): ?>
                                                <p class="special-price">
                                                    <span class="price">
                                                        Chưa VAT: <span itemprop="price"><?php echo number_format($old_price, 0, ',', '.'); ?></span> đ
                                                    </span>
                                                </p>
                                                <!--<p class="price-vat">
                                                    <span class="price">
                                                        Có VAT 10%: <?php //echo number_format(($old_price + $old_price * 0.1), 0, ',', '.'); ?> đ
                                                    </span>
                                                </p>-->
                                                <?php else: ?>
                                                <p class="special-price">
                                                    <span class="price">
                                                        Giá: <span itemprop="price">Liên hệ</span>
                                                    </span>
                                                </p>
                                                <?php endif; ?>
                                            <?php } ?>
                                        </div>
                                        <meta content="₫" itemprop="priceCurrency" />
                                        <?php if($tinh_trang == 'in_stock'): ?>
                                        <link itemprop="availability" href="http://schema.org/InStock" />
                                        <?php elseif($tinh_trang == 'out_of_stock'): ?>
                                        <link itemprop="availability" href="http://schema.org/OutOfStock" />
                                        <?php endif; ?>
                                    </span>
                                </div>

                                <div class="color-list">
                                    <?php
                                    $colors = get_the_terms(get_the_ID(), 'product_color' );
                                    if ($colors && !is_wp_error($colors)) {
                                        $color_img = "";
                                        foreach ( $colors as $term ) {
                                            $tag_meta = get_option("tag_{$term->term_id}");
                                            $color_img .= '<img src="' . $tag_meta['img'] . '" alt="' . $term->name . '" data-id="' . $term->term_id . '" />';
                                        }
                                        echo $color_img;
                                    }
                                    ?>
                                </div>
                                <span class="error color-error"></span>
                                <div class="color-selected" data-id="0" data-text="<?php _e('None', SHORT_NAME); ?>">
                                    <?php _e('Color', SHORT_NAME); ?>: <span><?php _e('None', SHORT_NAME); ?></span>
                                </div>

                                <div class="add-to-box">
                                    <div class="add-to-cart">
                                        <span class="fl">Số lượng: </span>
                                        <span class="quantity fl">
                                            <a href="#" class="plus">+</a>
                                            <input type="text" data-value="1" value="1" class="qtyval" />
                                            <a href="#" class="minus">-</a>
                                            <input type="hidden" id="qtyval" value="1" />
                                        </span>
                                        <button type="button" title="<?php _e('Add to Bag', SHORT_NAME); ?>" class="button btn-cart" onclick="AjaxCart.addToCart(<?php the_ID(); ?>, '<?php get_image_url(); ?>', '<?php the_title(); ?>', <?php echo $old_price; ?>, document.getElementById('qtyval').value, '');">
                                            <span><span><i class="fa fa-cart-plus"></i> <?php _e('Add to Bag', SHORT_NAME); ?></span></span>
                                        </button>
                                    </div>

                                    <div class="share-social-box">
                                        <div class="fb-like" data-href="<?php echo getCurrentRquestUrl() ?>" data-layout="button" data-action="like" data-show-faces="true" data-share="true" style="margin-right: 5px;float: left;margin-top: 3px;"></div>
                                        <g:plusone></g:plusone>
                                    </div>
                                </div>
                                <!--<div class="short-description"><?php //the_excerpt(); ?></div>-->
                            </div><!--/.product-shop-->
                            <div class="clearfix"></div>
                        </div><!--/.product-essential-->

                        <h3 class="uppercase font20 mb5">Chi tiết sản phẩm</h3>
                        <div class="full-content">
                            <?php the_content(); ?>
                        </div><!--/.full-content-->
                    </div><!--/.product-view-->
                </article><!-- #product-## -->

                <?php
                $taxonomy = 'product_cat';
                $terms = get_the_terms(get_the_ID(), $taxonomy);
                $terms_id = array();
                foreach ($terms as $term) {
                    array_push($terms_id, $term->term_id);
                }
                $loop = new WP_Query(array(
                    'post_type' => 'product',
                    'tax_query' => array(
                        array(
                            'taxonomy' => $taxonomy,
                            'field' => 'id',
                            'terms' => $terms_id,
                        )
                    ),
                    'post__not_in' => array(get_the_ID()),
                ));
                if($loop->found_posts > 0):
                ?>
                <div class="product-grid-container related-products">
                    <section class="widget">
                        <h3 class="widget-title"><?php _e('Related Products', SHORT_NAME) ?></h3>
                        <div class="widget-content">
                            <div class="row">
                                <?php
                                $count = 1;
                                while ($loop->have_posts()) : $loop->the_post();
                                    get_template_part('template/product-item');
                                    if($count % 4 == 0) echo '<div class="clearfix"></div>';
                                    $count++;
                                endwhile;
                                ?>
                            </div>
                        </div>
                    </section>
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