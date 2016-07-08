<?php
/* @var $this ConsultationCalendarController */
/* @var $model ConsultationCalendar */
?>

<h1>View ConsultationCalendar #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'time_from',
		'time_until',
	),
)); ?>
