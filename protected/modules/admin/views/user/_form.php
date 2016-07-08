<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'id_group'); ?>
		<?php echo CHtml::activeDropDownList($model, 'id_group', $groups_list); ?>
		<?php echo $form->error($model,'id_group'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>
	<?php if($model->isNewRecord){?>
	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->textField($model,'password',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>
	<?php }
	else{?>
	<div class="row">
		<?php echo $form->labelEx($model,'new_password'); ?>
		<?php echo $form->textField($model,'new_password',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'new_password'); ?>
	</div>
	<?php
	}?>
	<div class="row">
		<?php echo $form->labelEx($model,'password_confirm'); ?>
		<?php echo $form->textField($model,'password_confirm',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'password_confirm'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class' => 'global_button')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->