<?php
/* @var $this OrderProgressController */
/* @var $model OrderProgress */

$this->breadcrumbs=array(
	'Order Progresses'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List OrderProgress', 'url'=>array('index')),
	array('label'=>'Manage OrderProgress', 'url'=>array('admin')),
);
?>

<h1>Create OrderProgress</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>