<?php
/* @var $this ItemClassificationController */
/* @var $model ItemClassification */

$this->breadcrumbs=array(
	'Item Classifications'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List ItemClassification', 'url'=>array('index')),
	array('label'=>'Create ItemClassification', 'url'=>array('create')),
	array('label'=>'Update ItemClassification', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete ItemClassification', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ItemClassification', 'url'=>array('admin')),
);
?>

<h1>View ItemClassification #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'id_parent',
		'title',
		'priority',
	),
)); ?>
