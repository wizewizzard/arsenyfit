<?php
class ShopItems extends CWidget   
{
    public $items;
    public $param=null;
    public $param_lvl=null;
    public $mode='lined';
    public function run()
    {   
        $this->render('shopitems', array(
            'items' => $this->items,
            'mode' => $this->mode
        ));
        /*if($this->mode=='lined')
        $this->render('shopmenulined', array(
            'items' => $this->items,
            'param' => $this->param,
        ));
        else $this->render('shopmenutabled', array(
            'items' => $this->items,
            'param' => $this->param,
        ));*/
    }
}
?>