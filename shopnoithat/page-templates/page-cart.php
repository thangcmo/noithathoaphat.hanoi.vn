<?php
/*
  Template Name: Cart
 */
get_header();
?>

<section id="main">
    <div class="container pdb20">
        <?php
            if ( function_exists('yoast_breadcrumb') ) { yoast_breadcrumb('<div id="breadcrumbs">','</div>'); }

            if (empty($_SESSION['cart'])) {
                echo "<div class='cart-empty mt20'>Bạn chưa thêm sản phẩm nào vào giỏ!!!"
                . "<p>Hãy chọn những sản phẩm mà bạn yêu thích.</p>"
                . "<p><a href='" .  home_url() . "'>« Quay lại trang chủ</a></p>"
                . "</div>";
            } else {
        ?>
        <div class="table-responsive cartInfo">
            <table class="table table-bordered">
                <tr>
                    <th style="width: 98px">Hình ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Màu</th>
                    <th class="t_right">Đơn giá</th>
                    <th class="t_center">Chiết khấu</th>
                    <th style="width: 98px">Số lượng</th>
                    <th class="t_right">Thành tiền</th>
                    <th style="width: 50px">Xoá</th>
                </tr>
                <?php
                if (isset($_SESSION['cart']) and ! empty($_SESSION['cart'])):
                    $cart = $_SESSION['cart'];
                    $totalAmount = 0;
                    foreach ($cart as $product) :
                        $totalAmount += $product['amount'];
                        $product_id = $product['id'];
                        $permalink = get_permalink($product_id);
                        $title = get_the_title($product_id);
                        ?>
                        <tr id="product_item_<?php echo $product_id; ?>">
                            <td class="thumb">
                                <a href="<?php echo $permalink; ?>" title="<?php echo $title; ?>" target="_blank">
                                    <img style="width: 80px" src="<?php echo $product['thumb']; ?>" alt="" />
                                </a>
                            </td>
                            <td><a href="<?php echo $permalink; ?>" title="<?php echo $title; ?>" target="_blank"><?php echo $title; ?></a></td>
                            <td><?php _e('Color', SHORT_NAME); ?>: <?php echo $product['color']; ?></td>
                            <td class="t_right"><?php echo number_format($product['price'], 0, ',', '.'); ?> đ</td>
                            <td class="t_center discount">-<?php echo number_format($product['discount'], 0, ',', '.'); ?>%</td>
                            <td class="quantity">
                                <a href="#" class="plus">+</a>
                                <input type="text" data-value="<?php echo $product['quantity']; ?>" value="<?php echo $product['quantity']; ?>" class="qtyval" />
                                <a href="#" class="minus">-</a>
                                <input type="hidden" id="qtyval" value="<?php echo $product['quantity']; ?>" onchange="AjaxCart.updateItem(<?php echo $product_id; ?>, this.value)" />
                            </td>
                            <td class="t_right"><span class="product-subtotal"><?php echo number_format($product['amount'], 0, ',', '.'); ?> đ</span></td>
                            <td class="delete" style="width: 50px">
                                <a href="#" onclick="if (confirm('Are you sure you want to remove this item from your cart?')) {
                                        AjaxCart.deleteItem(<?php echo $product_id; ?>);
                                    }
                                return false;" title="<?php _e('Remove', SHORT_NAME); ?>"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/btnDel.png"/></a>
                            </td>
                        </tr>
                        <?php
                    endforeach;
                endif;
                ?>
            </table>
        </div>
        <div class="cart-price">
            <span><?php _e('Total', SHORT_NAME); ?>: </span> <span class="total_price"><?php echo number_format($totalAmount, 0, ',', '.'); ?> đ</span>
        </div>
        <div class="btnCart">
            <a href="<?php echo home_url(); ?>"><?php _e('Continue shopping', SHORT_NAME); ?></a>
            <a href="javascript://" onclick="AjaxCart.preCheckout();"><?php _e('Checkout', SHORT_NAME); ?></a>
        </div>
        <?php }?>
    </div>
</section>

<?php get_footer(); ?>