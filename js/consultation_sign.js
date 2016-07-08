function ajaxGetConsultationAndAppointments(id){
    return $.ajax({

        type: 'get',
        async: false,
        url: "/consultation/GetConsultationAndAppointments",
        data: {
            'id': id
        },
        success: function(data)
        {
            //$('#consultations_info').empty();



            var border_rad = 1;

            var jsonData = JSON.parse(data);
            c_beg = moment(jsonData[0].time_from+'Z').tz('Europe/Moscow');
            c_end = moment(jsonData[0].time_until+'Z').tz('Europe/Moscow');
            $('#left_limit').text(c_beg.format('HH:mm'));
            $('#right_limit').text(c_end.format('HH:mm'));

            var c_duration = moment.duration(c_end.diff(c_beg)).asMinutes();
            sectors_num =c_duration/div_value;

            offset_slider_lower=0;
            offset_slider_upper = sectors_num;

            var el_w = (($('#range_statusbar').width()-(sectors_num-1)*border_rad)/sectors_num);

            for(var i =0; i< sectors_num; i++){
                var el = $('<div class="sector free" id="s'+ (i) +'"></div>');
                el.css({'width' : el_w+'px'});
                $('#range_statusbar').append(el);
            }

            for(var i=0; i < jsonData[1].length; i++){
                var a_duration = moment.duration(jsonData[1][i].duration).asMinutes();
                var a_offset = moment.duration(jsonData[1][i].offset).asMinutes();

                var st_sec = Math.floor(a_offset/div_value);
                var a_sec_num = Math.ceil(a_duration/div_value);

                for(var j=st_sec; j < st_sec + a_sec_num; j++)
                    $('#range_statusbar').find('#s'+j).removeClass('free').addClass('booked');


            }
        },
        error: function (textStatus, errorThrown) {
            //stopLoadingAnimation(0);
            console.log( "nothing 2 " );
        }
    });
}
function fillRangeBar(){
    for(var j=0; j < sector_slider_lower; j++){
        $('#range_statusbar').find('#s'+j).removeClass('selected');
    }
    for(var j=sector_slider_lower; j <= sector_slider_upper; j++){
        $('#range_statusbar').find('#s'+j).addClass('selected');
    }
    for(var j=sector_slider_upper+1; j < $('#range_statusbar').children().length; j++){
        $('#range_statusbar').find('#s'+j).removeClass('selected');
    }
}
function updateLowerSliderVal(){
    $('#slider_lower_val').text(moment(c_beg).add('minutes', sector_slider_lower * div_value).format("HH:mm") );
    //console.log(c_beg.toString());
}
function updateUpperSliderVal(){
    $('#slider_upper_val').text(moment(c_beg).add( 'minutes', (sector_slider_upper+1) * div_value).format("HH:mm") );
    //console.log(c_beg.toString());
}
function updateInputVals(){
    var off =  moment.duration(sector_slider_lower * div_value, 'minutes');
    var t_off = moment(off._data).format("HH:mm:ss");
    $('input[name="Consultation[offset]"]').val(t_off);

    var dur = moment.duration(div_value * (sector_slider_upper - sector_slider_lower +1), 'minutes');
    var t_dur = moment(dur._data).format("HH:mm:ss");
   $('input[name="Consultation[duration]"]').val(t_dur);
}
var offset_slider_lower=0;
var offset_slider_upper=0;
var sector_slider_lower=0;
var sector_slider_upper=0;
var sectors_num = 0;
var c_beg, c_end;
var div_value=5;
$(window).load(
    function() {
        var c_id = $('.form').data('consultation-id');
        ajaxGetConsultationAndAppointments(c_id);
        x_grid =  ($('#range_statusbar').width()/sectors_num);
        sector_slider_upper = sectors_num-1;
        change_val = ($('#slider_lower').width()/2 - x_grid/2);
        $('#slider_lower').css({'width' : x_grid});
        $('#slider_upper').css({'width' : x_grid});

        change_val_l = ($('#slider_lower').find('img').width()/2);
        //console.log(change_val);
        $('#slider_lower').find('img').css( "left", function( index ) {
            return -change_val_l;
        });
        change_val_l = ($('#slider_lower_val').width()/2);
        $('#slider_lower_val').css( "margin-left", function( index ) {
            return -change_val_l;
        });
        change_val_u =$('#slider_upper').width() - $('#slider_upper').find('img').width()/2;
        $('#slider_upper').find('img').css( "left", function( index ) {
            return change_val_u;
        });
        change_val_u = $('#slider_upper').width() - $('#slider_upper_val').width()/2;
        $('#slider_upper_val').css( "margin-left", function( index ) {
            return change_val_u;
        });
        fillRangeBar();
        updateLowerSliderVal();
        updateUpperSliderVal();
        updateInputVals();
        var containmentX1 = $("#slider_lower").parent().offset().left-$('#slider_lower').outerWidth()/2;
        var containmentY1 = $("#slider_lower").parent().offset().top;
        var containmentX2 =  ($("#slider_lower").parent().outerWidth() +  $("#slider_lower").parent().offset().left - $('#slider_lower').outerWidth()/2) - x_grid/2;
        var containmentY2 = ($("#slider_lower").parent().outerHeight() +  $("#slider_lower").parent().offset().top - $('#slider_lower').outerHeight());

        $('#slider_upper').draggable({
            axis: "x",
            containment: [containmentX1, containmentY1, containmentX2, containmentY2],
            grid: [x_grid,0],
            drag: function(){
                var bar_width = $('#range_statusbar').width();
                var sector_step = bar_width/sectors_num ;
                var offset_x_u = $(this).position().left + $(this).width()/2;
                var offset_x_l = $("#slider_lower").position().left;
                //console.log(offset_slider_upper);


                    //offset_slider_upper =  $(this).css({'left': offset_slider_upper});

                for(var i= sector_slider_lower; i < $('#range_statusbar').children().length; i++){
                    var sec = $('#range_statusbar').find('#s'+i);
                    if( sec.position().left <= offset_x_u && sec.position().left + sec.width() >= offset_x_u){
                        sector_slider_upper=i;
                        break;
                    }

                }

                if(sector_slider_lower > sector_slider_upper){
                    sector_slider_upper = sector_slider_lower+1;
                    $(this).trigger('dragstop');
                }
                $(this).css( {"z-index": 2});
                $('#slider_lower').css({"z-index": 1});
                fillRangeBar();
                updateUpperSliderVal();
                updateInputVals();
                //$('#slider_upper_val').text(sector_slider_upper);
            }
        }).bind("dragstop", function()
        {
            var dif = sectors_num - sector_slider_upper - 1;
            var sec_w = ($('#range_statusbar').width()/sectors_num);

            var left_offset = sec_w * dif ;

            change_val = $(this).width()/2 - x_grid/2;

            $(this).css( "left", function( index ) {
                return -left_offset + change_val;
            });
            //$(this).css({'left': offset_slider_upper});
        });
        $('#slider_lower').draggable({
            axis: "x",
            containment: [containmentX1, containmentY1, containmentX2, containmentY2],
            grid: [x_grid,0],
            drag: function() {
                var bar_width = $('#range_statusbar').width();
                var sector_step = bar_width / sectors_num;
                var offset_x_l = $(this).position().left + $(this).width()/2;
                var offset_x_u = $("#slider_upper").position().left;



                for(var i= 0; i < $('#range_statusbar').children().length && i <= sector_slider_upper; i++){
                    var sec = $('#range_statusbar').find('#s'+i);
                    if( sec.position().left <= offset_x_l && (sec.position().left + sec.width()) >= offset_x_l){
                        sector_slider_lower=i;


                        break;
                    }
                }

                if(sector_slider_lower > sector_slider_upper){

                    sector_slider_lower  = sector_slider_upper - 1;
                    $(this).trigger('dragstop');
                }
                $(this).css( {"z-index": 2});
                $('#slider_upper').css({"z-index": 1});
                fillRangeBar();
                updateLowerSliderVal();//$('#slider_lower_val').text(sector_slider_lower);
                updateInputVals();

            }
        }).bind("dragstop", function()
        {
            var dif = sector_slider_lower;
            var sec_w = ($('#range_statusbar').width()/sectors_num);

            var left_offset = sec_w * dif ;

            change_val = $(this).width()/2 - x_grid/2;
            $(this).css( "left", function( index ) {
                return left_offset - change_val;
            });
            //$(this).css({'left': offset_slider_upper});
        });;



    })

