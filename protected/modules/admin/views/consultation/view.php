<?php
/* @var $this ConsultationController */
/* @var $model Consultation */
?>

<h1>View Consultation #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'id_calendar_time',
		'offset',
		'duration',
		'first_name',
		'last_name',
		'email',
		'other_contact_info',
		'key',
		'commentary',
		'payment_status',
	),
)); ?>
