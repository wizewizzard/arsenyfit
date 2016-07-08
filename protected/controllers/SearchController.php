<?php
class SearchController extends Controller
{
    public function actionIndex()
    {
        $cs = Yii::app()->clientScript;

        $cs->registerCssFile( Yii::app()->request->getBaseUrl() . '/css/search.css' );
        /*
        $cs->registerScriptFile( Yii::app()->request->getBaseUrl() . '/js/jquery.mCustomScrollbar.concat.min.js' );
        $cs->registerScriptFile( Yii::app()->request->getBaseUrl() . '/js/yoxview/yoxview-init.js' );*/

        $option = new SearchOption;
        $search = new SiteSearchForm;
        $first_page=false;
        if(isset($_POST['SiteSearchForm'])) {
            $search->attributes = $_POST['SiteSearchForm'];
            $_GET['searchString'] = urlencode($search->string);
        } else {
            $search->string = urldecode($_GET['searchString']);
        }
       // var_dump($search->string);
        $option->option = 'news';
        if(isset($_POST['SearchOption'])) {
            $first_page=true;
            $option->attributes = $_POST['SearchOption'];
            Yii::app()->request->cookies['search_option'] = new CHttpCookie('search_option', $option->option);
        }
        else if(isset(Yii::app()->request->cookies['search_option'])){
            $option->option =  Yii::app()->request->cookies['search_option'];
        }


        if($option->option == 'news'){

            $criteria = new CDbCriteria(array(
                'condition' => 'body LIKE :keyword or preview LIKE :keyword or title LIKE :keyword or tags LIKE :keyword',
                'order' => 'date DESC',
                'params' => array(
                    ':keyword' => '%'.$search->string.'%',
                ),
            ));

            $posts = Post::model()->findAll($criteria);
            $count = count($posts);
            $dataProvider = new CArrayDataProvider($posts,
                array(
                    'pagination'=>array(
                        'pageSize'=>20,
                    ),
                    )
            );

        }
        else if($option->option == 'shop'){
            $criteria = new CDbCriteria(array(
                'condition' => 'title LIKE :keyword or description LIKE :keyword or price LIKE :keyword',
                'order' => 'date DESC',
                'params' => array(
                    ':keyword' => '%'.$search->string.'%',
                ),
            ));
           /* $itemsCount = Item::model()->count($criteria);
            $pages = new CPagination($itemsCount);
            $pages->pageSize = 3;
            $pages->applyLimit($criteria);*/

            $items = Item::model()->findAll($criteria);
            $count = count($items);
            $dataProvider = new CArrayDataProvider($items,
            array(
                'pagination'=>array(
                'pageSize'=>20,
            )
            ));

        }

        if($first_page) $dataProvider->pagination->currentPage = 0;

        // var_dump($search->string);
        $this->render('index',array(
            'dataProvider' => $dataProvider,
            'search' => $search,
            'option' => $option,
            'count' => $count
        ));

    }
    public function actionChangeOption(){

    }
}