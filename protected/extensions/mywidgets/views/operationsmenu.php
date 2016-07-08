<ul>
    <?php
    if(!empty($url_table)){ ?>
        <li><?= CHtml::link('View all', $url_table); ?></li>
    <?php }
    ?>
    <?php
        if(!empty($url_create)){ ?>
          <li><?= CHtml::link('Create new', $url_create); ?></li>
    <?php }
    ?>
    <?php
    if(!empty($url_update)){ ?>
        <li><?= CHtml::link('Update current', $url_update); ?></li>
    <?php }
    ?>
    <?php
    if(!empty($url_delete)){ ?>
        <li><?= CHtml::link('Delete current', '#', $url_delete); ?></li>
    <?php }
    ?>

</ul>