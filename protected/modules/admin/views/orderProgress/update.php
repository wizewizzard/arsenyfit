<?php
/* @var $this OrderProgressController */
/* @var $model OrderProgress */

$this->breadcrumbs=array(
	'Order Progresses'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List OrderProgress', 'url'=>array('index')),
	array('label'=>'Create OrderProgress', 'url'=>array('create')),
	array('label'=>'View OrderProgress', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage OrderProgress', 'url'=>array('admin')),
);
?>

<h1>Update OrderProgress <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>