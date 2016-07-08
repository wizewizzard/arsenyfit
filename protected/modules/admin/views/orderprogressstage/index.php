<?php
/* @var $this OrderProgressStageController */
/* @var $dataProvider CActiveDataProvider */
?>

<h1>Order Progress Stages</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
