<?php $this->beginContent('//layouts/new_main'); ?>
    <div id="operations">
        <div id="left_column">
            <ul>
                <li><?= CHtml::link('To admin panel', array('/admin/default/index')) ?> </li>
            </ul>
        </div>
        <div id="right_column">
            <?php

            $this->widget('application.extensions.mywidgets.OperationsMenu', array(
                'url_create' => $this->url_create,
                'url_update' => $this->url_update,
                'url_table' => $this->url_admin,
                'url_delete' => $this->url_delete,
            ));
            ?>
        </div>
    </div>
    <div id="content">
        <?php echo $content; ?>
    </div><!-- content -->
<?php $this->endContent(); ?>