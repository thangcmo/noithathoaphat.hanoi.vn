var viewport_width = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
var viewport_height = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);

jQuery(document).ready(function ($) {
    jQuery(window).bind('load resize', function (){
        viewport_width = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
        viewport_height = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);
        
        
    });

    jQuery('.sidebar-categories > li > a').each(function (index, el) {
        var _index = (index + 1);
        if(_index.toString().length === 1){
            _index = "0" + _index;
        }
        jQuery(this).before('<span>' + _index + '</span>');
    });
    jQuery('.sidebar-categories > li > ul.children').each(function () {
        jQuery(this).parent().addClass('parent');
        if(!jQuery(this).parent().hasClass('current-cat') && !jQuery(this).parent().hasClass('current-cat-parent')){
            jQuery(this).css({
                left: jQuery(this).parent().width()
            });
        }
    });
    jQuery('.sidebar-categories > li > ul.children > li > ul.children').each(function () {
        jQuery(this).parent().addClass('parent');
        jQuery(this).css({
            left: jQuery(this).parent().parent().width()
        });
        if(jQuery(this).children().hasClass('current-cat')){
            jQuery(this).css({
                left: jQuery(this).parent().parent().width()
            });
        }
    });
    /*if(factor_id > 0){
        jQuery('.sidebar-categories li a').each(function () {
            var href = jQuery(this).attr('href') + "?factorfilter=" + factor_id;
            jQuery(this).attr('href', href);
        });
    }*/
    
    jQuery("select[name=pricefilter]").change(function (){
        window.location = '?pricefilter=' + jQuery(this).val();
    });
    
    // Back to top
    jQuery("#back-top").click(function (){
        jQuery("html, body").animate({ scrollTop: 0 }, "slow");
    });

    // Menu mobile
    jQuery('button.left-menu').click(function (){
        var effect = jQuery(this).attr('data-effect');
        if(jQuery(this).parent().parent().hasClass('mobile-clicked')){
            jQuery('.st-menu').animate({
                width: 0
            }).css({
                display: 'none',
                transform: 'translate(0px, 0px)',
                transition: 'transform 400ms ease 0s'
            });
            jQuery(this).parent().parent().addClass('mobile-unclicked').removeClass('mobile-clicked').css({
                transform: 'translate(0px, 0px)',
                transition: 'transform 400ms ease 0s'
            });
            jQuery(this).parent().parent().parent().removeClass('st-menu-open ' + effect);
//            jQuery("#overlay").hide();
        } else {
            jQuery(this).parent().parent().parent().addClass('st-menu-open ' + effect);
            jQuery('.st-menu').animate({
                width: 270
            }).css({
                display: 'block',
                transform: 'translate(270px, 0px)',
                transition: 'transform 400ms ease 0s'
            });
            jQuery(this).parent().parent().addClass('mobile-clicked').removeClass('mobile-unclicked').css({
                transform: 'translate(270px, 0px)',
                transition: 'transform 400ms ease 0s'
            });
//            jQuery("#overlay").show();
        }
    });
    
    jQuery("#search").focusin(function (){
        jQuery(this).prev().hide();
    });
    jQuery("#search").focusout(function (){
        jQuery(this).prev().show();
    });
    jQuery(".right-menu").click(function (){
        setLocation(cartUrl);
    });
    
    if(jQuery(".product-img-box .more-views ul li").length > 0){
        jQuery(".product-img-box .more-views ul").bxSlider({
            pager: false,
            infiniteLoop: false
        });
    }
    
    var cbHeight = jQuery(window).height() - jQuery("#wpadminbar").outerHeight(true);
    jQuery('.fancybox').colorbox({
        fixed: true,
        height: cbHeight
    });
});