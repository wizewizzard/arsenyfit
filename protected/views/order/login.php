<?php
/* @var $this PostController */
/* @var $model Post */
/* @var $form CActiveForm */
?>
<div class="page_title">Login<div class="underline"></div></div>
<div class="additional_info">Login to track your orders' status. Enter the <i><b>login</b></i> and the <i><b>key</b></i> you've entered during order execution.</div>
<div class="form">

    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'login-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation'=>false,
    )); ?>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->textField($model,'email',array('class' => 'global_textbox', 'placeholder' => 'email')); ?>
        <?php echo $form->error($model,'email'); ?>
    </div>

    <div class="row">
        <?php echo $form->textField($model,'key',array('class' => 'global_textbox', 'placeholder' => 'key')); ?>
        <?php echo $form->error($model,'key'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton("Login", array('class' => 'global_button')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->