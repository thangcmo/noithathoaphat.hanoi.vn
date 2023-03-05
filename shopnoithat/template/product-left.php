<div class="product_left">
    <div class="product_left_img">
        <a href="<?php the_permalink() ?>" title="<?php the_title() ?>" rel="bookmark">
            <?php the_post_thumbnail('thumbnail') ?>
        </a>
    </div>
    <div class="product_left_title">
        <a href="<?php the_permalink() ?>" title="<?php the_title() ?>" rel="bookmark"><?php the_title() ?></a>
    </div>
    <div class="product_size"><?php echo get_post_meta(get_the_ID(), 'size', true) ?></div>
    <?php
    $price = floatval(get_post_meta(get_the_ID(), "price", true));
    $price_vat = $price + $price * 0.1;
    if($price>0){
    ?>
    <div>Chưa VAT: <span><?php echo number_format(floatval($price), 0, ',', '.'); ?> đ</span></div>
    <!--<div class="price-vat">Có VAT 10%: <span><?php echo number_format(floatval($price_vat), 0, ',', '.'); ?> đ</span></div>-->
    <?php }else{ ?>
    <p>Giá: Liên hệ</p>
    <?php } ?>
</div>