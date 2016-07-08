<?php
/* @var $this ConfigController */
/* @var $model Config */
?>

<h1>View Config #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'param',
		'value',
		'default',
		'label',
		'type',
	),
)); ?>
