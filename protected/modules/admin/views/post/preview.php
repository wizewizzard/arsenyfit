<?php
/* @var $this PostController */
/* @var $model Post */
?>
<?php
foreach(Yii::app()->user->getFlashes() as $key => $message) {
    echo '<div class="flash-' . $key . '">' . $message . "</div>\n";
}
?>
<div class="info">То, как пост будет выглядеть в новостной ленте</div>
<div id="post_preview_be_like">
<article>
    <h3><?= CHtml::link($model->title, array('post/view',
            'id'=>$model->id)) ?></h3>
    <div class ="post_preview"><?= $model->preview ?></div>
    <?php $splitted_tags = preg_split("/[\s,]+/", $model->tags);
    //print_r($splitted_tags);
    ?>
    <div class="tags"><?php foreach($splitted_tags as $tag) {
            echo CHtml::link($tag, Yii::app()->createUrl('post/search', array('searchString' => $tag))) . ' ';
        }
        ?>
</article>
</div>
<div class="info">То, как пост будет выглядеть при просмотре</div>
<div id="post_view_be_like">
    <div id="post_container">
<h3><?php echo $model->title; ?></h3>
<div id="post_body">
    <?= $model->body ?>
</div>
</div>
</div>


