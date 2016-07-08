<?php
Yii::import("application.extensions.myclasses.*");
require_once('CartTotalCounter.php');
class CartController extends Controller
{
    public function beforeAction($action) {
    if( parent::beforeAction($action) ) {
        /* @var $cs CClientScript */
        $cs = Yii::app()->clientScript;
        /* @var $theme CTheme */

        $cs->registerCssFile( Yii::app()->request->getBaseUrl() . '/css/jquery.mCustomScrollbar.css' );
        $cs->registerScriptFile( Yii::app()->request->getBaseUrl() . '/js/jquery.mCustomScrollbar.concat.min.js' );
        $cs->registerScriptFile( Yii::app()->request->getBaseUrl() . '/js/yoxview/yoxview-init.js' );

        $cs->registerCssFile( Yii::app()->request->getBaseUrl() . '/css/cart.css' );
        $cs->registerScriptFile( Yii::app()->request->getBaseUrl() . '/js/cart.js' );

        return true;
    }
    return false;
    }

    public function actionAddToCart(){
        $id = Yii::app()->request->getPost('id');
        echo Yii::app()->cart->add($id);

    }
    public function actionUpdateitemincart(){
        $id = Yii::app()->request->getPost('id');
        $count = Yii::app()->request->getPost('count');

        echo Yii::app()->cart->update($id, $count);
    }
    public function actionIndex(){
        $items_in_cart=null;

        $array = Yii::app()->cart->get();
        if(!empty($array)){
            //print_r($array);
            $items_in_cart=array();
            foreach($array as $key => $value){
                $criteria = new CDbCriteria;
                $criteria->condition = 'id=:id';
                $criteria->params = array(':id' => $key);
                $item = Item::model()->find($criteria);
                if(isset($item))
                $items_in_cart[] = array($item,$value) ;
            }
        }
        $totals = Yii::app()->cart->total();
        $this->render('index',array('items' => $items_in_cart,
            'item_totals' => $totals['item_totals'],
            'total' => $totals['total'],
            'total_under_offers' => $totals['total_under_offers'] ) );
    }
    
    public function actionRemoveFromCart(){
        $id = Yii::app()->request->getPost('id');
        echo Yii::app()->cart->remove($id);
    }

    public function actionGetCart(){
        $array = array();
        $array = unserialize(Yii::app()->session['cart_items']);
        echo CJSON::encode($array);
    }

    public function actionClearCart(){
         if (isset(Yii::app()->session['cart_items'])) unset(Yii::app()->session['cart_items']);
    }
    //public function actionUpdate
}
?>