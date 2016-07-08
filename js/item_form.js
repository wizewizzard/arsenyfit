/*var pickers_num = 0;

function appendColorPicker(color){
    var color_picker = $('<div class="color_picker" id="color'+ (pickers_num) + '" ></div>');
    var picker_input = $('<input type="text" maxlength="6" size="6" name="colorpickerField'+ (pickers_num) +'" id="colorpickerField'+ (pickers_num) +'" value="'+color+'">');
    var color_div = $('<div class="color_div" style="width: 20px; height: 20px; background: #' + color +'"></div>')
    var remove_color = $('<div class="remove_color" data-id-color="' + (pickers_num++) +'">Remove</div>');
    remove_color.bind('click', function(){
        removeColorPicker($(this).data('id-color'));

    });
    color_div.ColorPicker({
        onChange: function(hsb, hex, rgb, el){
            //console.log($(el).parent().find('.color_div'));
            $(el).parent().find('input').val(hex);
            $(el).css({'background' : '#'+hex});
            //$(el).ColorPickerHide();
        },
        /*onBeforeShow: function () {
            $(this).ColorPickerSetColor(this.value);
        }*/
    /*});
    color_picker.append(picker_input).append(color_div).append(remove_color);
    $('#color_pickers_container').append(color_picker);
    $('#add_color_button').remove();
    var add_color = $('<div id="add_color_button">Add color</div>');
    add_color.bind('click', function(){
        appendColorPicker('#000000');
    });
    $('#color_pickers_container').append(add_color);
}

function removeColorPicker(id){

    $('#color_pickers_container').find('#color'+id).remove();
    for(var i = id+1; i < pickers_num; i++ )
        if( $('#color_pickers_container').find('#color'+i).length > 0) {

            $('#color_pickers_container').find('#color' + i).find('.remove_color').attr("data-id-color", (i - 1));
            $('#color_pickers_container').find('#color' + i).find('#colorpickerField'+i).attr("name","colorpickerField" + (i - 1));
            $('#color_pickers_container').find('#color' + i).find('#colorpickerField'+i).attr("id","colorpickerField" + (i - 1));

            $('#color_pickers_container').find('#color' + i).attr("id", "color" + (i - 1));

        }
    pickers_num--;
}
*/
$(document).ready(
    function() {
        $('.remove_img_icon').bind('click', function(){
            var img_name = $(this).data('img-name');
            $(this).parent().remove();
            $('#Item_images_to_delete').val($('#Item_images_to_delete').val() + ' ' + img_name);
        });
        $('.remove_file_icon').bind('click', function(){
            var img_name = $(this).data('file-id');
            $(this).parent().remove();
            $('#Item_files_to_delete').val($('#Item_files_to_delete').val() + ' ' + img_name);
        });
    }

)
$(window).load(function(){
    $(".image_row").mCustomScrollbar({
        axis:"x",
        theme: 'dark'
    });
});
