function ajaxRemoveFromCart(id_item, count) {
    return $.ajax({
        type: 'post',
        async: false,
        url: "/cart/removefromcart",
        data: {
            'id': id_item,
            'count': count
        },
        success: function(data)
        {
            console.log(data);
            if(data==1){

            }
        },
        error: function (textStatus, errorThrown) {
            console.log( "not removed 2 " );
        }
    });
}

function ajaxUpdateItemInCart(id_item, count) {
    return $.ajax({
        type: 'post',
        async: false,
        url: "/cart/updateitemincart",
        data: {
            'id': id_item,
            'count': count
        },
        success: function(data)
        {
            console.log(data);
        },
        error: function (textStatus, errorThrown) {
            console.log( "not removed 2 " );
        }
    });
}

function ajaxUpdateCartData() {
    return $.ajax({
        type: 'get',
        async: false,
        url: "/cart/getcart",
        success: function(data){
            var cart = JSON.parse(data);

            console.log(cart);
        },
        error: function (textStatus, errorThrown) {
            console.log( "not removed 2 " );
        }
    });
}
function modify_input(element, val) {
    var id_item = $(element).parent().parent().data('id-item');
    var count = $(element).parent().find('input').val();
    var count = parseInt(count) + val;
    if (count <= 0) {
        count = 1;
    }

    ajaxUpdateItemInCart(id_item, count);

    $(element).parent().find('input').val(count);
}
$(document).ready(
    function(){
        $(".item_image").yoxview();
        $('.item_number_container').change(function(){
            var id_item = $(this).parent().parent().data('id-item');
            var count = $(this).val();
            if(count <= 0 ) $(this).val(1);

            ajaxUpdateItemInCart(id_item, count);

        });

        $('.icon_remove_from_cart').click(function() {
            var id_item =  $(this).data("id-item");

            var count = 1;

            if(ajaxRemoveFromCart(id_item, count)['responseText']==1){
                    $(this).parent().fadeOut(300, function(){
                        $(this).remove();
                        if($('#cart_items_list').children().length == 0){
                            var empty_message = $('<div class="main_message">Your cart is empty. Take a look at our <a href="/shop/index" class="empty_cart_link">store</a>.</div>')
                            $('#cart_content').fadeOut(300, function(){
                                $(this).empty().append(empty_message);
                                $(this).fadeIn(100);
                            });
                        }
                    });

            };


        });


    }
)
$(window).load(function(){
    $(".item_images_row").mCustomScrollbar({
        axis:"x",
        theme: 'dark'
    });
});