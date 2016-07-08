<?php
/* @var $this ItemController */
/* @var $model Item */
?>

<h1>View Item #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'id_type',
		'id_subtype',
		'title',
		'description',
		'img_name',
		'price',
		'weight',
		'date',
		'preview_flag',
	),
)); ?>
