<?php
/* @var $this OfferController */
/* @var $model Offer */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'offer-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'id_item'); ?>
		<?php echo CHtml::activeDropDownList($model, 'id_item', $items_list); ?>
		<?php echo $form->error($model,'id_item'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textField($model,'description',array('size'=>60,'maxlength'=>500)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div id="notice">
		<h5>Структура шаблона скидки:</h5>
		<ul>
		<li>&lt;CONDITIONS&gt;</li>
			<ul>
				<li>&lt;CONDITION&gt;</li>
				<ul>
					<li>&lt;PARAM&gt;</li>
					<li>&lt;/PARAM&gt;</li>
					<li>&lt;SIGN&gt;</li>
					<li>&lt;/SIGN&gt;</li>
					<li>&lt;VALUE&gt;</li>
					<li>&lt;/VALUE&gt;</li>
				</ul>
				<li>&lt;/CONDITION&gt;</li>
			</ul>
		<li>&lt;/CONDITIONS&gt;</li>
		<li>&lt;RESULT&gt;</li>
			<ul>
				<li>&lt;APPLYFOR&gt;</li>
				<li>&lt;/APPLYFOR&gt;</li>
				<li>&lt;VALUE&gt;</li>
				<li>&lt;/VALUE&gt;</li>
			</ul>
		<li>&lt;/RESULT&gt;</li>
		</ul>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'template'); ?>
		<?php echo $form->textArea($model,'template',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'template'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class' => 'global_button')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->