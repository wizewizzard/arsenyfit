var Months= [
    {name:"January"},
    {name:"February"},
    {name:"March"},
    {name:"April"},
    {name:"May"},
    {name:"June"},
    {name:"July"},
    {name:"August"},
    {name:"September"},
    {name:"October"},
    {name:"November"},
    {name:"December"}
];
var isoWeekdays = [
    {name:"Monday", shortened: "Mo"},
    {name:"Tuesday", shortened: "Tu"},
    {name:"Wednesday", shortened: "We"},
    {name:"Thursday", shortened: "Th"},
    {name:"Friday", shortened: "Fr"},
    {name:"Saturday", shortened: "Sa"},
    {name:"Sunday", shortened: "Su"}
];

var default_search_message = "search";
$(document).ready(
    function() {
        $('#SiteSearchForm_string').val(default_search_message);
       // $min = $('#mainmenu').find('li').length;

       // $('#mainmenu').find('li').css({'width' : 100/$min+'%'});
    }
)