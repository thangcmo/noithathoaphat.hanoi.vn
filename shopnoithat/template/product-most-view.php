<?php
$products = new WP_Query(array(
    'post_type' => 'product',
    'meta_key' => 'post_views_count',
    'orderby' => 'meta_value_num',
    'order' => 'DESC',
    'showposts' => 6,
));
if($products->found_posts > 0):
?>
<div class="widget">
    <h3 class="widget-title"><?php _e('Most view products', SHORT_NAME) ?></h3>
    <div class="widget-content">
        <?php
        while ($products->have_posts()) : $products->the_post();
            get_template_part('template/product-left');
        endwhile;
        ?>
    </div>
</div>
<?php endif; ?>