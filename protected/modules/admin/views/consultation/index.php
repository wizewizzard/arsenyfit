<?php
/* @var $this ConsultationController */
/* @var $dataProvider CActiveDataProvider */
?>

<div class="page_title">консультации<div class="underline"></div></div>
<?php if(count($closest_consultations) > 0){ ?>
    <div id="closest_consultation_time">
        Ближайшая консультация запланирована через
        <?php
        date_default_timezone_set('UTC');
        $cur_time_tstmp = strtotime(date('Y-m-d H:i:s', time()));
        $day_st = strtotime('TODAY');
        $time_from = new DateTime($closest_consultations[0]['consultation_calendar']->time_from );
        $offset = new DateTime($closest_consultations[0]['consultation']->offset);
        $consultation_time_tstmp =  $time_from->getTimestamp() + $offset->getTimestamp()- $day_st - $cur_time_tstmp;
        //var_dump($closest_consultations[0]['consultation']);
        ?>
        <span>
        <?= floor($consultation_time_tstmp/3600); ?> часов
        <?= round(($consultation_time_tstmp%3600)/60); ?> минут.
        </span>
    </div>
    <div id="closest_consultations_list">
        <div>Ближайшие консультации:</div>
        <?php
        $day_st = strtotime('TODAY');
            foreach($closest_consultations as $closest_consultation){
                $time_from = new DateTime($closest_consultation['consultation_calendar']->time_from );
                $offset = new DateTime($closest_consultation['consultation']->offset);
                $date_tstmp =  $time_from->getTimestamp() + $offset->getTimestamp() - $day_st;
                date_default_timezone_set('Asia/Almaty');
                $date = new DateTime();
                $date->setTimestamp($date_tstmp); ?>
        <div class="consultation_date"><?= $date->format('H:i:s d/m') ?> (Nsk)</div>
        <?php

            }
        ?>
    </div>
<?php }
else{ ?>
    <div class="main_message">Консультаций не запланировано.</div>
<?php }?>



