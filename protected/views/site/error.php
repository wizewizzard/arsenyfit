<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle=Yii::app()->name . ' - Error';
?>



<div class="error main_message">
    <h2>Error <?php echo $code; ?></h2>
<?php echo CHtml::encode($message); ?>
</div>