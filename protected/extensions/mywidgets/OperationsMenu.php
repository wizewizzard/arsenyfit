<?php
class OperationsMenu extends CWidget
{
    //public $title='Recent Comments';
    public $url_create;
    public $url_update;
    public $url_table;
    public $url_delete;

    public function run()
    {
        $this->render('operationsmenu', array(
            'url_create' => $this->url_create,
            'url_update' => $this->url_update,
            'url_table' => $this->url_table,
            'url_delete' => $this->url_delete,
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