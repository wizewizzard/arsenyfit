<?php

class ItemController extends Controller
{
	public $url_create;
	public $url_update;
	public $url_admin;
	public $url_delete;
	public $layout='//layouts/admin_layout';

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */

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
				'actions'=>array('admin','delete','AjaxRemoveImg'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function beforeAction($action)
	{
		if (parent::beforeAction($action)) {
			$cs = Yii::app()->clientScript;

			$cs->registerCssFile(Yii::app()->request->getBaseUrl() . '/css/admin.css');
			$cs->registerScriptFile( Yii::app()->request->getBaseUrl() . '/js/jquery.mCustomScrollbar.concat.min.js' );
			return true;
		}
	}
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$model = $this->loadModel($id);
		$this->url_create = array('/admin/item/create');
		$this->url_update = array('/admin/item/update', 'id' => $model->id);
		$this->url_delete = array('submit'=>array('/admin/item/delete', "id"=>$model->id),
			'confirm' => 'Delete?');
		$this->url_admin = array('/admin/item/admin');
		$this->render('view',array(
			'model'=> $model,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$cs = Yii::app()->clientScript;

		$cs->registerScriptFile( Yii::app()->request->getBaseUrl() . '/js/ckeditor/ckeditor.js' );

		$cs->registerCssFile(Yii::app()->request->getBaseUrl() . '/js/colorpicker/css/colorpicker.css');
		$cs->registerScriptFile( Yii::app()->request->getBaseUrl() . '/js/colorpicker/js/colorpicker.js' );

		$cs->registerScriptFile( Yii::app()->request->getBaseUrl() . '/js/item_form.js' );

		$this->url_admin = array('/admin/item/admin');

		$model=new Item;
		$model->preview_flag = 1;
		$types = ItemClassification::model()->findAll();

		$types_list = CHtml::listData($types,
			'id', 'title');

		if(isset($_POST['Item']))
		{
			$model->attributes=$_POST['Item'];

			$model->images = CUploadedFile::getInstancesByName('images');
			$model->files_attached = CUploadedFile::getInstancesByName('files_attached');

			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
			'types_list' => $types_list
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$cs = Yii::app()->clientScript;
		$cs->registerCssFile(Yii::app()->request->getBaseUrl() . '/js/colorpicker/css/colorpicker.css');
		$cs->registerScriptFile( Yii::app()->request->getBaseUrl() . '/js/ckeditor/ckeditor.js' );
		$cs->registerScriptFile( Yii::app()->request->getBaseUrl() . '/js/item_form.js' );



		$model=$this->loadModel($id);
		$this->url_create = array('/admin/item/create');
		$this->url_delete = array('submit'=>array('/admin/item/delete', "id"=>$model->id),
			'confirm' => 'Delete?');
		$this->url_admin = array('/admin/item/admin');
		$types = ItemClassification::model()->findAll();

		$types_list = CHtml::listData($types,
			'id', 'title');

		if(isset($_POST['Item']))
		{
			$model->attributes=$_POST['Item'];

			//$model->images_to_delete = $_POST['Item']['images_to_delete'];
			$model->images = CUploadedFile::getInstancesByName('images');


			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
			'types_list' => $types_list
		));
	}

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
		$model=new Item('search');
		$this->url_create = array('/admin/item/create');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Item']))
			$model->attributes=$_GET['Item'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Item the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Item::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Item $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='item-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	public function adminOutputDescription($data,$row)
	{
		$allData=$data->description;
		$allData =  substr($allData, 0, 40);
		return $allData.'...';
	}
}
