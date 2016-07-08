<?php
/* @var $this ConsultationCalendarController */
/* @var $model ConsultationCalendar */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'time_from'); ?>
		<?php echo $form->textField($model,'time_from'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'time_until'); ?>
		<?php echo $form->textField($model,'time_until'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->