<?php
/* @var $this PostController */
/* @var $model Post */
?>
<?php
foreach(Yii::app()->user->getFlashes() as $key => $message) {
	echo '<div class="flash-' . $key . '">' . $message . "</div>\n";
}
?>
<div class="page_title"><?= $model->title?><div class="underline"></div></div>
<div id="post_container">
<div id="post_body">
	<?= $model->body ?>
</div>
</div>
<div id="commentaries_container">
</div>


