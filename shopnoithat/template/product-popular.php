<?php
$products = new WP_Query(array(
    'post_type' => 'product',
    'showposts' => 6,
    'meta_query' => array(
        array(
            'key' => 'sale',
            'value' => '0',
            'compare' => '>'
        ),
    ),
));
if($products->found_posts > 0):
?>
<div class="widget">
    <h3 class="widget-title"><?php _e('Promotional', SHORT_NAME) ?></h3>
    <div class="widget-content">
        <?php
        while ($products->have_posts()) : $products->the_post();
            get_template_part('template/product-left');
        endwhile;
        ?>
    </div>
</div>
<?php endif; ?>