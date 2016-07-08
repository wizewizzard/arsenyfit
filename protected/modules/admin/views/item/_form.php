<?php
switch(Yii::app()->config->get('GLOBAL.SITE_CURRENCY')){
	case 'DOL': $currency = ' $'; break;
	case 'RUB': $currency = ' руб.'; break;
	case 'EUR': $currency = ' eur'; break;
	default: $currency = ' $'; break;
}
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'item-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
	'htmlOptions' => array('enctype' => 'multipart/form-data')
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'id_type'); ?>
		<?php echo CHtml::activeDropDownList($model, 'id_type', $types_list, array('class' => 'global_textbox')); ?>
		<?php echo $form->error($model,'id_type'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'id_subtype'); ?>
		<?php echo CHtml::activeDropDownList($model, 'id_subtype', $types_list, array('empty' => 'Не выбрано','class' => 'global_textbox')); ?>
		<?php echo $form->error($model,'id_subtype'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('class' => 'global_textbox')); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('class' => 'global_textbox', 'id' => 'description')); ?>
		<?php echo $form->error($model,'description'); ?>
		<script>
			CKEDITOR.replace(
				'description',{
					customConfig: 'custom_config.js'
				});
		</script>
	</div>

	<div class="row image_row">
		<label>Текущие картинки</label>
		<?php if($model->img_name): ?>
			<p><?php

				$img_names = explode(" ", $model->img_name);
				foreach($img_names as $img_name ) {?>
				<div id="admin_item_image_container">
				<div class="admin_item_image">
				<?php
					echo CHtml::image(Yii::app()->request->baseUrl.'/images/items/thumbnails/'.$img_name);
					?>
					<div class = "remove_img_icon" data-img-name="<?= $img_name ?>">Delete</div>
				</div>

				</div>
				<?php
				}
				?></p>
		<?php endif; ?>

	</div>

	<div class="row">
		<?php echo $form->hiddenField($model,'images_to_delete'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'images'); ?>
	<?php
		$this->widget('CMultiFileUpload', array(
		'name' => 'images',
		'accept' => 'jpeg|jpg|gif|png', // useful for verifying files
		'duplicate' => 'Duplicate file!', // useful, i think
		'denied' => 'Invalid file type', // useful, i think
		));
	?>
		</div>
	<div class="row image_row">
		<label>Текущие файлы</label>
		<?php if(!empty($model->itemFiles)): ?>
			<p><?php

				$files = $model->itemFiles;

				foreach($files as $file){ ?>
					<div>
<a  href="<?php echo $this->createUrl('/admin/default/getfile', array('id'=>$file->id, 'key' => $file->secret_key) ) ;?>"  target="helperFrame" > Download <?= $file->filename ?> </a>
<div class = "remove_file_icon" data-file-id="<?= $file->id ?>">Delete</div>
</div>
			<?php	}
				/*foreach($files as $file ) {?>
				<div id="admin_item_file_container">
				<div class="admin_item_image">
				<?php
					echo CHtml::image(Yii::app()->request->baseUrl.'/images/items/thumbnails/'.$img_name);
					?>
				</div>
				<div class = "remove_img_icon" data-img-name="<?= $img_name ?>">Delete</div>
				</div>
				<?php
				}*/
				?></p>
		<?php endif; ?>

	</div>
	<div class="row">
		<?php echo $form->hiddenField($model,'files_to_delete'); ?>
	</div>
	<div class="row">
		<?php echo $form->label($model,'files_attached'); ?>
		<div class="admin_note">Требуется, если товар представляет собой электронный документ(ы).</div>
		<?php
		$this->widget('CMultiFileUpload', array(
			'name' => 'files_attached',
			'accept' => 'pdf|doc|docx', // useful for verifying files
			'duplicate' => 'Duplicate file!', // useful, i think
			'denied' => 'Invalid file type', // useful, i think
		));
		?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'price'); ?>
		<div class="admin_note">В <?=$currency?></div>
		<?php echo $form->textField($model,'price', array('class' => 'global_textbox')); ?>
		<?php echo $form->error($model,'price'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'weight'); ?>
		<div class="admin_note">Требуется, если товар будет отпавляться по почте. Для докуменов оставяется пустым.</div>
		<?php echo $form->textField($model,'weight', array('class' => 'global_textbox')); ?>
		<?php echo $form->error($model,'weight'); ?>
	</div>


	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class' => 'global_button')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->