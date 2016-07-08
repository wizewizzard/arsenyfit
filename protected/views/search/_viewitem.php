<?php
switch(Yii::app()->config->get('GLOBAL.SITE_CURRENCY')){
    case 'DOL': $currency = ' $'; break;
    case 'RUB': $currency = ' руб.'; break;
    case 'EUR': $currency = ' eur'; break;
    default: $currency = ' $'; break;
}
?>
<div class="search_post_container">
    <div class="search_post_title">
        <?php echo $data->title; ?>
    </div>
    <div class="search_post_price">
        <?php echo $data->price.$currency; ?>
    </div>
    <div class="search_post_button">
        <?php echo CHtml::link('Перейти к товару', array('shop/item', 'id' => $data->id), array('class' => 'global_button')); ?>
    </div>
</div>