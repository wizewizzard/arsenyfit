<?php
/* @var $this ConsultationCalendarController */
/* @var $dataProvider CActiveDataProvider */
?>

<h1>Consultation Calendars</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
