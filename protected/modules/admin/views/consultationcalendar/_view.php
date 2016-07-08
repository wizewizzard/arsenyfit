<?php
/* @var $this ConsultationCalendarController */
/* @var $data ConsultationCalendar */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('time_from')); ?>:</b>
	<?php echo CHtml::encode($data->time_from); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('time_until')); ?>:</b>
	<?php echo CHtml::encode($data->time_until); ?>
	<br />


</div>