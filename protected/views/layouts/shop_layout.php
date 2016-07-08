<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/new_main'); ?>
<!---<div class="shop_menu">
<?php
/*$this->widget('application.extensions.mywidgets.ShopMenu', array(
    'shop_menu' => $this->shop_menu_upd,
	'id_type' => $this->id_type,
	'id_subtype' => $this->id_subtype
)); */
 ?>
</div> -->
<div class="shop_items_container">
	<div id="content">
		<?php echo $content; ?>
	</div><!-- content -->
</div>
<?php $this->endContent(); ?>