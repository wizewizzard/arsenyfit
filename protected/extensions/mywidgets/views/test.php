<div id="items_container">
    <?php
    if(empty($items)){?>
        <div>Товары отсутствуют</div>
        <?php
    }
    else {
        if ($mode == 'lined') $class_name = 'item_row';
        else if ($mode == 'tabled') $class_name = 'item_row_t';
        foreach ($items as $key => $items_of_group) {
            ?>
            <div><?= $key; ?></div>
            <ul class="ul_forscroll mCustomScrollbar" data-mcs-theme="dark">
                <?php
                foreach ($items_of_group as $item) {
                    ?>
                    <li><div class="item_container">
                        <?= CHtml::link($item->title, Yii::app()->createUrl('shop/item', array('id' => $item->id)));
                        //CHtml::link($item->title, array('shop/aviewitem', 'id' => $item->id));
                        ?>
                        <div data-id-item="<?= $item->id; ?>" class="icon_cart_container">Add into cart</div>
                    </div></li>

                    <?php
                } ?>
            </ul>
            <?php
        }
    }
    ?>
</div>

<script>
    (function($){
        $(window).load(function(){
            $(".ul_forscroll").mCustomScrollbar({
                axis:"x",
                theme:"light-3",
                advanced:{autoExpandHorizontalScroll:true}
            });
        })
    })(jQuery);
</script>
