<div class="page_title">CART<div class="underline"></div></div>
<div id="cart_content">

<?php
$currency;
switch(Yii::app()->config->get('GLOBAL.SITE_CURRENCY') == 'DOL'){
    case 'DOL': $currency = ' $'; break;
    case 'RUB': $currency = ' rub'; break;
    case 'EUR': $currency = ' eur'; break;
    default: $currency = ' $'; break;
}
?>
<?php
if(!empty($items)){?>
<div id="left_column">
    <div id="cart_items_list">
<?php
foreach($items as $item){?>
    <div class="item_in_cart_container" data-id-item="<?= $item[0]->id ?>">
        <div class="cart_item_block col1">
        <div class="item_images_row">
            <?php
            if (!empty($item[0]->img_name)) {
                $images = $item[0]->getImagesArray();
                foreach($images as $image){ ?>
                    <div class="item_image">
                        <a href="<?= Yii::app()->request->baseUrl . '/images/items/original/' . $image?>">
                            <?php
                            echo CHtml::image(Yii::app()->request->baseUrl . '/images/items/thumbnails/' . $image);
                            ?>
                        </a>

                    </div>
                <?php }
            }
            ?>
        </div>
        </div>
    <div class="cart_item_block col2">
        <div class="title"><?= $item[0]->title; ?></div>
        <div class="item_total"><?= $item_totals[$item[0]->id]['total'].$currency; ?>
        <?php
        if(!empty($item_totals[$item[0]->id]['total_under_offer']) && $item_totals[$item[0]->id]['total_under_offer'] != $item_totals[$item[0]->id]['total']){ ?>
        / <span class="item_total_with_offer"><?= $item_totals[$item[0]->id]['total_under_offer'].$currency; ?></span>
        <?php } ?>
        </div>
    </div>
    <div class="cart_item_block col3">
            <input value="<?= $item[1]?>" class="item_number_container"/>
            <div class="down global_button unselectable" onclick="modify_input(this,-1)">-</div>
            <div class="up global_button unselectable" onclick="modify_input(this,1)">+</div>
    </div>

    <div data-id-item="<?= $item[0]->id; ?>" class="cart_item_block icon_remove_from_cart col4 global_button">Remove</div>
    </div>
<?php
}
?>
    </div>
    <div id="update_total_container">
    <div id="update_total" class="global_button" onclick="location.reload();">Update total</div>
    </div>
</div>
    <div id="right_column">
        <div id="amount">
            <div>Total</div><div><?= $total_under_offers.$currency; ?></div>
        </div>
        <div id="checkout" >
            <?= CHtml::link('<div class="global_button">Checkout</div>', array('order/makeorder')); ?>
        </div>
    </div>
<?php
}
else{?>
    <div class="main_message">Your cart is empty. Take a look at our <?= CHtml::link('store', '/shop/index', array('class' => 'empty_cart_link')) ?>.</div>
<?php }
?>

</div>
