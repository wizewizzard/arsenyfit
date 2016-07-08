<?php
/* @var $this OrderProgressStageController */
/* @var $model OrderProgressStage */
?>

<h1>View OrderProgressStage #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'title',
		'abbr',
	),
)); ?>
