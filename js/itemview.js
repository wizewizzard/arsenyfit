function ajaxAddToCart(id_item) {
    return $.ajax({
        type: 'post',
        async: false,
        url: "/cart/addtocart",
        data: {
            'id': id_item,
        },
        success: function(data)
        {
           console.log(data);

        },
        error: function (textStatus, errorThrown) {

        }
    });
}

(function($){
    $(document).ready(
        function() {
            $(".item_image").yoxview({
                   /* menuBackgroundColor: '#ffffff',
                    menuBackgroundOpacity: '1.0'*/
                }
            );
            $('.icon_cart_container').click(function() {
                var id_item =  $(this).data("id-item");
                //!!!!
                var count =  $(this).parent().find('.item_number_container').val();
                ajaxAddToCart(id_item);

            });
        }
    )
    $(window).load(function(){
        $("#item_images_row").mCustomScrollbar({
            axis:"x",
            theme: 'dark'
        });
        $("#item_description").mCustomScrollbar({
            axis:"y",
            theme: 'dark'
        });
    });
})(jQuery);