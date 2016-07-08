<?php

class ConsultationController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $url_create;
	public $url_update;
	public $url_admin;
	public $url_delete;
	public $layout='//layouts/admin_layout';

	protected function beforeAction()
	{
		$cs = Yii::app()->clientScript;

		$cs->registerCssFile( Yii::app()->request->getBaseUrl() . '/css/admin.css' );
		return true;
	}

	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$model = $this->loadModel($id);
		$this->url_update = array('/admin/consultation/update', 'id' => $model->id);
		$this->url_delete = array('submit'=>array('/admin/consultation/delete', "id"=>$model->id),
			'confirm' => 'Delete?');
		$this->url_admin = array('/admin/consultation/admin');
		$this->render('view',array(
			'model'=>$model,
		));
	}

	public function actionIndex()
	{
		$this->url_admin = array('/admin/consultation/admin');
		date_default_timezone_set('UTC');
		$criteria = new CDbCriteria;
		$criteria->condition = 'time_from > :cur_time';
		$criteria->params = array(':cur_time' => date('Y-m-d H:i:s a', time()));
		$criteria->order = 'time_from ASC';


		$consultations_calendar = ConsultationCalendar::model()->findAll($criteria);
		$count =0;
		$max_count =10;
		$closest_consultations = array();
		foreach($consultations_calendar as $consultation_calendar) {
			if($count >= $max_count) break;
			foreach ($consultation_calendar->consultations_srtd as $consultation) {
				if($count >= $max_count) break;
				$count++;
				$closest_consultations[] = array('consultation_calendar' => $consultation_calendar, 'consultation' => $consultation);
			}
		}
		$this->render('index',array(
			'closest_consultations' => $closest_consultations
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		$this->url_delete = array('submit'=>array('/admin/consultation/delete', "id"=>$model->id),
			'confirm' => 'Delete?');
		$this->url_admin = array('/admin/consultation/admin');
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Consultation']))
		{
			$model->attributes=$_POST['Consultation'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Consultation('search');

		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Consultation']))
			$model->attributes=$_GET['Consultation'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Consultation the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Consultation::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Consultation $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='consultation-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
