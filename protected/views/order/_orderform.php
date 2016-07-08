<div class="form contactform">
    <div class="order_title">Контактная информация</div>
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'contact_form',
        'enableAjaxValidation'=>false,
        'htmlOptions'=>array(
            'onsubmit'=>"return false;",/* Disable normal form submit */
            'data-active'=>'1'
        ),
    )); ?>


    <div class="error_summary" id="contact_error_summary" style="display:  none;"><?php if(!empty($contact->errors))
            echo CJSON::encode($contact->errors); ?></div>

    <div class="row">
        <?php echo $form->textField($contact,'first_name',array('class' => 'order_textbox', 'placeholder' => 'First name', 'data-title' => 'First name')); ?>
    </div>
    <div class="row">
        <?php echo $form->textField($contact,'last_name',array('class' => 'order_textbox', 'placeholder' => 'Last name', 'data-title' => 'Last name')); ?>
    </div>
    <div class="row">
        <?php echo $form->textField($contact,'email',array('class' => 'order_textbox', 'placeholder' => 'e-mail', 'data-title' => 'e-mail')); ?>
    </div>
    <div class="row">
        <?php echo $form->textField($contact,'key',array('class' => 'order_textbox', 'placeholder' => 'Key')); ?>
    </div>
    <div class="row">
        <?php echo $form->textField($contact,'repeat_key',array('class' => 'order_textbox', 'placeholder' => 'Repeat key')); ?>
    </div>

    <div id="contact_submit_button" class="global_button" onclick='sendcontact(this);'>сохранить</div>


    <?php $this->endWidget(); ?>

</div><!-- form -->
<div class="form shippingform">
    <div class="order_title">Информация для доставки</div>
    <?php if(isset($shipping)){ ?>


        <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'shipping_form',
            'enableAjaxValidation'=>false,
            'htmlOptions'=>array(
                'onsubmit'=>"return false;",/* Disable normal form submit */
                'data-active'=>'0'
            ),
        )); ?>


        <div class="error_summary" id="shipping_error_summary" style="display:  none;"><?php if(!empty($shipping->errors))
                echo CJSON::encode($shipping->errors); ?></div>

        <div class="row">
            <?php echo $form->textField($shipping,'address', array('class' => 'order_textbox', 'placeholder' => 'Address','data-title' => 'Address')); ?>
            <?php echo $form->error($shipping,'address'); ?>
        </div>
        <div class="row">
            <?php echo $form->textField($shipping,'apt_num', array('class' => 'order_textbox', 'placeholder' => 'Apt num', 'data-title' => 'Apt num')); ?>
            <?php echo $form->error($shipping,'apt_num'); ?>
        </div>
        <div class="row">
            <?php echo $form->textField($shipping,'city', array('class' => 'order_textbox', 'placeholder' => 'City', 'data-title' => 'City')); ?>
            <?php echo $form->error($shipping,'city'); ?>
        </div>
        <div class="row">
            <?php echo $form->textField($shipping,'state', array('class' => 'order_textbox', 'placeholder' => 'State / Province', 'data-title' => 'State / Province')); ?>
            <?php echo $form->error($shipping,'state'); ?>
        </div>
        <div class="row">
            <?php echo $form->textField($shipping,'zip_code', array('class' => 'order_textbox', 'placeholder' => 'Zip code', 'data-title' => 'Zip code')); ?>
            <?php echo $form->error($shipping,'zip_code'); ?>
        </div>
        <div class="row">
            <?php echo CHtml::activeDropDownList($shipping, 'country', $countries_list, array('class' => 'order_textbox', 'placeholder' => 'Country', 'data-title' => 'Country')); ?>
            <?php echo $form->error($shipping,'country'); ?>
        </div>
        <div class="row buttons">
            <div id="contact_submit_button" class="global_button" onclick='sendshipping(this);'>сохранить</div>
        </div>

        <?php $this->endWidget(); ?>


    <?php }
    else {
        ?>
        <div class="global_second_lvl_message">Доставка не требуется</div>
    <?php }?>
</div><!-- form -->
<div id="summary">
    <div class="order_title">Итог</div>
    <div class="error_summary" id="summary_error_summary" style="display:  none;"></div>
    <div id="summary_content">
        <div class="global_second_lvl_message">Заполните обязательные поля</div>
    </div>
</div>
<div class="form commentairesform">
    <div class="order_title">Пожелания, комментарии</div>
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
    <?php $this->endWidget(); ?>
    <div class="row buttons">
        <div id="submit_order_button" class="global_button" onclick='senddata(this);'>
            <?php if($update==1) echo 'Сохранить изменения';
            else echo 'Оформить заказ'?></div>
    </div>
</div><!-- form -->