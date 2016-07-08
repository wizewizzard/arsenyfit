<?php
/* @var $this OrderProgressController */
/* @var $model OrderProgress */

$this->breadcrumbs=array(
	'Order Progresses'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List OrderProgress', 'url'=>array('index')),
	array('label'=>'Create OrderProgress', 'url'=>array('create')),
	array('label'=>'Update OrderProgress', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete OrderProgress', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage OrderProgress', 'url'=>array('admin')),
);
?>

<h1>View OrderProgress #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'id_order',
		'id_stage',
	),
)); ?>
