<?php
/* @var $this ConfigController */
/* @var $dataProvider CActiveDataProvider */
?>

<h1>Configs</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
