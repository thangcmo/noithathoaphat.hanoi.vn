<?php 
//include_once 'libs/bbit-compress.php'; 

$appFBID = get_option(SHORT_NAME . "_appFBID");
$googlePlusURL = get_option(SHORT_NAME . "_googlePlusURL");
$cart_url = get_page_link(get_option(SHORT_NAME . "_pageCartID"));
$checkout_url = get_page_link(get_option(SHORT_NAME . "_pageCheckoutID"));
?>
<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html <?php language_attributes(); ?>> <!--<![endif]-->
<head>
    <meta http-equiv="Cache-control" content="no-store; no-cache"/>
    <meta http-equiv="Pragma" content="no-cache"/>
    <meta http-equiv="Expires" content="0"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo('charset'); ?>" />
    <title><?php wp_title('|', true, 'right'); ?></title>
    <meta name="author" content="PPO.VN" />
    <meta name="robots" content="index, follow" /> 
    <meta name="googlebot" content="index, follow" />
    <meta name="bingbot" content="index, follow" />
    <meta name="geo.region" content="VN" />
    <meta name="geo.position" content="14.058324;108.277199" />
    <meta name="ICBM" content="14.058324, 108.277199" />
    <?php if(!empty($appFBID)){ ?>
    <meta property="fb:app_id" content="<?php echo $appFBID; ?>" />
    <?php } ?>
    
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    
    <?php if(is_home() or is_front_page()): ?>
    <meta name="keywords" content="<?php echo get_option('keywords_meta') ?>" />
    <?php 
    endif;
    
    if(!empty($googlePlusURL)): 
    ?>
    <link rel="publisher" href="<?php echo $googlePlusURL; ?>"/>
    <?php endif; ?>
    <link rel="schema.DC" href="http://purl.org/dc/elements/1.1/" />        
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script>
        var siteUrl = "<?php bloginfo('siteurl'); ?>";
        var themeUrl = "<?php bloginfo('stylesheet_directory'); ?>";
        var is_home = <?php echo is_home() ? 'true' : 'false'; ?>;
        var is_product = <?php echo is_singular('product') ? 'true' : 'false'; ?>;
        var is_mobile = <?php echo wp_is_mobile() ? 'true' : 'false'; ?>;
        var is_user_logged_in = <?php echo is_user_logged_in() ? 'true' : 'false'; ?>;
        var no_image_src = themeUrl + "/images/no_image_available.jpg";
        var ajaxurl = '<?php echo admin_url('admin-ajax.php') ?>';
        var cartUrl = "<?php echo $cart_url; ?>";
        var checkoutUrl = "<?php echo $checkout_url; ?>";
        var lang = "<?php echo getLocale(); ?>";
        var factor_id = <?php echo (is_tax('product_factor')) ? get_queried_object_id() : 0; ?>;
    </script>
    <!--<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/modernizr.js"></script>-->
    
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <div id="ajax_loading" style="display: none;z-index: 99999" class="ajax-loading-block-window">
        <div class="loading-image"></div>
    </div>
    <!--Alert Message-->
    <div id="nNote" class="nNote" style="display: none;"></div>
    <!--END: Alert Message-->
    
    <!--MOBILE HEADER-->
    <div id="st-container" class="st-container">
        <div class="mobile-header clearfix mobile-unclicked" style="transform: translate(0px, 0px);">
            <div id="st-trigger-effects">
                <button data-effect="st-effect-4" class="left-menu">
                    <div class="menu-icon">
                        <span class="bar"></span>
                        <span class="bar"></span>
                        <span class="bar"></span>
                    </div>
                    <span>MENU</span>
                </button>
            </div>
            <div class="title">
                <?php
                if(get_option('mobilelogo')){
                ?>
                    <a title="<?php bloginfo("name"); ?>" href="<?php echo home_url(); ?>">
                        <img src="<?php echo get_option("mobilelogo"); ?>" alt="MISOTA" />
                    </a>
                <?php
                } else {
                ?>
                    <p class="proxima"><a title="<?php bloginfo("name"); ?>" href="<?php echo home_url(); ?>">MISOTA</a></p>
                <?php }?>
            </div>
            <div id="st-trigger-effects">
                <button data-effect="st-effect-5" class="right-menu">
                    <i class="fa fa-shopping-cart icon-dj-bag"></i>
                </button>
            </div>
        </div>
        
        <nav id="menu-4" class="st-menu st-effect-4">
            <form method="get" action="<?php echo home_url(); ?>" id="search_mini_form">
                <input type="hidden" name="post_type" value="product" />
                <div class="form-search">
                    <div class="searchcontainer"><span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                        <input type="text" maxlength="128" class="input-text" value="" name="s" id="search" />
                    </div>
                </div>
            </form>

            <?php
//            wp_nav_menu(array(
//                'container' => '',
//                'theme_location' => 'primary',
//                'menu_class' => 'nav',
//                'menu_id' => '',
//            ));
            ?>
            <?php ubermenu( 'main' , array( 'theme_location' => 'primary' ) ); ?>
        </nav>
    </div>
    <!--/MOBILE HEADER-->
    
    <!--DESKTOP HEADER-->
    <div class="container desktop-header">
        <div class="tophead">
            <?php
            wp_nav_menu(array(
                'container' => 'div',
                'container_class' => 'tophead_menu',
                'theme_location' => 'top',
                'menu_class' => 'nav',
                'menu_id' => '',
            ));
            ?>
        </div>
        <div class="header_logo" itemtype="http://schema.org/Organization" itemscope="itemscope">
            <a rel="home" title="<?php bloginfo("name"); ?>" href="<?php echo home_url(); ?>" itemprop="url">
                <img src="<?php echo get_option("sitelogo"); ?>" alt="<?php bloginfo("name"); ?>" itemprop="logo" />
            </a>
        </div>
        <div class="topmenu_bg">
            <?php ubermenu( 'main' , array( 'theme_location' => 'primary' ) ); ?>
        </div>
        <div class="top_search_bg">
            <div class="search_bg">
                <form method="get" action="<?php echo home_url(); ?>">
                    <input type="hidden" name="post_type" value="product" />
                    <div class="top_quick_search">
                        <div class="search_text"><strong><?php _e('Search', SHORT_NAME) ?></strong></div>
                        <div class="input_text">
                            <input type="text" name="s" value="<?php echo get_search_query(); ?>" placeholder="Nhập từ khóa tìm kiếm" />
                        </div>
                        <div class="input_select">
                            <?php wp_dropdown_categories( array(
                                'show_option_all' => __('-- Select a category --', SHORT_NAME),
                                'taxonomy' => 'product_cat',
                                'hide_empty' => 0,
                                'hierarchical' => true,
                                'name' => 'pcat',
                                'selected' => intval(getRequest('pcat')),
                            ) ); ?>
                        </div>
                        <div class="input_select">
                            <?php wp_dropdown_categories( array(
                                'show_option_all' => __('-- Select a price --', SHORT_NAME),
                                'taxonomy' => 'product_price',
                                'hide_empty' => 0,
                                'hierarchical' => true,
                                'name' => 'price',
                                'selected' => intval(getRequest('price')),
                            ) ); ?>
                        </div>
                        <input type="submit" value="<?php _e('Search', SHORT_NAME) ?>" class="search_img btn btn-primary" />
                    </div>
                </form>	
            </div>
            <div class="top_cart">
                <div class="top_cart_text">
                    <a href="<?php echo $cart_url; ?>" title="<?php _e('Your cart', SHORT_NAME) ?>">
                        <img style="margin-top: -6px" src="<?php bloginfo('stylesheet_directory'); ?>/images/cart_bg.gif" alt="<?php _e('Your cart', SHORT_NAME) ?>" />
                        <span class="pdt10" style="display: inline-block"><span class="cart-qty"><?php
                        if (isset($_SESSION['cart']) and !empty($_SESSION['cart'])) {
                            $cart = $_SESSION['cart'];
                            echo count($cart);
                        } else {
                            echo "0";
                        }
                        ?></span> sản phẩm</span>
                    </a>
                    <div class="fr pdl10 pdt10">
                        <a href="<?php echo $checkout_url; ?>" title="<?php _e('Checkout', SHORT_NAME) ?>">» <?php _e('Checkout', SHORT_NAME) ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/DESKTOP HEADER-->