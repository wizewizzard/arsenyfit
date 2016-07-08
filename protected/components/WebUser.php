<?php

// this file must be stored in:
// protected/components/WebUser.php

class WebUser extends CWebUser {

    // Store model to not repeat query.
    private $_model;

    public function isAdmin(){
        $user = $this->loadUser(Yii::app()->user->id);
        return intval($user->id_group) == Yii::app()->config->get('GLOBAL.ID_GROUP_ADMIN');
    }

    public function setOrderPair($email, $key){
        Yii::app()->session['order_email'] = $email;
        Yii::app()->session['order_key'] = $key;
    }

    public function hasOrderRights($id){
        if(!isset(Yii::app()->session['order_email']) || !isset(Yii::app()->session['order_key'])){ return false;}

        return true;
    }
    public function isOrderLogged(){
        if(!isset(Yii::app()->session['order_email']) || !isset(Yii::app()->session['order_key'])){ return false;}

        return true;
    }
    public function unsetOrderPair(){
        if(isset(Yii::app()->session['order_email'])) unset(Yii::app()->session['order_email']);
        if(isset(Yii::app()->session['order_key'])) unset(Yii::app()->session['order_key']);
    }

    protected function loadUser($id=null)
    {
        if($this->_model===null)
        {
            if($id!==null)
                $this->_model=User::model()->findByPk($id);
        }
        return $this->_model;
    }
}
?>