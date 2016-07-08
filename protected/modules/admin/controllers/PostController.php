<?php
class PostController extends Controller
{
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

	public function loadModel($id)
	{
		$model=Post::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	public function actionCreate()
	{
		$cs = Yii::app()->clientScript;

		$cs->registerScriptFile( Yii::app()->request->getBaseUrl() . '/js/ckeditor/ckeditor.js' );
		$model=new Post;
		$this->url_admin = array('/admin/post/admin');
		if(isset($_POST['Post']))
		{
			$model->attributes=$_POST['Post'];
			//$model->body = $_POST['Post']['body'];
			if(isset($_POST['preview'])){

				Yii::app()->user->setFlash('notice', "This is a post's preview!");
				Yii::app()->session->add("preview_model", serialize($model));
				$this->redirect(array('preview'));
				return;
			}
			else if(isset($_POST['publish'])){
				if($model->save()){
					if(isset(Yii::app()->session['preview_model'])) unset(Yii::app()->session['preview_model']);
					$this->redirect(array('/post/view','id'=>$model->id));
				}
			}
			else throw new CHttpException(404,'The requested page does not exist.');
			/**/
		}
		if(isset(Yii::app()->session['preview_model'])) unset(Yii::app()->session['preview_model']);
		$this->render('create',array(
			'model'=>$model,
		));
	}
	public function actionView($id)
	{
		$cs = Yii::app()->clientScript;
		$cs->registerCssFile( Yii::app()->request->getBaseUrl() . '/css/post_view.css' );
		$model = $this->loadModel($id);
		$this->url_create = array('/admin/post/create');
		$this->url_update = array('/admin/post/update', 'id' => $model->id);
		$this->url_delete = array('submit'=>array('/admin/post/delete', "id"=>$model->id),
			'confirm' => 'Delete?');
		$this->url_admin = array('/admin/post/admin');
		$this->render('view',array(
			'model'=> $model,
		));
	}
	public function actionPreview(){
		$cs = Yii::app()->clientScript;

		$cs->registerCssFile( Yii::app()->request->getBaseUrl() . '/css/news_seq.css' );
		$cs->registerCssFile( Yii::app()->request->getBaseUrl() . '/css/post_view.css' );
		$cs->registerCssFile( Yii::app()->request->getBaseUrl() . '/css/post_preview.css' );
		if(isset(Yii::app()->session['preview_model'])){
			$model = unserialize(Yii::app()->session['preview_model']);
			unset(Yii::app()->session['preview_model']);
			$this->render('preview',array(
				'model'=>$model,
			));
		}
		else{
			throw new CHttpException(404,'The requested page does not exist.');
		}
		//$model =

		/* $this->render('preview',array(
             'model'=>$this->loadModel($id),
         ));*/
	}
	public function actionUpdate($id)
	{
		$cs = Yii::app()->clientScript;
		$cs->registerScriptFile( Yii::app()->request->getBaseUrl() . '/js/ckeditor/ckeditor.js' );
		$model=$this->loadModel($id);
		$this->url_create = array('/admin/post/create');
		$this->url_delete = array('submit'=>array('/admin/post/delete', "id"=>$model->id),
			'confirm' => 'Delete?');
		$this->url_admin = array('/admin/post/admin');
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Post']))
		{
			$model->attributes=$_POST['Post'];
			//
			if(isset($_POST['preview'])) {

				Yii::app()->user->setFlash('notice', "This is a post's preview!");
				Yii::app()->session->add("preview_model", serialize($model));
				$this->redirect(array('preview'));
				return;
			}
			else if(isset($_POST['publish'])){
				Yii::app()->user->setFlash('success', "Post updated!");
				if($model->save()){
					$this->redirect(array('/post/view','id'=>$model->id));
				}
			}
			else throw new CHttpException(404,'The requested page does not exist.');
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();
		Yii::app()->user->setFlash('success', "Post deleted!");
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax'])){
			$this->redirect(array('operationresult'));
		}

	}

	public function actionOperationResult(){
		$this->render('operationresult');
	}

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

	public function actionSearch()
	{
		$search = new SiteSearchForm;

		if(isset($_POST['SiteSearchForm'])) {
			$search->attributes = $_POST['SiteSearchForm'];
			$_GET['searchString'] = $search->string;
		} else {
			$search->string = $_GET['searchString'];
		}

		$criteria = new CDbCriteria(array(
			'condition' => 'body LIKE :keyword or preview LIKE :keyword or title LIKE :keyword or tags LIKE :keyword',
			'order' => 'date DESC',
			'params' => array(
				':keyword' => '%'.$search->string.'%',
			),
		));

		$postsCount = Post::model()->count($criteria);
		$pages = new CPagination($postsCount);
		$pages->pageSize = 5;
		$pages->applyLimit($criteria);

		$posts = Post::model()->findAll($criteria);
		// var_dump($search->string);
		$this->render('found',array(
			'posts' => $posts,
			'pages' => $pages,
			'search' => $search,
		));

	}
	public function actionAdmin()
	{
		$model=new Post('search');
		$this->url_create = array('/admin/post/create');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Post']))
			$model->attributes=$_GET['Post'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	public function adminOutputPreview($data,$row)
	{
		$allData=$data->preview;
		$allData =  substr($allData, 0, 40);
		return $allData.'...';
	}
	public function adminOutputBody($data,$row)
	{
		$allData=$data->body;
		$allData =  substr($allData, 0, 40);
		return $allData.'...';
	}
}