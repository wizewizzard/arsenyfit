<h1>View GalleryImage #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'title',
		'description',
		array(
			'name'=>'Image',
			'type'=>'raw',
			'value'=>  CHtml::image(Yii::app()->request->baseUrl."/images/gallery/thumbnails/".$model->img_name),
		),
		'date',
	),
)); ?>
