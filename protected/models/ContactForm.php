<?php

class ContactForm extends CFormModel
{
    public $first_name;
    public $last_name;
    public $email;
    public $key;
    public $repeat_key;


    public function rules()
    {
        return array(
            array('first_name, last_name, email, key, repeat_key', 'required'),
            array('email', 'email','message'=>"The email isn't correct"),
            array('key', 'compare', 'compareAttribute'=>'repeat_key'),
        );
    }
    public function attributeLabels()
    {
        return array(
            'first_name'=>'First name',
            'last_name'=>'Last name',
            'email'=>'Email',
            'key'=>'Key',
            'repeat_key'=>'Repeat key',
        );
    }
    public function fillModelData($order){
        $this->first_name = $order->first_name;
        $this->last_name = $order->last_name;
        $this->email = $order->email;
        $this->key = $order->key;
        $this->repeat_key = $order->key;
    }
}