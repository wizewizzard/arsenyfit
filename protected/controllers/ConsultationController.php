<?php

class ConsultationController extends Controller
{
    public function beforeAction($action) {
        if( parent::beforeAction($action) ) {
            /* @var $cs CClientScript */
            $cs = Yii::app()->clientScript;
            /* @var $theme CTheme */

            $cs->registerScriptFile( Yii::app()->request->getBaseUrl() . '/js/moment.js' );
            $cs->registerScriptFile( Yii::app()->request->getBaseUrl() . '/js/moment_timezone.js' );
            $cs->registerScriptFile( Yii::app()->request->getBaseUrl() . '/js/jquery.mCustomScrollbar.concat.min.js' );
            $cs->registerCssFile( Yii::app()->request->getBaseUrl() . '/css/jquery.mCustomScrollbar.css' );

            return true;
        }
        return false;
    }
    public function actionIndex()
    {
        $cs = Yii::app()->clientScript;
        $cs->registerCssFile( Yii::app()->request->getBaseUrl() . '/css/calendar.css' );
        $cs->registerScriptFile( Yii::app()->request->getBaseUrl() . '/js/calendar.js' );
        $this->render('index');
    }

    public function actionGetConsultations($month, $year){
        $criteria = new CDbCriteria;
        $criteria->condition = 'year(time_from)=:year and month(time_from)=:month';
        $criteria->params = array(':year' => $year, ':month' => $month);
        $consultations = ConsultationCalendar::model()->findAll($criteria);

        echo CJSON::encode($consultations);
    }

    public function actionGetAppointments( $month, $year, $day=null){

        $criteria = new CDbCriteria;
        if(!empty($month) && !empty($year)) {
            if (!empty($day)) {
                $criteria->condition = 'year(time_from)=:year and month(time_from)=:month and day(time_from)=:day';
                $criteria->params = array(':year' => $year, ':month' => $month, ':day' => $day);
            } else {
                $criteria->condition = 'year(time_from)=:year and month(time_from)=:month';
                $criteria->params = array(':year' => $year, ':month' => $month);
            }
            $criteria->order = 'time_from asc';
            $consultations = ConsultationCalendar::model()->findAll($criteria);
            $appointments = array();
            foreach($consultations as $consultation)
                $appointments[$consultation->id] = $consultation->consultations_srtd;
            echo CJSON::encode(array($consultations, $appointments));
        }
        else throw new CHttpException(404,'The requested page does not exist');
    }

    public function actionGetConsultationAndAppointments($id){
        $criteria = new CDbCriteria;
        if(!empty($id)) {

            $criteria->condition = 'id=:id';
            $criteria->params = array (':id' => $id);
            $consultation = ConsultationCalendar::model()->find ($criteria);
            $appointments = array();
            $appointments = $consultation->consultations_srtd;
            echo CJSON::encode(array($consultation, $appointments));
        }
        else throw new CHttpException(404,'The requested page does not exist');
    }

    public function actionSign($id){

        $cs = Yii::app()->clientScript;
        $cs->registerCssFile( Yii::app()->request->getBaseUrl() . '/css/consultation_sign.css' );

        $cs->registerScriptFile( Yii::app()->request->getBaseUrl() . '/js/jquery-ui.js' );

        $cs->registerScriptFile( Yii::app()->request->getBaseUrl() . '/js/consultation_sign.js' );
       // $cs->registerScriptFile( Yii::app()->request->getBaseUrl() . '/js/consultation_sign.js' );

        $criteria = new CDbCriteria;
        $criteria->condition = 'id=:id';
        $criteria->params = array(':id' => $id);

        $consultation_calendar = ConsultationCalendar::model()->find($criteria);

        if(!empty($consultation_calendar)) {
            $model = new Consultation;
            $model->id_calendar_time = $id;
            $model->payment_status = 0;
            // Uncomment the following line if AJAX validation is needed
            //$this->performAjaxValidation($model);

            if (isset($_POST['Consultation'])) {
                $model->attributes = $_POST['Consultation'];
                if ($model->save()){
                    //var_dump($model);
                    $this->redirect(array('index'));
                }
                    //$this->redirect(array('view', 'id' => $consultation_calendar->id));
            }

            $this->render('sign', array(
                'model' => $model,
                'id' => $consultation_calendar->id
            ));
        }
        else
        throw new CHttpException(404,'The requested page does not exist');
    }

    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='consultation-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
?>