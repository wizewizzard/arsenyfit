<?php

class DefaultController extends Controller
{
	public function actionIndex()
	{
		$cs = Yii::app()->clientScript;
		$cs->registerCssFile( Yii::app()->request->getBaseUrl() . '/css/admin.css' );
		//$cs->registerCssFile( Yii::app()->request->getBaseUrl() . '/css/view_post.css' );
		//$cs->registerScriptFile( Yii::app()->request->getBaseUrl() . '/js/ckeditor/ckeditor.js' );
		$this->render('index');
	}
	public function actionGetfile($id, $key){
		$file = ItemFile::model()->findByPk($id);
		if(!empty($file)){
			if(strcmp($file->secret_key, $key) == 0){
				return Yii::app()->request->sendFile($file->filename, file_get_contents(Yii::getPathOfAlias('webroot.files').DIRECTORY_SEPARATOR.$file->filename));
			}
			else return null;
		}
		else return null;
	}
}