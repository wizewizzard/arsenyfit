<?php
/* @var $this ItemClassificationController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Item Classifications',
);

$this->menu=array(
	array('label'=>'Create ItemClassification', 'url'=>array('create')),
	array('label'=>'Manage ItemClassification', 'url'=>array('admin')),
);
?>

<h1>Item Classifications</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
