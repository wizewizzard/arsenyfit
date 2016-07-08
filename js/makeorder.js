var contact_form_validated=false;
var shipping_form_validated=false;
var additional_form_active;
function ajaxValidateContact(data) {
    return $.ajax({
        type: 'POST',
        url: 'Ajaxvalidatecontact',
        data: data,
        async : false,
        success:function(data){

        },
        error: function(data) { // if error occured

        },

        dataType:'html'
    });
}
function ajaxValidateShipping(data) {
    return $.ajax({
        type: 'POST',
        url: 'Ajaxvalidateshipping',
        data: data,
        async : false,
        success:function(data){

        },
        error: function(data) { // if error occured

        },

        dataType:'html'
    });
}
function ajaxShippingTotal(zip_code, id_country) {
    var url;
    //console.log(zip_code);
    if(typeof zip_code === 'undefined' && typeof id_country === 'undefined') url = 'Noshippingtotal';
    else if(typeof zip_code !== 'undefined' && typeof id_country !== 'undefined') url = 'Shippingtotal';
    else{
        fillErrorContainer($('#summary_error_summary'), {'wrong_input' : 'wrong_params'});
        return null;
    }
    $('#summary_content').empty();
    startLoadingAnimation($('#summary_content'));
    return $.ajax({
        type: 'GET',
        url: url,
        data: {
            'zip_code': zip_code,
            'id_country': id_country
        },
        async : true,
        error: function(data) {
            fillErrorContainer(JSON.parse(data));
        },
        dataType:'html'
    });
}
function ajaxSenddata(data){
    return $.ajax({
        type: 'POST',
        url: 'executeorder',
        data: data,
        async : false,
        success:function(data){
    console.log(data);
        },
        error: function(data) { // if error occured
            console.log(data);
        },

        dataType:'html'
    });
}
function senddata(element) {
    var data = $("#contact_form, #shipping_form, #additional_form").serialize();
//    var data = $('#contact_form').serialize() + $('#shipping_form').serialize() + $('#additional_form').serialize();
    response = ajaxSenddata(data);
    if (response['statusText'] == 'OK') {
        var result = JSON.parse(response['responseText']);
        if (result['status'] == 'success') {
            console.log('oformlen');
        }
        else{
            fillErrorContainer($('#summary_error_summary'), result['errors']);
        }

    }
    else{
        fillErrorContainer($('#summary_error_summary'), {'ajax_failed' : 'AJAX request failed. Please, try again later.'});
    }
}

function startLoadingAnimation(obj) // - функция запуска анимации
{
    var img = $('<img src="/images/others/loader3.gif" class="process_image"/>');

    var size  = ($(obj).height() > $(obj).width()) ?  $(obj).width() :  $(obj).height();

    var centerY = ($(obj).height() - img.height())/2;
    var centerX = ($(obj).width() - img.width())/2;


    img.css({'top': centerY, 'left': centerX});
    $(obj).empty();

    $(obj).append(img);
    img.fadeIn(300, function(){}).delay(500);

}

function fillErrorContainer(obj, errors){

    var error_container = $(obj);
    error_container.empty();
    for (var k in errors){
        if (errors.hasOwnProperty(k)) {
            var report_div = $('<div class="error_report">' + errors[k] +'</div>');
            error_container.append(report_div);
            //console.log("Key is " + k + ", value is" + errors[k]);

        }
    }
    error_container.fadeIn(100);
}

function stopLoadingAnimation(obj, result, callback) // - функция останавливающая анимацию
{
    var img;
    var text;
    if(result ==1){
        img = $('<img src="/images/others/accept_icon.png" class="process_image"/>');
        text = "Сохранить";
    }
    else if(result == 0){
        img = $('<img src="/images/others/reject_icon.png" class="process_image"/>');
        text = "Сохранить";
    }
    else{
        $(obj).find('img').fadeOut(500, function(){
            $(obj).find('img').remove();
            if(typeof callback !== 'undefined') callback();
        });

        return;
    }

    $(obj).children().fadeOut(100, function(){
        $(obj).empty();
        var size  = ($(obj).height() > $(obj).width()) ?  $(obj).width() :  $(obj).height();

        var centerY = ($(obj).height() - img.height())/2;
        var centerX = ($(obj).width() - img.width())/2;

        img.css({'top': centerY, 'left': centerX});

        $(obj).append(img);
        img.fadeIn(200);
        setTimeout(function(){
            img.fadeOut(200, function(){
                $(obj).empty();
               if(result == 1) {
                   $(obj).text(text);
               }
                else{
                   $(obj).text(text);
                    }
                if(typeof callback !== 'undefined') callback();
            })

        }, 1000);
    });

    return;
}

function sendcontact(obj)
{
    var cookieArray = {};
    $(obj).parent().parent().find('input').each(function(){
        cookieArray[$(this).attr('id')] = $(this).val();
    });
    var date = new Date();
    var minutes = 30;
    date.setTime(date.getTime() + (minutes * 60 * 1000));
    setCookie('contactformdata', JSON.stringify(cookieArray), date);

    $('#contact_error_summary').empty().hide();
    startLoadingAnimation(obj);
    var data=$("#contact_form").serialize();

    var response = ajaxValidateContact(data);
    //console.log(response);
    if(response['statusText']=='OK') {
        if (response['responseText'] == '1') {
            contact_form_validated=true;
            stopLoadingAnimation(obj, 1,
                function () {
                    formToInfoBlock($(obj).parent(), function () {
                            if($('#shipping_form').length ==0 && contact_form_validated){
                                $("#summary").removeClass('disabled').removeClass('noselect');
                                setSummaryData();
                                $("#additional_form").removeClass('disabled').removeClass('noselect');
                                $("#submit_order_button").removeClass('disabled').removeClass('noselect');
                            }
                            else{
                                if(shipping_form_validated && contact_form_validated) {
                                    $("#summary").removeClass('disabled').removeClass('noselect');
                                    setSummaryData();
                                    $("#additional_form").removeClass('disabled').removeClass('noselect');
                                    $("#submit_order_button").removeClass('disabled').removeClass('noselect');
                                }
                            }
                        }
                    )
                }
            );


        }
        else {
            var errors = JSON.parse(response['responseText']);

            window.setTimeout(function () {
                    stopLoadingAnimation(obj, 0,
                        function () {
                            fillErrorContainer($('#contact_error_summary'), errors)
                        })
                },
                800);

        }
    }
    else{
        window.setTimeout(function () {
                stopLoadingAnimation(obj, 0,
                    function () {
                        errors = {'server_error' : 'Server error occured.'}
                        fillErrorContainer($('#contact_error_summary'), errors)
                    })
            },
            800);
    }
    //console.log(response['responseText']);

}

function setSummaryData(){
    $.when(ajaxShippingTotal($('#ShippingForm_zip_code').val(), $('#ShippingForm_country').val())).
        then(function(data, textStatus){
            if(textStatus == 'success'){
                var totals = JSON.parse(data);
                if(totals['status']=='success') {
                    var label_column = $('<div class="label_column"></div>');
                    var value_column = $('<div class="value_column"></div>');

                    var total_cost = $('<div id="total_cost" class="transformed_label">Total</div>');
                    var shipping_cost = $('<div id="shipping_cost" class="transformed_label">Shipping</div>');
                    var days = $('<div id="days" class="transformed_label">Days</div>');
                    var total_cost_with_shipping = $('<div id="total_cost_with_shipping" class="transformed_label">Total with shipping</div>');
                    label_column.append(total_cost).append(shipping_cost).append(days).append(total_cost_with_shipping);

                    var error_message = $('<div id="error_message">Error occured. Please, try again later.</div>');
                    var show_error = false;
                    var currency = totals['currency_str'];
                    var currency_str;
                    if (typeof totals['currency_str'] !== 'undefined') {
                        if (currency == 'dol') currency_str = '$';
                        else if (currency == 'rub') currency_str = 'РУБ.';
                        else if (currency == 'eur') currency_str = 'EUR.';
                        else {
                            show_error = true;
                        }
                    }
                    if (typeof totals['total'] !== 'undefined') {
                        value_column.append('<div class="transformed_input">' + totals['total'] + ' ' + currency_str + '</div>');
                    }
                    else {
                        show_error = true;
                    }
                    if (typeof totals['shipping'] !== 'undefined') {
                        value_column.append('<div class="transformed_input">' + totals['shipping'] + ' ' + currency_str + '</div>');
                    }
                    else {
                        show_error = true;
                    }
                    if (typeof totals['days'] !== 'undefined') {
                        value_column.append('<div class="transformed_input">' + totals['days'] + '</div>');
                    }
                    else {
                        show_error = true;
                    }
                    if (typeof totals['total'] !== 'undefined' && typeof totals['shipping'] !== 'undefined') {
                        value_column.append('<div class="transformed_input">' + (totals['total'] + totals['shipping']) + ' ' + currency_str + '</div>');
                    }
                    else {
                        show_error = true;
                    }
                    if (show_error) {
                        stopLoadingAnimation($('#summary_content'), 2);
                        setTimeout(function () {
                            $('#summary_content').append(error_message);
                        }, 600);
                        return;
                    }
                    else {
                        stopLoadingAnimation($('#summary_content'), 2, function () {
                            $('#summary_content').hide();
                            $('#summary_content').append(label_column).append(value_column);
                            $('#summary_content').fadeIn(1000);
                        });
                    }
                }
                else{
                    stopLoadingAnimation($('#summary_content'), 2, function () {
                        fillErrorContainer($('#summary_error_summary'), totals['errors']);
                    });

                }
            }
            else{
                stopLoadingAnimation($('#summary_content'),2, function(){
                    $('#summary_content').append(error_message);
                });
            }
            // alert(textStatus);
        });
}

function sendshipping(obj)
{
    $('#shipping_error_summary').empty().hide();
    startLoadingAnimation(obj);


    var cookieArray = {};
    $(obj).parent().parent().find('input').each(function(){
        cookieArray[$(this).attr('id')] = $(this).val();
    });
    $(obj).parent().parent().find('select').each(function(){
        cookieArray[$(this).attr('id')] = $(this).val();
    });
    var date = new Date();
    var minutes = 30;
    date.setTime(date.getTime() + (minutes * 60 * 1000));

    setCookie('shippingformdata', JSON.stringify(cookieArray), date);



    var data=$("#shipping_form").serialize();


    var response = ajaxValidateShipping(data);
    if(response['statusText']=='OK') {
        if (response['responseText'] == 1) {
            shipping_form_validated = true;
            stopLoadingAnimation(obj, 1,
                function () {
                    formToInfoBlock($(obj).parent().parent(),
                        function () {
                            // $("#shipping_form").data('active', 1);
                            if(shipping_form_validated && contact_form_validated) {
                                $("#summary").removeClass('disabled').removeClass('noselect');
                                setSummaryData();
                                $("#additional_form").removeClass('disabled').removeClass('noselect');
                                $("#submit_order_button").removeClass('disabled').removeClass('noselect');

                            }
                        })
                });

            // $("#shipping_form").removeClass('disabled').removeClass('noselect');

        }
        else {
            var errors = JSON.parse(response['responseText']);
            window.setTimeout(function () {
                stopLoadingAnimation(obj, 0, function () {
                    fillErrorContainer($('#shipping_error_summary'), errors)
                })
            }, 800);
        }
    }
    else{
        window.setTimeout(function () {
                stopLoadingAnimation(obj, 0,
                    function () {
                        errors = {'server_error' : 'Server error occured.'}
                        fillErrorContainer($('#contact_error_summary'), errors)
                    })
            },
            800);
    }
}

function formToInfoBlock(form, callback){
    console.log($(form).parent());
    var prev_height = $(form).parent().height();
        $(form).find('.global_button').hide();

    var transformed_form = $('<div class="transformed_form"></div>');
    var label_column = $('<div class="label_column"></div>');
    var value_column = $('<div class="value_column"></div>');
    $(form).find('input, select').each(function(){
        var val = $(this).val();
        var label = $(this).attr('data-title');
        if(typeof label === 'undefined') return;
        if($(this).is('select')){

            val=$(this).find('option:selected').text();
        }
        var transformed_input;
        if(val == '') val='empty';
        //transformed_input  = $('<div class="transformed_row"><div class="transformed_label">'+label+'</div><div class="transformed_input">'+val+'</div></div>');
        label_column.append('<div class="transformed_label">'+label+'</div>');
        value_column.append('<div class="transformed_input">'+val+'</div>');

    });
    transformed_form.append(label_column).append(value_column);
    var button = $('<br/><div class="global_button" onclick="revealform(this);">Изменить</div>');
    button.text('Изменить');
    transformed_form.append(button);
    $(form).fadeOut(100, function(){
        form.attr('data-active', '0');
        $(form).parent().append(transformed_form);
        $(form).parent().css('height', 'auto');
        var cur_height = $(form).parent().height();
        console.log(prev_height + 'to' + cur_height);
        $(form).parent().height(prev_height).animate({height: cur_height}, 400);
        var m_left = transformed_form.css('margin-top');
        transformed_form.css({'margin-top' : - transformed_form.height() +'px' }).animate({marginTop: m_left}, 300, function(){
            $(form).parent().css('height', 'auto');
            if(typeof callback !== 'undefined') callback();
        });
    });
}

function revealform(element, callback){
    var transformed_form = $(element).parent();
    var prev_height = $(transformed_form).parent().height();
    transformed_form.animate({marginLeft: - transformed_form.width()*2},300, function(){
        var form = $(transformed_form).parent().find('form');
        form.attr('data-active', '1');
       /* $(document).find('form').each(function(){
            if(!$(this).is(form) && $(this).attr('data-active') == '1'){
                formToInfoBlock($(this));
            }
        });*/
        console.log(element);
        transformed_form.remove();
        form.find('.global_button').show();
        form.show();
        if(form.attr('id')=='shipping_form') shipping_form_validated=false;
        else if(form.attr('id')=='contact_form')  contact_form_validated=false;
        $("#summary").addClass('disabled').addClass('noselect');
        $("#additional_form").addClass('disabled').addClass('noselect');
        $('#submit_order_button').addClass('disabled').addClass('noselect');
        var m_left = form.css('margin-left');
        form.css({'margin-left' : - form.width() +'px' }).animate({marginLeft: m_left}, 300);

        $(form).parent().css('height', 'auto');
        var autoHeight = $(form).parent().height();
        console.log(form);
        console.log(prev_height + 'to' + autoHeight);
        $(form).parent().height(prev_height).animate({height: autoHeight}, 400, function(){
            $(form).parent().css('height', 'auto');
        });
        if(typeof callback !== 'undefined') callback();
    });



}

$(document).ready(function(){
    $('.error_summary').each(function(){
        if($(this).text()!=''){
            console.log($(this).text());
            fillErrorContainer($(this), JSON.parse($(this).text()));
        }
    })
    var cookiestr = getCookie('contactformdata');
    if(typeof cookiestr !== 'undefined') {
        cookieArray = JSON.parse(cookiestr);
        for (var key in cookieArray) {
            $('#' + key).val(cookieArray[key]);
        }
    }
    cookiestr = getCookie('shippingformdata');
    if(typeof cookiestr !== 'undefined') {
        cookieArray = JSON.parse(cookiestr);
        for (var key in cookieArray) {
            $('#' + key).val(cookieArray[key]);
        }
    }
    /*$("#shipping_form").addClass('disabled').addClass('noselect');*/
    $("#summary").addClass('disabled').addClass('noselect');
    $("#additional_form").addClass('disabled').addClass('noselect');
    $('#submit_order_button').addClass('disabled').addClass('noselect');
})