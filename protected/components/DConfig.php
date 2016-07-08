<?php
class DConfig extends CApplicationComponent
{
    protected $data = array();
 
    public function init()
    {
        $items = Config::model()->cache(3600)->findAll();
        foreach ($items as $item){
            if ($item->param) 
                $this->data[$item->param] = $item->value === '' ?  $item->default : $item->value;
        }
        parent::init();
    }
 
    public function get($key)
    {
        if (array_key_exists($key, $this->data)){
            return $this->data[$key];
        } else {
            //var_dump($this->data);
            throw new CException('Undefined parameter '.$key);
        }
    }
 
    public function set($key, $value)
    {              
        $model = Config::model()->findByAttributes(array('param'=>$key));
        if (!$model) 
            throw new CException('Undefined parameter '.$key);
 
        $this->data[$key] = $value;
        $model->value = $value;
        $model->save();
    }
}