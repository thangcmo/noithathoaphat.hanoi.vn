<?php
/*
  Template Name: Kết quả báo giá
 */
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
    
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    
    <link rel="schema.DC" href="http://purl.org/dc/elements/1.1/" />        
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <div class="page-price">
        <h1 class="title">Báo giá sản phẩm</h1>
        <div class="table-responsive">
            <table class="table table-bordered">
                <tr>
                    <th>STT</th>
                    <th>Mã sản phẩm</th>
                    <th>Tên sản phẩm</th>
                    <th>Chuyên mục</th>
                    <th>Thương hiệu</th>
                    <th>Giá</th>
                    <th>Tình trạng</th>
                </tr>
                <?php
                $s = getRequest('s');
                $cat = intval(getRequest('pcat'));
                $price = intval(getRequest('price'));
                $factor = intval(getRequest('factor'));
                $tax_query = array(
                    'relation' => 'AND',
                );
                $args = array(
                    'post_type' => 'product',
                    'posts_per_page' => -1,
                );
                if(!empty($s)){
                    $args['s'] = $s;
                }
                if($cat > 0){
                    array_push($tax_query, array(
                        'taxonomy' => 'product_cat',
                        'field' => 'id',
                        'terms' => $cat,
                    ));
                }
                if($price > 0){
                    array_push($tax_query, array(
                        'taxonomy' => 'product_price',
                        'field' => 'id',
                        'terms' => $price,
                    ));
                }
                if($factor > 0){
                    array_push($tax_query, array(
                        'taxonomy' => 'product_factor',
                        'field' => 'id',
                        'terms' => $factor,
                    ));
                }
                if($cat > 0 or $price > 0 or $factor > 0){
                    $args['tax_query'] = $tax_query;
                }
                $loop = new WP_Query($args);
                $count = 1;
                $status = array(
                    'in_stock' => 'Còn hàng',
                    'out_of_stock' => 'Hết hàng',
                    'coming_soon' => 'Sắp có hàng',
                );
                while ($loop->have_posts()) : $loop->the_post();
                    $price = floatval(get_post_meta(get_the_ID(), "price", true));
                    $tinh_trang = get_post_meta(get_the_ID(), 'tinh_trang', true);
                ?>
                <tr>
                    <td><?php echo $count ?></td>
                    <td><?php echo get_post_meta(get_the_ID(), 'code', true) ?></td>
                    <td><?php the_title() ?></td>
                    <td><?php
                        $terms = get_the_terms(get_the_ID(), 'product_cat');
                        for ($i = 0; $i <= count($terms); $i++) {
                            if($i < count($terms) - 1)
                                echo $terms[$i]->name . ", ";
                            else
                                echo $terms[$i]->name;
                        }
                    ?></td>
                    <td><?php
                        $factors = get_the_terms(get_the_ID(), 'product_factor');
                        for ($i = 0; $i <= count($factors); $i++) {
                            if($i < count($factors) - 1)
                                echo $factors[$i]->name . ", ";
                            else
                                echo $factors[$i]->name;
                        }
                    ?></td>
                    <td><?php echo number_format($price, 0, ',', '.'); ?> đ</td>
                    <td><?php echo $status[$tinh_trang] ?></td>
                </tr>
                <?php
                    $count++;
                endwhile;
                ?>
            </table>
        </div>
        <div style="padding-top:10px; text-align:center;">
            <input type="button" value="In báo giá" class="btn btn-default btn-lg" id="btn_print" />
        </div>
    </div>

    <script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery.min.js"></script>
    <script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery-migrate.min.js"></script>
    <script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        jQuery(document).ready(function () {
            jQuery("#btn_print").click(function () {
                jQuery(this).hide();
                window.print();
                window.close();
                jQuery(this).show();
            });
        });
    </script>
    
    <?php wp_footer(); ?>
</body>
</html>