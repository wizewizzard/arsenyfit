<?php

class ConsultationcalendarController extends Controller
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

	protected function beforeAction($action){
		if( parent::beforeAction($action) ) {
			$cs = Yii::app()->clientScript;
			$cs->registerCssFile( Yii::app()->request->getBaseUrl() . '/js/datetimepicker/jquery.simple-dtpicker.css' );
			$cs->registerScriptFile( Yii::app()->request->getBaseUrl() . '/js/datetimepicker/jquery.simple-dtpicker.js' );
			$cs->registerCssFile( Yii::app()->request->getBaseUrl() . '/css/admin.css' );
			return true;
		}
	}

	/**
	 * @return array action filters
	 */
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
		$this->url_create = array('/admin/consultationcalendar/create');
		$this->url_update = array('/admin/consultationcalendar/update', 'id' => $model->id);
		$this->url_delete = array('submit'=>array('/admin/consultationcalendar/delete', "id"=>$model->id),
			'confirm' => 'Delete?');
		$this->url_admin = array('/admin/consultationcalendar/admin');
		$this->render('view',array(
			'model'=>$model,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new ConsultationCalendar;
		$this->url_admin = array('/admin/consultationcalendar/admin');
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['ConsultationCalendar']))
		{
			$model->attributes=$_POST['ConsultationCalendar'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
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
		$this->url_create = array('/admin/consultationcalendar/create');
		$this->url_delete = array('submit'=>array('/admin/consultationcalendar/delete', "id"=>$model->id),
			'confirm' => 'Delete?');
		$this->url_admin = array('/admin/consultationcalendar/admin');

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['ConsultationCalendar']))
		{
			$model->attributes=$_POST['ConsultationCalendar'];
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
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new ConsultationCalendar('search');
		$this->url_create = array('/admin/consultationcalendar/create');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ConsultationCalendar']))
			$model->attributes=$_GET['ConsultationCalendar'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return ConsultationCalendar the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=ConsultationCalendar::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param ConsultationCalendar $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='consultation-calendar-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
