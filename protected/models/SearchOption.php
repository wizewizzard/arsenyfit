<?php

/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 04.04.2016
 * Time: 19:14
 */
class SearchOption extends CFormModel
{
    public $option;
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('option', 'required'),
        );
    }
}