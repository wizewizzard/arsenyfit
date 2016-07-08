<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		$cs = Yii::app()->clientScript;
		$cs->registerCssFile( Yii::app()->request->getBaseUrl() . '/css/news_seq.css' );
		$cs->registerScriptFile( Yii::app()->request->getBaseUrl() . '/js/moment.js' );
		$cs->registerScriptFile( Yii::app()->request->getBaseUrl() . '/js/news_seq.js' );

        /*$posts_per_page=Yii::app()->config->get('POSTS.PER_HOMEPAGE');
        $criteria = new CDbCriteria;
        $criteria->order = 't.date DESC';
        $criteria->limit=$posts_per_page;
        
        $models=Post::model()->findAll($criteria);
         $this->render('index', array(
                'models'=>$models,
            ));*/
		$this->render('index');
	}
	public function actionAbout()
	{
		$this->render('about');
	}
    public function actionAjaxGetPosts($offset){
        
        //echo $offset;
       // $this->processPageRequest('page');
        $posts_per_page=Yii::app()->config->get('POSTS.PER_HOMEPAGE');
        $criteria = new CDbCriteria;
        $criteria->order = 't.date DESC';
        $criteria->limit=$posts_per_page;
        $criteria->offset=$offset*$posts_per_page;
        $models=Post::model()->findAll($criteria);
        if(!empty($models))
        echo CJSON::encode($models);
        else echo "-1";
    }

	/**
	 * This is the action to handle external exceptions.
	 */
    /*public function actionGenerate(){
        for($i=4; $i < 100; $i++){
            $post = new Post;
            $post->title = "post{$i}";
            $post->body = "post{$i}";
            $post->date = date("Y-m-d H:i:s");
            $post->save();
        }
    }*/
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */

	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}




	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}