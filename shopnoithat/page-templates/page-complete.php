<?php
/*
  Template Name: Ngan Luong Complete
 */
?>
<?php
//Lấy thông tin giao dịch
$transaction_info = $_GET["transaction_info"];
//Lấy mã đơn hàng 
$order_code = $_GET["order_code"];
//Lấy tổng số tiền thanh toán tại ngân lượng 
$price = $_GET["price"];
//Lấy mã giao dịch thanh toán tại ngân lượng
$payment_id = $_GET["payment_id"];
//Lấy loại giao dịch tại ngân lượng (1=thanh toán ngay ,2=thanh toán tạm giữ)
$payment_type = $_GET["payment_type"];
//Lấy thông tin chi tiết về lỗi trong quá trình giao dịch
$error_text = $_GET["error_text"];
//Lấy mã kiểm tra tính hợp lệ của đầu vào 
$secure_code = $_GET["secure_code"];
?>
<?php get_header(); ?>

<section id="main">
    <div class="container">
        <div class="row">
            <?php get_sidebar('left'); ?>

            <div class="col-lg-8 col-md-9 col-sm-12">
                <?php while (have_posts()) : the_post(); ?>
                <h1 class="page-title">Cảm ơn bạn đã đặt hàng!</h1>
                <div class="single-content">
                    <?php
                    $html = "";
                    global $nl_checkout;
                    $check = $nl_checkout->verifyPaymentUrl($transaction_info, $order_code, $price, $payment_id, $payment_type, $error_text, $secure_code);
                    if ($check){
                        global $wpdb;
                        $tblOrders = $wpdb->prefix . 'orders';
                        $nl_payment_id = $wpdb->get_var("SELECT nl_payment_id FROM $tblOrders WHERE nl_payment_id = $payment_id");
                        if(!$nl_payment_id){
                            $html .="<div align=\"center\">Cám ơn quý khách, quá trình thanh toán đã được hoàn tất. Chúng tôi sẽ kiểm tra và chuyển hàng sớm!</div>";
                            $coupon_amount = isset($_SESSION['coupon']) ? $_SESSION['coupon'] : 0;
                            $customer_id = isset($_SESSION['CUSTOMER_ID']) ? $_SESSION['CUSTOMER_ID'] : 0;
                            $customer_info = isset($_SESSION['CUSTOMER_INFO']) ? $_SESSION['CUSTOMER_INFO'] : json_encode(array());
                            $products = isset($_SESSION['PRODUCTS_CART']) ? $_SESSION['PRODUCTS_CART'] : json_encode(array());
                            $beforeVAT = isset($_SESSION['BEFORE_VAT']) ? $_SESSION['BEFORE_VAT'] : 0;
                            $afterVAT = isset($_SESSION['AFTER_VAT']) ? $_SESSION['AFTER_VAT'] : 0;
                            $referrer = $_COOKIE['ap_id'];
                            $payment_method = "Thanh toán khi nhận hàng qua Ngân Lượng";
                            switch ($payment_type) {
                                case 1:
                                    $payment_method = "Thanh toán ngay qua Ngân Lượng";
                                    break;
                                case 2:
                                    $payment_method = "Thanh toán tạm giữ qua Ngân Lượng";
                                    break;
                                default:
                                    break;
                            }

                            $result = $wpdb->query($wpdb->prepare("INSERT INTO $tblOrders SET customer_id = %d, customer_info = '%s', ship_info = '%s', 
                                payment_method = '%s', products = '%s', before_vat = '%s', after_vat = '%s', discount = '%s', total_amount = '%s', status = 1, 
                                nl_payment_id = '%s', nl_secure_code = '%s', affiliate_id = '%s'",
                                $customer_id, $customer_info, $ship_info, $payment_method, $products, $beforeVAT, $afterVAT, $coupon_amount, $price, $payment_id, $secure_code, $referrer));

                            if($result){
                                // Send invoice to email
                                sendInvoiceToEmail($customer_info);
                                // Remove Cart
                                unset($_SESSION['cart']);
                            } else {
                                $html = "Quá trình thanh toán không thành công bạn vui lòng thực hiện lại";
                            }
                        }
                    }else{
                        $html.="Quá trình thanh toán không thành công bạn vui lòng thực hiện lại";
                    }
                    echo $html;
                    ?>
                </div>
                <?php endwhile; ?>
            </div>

            <?php get_sidebar(); ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>