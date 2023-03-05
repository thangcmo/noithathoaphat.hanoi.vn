<div class="col-sm-3 col-xs-6">
    <div class="item">
        <div class="thumbnail">
            <a title="<?php the_title(); ?>" href="<?php the_permalink(); ?>" rel="bookmark">
                <?php the_post_thumbnail('thumbnail'); ?>
            </a>
        </div>  
        <h3><a title="<?php the_title(); ?>" href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h3>
        <div class="product_size"><?php echo get_post_meta(get_the_ID(), 'size', true) ?></div>
        <div class="price">
            <?php
            $price = floatval(get_post_meta(get_the_ID(), "price", true));
            $price_vat = $price + $price * 0.1;
            if($price>0){
            ?>
            <p>Chưa VAT: <span><?php echo number_format(floatval($price), 0, ',', '.'); ?> đ</span></p>
            <!--<p class="price-vat">Có VAT 10%: <span><?php echo number_format($price_vat, 0, ',', '.'); ?> đ</span></p>-->
            <?php }else{ ?>
            <p></p>
            <p>Giá: Liên hệ</p>
            <?php } ?>
        </div>
    </div>
</div>