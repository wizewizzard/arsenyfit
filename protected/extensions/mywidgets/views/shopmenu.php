<?php
    function outputTree($tree, $depth, $id_seq, $id_type, $id_subtype){
        if($depth >= 2) return "";
        if(empty($tree)) return "";
        $str="<ul>";
        foreach($tree as $menuitem){
            switch ($depth){
                case 0:
                    $params = array(
                        'id' => $menuitem['node']->id);
                    break;
                case 1:
                    $params = array(
                        'id' => $id_seq[0],
                        'sid' => $menuitem['node']->id);
                    break;
            }
            $id_seq[$depth]= $menuitem['node']->id;
            $class='inactive_option';
            //echo
            if($depth==0 && $menuitem['node']->id == $id_type ) $class = 'active_option';
            if($depth==1 && $menuitem['node']->id == $id_subtype ) $class = 'active_option';
            $str.="<li class='hvr-bounce-in ".$class."'>".CHtml::link($menuitem['node']->title,
                    Yii::app()->createUrl(
                        'shop/view',
                        $params
                        ))."</li>";

            $str.= outputTree($menuitem['children'], $depth+1, $id_seq, $id_type, $id_subtype);
        }
        $str.="</ul>";
        //echo $str;
        return $str;
    }
?>

<ul>
<?php if($id_type== null){ ?>
<li class="active_option hvr-bounce-in shop_main_li"><?= CHtml::link('Магазин', array('shop/index')); ?></li>
<?php }
else{?>
<li class="inactive_option hvr-bounce-in shop_main_li"><?= CHtml::link('Магазин', array('shop/index')); ?></li>
<?php } ?>

<?= outputTree($shop_menu, 0, array(), $id_type, $id_subtype); ?>

</ul>