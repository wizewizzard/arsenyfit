<?php
/* @var $this ItemClassificationController */
/* @var $model ItemClassification */

$this->breadcrumbs=array(
	'Item Classifications'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List ItemClassification', 'url'=>array('index')),
	array('label'=>'Create ItemClassification', 'url'=>array('create')),
	array('label'=>'View ItemClassification', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage ItemClassification', 'url'=>array('admin')),
);
?>

<h1>Update ItemClassification <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>