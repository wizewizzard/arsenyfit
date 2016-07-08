<?php
Yii::import("application.extensions.myclasses.*");
require_once('TreeBuilder.php');
class ShopController extends Controller
{

    public $layout='//layouts/shop_layout';
    public $defaultAction = 'index';

    protected $shop_menu_upd=array();
    protected $id_type = null;
    protected $id_subtype = null;
    public function beforeAction($action) {
    if( parent::beforeAction($action) ) {
        /* @var $cs CClientScript */
        $cs = Yii::app()->clientScript;

        $cs->registerCssFile( Yii::app()->request->getBaseUrl() . '/css/shop.css' );
        $cs->registerCssFile( Yii::app()->request->getBaseUrl() . '/css/jquery.mCustomScrollbar.css' );
        $cs->registerScriptFile( Yii::app()->request->getBaseUrl() . '/js/shop.js' );
        $cs->registerScriptFile( Yii::app()->request->getBaseUrl() . '/js/jquery.mCustomScrollbar.concat.min.js' );
        $cs->registerScriptFile( Yii::app()->request->getBaseUrl() . '/js/yoxview/yoxview-init.js' );
        return true;
    }
    }
    public function actionIndex(){
        $criteria = new CDbCriteria;
        $criteria->order = 't.priority DESC';
        //Yii::app()->cache->flush();
        $classificators = ItemClassification::model()->findAll($criteria);

        $menu_depth = Yii::app()->config->get('SHOPMENU.DEPTH');

        $builder = new TreeBuilder;
        $this->shop_menu_upd = $builder->buildTree(null, 0, $classificators, $menu_depth);

        $criteria = new CDbCriteria;
        $criteria->join = 'LEFT JOIN ' . Yii::app()->db->tablePrefix . 'item_classification AS cl on t.id_type=cl.id';
        $criteria->order = 'cl.priority desc';
        $criteria->condition = 'cl.id_parent is NULL'; //  and t.preview_flag = 1

        $items_by_types = array();
        $preview_items = Item::model()->findAll($criteria);

        foreach ($preview_items as $item) {
            foreach ($classificators as $classificator) {
                if ($classificator->id == $item->id_type) {//!!!! сиправить ниже
                    $items_by_types[$classificator->title][$item->id] = $item;
                }
            }
        }
        $this->render('index', array(
            'param_lvl' => 1,
            'mode' => Yii::app()->config->get('ITEMS.MODE_SHOPHOMEPAGE'),
            'items' => $items_by_types));
    }
    public function actionView($id)
    {
        $criteria = new CDbCriteria;
        $criteria->order = 't.priority DESC';
        //Yii::app()->cache->flush();
        $classificators = ItemClassification::model()->cache(3600)->findAll($criteria);

        $menu_depth = Yii::app()->config->get('SHOPMENU.DEPTH');
        $this->id_type = $id;
        $builder = new TreeBuilder;
        $this->shop_menu_upd = $builder->buildTree(null, 0, $classificators, $menu_depth);
        if(!empty($id)) {
            $criteria = new CDbCriteria;
            $criteria->join = 'LEFT JOIN ' . Yii::app()->db->tablePrefix . 'item_classification AS cl on t.id_type=cl.id';
            $criteria->order = 'cl.priority desc';
            $criteria->condition = 'cl.id_parent is NULL and t.id_type=:id_t';
            $criteria->params = array(':id_t' => $id);

            $items_by_types = array();
            $items_of_type = Item::model()->findAll($criteria);

            foreach ($items_of_type as $item) {
                foreach ($classificators as $classificator) {
                    if ($classificator->id == $item->id_type) {//!!!! сиправить ниже
                        $items_by_types[$classificator->title][$item->id] = $item;
                    }
                }
            }
            $this->render('view', array(
                'mode' => Yii::app()->config->get('ITEMS.MODE_SHOPOTHERSPAGE'),
                'items' => $items_by_types));
        }
        else{
            new CHttpException(404,'The requested page does not exist.');
        }
    }
       /* if(empty($cid)) {

            $criteria = new CDbCriteria;
            $criteria->join = 'LEFT JOIN ' . Yii::app()->db->tablePrefix . 'item_classification AS cl on t.id_type=cl.id';
            $criteria->order = 'cl.priority desc';
            $criteria->condition = 'cl.id_parent is NULL and t.preview_flag = 1';

            $items_by_types = array();
            $preview_items = Item::model()->findAll($criteria);

            foreach ($preview_items as $item) {
                foreach ($classificators as $classificator) {
                    if ($classificator->id == $item->id_type) {//!!!! сиправить ниже
                        $items_by_types[$classificator->title][$item->id] = $item;
                    }
                }
            }
            $this->render('view', array(
                'param_lvl' => 1,
                'mode' => Yii::app()->config->get('ITEMS.MODE_SHOPHOMEPAGE'),
                'items' => $items_by_types));

        }
        else if(empty($sid)){
            $criteria = new CDbCriteria;
            $criteria->condition = 'id = :id and id_parent is NULL';
            $criteria->params = array(':id' => $cid);
            $type = ItemClassification::model()->find($criteria);
            if(!empty($type)) {
                $criteria = new CDbCriteria;
                $criteria->join = 'LEFT JOIN ' . Yii::app()->db->tablePrefix . 'item_classification AS cl ON cl.id=t.id_type';
                $criteria->order = 'cl.priority desc';
                $criteria->condition = 't.id_type = :id_t';
                $criteria->params = array(':id_t' => $cid);

                $items_by_type = array();
                $items_of_type = Item::model()->findAll($criteria);
                //echo count($items_by_type);
                    foreach ($items_of_type as $item) {
                        if (!empty($item->id_subtype)) {
                            $found = false;
                            foreach ($classificators as $classificator) {
                                if ($classificator->id == $item->id_subtype) {
                                    $items_by_subtype[$classificator->title][$item->id] = $item;
                                    $found = true;
                                    break;
                                }
                            }
                            if (!$found) $items_by_subtype['default'][$item->id] = $item;
                        }
                        else
                            $items_by_subtype['default'][$item->id] = $item;
                    }
                $this->render('view', array(
                    'mode' => Yii::app()->config->get('ITEMS.MODE_SHOPOTHERSPAGE'),
                    'items' => $items_by_type));
                /*$tree = $builder->buildTree(null,0,$classificators,2);
                $criteria = new CDbCriteria;
                $criteria->condition = 'id_type = :id_t';
                $criteria->params = array(':id_t' => $cid);
                $items = Item::model()->findAll($criteria);
                foreach($items as $item)
                    $builder->filerItem($tree, 2, 0, $item);

                $this->render('viewx', array(
                    'param_lvl' => 1,
                    'mode' => Yii::app()->config->get('ITEMS.MODE_SHOPOTHERSPAGE'),
                    'tree' => $tree,
                    'depth' => 1));*/
       /*     }
            else throw new CHttpException(404,'The requested page does not exist.');
        }
        else if(!empty($sid)){
            $criteria = new CDbCriteria;
            $criteria->condition = 'id = :id and id_parent=:id_p';
            $criteria->params = array(':id' => $sid, ':id_p' => $cid);
            $subtype = ItemClassification::model()->find($criteria);
            if(!empty($subtype)) {
                $criteria = new CDbCriteria;
                $criteria->join = 'LEFT JOIN ' . Yii::app()->db->tablePrefix . 'item_classification AS cl ON cl.id=t.id_subtype';
                $criteria->order = 'cl.priority desc';
                $criteria->condition = 't.id_type = :id_t and t.id_subtype=:id_s';
                $criteria->params = array(':id_t' => $cid, ':id_s' => $sid);

                $items_by_subtypes = array();
                $items_of_subtype = Item::model()->findAll($criteria);

                foreach ($items_of_subtype as $item) {
                    if (!empty($item->id_subtype)) {
                        $found = false;
                        foreach ($classificators as $classificator) {
                            if ($classificator->id == $item->id_subtype) {
                                $items_by_subtype[$classificator->title][$item->id] = $item;
                                $found = true;
                                break;
                            }
                        }
                        if (!$found) $items_by_subtype['default'][$item->id] = $item;
                    }
                    else
                        $items_by_subtype['default'][$item->id] = $item;
                }
                $this->render('view', array(
                    'mode' => Yii::app()->config->get('ITEMS.MODE_SHOPOTHERSPAGE'),
                    'items' => $items_by_subtype));
            }
            else throw new CHttpException(404,'The requested page does not exist.');
        }
        else throw new CHttpException(404,'The requested page does not exist');*/

    public function actionItem($id){
       //\\ $this->layout='main_vk_layout';
        $cs = Yii::app()->clientScript;

            $cs->registerCssFile( Yii::app()->request->getBaseUrl() . '/css/itemview.css' );
            $cs->registerScriptFile( Yii::app()->request->getBaseUrl() . '/js/itemview.js' );
            $criteria = new CDbCriteria;
            $criteria->order = 't.priority DESC';
            $classificators = ItemClassification::model()->cache(3600)->findAll($criteria);

            $depth = Yii::app()->config->get('SHOPMENU.DEPTH');

            $builder = new TreeBuilder;
            $this->shop_menu_upd = $builder->buildTree(null,0,$classificators,$depth);
            $criteria = new CDbCriteria;
            $criteria->condition = 'id=:id_i';
            $criteria->params=array(':id_i' => $id);
            $item = Item::model()->find($criteria);
            
            if(!empty($item))
            $this->render('viewitem', array('item' => $item));
            else 
            throw new CHttpException(404,'The requested page does not exist.');
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
}
?>