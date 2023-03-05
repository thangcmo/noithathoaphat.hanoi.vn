(function($) {
    
    $('#posts-filter a.editinline').each(function(){
        $(this).live('click', function() {
            var id = inlineEditPost.getId(this);
            
            $(".product-custom-fields span.spinner").show();
            
            $.ajax({
                type: 'GET',
                url: ajaxurl,
                data: {
                    action: 'get_product_meta',
                    product_id: id
                },
                dataType: 'json',
                cache: false,
                success: function(response, textStatus, XMLHttpRequest){
                    $("input[name='price']").val(response.price);
                    $("input[name='sale_price']").val(response.sale_price);
                },  
                error: function(MLHttpRequest, textStatus, errorThrown){
                },
                complete:function(){
                    $(".product-custom-fields span.spinner").hide();
                }
            }); 
        });
    });
    
})(jQuery);