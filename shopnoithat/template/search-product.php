<div class="product-grid-container">
    <section class="widget">
        <div class="widget-content">
            <div class="row">
                <?php
                $count = 1;
                while (have_posts()) : the_post();
                    get_template_part('template/product-item');
                    if($count % 4 == 0) echo '<div class="clearfix"></div>';
                    $count++;
                endwhile;
                ?>
            </div>
        </div>
    </section>
</div>