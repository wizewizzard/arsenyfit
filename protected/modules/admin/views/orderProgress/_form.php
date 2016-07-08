<?php
/* @var $this OrderProgressController */
/* @var $model OrderProgress */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'order-progress-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'id_order'); ?>
		<?php echo $form->textField($model,'id_order'); ?>
		<?php echo $form->error($model,'id_order'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'id_stage'); ?>
		<?php echo $form->textField($model,'id_stage'); ?>
		<?php echo $form->error($model,'id_stage'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class' => 'global_button')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->