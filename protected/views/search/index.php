<div class="page_title">SEARCH<div class="underline"></div></div>
<div id="search_among_form">

    <?php
    $form=$this->beginWidget('CActiveForm', array(
        'id'=>'option_form',

        'enableAjaxValidation'=>false,
    )); ?>
    <?php
    echo CHtml::activeHiddenField($search, 'string');
    ?>
    Искать среди:
    <div class="radio">
    <?php
    echo
    $form->radioButtonList($option, 'option',array('news'=>'Новостей','shop'=>'Товаров'),array('separator'=>'', 'labelOptions'=>array('style'=>'display:inline')));
    ?>
    </div>
    <?php
    echo CHtml::submitButton('Обновить', array('class' => 'global_button', 'id' => 'refresh_option'));
    ?>
    <?php $this->endWidget(); ?>

</div>
<div id="search_result">
    <?php
    switch($option->option){
        case 'news': $view = '_viewpost'; break;
        case 'shop': $view = '_viewitem'; break;
        default: $view = '_viewpost'; break;
    }
    if( $dataProvider->itemCount > 0){
        $this->widget('zii.widgets.CListView', array(
            'dataProvider' => $dataProvider, // переданный дата-провайдер
            'itemView' => $view, // имя view для отрисовки отдельного поста
            'ajaxUpdate'=>false, // не будем делать обновление через ajax
            'enablePagination'=>true,// отключаем стандартную пагинацию CListView
            'summaryText' => 'Показано '. $dataProvider->itemCount .' результатов из '. $count.'.', // текст с количество найденных постов
        ));

    }
    else{ ?>
        <div class="main_message">Nothing was found.</div>
    <?php }?>
</div>

<?php

?>