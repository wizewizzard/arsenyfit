<?php
class MyPaginator extends CWidget   
{
    //public $title='Recent Comments';
    public $range=4;
    public $cur_page;
    public $pages_num;
    
    public function run()
    {   
        $this->render('mypaginator', array(
            'range' => $this->range,
            'cur_page' => $this->cur_page,
            'pages_num' => $this->pages_num
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