<div id="items_container">
<?php 
if(empty($items)){?>
 <div class="main_message">Товары отсутствуют</div>
 <?php   
}
else {
   // if ($mode == 'lined') $class_name = 'item_row';
    //else if ($mode == 'tabled') $class_name = 'item_row_t';
    $class_name = 'item_row';
    foreach ($items as $key => $items_of_group) {
        ?>
        <div class="item_type"><?= $key; ?></div>
        <div class="<?= $class_name ?>">
            <?php
            foreach ($items_of_group as $item) {
                ?>
                <div class="item_container hvr-float hvr-underline-from-center" onclick="window.location='<?= Yii::app()->createUrl('shop/item', array('id' => $item->id)); ?>';">
                <div class="item_title">
                    <?= $item->title ?>

                </div>
                <div class="item_image">
                <?php
                if (!empty($item->img_name)) {
                    $images = $item->getImagesArray();
                    if (@file_exists(Yii::getPathOfAlias('webroot.images.items.thumbnails') . DIRECTORY_SEPARATOR .  $images[0]) !== false) {
                    ?>

                        <?php
                        echo CHtml::image(Yii::app()->request->baseUrl . '/images/items/thumbnails/' . $images[0]);
                        ?>
                    <?php
                        }
                    else
                        echo CHtml::image(Yii::app()->request->baseUrl.'/images/others/no_image_available.png');
                    }
                    else
                        echo CHtml::image(Yii::app()->request->baseUrl.'/images/others/no_image_available.png');
                    ?>
                    </div>
                    <?php
                    $currency;
                    switch(Yii::app()->config->get('GLOBAL.SITE_CURRENCY')){
                        case 'DOL': $currency = ' $'; break;
                        case 'RUB': $currency = ' руб.'; break;
                        case 'EUR': $currency = ' eur'; break;
                        default: $currency = ' $'; break;
                    }
                    ?>
                    <div class = "item_price"><?= $item->price.$currency; ?></div>
                    <!---
                    <div data-id-item="<?= $item->id; ?>" class="icon_cart_container">Add into cart
                    <?php
                     //CHtml::textField('item_number','1',array('class' => 'item_number_container'));
                    ?>
                    </div> -->
                </div>
                <?php
            } ?>
        </div>
        <?php
    }
}
?>
</div>

<script>

    (function($){
        $(document).ready(
            function() {
                $(".item_image").yoxview();
            }
        )
        $(window).load(function(){
            $(".item_row").mCustomScrollbar({
                axis:"x",
                theme: 'dark'
            });
        });
    })(jQuery);
</script>


