<div class="page_title">Gallery<div class="underline"></div></div>
<div id="gallery_row">
    <?php
    foreach($images as $image){
        ?>
        <div class="row_image">
        <a href ="<?= Yii::app()->request->baseUrl.'/images/gallery/original/'.$image->img_name?>">
            <?= CHtml::image(Yii::app()->request->baseUrl.'/images/gallery/thumbnails/'.$image->img_name); ?>
        </a>
        </div>
    <?php
    }
    ?>

</div>

<?php $this->widget('application.extensions.mywidgets.MyPaginator', array(
    'range' => Yii::app()->config->get('PAGINATION.GALLERY_NEARBYPAGESNUM'),
    'cur_page' => $cur_page,
    'pages_num' => $pages_num
)); ?>

