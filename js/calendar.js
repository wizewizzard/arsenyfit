var cur_mm;
var cur_yyyy;

function getMonth(dir){
    switch(dir){
        case 1:
            cur_mm++;
            if(cur_mm>=12){
                cur_mm=0;
                cur_yyyy++;
            }
            break;
        case -1:
            cur_mm--;
            if(cur_mm<0){
                cur_mm=11;
                cur_yyyy--;
            }
            break;
        default: return;
    }
}
function generateCalendar(mm, yyyy, dir){

   // var moment = require('moment');

    // month in moment is 0 based, so 9 is actually october, subtract 1 to compensate
    // array is 'year', 'month', 'day', etc
    //if($('#calendar_title').length) $('#calendar_title').remove();
    if($('#calendar_table').length) $('#calendar_table').remove();
    var table_element = $('<table id="calendar_table"></table>').css({'opacity':'0.0'});

    $('#calendar_title').find('#title').text(Months[mm-1]['name']+' ' + yyyy);

    var startDate = moment([yyyy, mm - 1]);
    var endDate = moment(startDate).endOf('month');
    var start_day_of_week = moment(startDate).isoWeekday();
    var days_in_month = moment(startDate).daysInMonth();

    //var month_
    first = moment(startDate).isoWeek();
    last = moment(endDate).isoWeek();

    if( first > last) {
        last = first + last;
    }
    var weeks_num = last - first + 1;
    var th_row = $('<tr id="headers"></tr>');
    table_element.append(th_row);

    for(var j=0; j < 7; j++){
        th_row.append('<th class="calendar_cell"><div>'+isoWeekdays[j]['shortened']+'</div></th>');
    }

    for(var i =0 ; i< weeks_num; i++) {
        table_element.append('<tr data-id-week="' + (i+1) + '" class="week_row_' + (i+1) + '"></tr>');
        for(var j=0; j < 7; j++){
            var day_cell = $('<td id="day_n_'+(i*7+j+1)+'"></td>');
            //var info_div = $('<div class = "calendar_table_not_month_day"></div>');
            //day_cell.append(info_div);
            table_element.find('.week_row_' + (i+1)).
                append(day_cell);
        }
    }

    for(var i = 1; i < weeks_num*7; i++){
        if(i<start_day_of_week || i >= start_day_of_week+ days_in_month) table_element.find('#day_n_'+i).removeAttr('id').addClass('calendar_table_not_month_day');
        else
        table_element.find('#day_n_'+i).removeAttr('id').addClass('calendar_table_day').removeClass('calendar_table_not_month_day').
            text((i-start_day_of_week+1)).attr('id', i-start_day_of_week+1).data('date', i-start_day_of_week+1);
    }
    $('#table_container').append(table_element);
    table_element.find('.calendar_table_day').bind( "click", function() {
        //console.log($(this).attr('id'));
        ajaxGetDayInfo(cur_mm+1, cur_yyyy, $(this).attr('id'));
       // console.log( $(this).data('date'))
    });

    switch(dir){
        case -1:
            table_element.css({'margin-left':'-400px'});
            table_element.animate({
                opacity: 1.0,
                marginLeft: 0
            }, 300, function() {
                //table_element.find('.calendar_cell').fadeOut(5000);
            }); break;
        case 0:  table_element.animate({
            opacity: 1.0
        }, 1000, function() {
            //table_element.find('.calendar_cell').fadeOut(5000);
        }); break;
        case 1:
            table_element.css({'margin-left':'800px'});
            table_element.animate({
                opacity: 1.0,
                marginLeft: 0
            }, 300, function() {
                //table_element.find('.calendar_cell').fadeOut(5000);
            }); break;
    }


     /*table_element.animate({
     opacity: 1.0
     }, 5000, function() {
     // Animation complete.
     });*/
    /*for(var i =0 ; i < weeks_num; i++)
    for()
        $('#calendar_table').append('<tr class="id_week'+i+1+'"></tr>');*/

    // make sure to call toDate() for plain JavaScript date type
}
function setCalendarDay(day_n){
    $('#calendar_table').find('#'+day_n).addClass('consultation_day'); //text((i-start_day_of_week+1));
}
function ajaxGetConsultations(mm, yyyy) {
    return $.ajax({
        type: 'get',
        async: false,
        url: "/consultation/getconsultations",
        data: {
            'month': mm,
            'year': yyyy
        },
        success: function(data)
        {
            var jsonData = JSON.parse(data);
            console.log(data);
            for (var i = 0; i < jsonData.length; i++) {
                var consultation = jsonData[i];

                var consultation_date = moment(consultation.time_from +'Z').tz('Europe/Moscow');
                var date = moment(consultation_date).date();
                setCalendarDay(date);
            }
        },
        error: function (textStatus, errorThrown) {
            stopLoadingAnimation(0);
            console.log( "nothing 2 " );
        }
    });
}
function ajaxGetDayInfo(mm, yyyy, date){
    return $.ajax({

        type: 'get',
        async: false,
        url: "/consultation/GetAppointments",
        data: {
            'month': mm,
            'year': yyyy,
            'day': date
        },
        success: function(data)
        {
            $('#consultations_info').empty();
            var jsonData = JSON.parse(data);
            //console.log(jsonData[1]);
            var no_consultations_message = $('<div class="no_consultations_message">На этот день консультаций не запланировано.</div>');

            if(jsonData[0].length > 0) {
                var consultations_container = $('<div class="consultations_container"></div>');


                for (var i = 0; i < jsonData[0].length; i++) {
                    var c_id = jsonData[0][i].id;
                    var c_beg = moment(jsonData[0][i].time_from+'Z').tz('Europe/Moscow');
                    var c_end = moment(jsonData[0][i].time_until+'Z').tz('Europe/Moscow');

                    var c_duration = moment.duration(c_end.diff(c_beg)).asMinutes();
                    var consultation_container = $('<div class="consultation_container"></div>');
                    var consultation_statusbar = $('<div class="consultation_statusbar"></div>');
                    var consultation_info = $('<div class = "consultation_info"></div>');
                    var consultation_date = $('<div class = "consultation_date"></div>');
                    var enroll_button = $('<div class="button_container"><a href="sign/'+c_id+'" class="global_button">Записаться</a></div>');
                    //Months[mm-1]['name']+
                    var date = c_beg.date();
                    var month = Months[c_beg.month()]['name'];
                    //console.log(Months[c_beg.month()]['name']);
                    consultation_date.append('<span class="date_span">' + date + ' of ' + Months[c_beg.month()]['name'] + '</span>' +
                        '<span class="duration_span">' + c_beg.format('HH:mm') + ' - ' + c_end.format('HH:mm') + '</span>');
                    consultation_info.append(consultation_date);
                    consultation_info.append(consultation_statusbar);
                    consultation_container.append(consultation_info);

                    var no_appointments_message = $('<div class="no_appointments_message">На этот сеанс еще никто не записался.</div>');
                    var appointments_container = $('<div class="appointments_container"></div>');
                    console.log(i);
                    if (jsonData[1][c_id].length > 0) {
                        var man_icon = '<img class="man_icon" src="/images/others/man_icon.png" />';
                        for (var j = 0; j < jsonData[1][c_id].length; j++) {

                            var a_duration = moment.duration(jsonData[1][c_id][j].duration).asMinutes();
                            var a_offset = moment.duration(jsonData[1][c_id][j].offset).asMinutes();
                            var a_p_duration = a_duration / c_duration * 100;
                            var a_p_offset = a_offset / c_duration * 100;
                            console.log('beg: ' + c_beg);
                            console.log('ofs: ' + a_offset);
                            console.log('dur: '+ a_duration);
                            var t1_beg = (c_beg).clone();
                            var t2_beg = (c_beg).clone();
                            var appointment_container = $('<div class="appointment_container"></div>');
                            var statusbar_value = $('<div class="statusbar_value"></div>');
                            statusbar_value.css({'width': a_p_duration + '%', 'left': a_p_offset + '%'});
                            appointment_container.append(man_icon);
                            appointment_container.append('<div class="appointment_info">' + t1_beg.add(a_offset, 'minutes').format('HH:mm') +
                                ' - ' + t2_beg.add(a_offset, 'minutes').add(a_duration, 'minutes').format('HH:mm') + '</div>');
                            appointments_container.append(appointment_container);
                            consultation_statusbar.append(statusbar_value);
                        }
                    }
                    else {
                        appointments_container.append(no_appointments_message);
                    }
                    consultation_container.append(appointments_container);
                    if(!moment.utc().isAfter(c_end))
                    consultation_container.append(enroll_button);
                    consultations_container.append(consultation_container);

                }
                $('#consultations_info').append(consultations_container);

            }
            else{
                //alert(jsonData[0].length);
                $('#consultations_info').append(no_consultations_message);
            }
        },
        error: function (textStatus, errorThrown) {
            //stopLoadingAnimation(0);
            console.log( "nothing 2 " );
        }
    });
}

function ajaxGetMonthInfo(mm, yyyy){
    return $.ajax({
        type: 'get',
        async: false,
        url: "/consultation/GetAppointments",
        data: {
            'month': mm,
            'year': yyyy
        },
        success: function(data)
        {
            //console.log(data);
            $('#consultations_info').empty();
            var jsonData = JSON.parse(data);
            //console.log(jsonData[1]);
            var no_consultations_message = $('<div class="no_consultations_message">На этот месяц консультаций не запланировано.</div>');

            if(jsonData[0].length > 0) {
                var consultations_container = $('<div class="consultations_container"></div>');


                for (var i = 0; i < jsonData[0].length; i++) {
                    var c_id = jsonData[0][i].id;
                    var c_beg = moment(jsonData[0][i].time_from+'Z').tz('Europe/Moscow');
                    console.log(c_beg);
                    var c_end = moment(jsonData[0][i].time_until+'Z').tz('Europe/Moscow');

                    var c_duration = moment.duration(c_end.diff(c_beg)).asMinutes();
                    var consultation_container = $('<div class="consultation_container"></div>');
                    var consultation_statusbar = $('<div class="consultation_statusbar"></div>');
                    var consultation_info = $('<div class = "consultation_info"></div>');
                    var consultation_date = $('<div class = "consultation_date"></div>');
                    var enroll_button = $('<div class="button_container"><a href="sign/'+c_id+'" class="global_button">Записаться</a></div>')
                    //Months[mm-1]['name']+
                    var date = c_beg.date();
                    var month = Months[c_beg.month()]['name'];
                    //console.log(Months[c_beg.month()]['name']);
                    consultation_date.append('<span class="date_span">' + date + ' of ' + Months[c_beg.month()]['name'] + '</span>' +
                        '<span class="duration_span">' + c_beg.format('HH:mm') + ' - ' + c_end.format('HH:mm') + '</span>');
                    consultation_info.append(consultation_date);
                    consultation_info.append(consultation_statusbar);
                    consultation_container.append(consultation_info);

                    var no_appointments_message = $('<div class="no_appointments_message">На этот сеанс еще никто не записался.</div>');
                    var appointments_container = $('<div class="appointments_container"></div>');
                    console.log(i);
                    if (jsonData[1][c_id].length > 0) {
                        var man_icon = '<img class="man_icon" src="/images/others/man_icon.png" />';
                        for (var j = 0; j < jsonData[1][c_id].length; j++) {

                            var a_duration = moment.duration(jsonData[1][c_id][j].duration).asMinutes();
                            var a_offset = moment.duration(jsonData[1][c_id][j].offset).asMinutes();
                            var a_p_duration = a_duration / c_duration * 100;
                            var a_p_offset = a_offset / c_duration * 100;
                            var t1_beg = (c_beg).clone();
                            var t2_beg = (c_beg).clone();
                            var appointment_container = $('<div class="appointment_container"></div>');
                            var statusbar_value = $('<div class="statusbar_value"></div>');
                            statusbar_value.css({'width': a_p_duration + '%', 'left': a_p_offset + '%'});
                            appointment_container.append(man_icon);
                            appointment_container.append('<div class="appointment_info">' + t1_beg.add(a_offset, 'minutes').format('HH:mm') +
                                ' - ' + t2_beg.add(a_offset, 'minutes').add(a_duration, 'minutes').format('HH:mm') + '</div>');
                            appointments_container.append(appointment_container);
                            consultation_statusbar.append(statusbar_value);
                        }
                    }
                    else {
                        appointments_container.append(no_appointments_message);
                    }
                    consultation_container.append(appointments_container);
                    if(!moment.utc().isAfter(c_end))
                    consultation_container.append(enroll_button);
                    consultations_container.append(consultation_container);

                }
                $('#consultations_info').append(consultations_container);

            }
            else{
                //alert(jsonData[0].length);
                $('#consultations_info').append(no_consultations_message);
            }
        },
        error: function (textStatus, errorThrown) {
            //stopLoadingAnimation(0);
            console.log( "nothing 2 " );
        }
    });
}

function startLoadingAnimation() // - функция запуска анимации
{
    // найдем элемент с изображением загрузки и уберем невидимость:
    var imgObj = $("#loader_image");
    imgObj.show();

    // вычислим в какие координаты нужно поместить изображение загрузки,
    // чтобы оно оказалось в серидине страницы:
    var centerY = (imgObj.parent().height() - imgObj.height())/2;
    var centerX = (imgObj.parent().width() - imgObj.width())/2;
   // console.log($(this).parent().width + ' ' + centerY);
    // поменяем координаты изображения на нужные:
    imgObj.css({'top': centerY, 'left': centerX});
   // imgObj.position({top:centerY, left:centerX});
}

function stopLoadingAnimation() // - функция останавливающая анимацию
{
    $("#loader_image").hide();
}


$(document).ready(
    function(){
        $('#show').click(function() {
            ajaxGetMonthInfo(cur_mm+1,cur_yyyy);
        });
        $('#next').click(function() {
            //var mm = moment().utc().month();
            //var yyyy = moment().utc().year();
            getMonth(1);
            startLoadingAnimation();

            generateCalendar(cur_mm+1,cur_yyyy, 1);
            ajaxGetConsultations(cur_mm+1,cur_yyyy);
            ajaxGetMonthInfo(cur_mm+1,cur_yyyy);
            stopLoadingAnimation();
        });
        $('#prev').click(function() {
            getMonth(-1);
            startLoadingAnimation();
            generateCalendar(cur_mm+1,cur_yyyy, -1);
            ajaxGetConsultations(cur_mm+1,cur_yyyy);
            ajaxGetMonthInfo(cur_mm+1,cur_yyyy);
            stopLoadingAnimation();
        });
        cur_mm = moment().utc().month();
        cur_yyyy = moment().utc().year();
       // console.log(moment().utc().toString());
        //sunday =0, monday 1
        //console.log(mm + ' ' + yyyy);
        startLoadingAnimation();
        generateCalendar(cur_mm+1,cur_yyyy, 0);
        ajaxGetConsultations(cur_mm+1,cur_yyyy);
        ajaxGetMonthInfo(cur_mm+1,cur_yyyy);
        stopLoadingAnimation();
        /*console.log(date + ' ' + dd + ' ' + mm + ' ' + yyyy);
        var t = moment().endOf('month');
        console.log(moment.utc().toString());
        console.log(moment(moment.utc().toString()).local().toString());
       // JSON.decode( t);
        console.log( t.day().toString());

        //console.log( JSON.parse(t));
        console.log(
            moment(moment().format(), "YYYY-MM").daysInMonth());*/

        /*console.log(
            parseInt(getNumFromStr(moment().endOf('month').fromNow())));*/


    }
)

