<div id="search">
    	<?php $url = $this->getController()->createUrl('search/index'); ?>
		<?php echo CHtml::beginForm($url); ?>
		<div class="row">
		<?php echo CHtml::activeTextField($form,'string', array('class' => 'textbox',
    'onfocus'=>"if (this.value == 'search') {this.value = '';}",
            'onblur' => "if (this.value == '') {this.value = 'search';}")) ?>
		<?php echo CHtml::error($form,'string'); ?>
		</div>
		<?php echo CHtml::endForm(); ?>
    <div id="SearchFooter"></div>
</div>