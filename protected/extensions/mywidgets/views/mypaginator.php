<?php 

if($pages_num>1){

    $range = Yii::app()->config->get('PAGINATION.GALLERY_NEARBYPAGESNUM');
    $first_page_flag=false;
    $last_page_flag=false;
    $prev_page_flag=false;
    $next_page_flag=false;

    
    if($cur_page-$range <= 1) $first_page_flag=true;
    if($cur_page+$range >= $pages_num) $last_page_flag=true;
    if($cur_page-1 >= 1) $prev_page_flag=true;
    if($cur_page+1 <= $pages_num) $next_page_flag=true;
    ?>
    <div id="gallery_paginator">
    <ul>
    <?php
    if($first_page_flag===true){
        if($cur_page==1){?>
            <li class="paginator_active_page">1</li>
        <?php
        }
        else{
        if($prev_page_flag===true){?>
            <?= CHtml::link('<li class="paginator_page txt_page">prev</li>',array('gallery/index','page'=>$cur_page-1), array('class' => 'global_button'));?>
        <?php
        }?>
            <?= CHtml::link('<li class="paginator_page">1</li>',array('gallery/index','page'=>1), array('class' => 'global_button'));?>
        <?php
        }
    }
    else{?>
            <?= CHtml::link('<li class="paginator_page txt_page">first</li>',array('gallery/index','page'=>1), array('class' => 'global_button'));?>
            
        <?php
        if($prev_page_flag===true){?>
            <?= CHtml::link('<li class="paginator_page">prev</li>',array('gallery/index','page'=>$cur_page-1), array('class' => 'global_button'));?>
        <?php
        }
    }?>
    
    
    <?php
        for($i=$cur_page-$range; $i < $cur_page; $i++){
            if($i > 1){
                ?>
                 <?= CHtml::link('<li class="paginator_page">'.$i.'</li>',array('gallery/index','page'=>$i), array('class' => 'global_button'));?>
                <?php
            }
        }
    ?>
    
    <?php
        if($cur_page != 1 && $cur_page != $pages_num){?>
            <li class="paginator_active_page"><?= $cur_page; ?></li>
        <?php }
    ?>
    
    <?php
        for($i=$cur_page+1; $i <= $cur_page + $range; $i++){
            if($i < $pages_num){
                ?>
                 <?= CHtml::link('<li class="paginator_page">'.$i.'</li>',array('gallery/index','page'=>$i), array('class' => 'global_button'));?>
                <?php
            }
        }
    ?>
    
    <?php
    if($last_page_flag===true){
        if($cur_page==$pages_num){?>
            <li class="paginator_active_page"><?= $pages_num; ?></li>
        <?php
        }
        else{?>
          <?= CHtml::link( '<li class="paginator_page">'.$pages_num.'</li>',array('gallery/index','page'=>$pages_num), array('class' => 'global_button'));?>
           
        <?php
        if($next_page_flag===true){?>
            <?= CHtml::link('<li class="paginator_page txt_page">next</li>',array('gallery/index','page'=>$cur_page+1), array('class' => 'global_button'));?>
        <?php
    }
        }
    }
    else{
        if($next_page_flag===true){?>
            <?= CHtml::link('<li class="paginator_page txt_page">next</li>',array('gallery/index','page'=>$cur_page+1), array('class' => 'global_button'));?>
        <?php
    }?>
    
            <?= CHtml::link('<li class="paginator_page txt_page">last</li>',array('gallery/index','page'=>$pages_num), array('class' => 'global_button'));?>
        <?php
    }
    ?>
    </ul>
    </div>
    <?php
    }
?>