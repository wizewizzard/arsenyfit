<?php
switch(Yii::app()->config->get('GLOBAL.SITE_CURRENCY')){
    case 'DOL': $currency = ' $'; break;
    case 'RUB': $currency = ' руб.'; break;
    case 'EUR': $currency = ' eur'; break;
    default: $currency = ' $'; break;
}
?>
<div class="page_title"><?= $item->title; ?><div class="underline"></div></div>
<div id="item_full_info">
    <div id="left_column">
    <div id="item_images_row">
        <?php
        if (!empty($item->img_name)) {
        $images = $item->getImagesArray();
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
        <div id="price_container"><?= $item->price.$currency ?></div>
        <?php
        $offers = $item->offers;
        if(isset($offers))
        foreach($offers as $offer){
        ?>
        <div id="offer_container"><?= $offer->description ?></div>
        <?php }
        ?>
        <div id="cart_container">
            <div data-id-item="<?= $item->id; ?>" class="global_button icon_cart_container">Add into cart</div>
        </div>
    </div>
    <div id="right_column">
    <div id="item_info">
        <div id="description_title">Описание</div>
        <div id="item_description">

            <?= (!empty($item->description)) ? $item->description: 'Описание товара отсутствует.' ; ?>
        </div>
    </div>
    </div>
</div>
<div id="commentary_section">
    <div id="vk_comments"></div>
    <script type="text/javascript">
        VK.Widgets.Comments("vk_comments", {limit: 15, width: "600px", attach: "*"});
    </script>
</div>

