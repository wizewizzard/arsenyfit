<?php
/* @var $this OrderProgressController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Order Progresses',
);

$this->menu=array(
	array('label'=>'Create OrderProgress', 'url'=>array('create')),
	array('label'=>'Manage OrderProgress', 'url'=>array('admin')),
);
?>

<h1>Order Progresses</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
