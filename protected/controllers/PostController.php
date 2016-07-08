<?php
class PostController extends Controller
{
    public function loadModel($id)
    {
        $model=Post::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    public function actionView($id)
    {
        $cs = Yii::app()->clientScript;
        $cs->registerCssFile( Yii::app()->request->getBaseUrl() . '/css/post_view.css' );
        $this->render('view',array(
            'model'=>$this->loadModel($id),
        ));
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
}