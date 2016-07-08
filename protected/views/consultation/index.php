<div class="page_title">Consultation<div class="underline"></div></div>
<div id="calendar">
    <div id="calendar_left_column">
    <div id="calendar_title">
        <div id="prev"><?= CHtml::image(Yii::app()->request->baseUrl.'/images/others/left_arrow.png'); ?></div>
        <div id="title"></div>
        <div id="next"><?= CHtml::image(Yii::app()->request->baseUrl.'/images/others/right_arrow.png'); ?></div>
    </div>
    <div id ="table_container">
        <img id="loader_image" src="<?= Yii::app()->request->baseUrl;?>/images/others/loader.gif" />
    </div>
    </div>
    <div id="calendar_right_column">
        <div id="pre_info">
            Is he staying arrival address earnest. To preference considered it themselves inquietude collecting estimating. View park for why gay knew face. Next than near to four so hand. Times so do he downs me would. Witty abode party her found quiet law. They door four bed fail now have.

            Compliment interested discretion estimating on stimulated apartments oh.  </div>
    <div id="consultations_info">
        <div id="no_consultations_message">Консультаций не запланировано.</div>
        <div id="show">SHOW</div>

    </div>

    </div>
</div>
<script>
    (function($){
        $(window).load(function(){
            $("#consultations_info").mCustomScrollbar({
                theme:"dark"
            });
        });
    })(jQuery);
</script>