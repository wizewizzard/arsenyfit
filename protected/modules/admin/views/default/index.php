<?php
?>
<div id="admin_panel">
	<h2>Панель администрирования</h2>
	<div class="admin_group">
		<div class="group_title">Управление контентом</div>
		<?= CHtml::image(Yii::app()->request->baseUrl.'/images/others/monochrome_icons/Writing.png', 'text', array('class' => 'group_image')) ?>
		<ul class="admin_list" id="admin_content_group">
			<li><?php echo CHtml::link('Посты', array('post/admin'));?> </li>
			<li><?php echo CHtml::link('Галерея', array('galleryimage/admin'));?> </li>
		</ul>
	</div>
	<div class="admin_group">
	<div class="group_title">Управление товарами</div>
		<?= CHtml::image(Yii::app()->request->baseUrl.'/images/others/monochrome_icons/Screenshot.png', 'text', array('class' => 'group_image')) ?>
		<ul class="admin_list" id="admin_item_group">
		<li><?php echo CHtml::link('Товары', array('item/admin'));?> </li>
		<li><?php echo CHtml::link('Скидки', array('offer/admin'));?></li>
	</ul>
	</div>
	<div class="admin_group">
	<div class="group_title">Управление заказами</div>
		<?= CHtml::image(Yii::app()->request->baseUrl.'/images/others/monochrome_icons/AIM.png', 'text', array('class' => 'group_image')) ?>
	<ul class="admin_list" id="admin_order_group">
		<li><?php echo CHtml::link('Заказы', array('order/admin'));?></li>
	</ul>
	</div>
	<div class="admin_group">
	<div class="group_title">Управление консультациями</div>
		<?= CHtml::image(Yii::app()->request->baseUrl.'/images/others/monochrome_icons/Contact.png', 'text', array('class' => 'group_image')) ?>
		<ul class="admin_list" id="admin_consultation_group">
		<li><?php echo CHtml::link('Консультации', array('consultation/index'));?></li>
		<li><?php echo CHtml::link('Календарь консультаций', array('consultationcalendar/admin'));?></li>
	</ul>
	</div>

	<div class="admin_group">
	<div class="group_title">Управление настройками сайта</div>
		<?= CHtml::image(Yii::app()->request->baseUrl.'/images/others/monochrome_icons/Wrench.png', 'text', array('class' => 'group_image')) ?>
		<ul class="admin_list" id="admin_sait_group">
		<li><?php echo CHtml::link('config', array('config/admin'));?></li>
		<li><?php echo CHtml::link('order progress stage', array('orderprogressstage/admin'));?> </li>
		<li><?php echo CHtml::link('user', array('user/admin'));?> </li>
	</ul>
	</div>









</div>