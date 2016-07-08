<?php
/* @var $this PostController */
/* @var $model Post */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'post-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'preview'); ?>
		<?php echo $form->textArea($model,'preview',array('rows'=>6, 'cols'=>50, 'id' => 'preview')); ?>
		<?php echo $form->error($model,'preview'); ?>
		<script>
			CKEDITOR.replace(
				'preview',{
					customConfig: 'config.js'
				});

		</script>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'body'); ?>
		<?php echo $form->textArea($model,'body',array('rows'=>6, 'cols'=>50, 'id' => 'body')); ?>
		<?php echo $form->error($model,'body'); ?>
		<script>
			CKEDITOR.replace( 'body' );
		</script>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tags'); ?>
		<?php echo $form->textField($model,'tags',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'tags'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton("Preview", array('name' => 'preview', 'onclick' => "this.form.target='_blank';return true;")); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton("Publish", array('name' => 'publish')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->