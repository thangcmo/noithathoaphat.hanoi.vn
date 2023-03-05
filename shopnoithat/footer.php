<section id="footer">
    <div class="container">
        <div class="foot_sitmap">
            <div class="row">
                <div class="col-md-7 col-sm-12">
                    <div class="row">
                        <div class="col-sm-4 col-xs-12 mb15">
                            <?php if ( is_active_sidebar( 'footer1' ) ) { dynamic_sidebar( 'footer1' ); } ?>
                            <?php /*
                            <div class="widget mt10">
                                <h3 class="widget-title">Liên kết</h3>
                                <div class="social">
                                    <ul>
                                        <?php
                                        $fbURL = get_option(SHORT_NAME . "_fbURL");
                                        $twitterURL = get_option(SHORT_NAME . "_twitterURL");
                                        $linkedInURL = get_option(SHORT_NAME . "_linkedInURL");
                                        $googlePlusURL = get_option(SHORT_NAME . "_googlePlusURL");
                                        $youtubeURL = get_option(SHORT_NAME . "_youtubeURL");
                                        $pinterestURL = get_option(SHORT_NAME . "_pinterestURL");
                                        $instagramURL = get_option(SHORT_NAME . "_instagramURL");
                                        ?>
                                        <?php if (!empty($fbURL)): ?>
                                        <li><a class="btn btn-primary" href="<?php echo $fbURL; ?>"><i class="fa fa-facebook"></i></a></li>
                                        <?php endif; ?>
                                        <?php if (!empty($twitterURL)): ?>
                                        <li><a class="btn btn-info" href="<?php echo $twitterURL; ?>"><i class="fa fa-twitter"></i></a></li>
                                        <?php endif; ?>
                                        <?php if (!empty($linkedInURL)): ?>
                                        <li><a class="btn btn-primary" href="<?php echo $linkedInURL; ?>"><i class="fa fa-linkedin"></i></a></li>
                                        <?php endif; ?>
                                        <?php if (!empty($googlePlusURL)): ?>
                                        <li><a class="btn btn-danger" href="<?php echo $googlePlusURL; ?>"><i class="fa fa-google-plus"></i></a></li>
                                        <?php endif; ?>
                                        <?php if (!empty($youtubeURL)): ?>
                                        <li><a class="btn btn-danger" href="<?php echo $youtubeURL; ?>"><i class="fa fa-youtube"></i></i></a></li>
                                        <?php endif; ?>
                                        <?php if (!empty($pinterestURL)): ?>
                                        <li><a class="btn btn-danger" href="<?php echo $pinterestURL; ?>"><i class="fa fa-pinterest"></i></a></li>
                                        <?php endif; ?>
                                        <?php if (!empty($instagramURL)): ?>
                                        <li><a class="btn btn-primary" href="<?php echo $instagramURL; ?>"><i class="fa fa-instagram"></i></a></li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                            */ ?>
                        </div>
                        <div class="col-sm-4 col-xs-12 mb15">
                            <?php if ( is_active_sidebar( 'footer2' ) ) { dynamic_sidebar( 'footer2' ); } ?>
                        </div>
                        <div class="col-sm-4 col-xs-12 mb15">
                            <?php if ( is_active_sidebar( 'footer3' ) ) { dynamic_sidebar( 'footer3' ); } ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 col-sm-12 col-xs-12">
                    <div class="widget">
                        <h3 class="widget-title">Liên hệ</h3>
                        <div class="footer_contact">
                            <?php echo stripslashes(get_option(SHORT_NAME . "_footer_contact")); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="footer_bg">
            <div class="footer_text">
                <?php 
                if(is_home() or is_front_page()){
                    echo '<h1 class="font24">CÔNG TY CP NỘI THẤT HÒA PHÁT - CN MIỀN BẮC</h1>';
                }
                echo stripslashes(get_option(SHORT_NAME . "_footer_content")); 
                ?>
            </div>
        </div>
        <div class="copyright">
            <span>Copyright &copy; <?php bloginfo('name') ?>. All rights reserved. Thiết kế bởi <a href="http://ppo.vn" title="Thiết kế web chuyên nghiệp">PPO.VN</a></span>
        </div>
    </div>
</section>

<!-- script references -->
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>-->
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery-migrate.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery.bxslider.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/colorbox/jquery.colorbox-min.js"></script>
<!--<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/disable-copy.js"></script>-->
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/prototype.js"></script>
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/effects.js"></script>
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/custom.js"></script>
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/app.js"></script>
<?php if(is_singular( 'product' )): ?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $(".color-list img").each(function (){
            $(this).click(function (){
                $(".color-list img").removeClass('active');
                $(this).addClass('active');
                $(".color-selected").attr({
                    'data-id': $(this).attr('data-id'),
                    'data-text': $(this).attr('alt')
                });
                $(".color-selected span").text($(this).attr('alt'));
            });
        });
    });
</script>
<?php endif; ?>
<?php if(is_page()): ?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $("#search_price").submit(function (){
            var s = $("#search_price input[name=s]").val().trim();
            var pcat = $("#search_price select[name=pcat]").val().trim();
            var factor = $("#search_price select[name=factor]").val().trim();
            var price = $("#search_price select[name=price]").val().trim();
            if(s.length === 0 && pcat === '0' && factor === '0' && price === '0'){
                displayBarNotification(true, "nWarning", "Vui lòng chọn ít nhất một điều kiện tìm kiếm!");
                return false;
            }
        });
    });
</script>
<?php endif; ?>
<?php wp_footer(); ?>
<script src="https://apis.google.com/js/platform.js" async defer></script>
</body>
</html>