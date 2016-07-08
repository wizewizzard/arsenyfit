<?php
/* @var $this OrderController */
/* @var $model Order */

?>

<h1>Update Order <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model,
    'order_progress' => $order_progress,
    'stages' => $stages)); ?>