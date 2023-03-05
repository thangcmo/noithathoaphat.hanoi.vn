<?php

/* ----------------------------------------------------------------------------------- */
# Quick edit
/* ----------------------------------------------------------------------------------- */
function get_product_meta(){
    $product_id = getRequest('product_id');
    $response = array(
        'price' => get_post_meta( $product_id, 'price', true ),
        'sale_price' => get_post_meta( $product_id, 'sale', true ),
    );
    
    Response(json_encode($response));
    
    exit();
}

/* ----------------------------------------------------------------------------------- */
# Add product to Cart
/* ----------------------------------------------------------------------------------- */
function addToCart(){
    $locale = getRequest("locale");
    $lang['added'] = ($locale == "vn") ? "Đã thêm vào giỏ hàng" : "Added to your cart";
    
    $id = intval(getRequest('id'));
    $price = getRequest('price');
    $quantity = intval(getRequest('quantity'));
    $discount = get_discount_value($id);
    $amount = ($price * $quantity) - ($price * $quantity * $discount/100);
    $product = array(
        'id' => $id,
        'thumb' => getRequest('thumb'),
        'title' => getRequest('title'),
        'color' => getRequest('color'),
        'price' => $price,
        'quantity' => $quantity,
        'discount' => $discount,
        'amount' => $amount,
    );
    
    if(isset($_SESSION['cart']) and !empty($_SESSION['cart'])){
        $addToCart = TRUE;
        $cart = $_SESSION['cart'];
        foreach ($cart as $k => $v) {
            if(getRequest('id') == $v['id']){
                if($v['quantity'] == $quantity and $v['price'] == $price){
                    $addToCart = FALSE;
                }else{
                    unset($cart[$k]);
                }
                break;
            }
        }
        if($addToCart == TRUE){
            array_push($cart, $product);
            $_SESSION['cart'] = $cart;
        }
    }else{
        $cart = array();
        array_push($cart, $product);
        $_SESSION['cart'] = $cart;
    }

    $cart = $_SESSION['cart'];
    $totalAmount = 0;
    foreach ($cart as $product) {
        $totalAmount += $product['amount'];
    }

    // Response message
    Response(json_encode(array(
        'status' => 'success',
        'countCart' => count($cart),
        'totalAmount' => number_format($totalAmount,0,',','.') . " đ",
        'message' => $lang['added'],
    )));
    exit();
}
/* ----------------------------------------------------------------------------------- */
# Remove a product in Cart
/* ----------------------------------------------------------------------------------- */
function deleteCartItem(){
    $locale = getRequest("locale");
    $lang['cart_empty'] = ($locale == "vn") ? "Bạn không có sản phẩm nào trong giỏ hàng" : "Your cart is empty";
    $lang['removed'] = ($locale == "vn") ? "Đã xóa sản phẩm khỏi giỏ hàng" : "Product removed from cart";
    
    if (isset($_SESSION['cart']) and !empty($_SESSION['cart'])) {
        $product_id = intval(getRequest('id'));
        if($product_id > 0){
            $cart = $_SESSION['cart'];
            $totalAmount = 0;
            foreach ($cart as $key => $product) {
                if($product['id'] == $product_id){
                    unset($cart[$key]);
                }else{
                    $totalAmount += $product['amount'];
                }
            }
            array_values($cart);
            $_SESSION['cart'] = $cart;

            Response(json_encode(array(
                'status' => 'success',
                'countCart' => count($cart),
                'totalAmount' => number_format($totalAmount,0,',','.') . " đ",
                'message' => $lang['removed'],
            )));
        }
    }else{
        Response(json_encode(array(
            'status' => 'error',
            'message' => $lang['cart_empty'],
        )));
    }
    exit();
}
/* ----------------------------------------------------------------------------------- */
# Update Cart
/* ----------------------------------------------------------------------------------- */
function updateCartItem(){
    $locale = getRequest("locale");
    $lang['cart_empty'] = ($locale == "vn") ? "Bạn không có sản phẩm nào trong giỏ hàng" : "Your cart is empty";
    $lang['cart_updated'] = ($locale == "vn") ? "Đã cập nhật giỏ hàng" : "Your cart has been updated";
    
    if (isset($_SESSION['cart']) and !empty($_SESSION['cart'])) {
        $product_id = intval(getRequest('id'));
        $quantity = intval(getRequest('quantity'));
        if($product_id > 0 and $quantity > 0){
            $cart = $_SESSION['cart'];
            $totalAmount = 0;
            $item_amount = 0;
            $discount = get_discount_value($product_id);
            foreach ($cart as $key => $product) {
                if($product['id'] == $product_id){
                    $amount = ($product['price'] * $quantity) - ($product['price'] * $quantity * $discount/100);
                    $new_product = $product;
                    $new_product['quantity'] = $quantity;
                    $new_product['discount'] = $discount;
                    $new_product['amount'] = $amount;
                    unset($cart[$key]);
                    array_push($cart, $new_product);
                    $item_amount = $amount;
                    $totalAmount += $amount;
                }else{
                    $totalAmount += $product['amount'];
                }
            }
            array_values($cart);
            $_SESSION['cart'] = $cart;

            Response(json_encode(array(
                'status' => 'success',
                'countCart' => count($cart),
                'discount' => "-" . $discount . "%",
                'item_amount' => number_format($item_amount,0,',','.') . " đ",
                'totalAmount' => number_format($totalAmount,0,',','.') . " đ",
                'message' => $lang['cart_updated'],
            )));
        }
    }else{
        Response(json_encode(array(
            'status' => 'error',
            'message' => $lang['cart_empty'],
        )));
    }
    exit();
}
/* ----------------------------------------------------------------------------------- */
# Check cart before redirect to checkout page
/* ----------------------------------------------------------------------------------- */
function preCheckout(){
    $locale = getRequest("locale");
    $lang['cart_empty'] = ($locale == "vn") ? "Bạn chưa chọn khoá học nào, vui lòng chọn đăng ký một khoá học." : "Your cart is empty, please add product into your cart.";
    
    if (isset($_SESSION['cart']) and !empty($_SESSION['cart'])) {        
        Response(json_encode(array(
                    'status' => 'success',
                    'message' => "",
                )));
    }else{
        Response(json_encode(array(
                    'status' => 'error',
                    'message' => $lang['cart_empty'],
                )));
    }
    exit();
}
/* ----------------------------------------------------------------------------------- */
# Complete order
/* ----------------------------------------------------------------------------------- */
function orderComplete() {
    $locale = getRequest("locale");
    $lang['coupon_wrong'] = ($locale == "vn") ? "Sai mã giảm giá" : "Coupon code is wrong!";
    $lang['cart_empty'] = ($locale == "vn") ? "Bạn chưa chọn khoá học nào, vui lòng chọn đăng ký một khoá học." : "Your cart is empty, please add product into your cart.";
    $lang['cEmail_invalid'] = ($locale == "vn") ? "<p>Địa chỉ email khách hàng không hợp lệ</p>" : "Customer email invalid!";
    $lang['shipEmail_invalid'] = ($locale == "vn") ? "<p>Địa chỉ email nhận hàng không hợp lệ</p>" : "Ship email invalid!";
    $lang['order_success'] = ($locale == "vn") ? "Đăng ký thành công! Chúng tôi sẽ liên lạc với bạn trong thời gian sớm nhất!" : "Order Success! We will contact you soon!";
    $lang['order_failure'] = ($locale == "vn") ? "Đăng ký không thành công! Hãy liên lạc với chúng tôi ngay để được trợ giúp!" : "Order Failure! Please contact us for assistance!";

    if(isset($_SESSION['cart']) and !empty($_SESSION['cart'])) {
        $errorMsg = "";
        $coupon_amount = 0;
        $coupon_code = getRequest('coupon_code');
        $total_amount_before_cp = intval(getRequest('total_amount'));

        ## BEGIN Check Coupon
        /*if ($coupon_code != '') {
            $args = array(
                'post_type' => 'coupon',
                'meta_query' => array(
                    array(
                        'key' => 'coupon_code',
                        'value' => $coupon_code,
                    )
                )
            );
            $coupons = new WP_Query($args);
            if($coupons->post_count == 1) {
                while ($coupons->have_posts()) : $coupons->the_post();
                    $coupon_type = get_post_meta(get_the_ID(), "coupon_type", true);
                    $coupon_usage = intval(get_post_meta(get_the_ID(), "coupon_usage", true));
                    $coupon_expiry_date = intval(get_post_meta(get_the_ID(), "coupon_expiry_date", true));
                    $coupon_minimum_amount = intval(get_post_meta(get_the_ID(), "coupon_minimum_amount", true));
                    $coupon_amount2 = intval(get_post_meta(get_the_ID(), "coupon_amount", true));
                    $date = new DateTime(date("Y-m-d H:i:s", get_the_time('U')));
                    $currentDate = new DateTime(date("Y-m-d H:i:s"));
                    $diff = $date->diff($currentDate);
                    $day = $diff->format('%d');
                    if($day > $coupon_expiry_date){
                        $errorMsg .= 'Mã giảm giá đã hết hạn';
                    }elseif ($coupon_usage <= 0) {
                        $errorMsg .= 'Mã giảm giá đã quá số lượng sử dụng';
                    }elseif($total_amount_before_cp <= $coupon_minimum_amount){
                        $errorMsg .= 'Giá trị đơn hàng không đạt yêu cầu để sử dụng mã giảm giá';
                    }else{
                        if($coupon_type == 'cp_percent_order') {
                            $coupon_amount = intval(getRequest('total_amount')) * $coupon_amount2 / 100;
                        }elseif($coupon_type=='cp_order'){
                            $coupon_amount = $coupon_amount2;
                        }
                        update_post_meta(get_the_ID(), 'coupon_usage', $coupon_usage-1);
                    }
                endwhile;
            }else{
                $errorMsg .= $lang['coupon_wrong'];
            }
        }*/
        ## END Check Coupon

        if(!is_valid_email(getRequest("cEmail"))){
            $errorMsg .= $lang['cEmail_invalid'];
        }
        if(getRequest("ship") == 1 && !is_email(getRequest("shipEmail"))){
            $errorMsg .= $lang['shipEmail_invalid'];
        }
        
        if($errorMsg == ""){
            $cart = $_SESSION['cart'];
            foreach ($cart as $k => $v) {
                unset($v['thumb']);
                $product = $v;
                unset($cart[$k]);
                array_push($cart, $product);
            }
            
            $name = getRequest('cName');
            $email = getRequest('cEmail');
            $phone = getRequest('cPhone');
            $address = getRequest('cAddress');
            $city = getRequest('cCity');
            $customer_id = 0;

            if(is_user_logged_in()){
                global $current_user;
                get_currentuserinfo();
                $customer_id = $current_user->ID;
            } elseif(email_exists($email)){
                $user = get_user_by_email($email);
                $customer_id = $user->ID;
            }

            $customer_info = json_encode(array(
                'fullname' => $name,
                'email' => $email,
                'phone' => $phone,
                'address' => $address,
                'city' => $city,
            ));
            $ship_info = $customer_info;
            if(getRequest("ship") == 1){
                $ship_info = json_encode(array(
                    'fullname' => getRequest('shipName'),
                    'email' => getRequest('shipEmail'),
                    'phone' => getRequest('shipPhone'),
                    'address' => getRequest('shipAddress'),
                    'city' => getRequest('shipCity'),
                ));
            }
            $payment_method = getRequest('payment_method');
            $products = json_encode($cart);
            $beforeVAT = (is_numeric(getRequest("beforeVAT"))) ? getRequest("beforeVAT") : '0';
            $afterVAT = (is_numeric(getRequest("afterVAT"))) ? getRequest("afterVAT") : '0';
            $total_amount = $total_amount_before_cp - $coupon_amount;
            $referrer = isset($_COOKIE['ap_id']) ? $_COOKIE['ap_id'] : "";

            global $wpdb;
            $tblOrders = $wpdb->prefix . 'orders';
            $result = $wpdb->query($wpdb->prepare("INSERT INTO $tblOrders SET customer_id = %d, customer_info = '%s', ship_info = '%s', 
                payment_method = '%s', products = '%s', before_vat = '%s', after_vat = '%s', discount = '%s', total_amount = '%s', affiliate_id = '%s'", 
                $customer_id, $customer_info, $ship_info, $payment_method, $products, $beforeVAT, $afterVAT, $coupon_amount, $total_amount, $referrer));

            if($result){
                Response(json_encode(array(
                        'status' => 'success',
                        'message' => $lang['order_success'],
                    )));
                // Send invoice to email
                sendInvoiceToEmail($customer_info);
                // Remove Cart
                unset($_SESSION['cart']);
            }else{
                Response(json_encode(array(
                        'status' => 'failure',
                        'message' => $lang['order_failure'],
                    )));
            }
        }else{
            Response(json_encode(array(
                    'status' => 'error',
                    'message' => $errorMsg,
                )));
        }
    } else {
        Response(json_encode(array(
                    'status' => 'error',
                    'message' => $lang['cart_empty'],
                )));
    }
    
    exit();
}
function orderNganLuong(){
    $locale = getRequest("locale");
    $lang['coupon_wrong'] = ($locale == "vn") ? "Sai mã giá" : "Coupon code is wrong!";
    $lang['cart_empty'] = ($locale == "vn") ? "Bạn chưa chọn khoá học nào, vui lòng chọn đăng ký một khoá học." : "Your cart is empty, please add product into your cart.";
    $lang['cEmail_invalid'] = ($locale == "vn") ? "<p>Địa chỉ email khách hàng không hợp lệ</p>" : "Customer email invalid!";

    if(isset($_SESSION['cart']) and !empty($_SESSION['cart'])) {
        $errorMsg = "";
        $coupon_amount = 0;
        $coupon_code = getRequest('coupon_code');
        $total_amount_before_cp = intval(getRequest('total_amount'));

        ## BEGIN Check Coupon
        /*if ($coupon_code != '') {
            $args = array(
                'post_type' => 'coupon',
                'meta_query' => array(
                    array(
                        'key' => 'coupon_code',
                        'value' => $coupon_code,
                    )
                )
            );
            $coupons = new WP_Query($args);
            if($coupons->post_count == 1) {
                while ($coupons->have_posts()) : $coupons->the_post();
                    $coupon_type = get_post_meta(get_the_ID(), "coupon_type", true);
                    $coupon_usage = intval(get_post_meta(get_the_ID(), "coupon_usage", true));
                    $coupon_expiry_date = intval(get_post_meta(get_the_ID(), "coupon_expiry_date", true));
                    $coupon_minimum_amount = intval(get_post_meta(get_the_ID(), "coupon_minimum_amount", true));
                    $coupon_amount2 = intval(get_post_meta(get_the_ID(), "coupon_amount", true));
                    $date = new DateTime(date("Y-m-d H:i:s", get_the_time('U')));
                    $currentDate = new DateTime(date("Y-m-d H:i:s"));
                    $diff = $date->diff($currentDate);
                    $day = $diff->format('%d');
                    if($day > $coupon_expiry_date){
                        $errorMsg .= 'Mã giảm giá đã hết hạn';
                    }elseif ($coupon_usage <= 0) {
                        $errorMsg .= 'Mã giảm giá đã quá số lượng sử dụng';
                    }elseif($total_amount_before_cp <= $coupon_minimum_amount){
                        $errorMsg .= 'Giá trị đơn hàng không đạt yêu cầu để sử dụng mã giảm giá';
                    }else{
                        if($coupon_type == 'cp_percent_order') {
                            $coupon_amount = intval(getRequest('total_amount')) * $coupon_amount2 / 100;
                        }elseif($coupon_type=='cp_order'){
                            $coupon_amount = $coupon_amount2;
                        }
                        update_post_meta(get_the_ID(), 'coupon_usage', $coupon_usage-1);
                    }
                endwhile;
            }else{
                $errorMsg .= $lang['coupon_wrong'];
            }
        }*/
        ## END Check Coupon

        if(!is_valid_email(getRequest("cEmail"))){
            $errorMsg .= $lang['cEmail_invalid'];
        }
        if(getRequest("ship") == 1 && !is_email(getRequest("shipEmail"))){
            $errorMsg .= $lang['shipEmail_invalid'];
        }

        if($errorMsg == ""){
            $cart = $_SESSION['cart'];
            foreach ($cart as $k => $v) {
                unset($v['thumb']);
                $product = $v;
                unset($cart[$k]);
                array_push($cart, $product);
            }
            
            $name = getRequest('cName');
            $email = getRequest('cEmail');
            $phone = getRequest('cPhone');
            $address = getRequest('cAddress');
            $city = getRequest('cCity');
            $customer_id = 0;

            if(is_user_logged_in()){
                global $current_user;
                get_currentuserinfo();
                $customer_id = $current_user->ID;
            } elseif(email_exists($email)){
                $user = get_user_by_email($email);
                $customer_id = $user->ID;
            }

            $customer_info = json_encode(array(
                'fullname' => $name,
                'email' => $email,
                'phone' => $phone,
                'address' => $address,
                'city' => $city,
            ));
            $ship_info = $customer_info;
            if(getRequest("ship") == 1){
                $ship_info = json_encode(array(
                    'fullname' => getRequest('shipName'),
                    'email' => getRequest('shipEmail'),
                    'phone' => getRequest('shipPhone'),
                    'address' => getRequest('shipAddress'),
                    'city' => getRequest('shipCity'),
                ));
            }
            $products = json_encode($cart);
            $_SESSION['BEFORE_VAT'] = (is_numeric(getRequest("beforeVAT"))) ? getRequest("beforeVAT") : '0';
            $_SESSION['AFTER_VAT'] = (is_numeric(getRequest("afterVAT"))) ? getRequest("afterVAT") : '0';
            $_SESSION['CUSTOMER_ID'] = $customer_id;
            $_SESSION['CUSTOMER_INFO'] = $customer_info;
            $_SESSION['PRODUCTS_CART'] = $products;
            $_SESSION['coupon'] = $coupon_amount;
            $totalAmount = $total_amount_before_cp - $coupon_amount;
            
            $receiver = stripslashes(get_option("nl_email"));
            $return_url = get_page_link(get_option(SHORT_NAME . "_pageNLComplete"));
            $order_code = random_string(6);
            
            global $nl_checkout;
            $url = $nl_checkout->buildCheckoutUrl($return_url, $receiver, '', $order_code, $totalAmount);
            
            Response(json_encode(array(
                    'status' => 'success',
                    'message' => "Kiểm tra hợp lệ, chúng tôi sẽ chuyển sang cổng thanh toán Ngân Lượng ngay bây giờ.",
                    'nganluongUrl' => $url,
                )));
        }else{
            Response(json_encode(array(
                    'status' => 'error',
                    'message' => $errorMsg,
                )));
        }
    } else {
        Response(json_encode(array(
                    'status' => 'error',
                    'message' => $lang['cart_empty'],
                )));
    }
    
    exit();
}