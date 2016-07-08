<div class="page_title">ORDER<div class="underline"></div></div>

<div class="form contactform">

    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'contact_form',
        'enableAjaxValidation'=>false,
        'htmlOptions'=>array(
            'onsubmit'=>"return false;",/* Disable normal form submit */
            'onkeypress'=>" if(event.keyCode == 13){ sendcontact(); } " ,/* Do ajax call when user presses enter key */
            'data-active'=>'1'
        ),
    )); ?>


    <div class="error_summary" id="contact_error_summary" style="display:  none;"><?= CJSON::encode($contact->errors); ?></div>

    <div class="row">
        <?php echo $form->textField($contact,'first_name',array('class' => 'order_textbox', 'placeholder' => 'First name')); ?>
    </div>
    <div class="row">
        <?php echo $form->textField($contact,'last_name',array('class' => 'order_textbox', 'placeholder' => 'Last name')); ?>
    </div>
    <div class="row">
        <?php echo $form->textField($contact,'email',array('class' => 'order_textbox', 'placeholder' => 'e-mail')); ?>
    </div>
    <div class="row">
        <?php echo $form->textField($contact,'key',array('class' => 'order_textbox', 'placeholder' => 'Key')); ?>
    </div>
    <div class="row">
        <?php echo $form->textField($contact,'repeat_key',array('class' => 'order_textbox', 'placeholder' => 'Repeat key')); ?>
    </div>

    <div id="contact_submit_button" class="order_button" onclick='sendcontact(this);'>SAVE</div>


    <?php $this->endWidget(); ?>

</div><!-- form -->
<div class="form shippingform">

    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'shipping_form',
        'enableAjaxValidation'=>false,
        'htmlOptions'=>array(
            'onsubmit'=>"return false;",/* Disable normal form submit */
            'onkeypress'=>" if(event.keyCode == 13){ sendshipping(); } ", /* Do ajax call when user presses enter key */
            'data-active'=>'0'
        ),
    )); ?>


    <div class="error_summary" id="shipping_error_summary" style="display:  none;"><?= $shipping->errors; ?></div>

    <div class="row">
        <?php echo $form->textField($shipping,'address', array('class' => 'order_textbox', 'placeholder' => 'Address')); ?>
        <?php echo $form->error($shipping,'address'); ?>
    </div>
    <div class="row">
        <?php echo $form->textField($shipping,'apt_num', array('class' => 'order_textbox', 'placeholder' => 'Apt num')); ?>
        <?php echo $form->error($shipping,'apt_num'); ?>
    </div>
    <div class="row">
        <?php echo $form->textField($shipping,'city', array('class' => 'order_textbox', 'placeholder' => 'City')); ?>
        <?php echo $form->error($shipping,'city'); ?>
    </div>
    <div class="row">
        <?php echo $form->textField($shipping,'state', array('class' => 'order_textbox', 'placeholder' => 'State / Province')); ?>
        <?php echo $form->error($shipping,'state'); ?>
    </div>
    <div class="row">
        <?php echo $form->textField($shipping,'zip_code', array('class' => 'order_textbox', 'placeholder' => 'Zip code')); ?>
        <?php echo $form->error($shipping,'zip_code'); ?>
    </div>
    <div class="row">
        <?php echo CHtml::activeDropDownList($shipping, 'country', $countries_list, array('class' => 'order_textbox', 'placeholder' => 'Country')); ?>
        <?php echo $form->error($shipping,'country'); ?>
    </div>
    <div class="row buttons">
        <div id="contact_submit_button" class="order_button" onclick='sendshipping(this);'>SAVE</div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->
<div id="summary">
    <div id="summary_title">summary</div>
    <div id="summary_content">

    </div>
</div>
<div class="form commentairesform">

    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'additional_form',
        'enableAjaxValidation'=>false,
        'htmlOptions'=>array(
            'data-active'=>'0'
        ),
    )); ?>


    <div class="error_summary" id="shipping_error_summary" style="display:  none;"></div>

    <div class="row">
        <?php echo $form->textField($additional,'commentaries', array('class' => 'order_textbox', 'placeholder' => 'Instructions or options')); ?>
        <?php echo $form->error($additional,'commentaries'); ?>
    </div>

</div><!-- form -->
<?= CHtml::linkButton('Submit form', array('submit' => array('oder/makeorder'))); ?>