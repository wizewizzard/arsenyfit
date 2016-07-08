<?php
/* @var $this PostController */
/* @var $model Post */
?>
<h1>Update Post <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>