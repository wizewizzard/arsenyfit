<?php
/* @var $this ItemController */
/* @var $model Item */
?>

<h1>Create Item</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'types_list' => $types_list)); ?>