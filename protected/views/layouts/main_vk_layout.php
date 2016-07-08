<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html>
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="language" content="en">

    <!-- JS -->
    <script src="http://code.jquery.com/jquery-1.12.1.js"
            integrity="sha256-VuhDpmsr9xiKwvTIHfYWCIQ84US9WqZsLfR4P7qF6O8="
            crossorigin="anonymous"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/cookie.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/scroll_helper.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/easing.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/main.js"></script>
    <!-- blueprint CSS framework -->
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection">
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print">
    <!--[if lt IE 8]>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection">
    <![endif]-->

    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/new_main.css">
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css">
    <link rel="shortcut icon" href="<?php echo Yii::app()->baseUrl; ?>/images/others/lambda_icon.png" type="image/png">
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div class="container" id="page">

    <div id="header">
        <?= CHtml::link('<div id="logo">Arseny
		<div id="sublogo">professional athlete</div>
		</div>', array('/site/index')) ?>
        <?php $this->widget('application.extensions.mywidgets.SiteSearch'); ?>
        <div id="header_image"><img src="<?php echo Yii::app()->baseUrl; ?>/images/others/Nature.jpg"/></div>
        <div id="contact_icons">
            <?= CHtml::link('<img class="icon_contact" src="'. Yii::app()->request->baseUrl.'/images/others/instagram.png" alt="instagram icon" />', 'https://www.instagram.com/');?>
            <?= CHtml::link('<img class="icon_contact" src="'. Yii::app()->request->baseUrl.'/images/others/vk.png" alt="instagram icon" />', 'https://www.vk.com/');?>
            <?= CHtml::link('<img class="icon_contact" src="'. Yii::app()->request->baseUrl.'/images/others/youtube.png" alt="instagram icon" />', 'https://www.youtube.com/');?>
        </div>
    </div><!-- header -->

    <div id="mainmenu">
        <?php $this->widget('zii.widgets.CMenu',array(
            'encodeLabel' => false,
            'items'=>array(
                array('label'=>'Home', 'url'=>array('/site/index')),
                array('label'=>'Gallery', 'url'=>array('/gallery/index')),
                array('label'=>'Consultation', 'url'=>array('/consultation/index')),
                array('label'=>'Shop', 'url'=>array('/shop/index')),
                array('label'=>'About', 'url'=>array('/site/about')),
                //array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
                array('label'=>'Logout', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest),
                array('label'=>'Admin', 'url'=>array('/admin/default/index'), 'visible'=>Yii::app()->user->isAdmin()),
                array('label'=> '<img src="/images/others/cart_icon_white.png" />',
                    'url'=>array('/cart/index'),
                    'itemOptions' => array(
                        'id' => 'cart_icon'
                    )),
                /*array('label'=> '<img src="/images/others/lock_icon.png" />',
                    'url'=>array('/site/login'),
                    'visible'=>Yii::app()->user->isGuest,
                    'itemOptions' => array(
                        'id' => 'lock_icon'
                    )),*/
            ),
            'id' => 'menu'
        )); ?>

    </div><!-- mainmenu -->
    <?php if(isset($this->breadcrumbs)):?>
        <?php $this->widget('zii.widgets.CBreadcrumbs', array(
            'links'=>$this->breadcrumbs,
        )); ?><!-- breadcrumbs -->
    <?php endif?>

    <?php echo $content; ?>

    <div class="clear"></div>
    <div id="main_footer">
        <div id="footer_contact_icons">
            Find me on Instagram <?= CHtml::link('arseny_fit', 'https://www.instagram.com/');?> /

            VK <?= CHtml::link('arseny_fit', 'https://www.vk.com/');?> /
            Youtube <?= CHtml::link('arseny_fit', 'https://www.youtube.com/');?>
        </div>
        <div id="copyright">
            Copyright © <?= date("Y"); ?> <?= Yii::app()->name ?>.  All rights reserved.
        </div>
    </div>

</div><!-- page -->
</body>
</html>
