<?php

class Category_Product_Grid_Widget extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'cat-product-grid-widget', 'description' => __('Show product by category.'));
        $control_ops = array('id_base' => 'cat_product_grid_widget');
        parent::__construct('cat_product_grid_widget', 'PPO: Products Grid', $widget_ops, $control_ops);
    }

    /**
     * Displays category posts widget on blog.
     *
     * @param array $instance current settings of widget .
     * @param array $args of widget area
     */
    function widget($args, $instance) {
        global $post;
        extract($args);

        $taxonomy = 'product_cat';
        $title = apply_filters('title', $instance['title']);
        $term_id = trim($instance["cat"]);
        if($term_id > 0):
            $category_info = get_term($term_id, $taxonomy);
            // If not title, use the name of the category.
            if (!$instance['title']) {
                $title = $category_info->name;
            }

            echo $before_widget;
            // Widget title
            echo $before_title;
            ?>
            <a href="<?php echo get_term_link($category_info, $taxonomy) ?>" title="<?php echo ucfirst($category_info->name); ?>" class="title_link_a"><?php echo ucfirst($category_info->name); ?></a>
            <?php echo $after_title; ?>
            <div class="widget-content">
                <div class="row">
                    <?php
                    $cat_posts = new WP_Query(array(
                        'post_type' => 'product',
                        'showposts' => $instance["num"],
                        'tax_query' => array(
                            array(
                                'taxonomy' => $taxonomy,
                                'field' => 'id',
                                'terms' => $term_id,
                            )
                        ),
                    ));
                    $count = 1;
                    while ($cat_posts->have_posts()) : $cat_posts->the_post();
                    ?>
                    <div class="col-sm-3 col-xs-6">
                        <div class="item">
                            <div class="thumbnail">
                                <a title="<?php the_title(); ?>" href="<?php the_permalink(); ?>" rel="bookmark">
                                    <?php the_post_thumbnail('thumbnail'); ?>
                                </a>
                            </div>  
                            <h4><a title="<?php the_title(); ?>" href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h4>
                            <div class="product_size"><?php echo get_post_meta(get_the_ID(), 'size', true) ?></div>
                            <div class="price">
                                <?php
                                $price = floatval(get_post_meta(get_the_ID(), "price", true));
                                $price_vat = $price + $price * 0.1;
                                if($price>0){
                                ?>
                                <p>Chưa VAT: <span><?php echo number_format(floatval($price), 0, ',', '.'); ?> đ</span></p>
                                <!--<p class="price-vat">Có VAT 10%: <span><?php //echo number_format(floatval($price_vat), 0, ',', '.'); ?> đ</span></p>-->
                                <?php }else{ ?>
                                <p></p>
                                <p>Giá: Liên hệ</p>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php
                        if($count % 4 == 0) echo '<div class="clearfix"></div>';
                        $count++;
                    endwhile;
                    wp_reset_query();
                    ?>
                </div>
            </div>
            <?php
            echo $after_widget;
        endif;
    }

    /**
     * Form processing...
     *
     * @param array $new_instance of widget .
     * @param array $old_instance of widget .
     */
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['cat'] = $new_instance['cat'];
        $instance['num'] = $new_instance['num'];
        return $instance;
    }

    /**
     * The configuration form.
     *
     * @param array $instance of widget to display already stored value .
     * 
     */
    function form($instance) {
        ?>		
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', SHORT_NAME) ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
        </p>
        <p>
            <label><?php _e('Category', SHORT_NAME) ?></label><br />
            <?php 
            wp_dropdown_categories(array(
                'name' => $this->get_field_name("cat"), 
                'taxonomy' => 'product_cat', 
                'hide_empty' => 0, 
                'selected' => $instance["cat"],
                'hierarchical' => true,
                'class' => 'widefat',
            ));
            ?>
        </p>
        <p>
            <label><?php _e('Number', SHORT_NAME) ?></label><br />
            <input class="widefat" id="<?php echo $this->get_field_id("num"); ?>" name="<?php echo $this->get_field_name("num"); ?>" type="text" value="<?php echo intval($instance["num"]); ?>" />
        </p>
        <?php
    }

}
