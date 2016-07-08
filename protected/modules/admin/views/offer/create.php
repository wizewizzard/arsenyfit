<?php
/* @var $this OfferController */
/* @var $model Offer */

$this->breadcrumbs=array(
	'Offers'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Offer', 'url'=>array('index')),
	array('label'=>'Manage Offer', 'url'=>array('admin')),
);
?>

<h1>Create Offer</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'items_list' => $items_list)); ?>