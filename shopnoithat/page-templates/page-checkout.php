<?php
/*
  Template Name: Checkout
 */

get_header();
global $current_user;
get_currentuserinfo();
$cities = vn_city_list();
?>

<section id="main">
    <div class="container">
        <?php if ( function_exists('yoast_breadcrumb') ) { yoast_breadcrumb('<div id="breadcrumbs">','</div>'); } ?>

        <div class="area-content">
            <div id="content" role="main" class="site-content page-cart">
                <!--BEGIN PAGE CHECKOUT-->
                <form action="" method="post" id="frmOrder">
                    <input type="hidden" name="action" value="orderComplete" />
                    <input type="hidden" name="locale" value="<?php echo getLocale(); ?>" />
                    <div class="checkout row mt20 mb20">
                        <div class="customer-info col-sm-5">
                            <div class="customer">
                                <div class="title"><?php _e('Customer Information', SHORT_NAME) ?></div>
                                <div class="form-group">
                                    <input name="cName" type="text" placeholder="<?php _e('Fullname', SHORT_NAME) ?>" class="form-control" value="<?php echo (is_user_logged_in()) ? $current_user->display_name : ""; ?>" />
                                </div>
                                <div class="form-group">
                                    <input name="cEmail" type="text" placeholder="<?php _e('E-mail address', SHORT_NAME) ?>" class="form-control" value="<?php echo (is_user_logged_in()) ? $current_user->user_email : ""; ?>" />
                                </div>
                                <div class="form-group">
                                    <input name="cPhone" type="text" placeholder="<?php _e('Phone number', SHORT_NAME) ?>" class="form-control" value="<?php echo (is_user_logged_in()) ? esc_attr(get_the_author_meta('user_phone', $current_user->ID)) : ""; ?>" />
                                </div>
                                <div class="form-group">
                                    <input name="cAddress" type="text" placeholder="<?php _e('Address', SHORT_NAME) ?>" class="form-control" value="<?php echo (is_user_logged_in()) ? esc_attr(get_the_author_meta('user_address1', $current_user->ID)) : ""; ?>" />
                                </div>
                                <div class="form-group">
                                    <select name="cCity" class="form-control">
                                        <?php
                                        foreach ($cities as $city) {
                                            if (esc_attr(get_the_author_meta('user_city', $current_user->ID)) == $city) {
                                                echo "<option value=\"$city\" selected>$city</option>";
                                            } else {
                                                echo "<option value=\"$city\">$city</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type="hidden" name="ship" value="0" />
                                    <input type="checkbox" id="ship" name="ship" />
                                    <label for="ship" style="font-weight: normal"><?php _e('Other shipping address', SHORT_NAME) ?></label>
                                </div>
                            </div>
                            <div class="shiping" id="divShip" style="display: none">
                                <div class="title"><?php _e('Shipping Information', SHORT_NAME) ?></div>
                                <div class="form-group">
                                    <input name="shipName" class="form-control" type="text" placeholder="<?php _e('Fullname', SHORT_NAME) ?>"/>
                                </div>
                                <div class="form-group">
                                    <input name="shipEmail" class="form-control" type="text" placeholder="<?php _e('E-mail address', SHORT_NAME) ?>"/>
                                </div>
                                <div class="form-group">
                                    <input name="shipPhone" class="form-control" type="text" placeholder="<?php _e('Phone number', SHORT_NAME) ?>"/>
                                </div>
                                <div class="form-group">
                                    <input name="shipAddress" class="form-control" type="text" placeholder="<?php _e('Address', SHORT_NAME) ?>"/>
                                </div>
                                <div class="form-group">
                                    <select name="shipCity" class="form-control">
                                        <?php
                                        foreach ($cities as $city) {
                                            if (esc_attr(get_the_author_meta('user_city', $current_user->ID)) == $city) {
                                                echo "<option value=\"$city\" selected>$city</option>";
                                            } else {
                                                echo "<option value=\"$city\">$city</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="cart-info col-sm-7">
                            <div class="payment-info">
                                <div class="title"><?php _e('Payment Method', SHORT_NAME) ?></div>
                                <div class="PaymentMethod">
                                    <div class="PaymentMethod_Name">
                                        <input type="radio" name="payment_method" value="<?php _e('Cost on delivery', SHORT_NAME) ?>" id="ck1" checked>
                                        <label for="ck1"><?php _e('Cost on delivery', SHORT_NAME) ?></label>
                                    </div>
                                    <div class="PaymentMethod_Info" id="method1">
                                        <?php echo stripslashes(get_option('payment_cashOnDelivery')); ?>
                                    </div>
                                </div>
                                <div class="PaymentMethod">
                                    <div class="PaymentMethod_Name">
                                        <input type="radio" name="payment_method" value="<?php _e('Payment at office', SHORT_NAME) ?>" id="ck2">
                                        <label for="ck2"><?php _e('Payment at office', SHORT_NAME) ?></label>
                                    </div>
                                    <div class="PaymentMethod_Info" id="method2" style="display: none;">
                                        <?php echo stripslashes(get_option('payment_atOffice')); ?>
                                    </div>
                                </div>
                                <div class="PaymentMethod">
                                    <div class="PaymentMethod_Name">
                                        <input type="radio" name="payment_method" value="<?php _e('Online payment', SHORT_NAME) ?>" id="ck3">
                                        <label for="ck3"><?php _e('Online payment', SHORT_NAME) ?></label>
                                    </div>
                                    <div class="PaymentMethod_Info" id="method3" style="display: none;">
                                        <?php echo stripslashes(get_option('payment_atNganLuong')); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="cartCheckout">
                                <table style="width: 100%">
                                    <tbody>
                                        <?php
                                        if (isset($_SESSION['cart']) and ! empty($_SESSION['cart'])):
                                            $cart = $_SESSION['cart'];
                                            $totalAmount = 0;
                                            foreach ($cart as $product) :
                                                $totalAmount += $product['amount'];
                                                ?>
                                                <tr>
                                                    <td>
                                                        <span class="uppercase"><?php echo get_the_title($product['id']); ?></span><br />
                                                        <span class="cart-meta">
                                                            <?php _e('Color', SHORT_NAME); ?>: <?php echo $product['color']; ?><br />
                                                            <?php _e('Quantity', SHORT_NAME); ?>: <?php echo $product['quantity']; ?><br />
                                                            <?php _e('Discount', SHORT_NAME); ?>: <?php echo number_format($product['discount'], 0, ',', '.'); ?>%
                                                        </span>
                                                    </td>
                                                    <td class="t_right"><?php echo number_format($product['amount'], 0, ',', '.'); ?> đ</td>
                                                </tr>
                                            <?php 
                                            endforeach;
                                            $vat = $totalAmount * 0.1;
                                            $totalPay = $totalAmount + $vat;
                                        endif;
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="t_right">
                                <div class="cartInfo-price">
                                    <span><?php _e('Total', SHORT_NAME) ?>:</span>
                                    <span class="totalAmount"><?php echo number_format($totalAmount, 0, ',', '.'); ?> đ
                                </div>
                                <!--<div class="cartInfo-price">
                                    <span><?php _e('VAT (10%)', SHORT_NAME) ?>:</span>
                                    <span class="bold"><?php //echo number_format($vat, 0, ',', '.'); ?> đ
                                </div>
                                <div class="cartInfo-price">
                                    <span><?php _e('Total pay', SHORT_NAME) ?>:</span>
                                    <span class="bold t_red"><?php //echo number_format($totalPay, 0, ',', '.'); ?> đ
                                </div>-->
                                <?php /*<div class="coupon-area"><?php _e('Coupon code', SHORT_NAME); ?>: <input type="text" name="coupon_code" id="coupon_code" value="" placeholder="<?php _e('Coupon code', SHORT_NAME); ?>"/></div>*/ ?>
                                <input type="hidden" id="beforeVAT" name="beforeVAT" value="<?php echo $totalAmount; ?>" />
                                <input type="hidden" id="afterVAT" name="afterVAT" value="<?php echo $totalPay; ?>" />
                                <input type="hidden" id="total_amount" name="total_amount" value="<?php echo $totalPay; ?>" />
                                <div class="btnCart">
                                    <a href="<?php echo get_page_link(get_option(SHORT_NAME . "_pageCartID")); ?>"><?php _e('Back to Cart', SHORT_NAME); ?></a>
                                    <a href="javascript://" id="btnNganLuong" style="display: none"><?php _e('Process To Payment', SHORT_NAME); ?></a>
                                    <a href="javascript://" id="btnMuaHang"><?php _e('Process To Payment', SHORT_NAME); ?></a>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </form>
                <!--END PAGE CHECKOUT-->
                <script type="text/javascript">
                    jQuery(document).ready(function($){
                        $('#ship').change(function(){
                            if(this.checked){
                                $('#divShip').fadeIn('fast');
                                $('input[name=ship]').val(1);
                            }else{
                                $('#divShip').fadeOut('normal');
                                $('input[name=ship]').val(0);
                            }
                        });
                        $(window).load(function(){
                            if($('#ship').get(0).checked){
                                $('#divShip').fadeIn('fast');
                                $('input[name=ship]').val(1);
                            }else{
                                $('#divShip').fadeOut('normal');
                                $('input[name=ship]').val(0);
                            }
                            $("#ck1").click();
                        });

                        if($("#ck2").is(":checked")){
                            $("#method1").hide();
                            $("#method2").show();
                            $("#method3").hide();
                            $("#btnNganLuong").hide();
                            $("#btnMuaHang").show();
                        }
                        if($("#ck3").is(":checked")){
                            $("#method1").hide();
                            $("#method2").hide();
                            $("#method3").show();
                            $("#btnNganLuong").show();
                            $("#btnMuaHang").hide();
                        }

                        /* switch payment method */
                        $("#ck1").click(function(){
                            $("#method1").show();
                            $("#method2").hide();
                            $("#method3").hide();
                            $("#btnNganLuong").hide();
                            $("#btnMuaHang").show();
                        });
                        $("#ck2").click(function(){
                            $("#method1").hide();
                            $("#method2").show();
                            $("#method3").hide();
                            $("#btnNganLuong").hide();
                            $("#btnMuaHang").show();
                        });
                        $("#ck3").click(function(){
                            $("#method1").hide();
                            $("#method2").hide();
                            $("#method3").show();
                            $("#btnNganLuong").show();
                            $("#btnMuaHang").hide();
                        });

                        // Complete order
                        $("#btnMuaHang").click(function(){
                            if(validate_info() && $("#btnMuaHang").is(":visible")){
                                $("#frmOrder input[name=action]").val('orderComplete');
                                AjaxCart.orderComplete($("#frmOrder").serialize());
                            }else{
                                displayBarNotification(true, "nWarning", "Vui lòng nhập đầy đủ thông tin.");
                            }
                        });
                        $("#btnNganLuong").click(function(){
                            if(validate_info() && $("#btnNganLuong").is(":visible")){
                                $("#frmOrder input[name=action]").val('orderNganLuong');
                                AjaxCart.orderNganLuong($("#frmOrder").serialize());
                            }else{
                                displayBarNotification(true, "nWarning", "Vui lòng nhập đầy đủ thông tin.");
                            }
                            return false;
                        });

                        function validate_info() {
                            var valid = true;
                            $(".customer input[type=text], .customer select").each(function () {
                                if ($(this).val().length == 0) {
                                    $(this).parent().addClass('has-error');
                                    valid = false;
                                } else {
                                    $(this).parent().removeClass('has-error');
                                }
                            });
                            if ($('#ship').is(":checked")) {
                                $(".shiping input[type=text], .shiping select").each(function () {
                                    if ($(this).val().length == 0) {
                                        $(this).parent().addClass('has-error');
                                        valid = false;
                                    } else {
                                        $(this).parent().removeClass('has-error');
                                    }
                                });
                            } else {
                                $(".shiping input[type=text], .shiping select").each(function () {
                                    $(this).parent().removeClass('has-error');
                                });
                            }
                            return valid;
                        }
                    });
                </script>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>