<?php
/* @var $this ConsultationCalendarController */
/* @var $model ConsultationCalendar */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'consultation-calendar-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'time_from'); ?>
		<?php echo $form->textField($model,'time_from', array('class' => 'global_textbox')); ?>
		<?php echo $form->error($model,'time_from'); ?>
	</div>
	<script type="text/javascript">

		$(function(){
			$('#ConsultationCalendar_time_from').appendDtpicker({"inline": true});
		});

	</script>

	<div class="row">
		<?php echo $form->labelEx($model,'time_until'); ?>
		<?php echo $form->textField($model,'time_until', array('class' => 'global_textbox')); ?>
		<?php echo $form->error($model,'time_until'); ?>
	</div>

	<div id="date_picker"></div>
	<script type="text/javascript">

		$(function(){
			$('#ConsultationCalendar_time_until').appendDtpicker({"inline": true});
		});

	</script>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class' => 'global_button')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->