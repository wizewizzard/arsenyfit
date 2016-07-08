<?php

class TreeBuilder{

    public function buildTree($par_id, $c_depth, $elements, $max_depth ){
        if($c_depth >= $max_depth) return null;
        $tree = array();
        foreach($elements as $element ){
            if($element->id_parent == $par_id){
                $tree[] = array(
                    'node' => $element,
                    'children' =>  $this->buildTree($element->id, $c_depth+1, $elements, $max_depth));
            }
        }

        return $tree;
    }

    public function filerItem(&$tree, $depth, $c_depth, $item){
        if($c_depth > $depth) return;
        $c_depth++;
        if($depth == $c_depth || empty($item->id_subtype)) {
            for ($i = 0; $i < count($tree); $i++) {
                if($c_depth==1){
                    if($tree[$i]['node']->id == $item->id_type) {$tree[$i]['items'][] = $item; return; }
                }
                else if($c_depth==2){
                    if($tree[$i]['node']->id == $item->id_subtype) {$tree[$i]['items'][] = $item; return; }
                }
            }
        }
        else{
            for ($i = 0; $i < count($tree); $i++) {
                if($c_depth==1){
                    if($tree[$i]['node']->id == $item->id_type) {$this->filerItem($tree[$i]['children'], $depth, $c_depth, $item); }
                }
                else if($c_depth==2){
                    if($tree[$i]['node']->id == $item->id_subtype) {$this->filerItem($tree[$i]['children'], $depth, $c_depth, $item); }
                }
            }
        }

    }
    protected function addToArray(&$node, $item){

    }
}
?>