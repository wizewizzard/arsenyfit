<?php
class ShopMenu extends CWidget   
{
    //public $title='Recent Comments';
    public $shop_menu;
    public $id_type;
    public $id_subtype;
    public function run()
    {   
        $this->render('shopmenu', array(
            'shop_menu' => $this->shop_menu,
            'id_type' => $this->id_type,
            'id_subtype' => $this->id_subtype
        ));
    }
    /*public function init()
    {
        if($this->cssFile===null)
        {
            $file=dirname(__FILE__).DIRECTORY_SEPARATOR.'tabview.css';
            $this->cssFile=Yii::app()->getAssetManager()->publish($file);
        }
        parent::init();
    }*/
}
?>