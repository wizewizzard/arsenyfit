<?php
/* @var $this OrderController */
/* @var $dataProvider CActiveDataProvider */
?>

<h1>Orders</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
