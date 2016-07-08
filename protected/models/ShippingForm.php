<?php

/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 31.03.2016
 * Time: 18:31
 */
class ShippingForm extends CFormModel
{
    public $address;
    public $apt_num;
    public $city;
    public $state;
    public $zip_code;
    public $country;


    public function rules()
    {
        return array(
            array('address, city, state, zip_code, country', 'required'),
            array('address, city, state, zip_code, apt_num', 'length', 'max'=>128),
        );
    }
    public function attributeLabels()
    {
        return array(
            'address'=>'Address',
            'city'=>'City',
            'state'=>'State',
            'zip_code'=>'Zip code',
            'country' => 'Country',
            'apt_num' => 'Apt num',
        );
    }
    public function fillModelData($shipping){
        $this->address = $shipping->address;
        $this->apt_num = $shipping->apt_num;
        $this->state = $shipping->state;
        $this->zip_code = $shipping->zip_code;
        $this->country = $shipping->id_country;
        $this->city = $shipping->city;
    }
}