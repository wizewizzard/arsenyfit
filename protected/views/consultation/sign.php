<?php
/* @var $this ConsultationController */
/* @var $model Consultation */
/* @var $form CActiveForm */
?>
<div class="page_title">ENROLL<div class="underline"></div></div>
<div class="form" data-consultation-id="<?= $id; ?>">

    <?php
    $form=$this->beginWidget('CActiveForm', array(
        'id'=>'consultation-form',
        'enableAjaxValidation'=>false,
    ));
    ?>

    <?php echo $form->errorSummary($model); ?>
    <div class="row">
        <div id = "limits">
            <div id="left_limit">default</div>
            <div id="right_limit">default</div>
        </div>
        <div id="range_statusbar">

        </div>
        <div id="slider">
            <div id="slider_lower">
                <?= CHtml::image(Yii::app()->request->baseUrl.'/images/others/slider.png') ?>
                <div id="slider_lower_val">default</div>
            </div>
            <div id="slider_upper">
                <?= CHtml::image(Yii::app()->request->baseUrl.'/images/others/slider.png') ?>
                <div id="slider_upper_val">default</div>
            </div>
        </div>
        <?php echo $form->hiddenField($model,'offset',array('size'=>20,'maxlength'=>20)); ?>
        <?php echo $form->hiddenField($model,'duration',array('size'=>20,'maxlength'=>20)); ?>
    </div>
<div id="textfields">
    <div class="row">
        <?php echo $form->textField($model,'first_name',array('class' => 'sign_textbox', 'placeholder' => 'First name')); ?>
        <?php echo $form->error($model,'first_name'); ?>
    </div>

    <div class="row">
        <?php echo $form->textField($model,'last_name', array('class' => 'sign_textbox', 'placeholder' => 'Last name')); ?>
        <?php echo $form->error($model,'last_name'); ?>
    </div>

    <div class="row">
        <?php echo $form->textField($model,'email', array('class' => 'sign_textbox', 'placeholder' => 'e-mail')); ?>
        <?php echo $form->error($model,'email'); ?>
    </div>

    <div class="row">
        <?php echo $form->textField($model,'other_contact_info',array('class' => 'sign_textbox', 'placeholder' => 'Contact info (skype, vk, etc.)')); ?>
        <?php echo $form->error($model,'other_contact_info'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'commentary'); ?>
        <?php echo $form->textArea($model,'commentary',array('rows'=>6, 'cols'=>50)); ?>
        <?php echo $form->error($model,'commentary'); ?>
    </div>


    <div class="row buttons">
        <?php echo CHtml::submitButton('Записаться', array('class' => 'global_button')); ?>
    </div>
</div>
    <?php $this->endWidget(); ?>

</div><!-- form -->