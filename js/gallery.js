/**
 * Created by Alex on 25.03.2016.
 */
$(document).ready(
    function() {
        $("td").yoxview();
        $(".row_image").yoxview();

        $("#gallery_row").mCustomScrollbar({
            axis:"x",
            theme: 'dark'
        });
    }
)