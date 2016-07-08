<?php

/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 07.04.2016
 * Time: 18:11
 */
class OrderLoginForm extends CFormModel
{
    public $email;
    public $key;

    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('email, key', 'required'),
            array('email', 'email'),
            array('key', 'length', 'max'=>256),
            array('key', 'length', 'min'=>5),
            array('email','check_pair'),
            );
    }
    public function check_pair()
    {
        $criteria = new CDbCriteria;
        $criteria->condition = ('t.email=:email AND t.key=:key');
        $criteria->params = array(':email' => $this->email, ':key' => $this->key);
        $orders = Order::model()->findAll($criteria);
        if(!isset($orders) || count($orders) == 0 )
        {
            $this->addError('no_orders','There is no orders for this pair email-key.');
            return false;
        }
        return true;
    }
}