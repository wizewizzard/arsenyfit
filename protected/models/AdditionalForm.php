<?php

/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 01.04.2016
 * Time: 17:03
 */
class AdditionalForm extends CFormModel
{
    public $commentaries;
    public function rules() {
        return array(array('commentaries', 'safe'));
    }

    public function safeAttributes() {
        return array('commentaries',);
    }
}