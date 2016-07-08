<h1>Update Item <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'types_list' => $types_list)); ?>