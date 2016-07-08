<?php
/* @var $this ItemController */
/* @var $dataProvider CActiveDataProvider */

?>

<h1>Items</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
