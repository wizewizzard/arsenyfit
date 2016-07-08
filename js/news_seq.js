( function( $ ) {
    $(document).ready(function(){
        var offset=0;
        var loading_flag = false;
        var scrolling_flag=true;
        function ajaxFunction() {
            return $.ajax({
                type: 'get',
                async: false,
                url: "/site/ajaxgetposts",
                data: {
                    'offset': offset++
                },
                success: function(data)
                {
                    if(data==-1){scrolling_flag = false;}
                    else{
                        var posts = JSON.parse(data);
                        for( var i=0; i < posts.length; i++){
                            var article_container = $('<article></article>');
                            var h = $('<div class="post_title"><a href="../post/view/'+posts[i]['id']+'">' + posts[i]['title'] + '</a></div>');
                            var date = moment.utc(posts[i]['date']);
                            var date_str = Months[date.month()]['name'] + ' ' + date.date() +  ', ' + date.year();
                            var date_container = $('<div class="post_date"></div>');
                            date_container.text(date_str);
                            console.log(date_str);
                            var preview = $('<div class="post_preview">' + posts[i]['preview'] + '</div>');

                            var str_tags = "";
                            if(posts[i]['tags'] != null && posts[i]['tags'].length > 0) {
                                var re = /,\s*/;
                                var tags = posts[i]['tags'].split(re);
                                for(var j=0; j < tags.length; j++){
                                    var params = encodeURIComponent('searchString') + '=' + encodeURIComponent(tags[j]);
                                    var href = '/search/index?' + params;
                                    var a = '<a href="'+href + '">' + tags[j] + '</a> ';
                                    str_tags += a;
                                }
                            }
                            var tags_container = $('<div class="tags"></div>');


                            tags_container.append(str_tags);
                            article_container.append(date_container).append(h).append(preview).append(tags_container);
                            $("#post_seq").append(article_container);
                        }
                        //console.log(data);
                       // console.log("done ajax");
                    }
                },
                error: function (textStatus, errorThrown) {
                    scrolling_flag = false;
                }
            });
        }

        while(scrolling_flag && inView('#showMore')){
            if (!loading_flag)
            {
                loading_flag = true;

                var saved = $('#showMore').clone(true);
                $('#showMore').remove().delay(500);

                ajaxFunction();
                if(scrolling_flag)
                    $.when($("#post_seq").append(saved)).then(function(){loading_flag = false;});
                else loading_flag = false;
                console.log("while step compl");

            }
        }
        $(window).scroll(function(){
            if(scrolling_flag && inView('#showMore')){
                if (!loading_flag)
                {
                    var saved = $('#showMore').clone(true);
                    $('#showMore').remove();
                    // выставляем блокировку
                    loading_flag = true;
                    ajaxFunction();
                    if(scrolling_flag)
                        $.when($("#post_seq").append(saved)).then(function(){loading_flag = false;});
                    else loading_flag = false;
                }
            }
        });
        $('#showMore').bind('click', function(){
            if(scrolling_flag && inView('#showMore')){
                if (!loading_flag)
                {
                    var saved = $('#showMore').clone(true);
                    $('#showMore').remove();
                    // выставляем блокировку
                    loading_flag = true;
                    ajaxFunction();
                    if(scrolling_flag)
                        $.when($("#post_seq").append(saved)).then(function(){loading_flag = false;});
                    else loading_flag = false;
                }
            }
        });
    });
} )( jQuery );