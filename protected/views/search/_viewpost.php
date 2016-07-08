<div class="search_post_container">
    <div class="search_post_title">
    <?php echo $data->title; ?>
    </div>
    <div class="search_post_date">
        <?php echo $data->date; ?>
    </div>
    <div class="search_post_button">
    <?php echo CHtml::link('Перейти к посту', array('post/view', 'id' => $data->id), array('class' => 'global_button')); ?>
    </div>
</div>