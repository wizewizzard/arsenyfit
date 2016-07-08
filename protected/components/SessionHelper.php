<?php
	class SessionHelper {
    /**
     * Иницилизация сессии
     */
    public static function initSession()
    {
 
        /** @var CHttpSession $session */
        
        //$session = Yii::app()->session;
        if (Yii::app()->session->isStarted == false){
            Yii::app()->session = new CHttpSession;
            Yii::app()->session->open();
        }
        /*if(!Yii::app()->session->isStarted)
        Yii::app()->session->open();*/
    }
}
?>