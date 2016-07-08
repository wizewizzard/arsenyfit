<?php



function outputItemTree($tree, $depth, $c_depth, $mode, $param_lvl){
    if($mode=='lined') $class_name='item_row';
    else if($mode=='tabled') $class_name='item_row_t';
    if($c_depth > $depth) return "";
    if(empty($tree)) return "";
    $str="";
    foreach($tree as $classificator){

        $str.=outputItemTree($classificator['children'], $depth, $c_depth+1, $mode, $param_lvl);
            if(!empty($classificator['items'])){
                if($param_lvl -1 != $c_depth){
                    $str.='<div>default</div>';
                }
                else{
                    $str.='<div>'.$classificator['node']->title.'</div>';
                }
                $str.='<div class="'.$class_name.'">';
                foreach($classificator['items'] as $item){
                    $str.='<div class="item_container">';
                        $str.= CHtml::link($item->title, Yii::app()->createUrl('shop/item',array('id'=>$item->id)));
                    $str.='<div data-id-item='.$item->id.' class="icon_cart_container">Add into cart</div>';
                    $str.='</div>';

                }
                $str.="</div>";
}
    }

    //echo $str;
    return $str;
}
?>

<div id="items_container">
    <?= outputItemTree($tree, $depth, 0, 'lined', $param_lvl); ?>
</div>