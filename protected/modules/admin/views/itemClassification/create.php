<?php
/* @var $this ItemClassificationController */
/* @var $model ItemClassification */

$this->breadcrumbs=array(
	'Item Classifications'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List ItemClassification', 'url'=>array('index')),
	array('label'=>'Manage ItemClassification', 'url'=>array('admin')),
);
?>

<h1>Create ItemClassification</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>