<?php
Yii::import("application.extensions.myclasses.*");
require_once('RussianPostCalc.php');
require_once('CartTotalCounter.php');
class OrderController extends Controller
{

    public function beforeAction($action) {
        if( parent::beforeAction($action) ) {
            /* @var $cs CClientScript */
            $cs = Yii::app()->clientScript;

            $cs->registerCssFile( Yii::app()->request->getBaseUrl() . '/css/order.css' );
            //$cs->registerScriptFile( Yii::app()->request->getBaseUrl() . '/js/shop.js' );
            return true;
        }
    }
    public function filters()
    {
        return array(
            'postOnly + deleteorder', // we only allow deletion via POST request
        );
    }
    public function loadModel($id)
    {
        $model = Order::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }
    public function actionMakeorder(){
        $cs = Yii::app()->clientScript;

        $cs->registerScriptFile(Yii::app()->request->getBaseUrl() . '/js/makeorder.js');

        $countries = Country::model()->findAll();

        $countries_list = CHtml::listData($countries,
            'id', 'country_name_en');
        $cform=new ContactForm;

        $aform = new AdditionalForm;

        $totals_w = Yii::app()->cart->total();
        if($totals_w['total_weight']) $sform=new ShippingForm;

        $this->render('makeorder',array('contact'=>$cform, 'shipping' => $sform, 'additional' => $aform, 'countries_list' => $countries_list));
    }
    public function actionExecuteorder(){
        if (!isset(Yii::app()->session['cart_items']) || count(Yii::app()->session->get('cart_items'))==0)
        {
            echo CJSON::encode(array('status'=> 'error','errors' => array('empty_cart' => 'Your cart is empty.')));
            Yii::app()->end();
        }
        $cform=new ContactForm;
        $sform=new ShippingForm;
        $aform = new AdditionalForm;

        $cform->attributes=$_POST['ContactForm'];
        $sform->attributes=$_POST['ShippingForm'];
        $aform->attributes=$_POST['AdditionalForm'];
        $items = Yii::app()->cart->get();
        $totals = Yii::app()->cart->total();
        if(!empty($items)){
        if($cform->validate()  && $aform->validate()){
            $order = new Order;
            $order->fillModelData($cform, $totals, $aform);
            if($order->save()){
                if($order->shipping_needed){
                    if($sform->validate()) {
                        $shipping = new Shipping();
                        $shipping->fillModelData($sform, $totals);

                        $shipping->id_order = $order->id;
                        $shipping->save();

                        foreach ($items as $key => $count) {
                            $order_item = new OrderItem;
                            $order_item->id_item = $key;
                            $order_item->id_order = $order->id;
                            $order_item->count = $count;
                            if (isset($totals['item_totals'][$key]['total_under_offer']))
                                $order_item->total = floatval($totals['item_totals'][$key]['total_under_offer']);
                            else $order_item->total = floatval($totals['item_totals'][$key]['total']);
                            $order_item->save();
                        }
                        echo CJSON::encode(array('status'=> 'success', 'id' => $order->id));
                        Yii::app()->cart->clear();
                    }
                    else{
                        echo CJSON::encode(array('status'=> 'error','errors' => $sform->errors));
                    }
                }
                else{
                        foreach($items as $key => $count){
                            $order_item = new OrderItem;
                            $order_item->id_item=$key;
                            $order_item->id_order=$order->id;
                            $order_item->count = $count;
                            if(isset($totals['item_totals'][$key]['total_under_offer']))
                                $order_item->total = floatval($totals['item_totals'][$key]['total_under_offer']);
                            else $order_item->total = floatval($totals['item_totals'][$key]['total']);
                            $order_item->save();
                        }
                        echo CJSON::encode(array('status'=> 'success', 'id' => $order->id));
                    Yii::app()->cart->clear();

                }
            }
            else{
                echo CJSON::encode(array('status'=> 'error','errors' => $order->errors));
            }
        }
        else{
            echo CJSON::encode(array('status'=> 'error','errors' => array_merge($cform->errors, $aform->errors)));
        }
        }
        else{
            echo CJSON::encode(array('status'=> 'error','errors' => array("cart_empty_error" => 'Your cart is empty.')));
        }

    }
    public function actionVieworder($id){
        if(Yii::app()->user->isOrderLogged()){
            $email = Yii::app()->session['order_email'];
            $key = Yii::app()->session['order_key'];
            $criteria = new CDbCriteria;
            $criteria->condition = ('t.id = :id and t.email = :email and t.key = :key');
            $criteria->params= array(':id' => $id, ':email' => $email, ':key' => $key);
            $order = Order::model()->find($criteria);
            if(isset($order) && count($order) > 0 )
            {
                $this->render('vieworder', array('order' => $order));
            }
            else{
                throw new CHttpException('403', 'You have no rights to access this page.');
            }
        }
        else{
            throw new CHttpException('403', 'You have no rights to access this page.');
        }
    }
    public function actionIndex(){
        $cs = Yii::app()->clientScript;
        $cs->registerCssFile( Yii::app()->request->getBaseUrl() . '/css/jquery.mCustomScrollbar.css' );
        $cs->registerScriptFile( Yii::app()->request->getBaseUrl() . '/js/jquery.mCustomScrollbar.concat.min.js' );
        $cs->registerScriptFile( Yii::app()->request->getBaseUrl() . '/js/orderindex.js' );
        if(Yii::app()->user->isOrderLogged()){
            $email = Yii::app()->session['order_email'];
            $key = Yii::app()->session['order_key'];
            $criteria = new CDbCriteria;
            $criteria->condition = ('t.email = :email and t.key = :key');
            $criteria->params= array(':email' => $email, ':key' => $key);
            $orders = Order::model()->findAll($criteria);
            if(isset($orders) && count($orders) > 0 )
            {
                $this->render('index', array('orders' => $orders));
            }
            else{
                Yii::app()->user->unsetOrderPair();
                $this->render('index');

            }
        }
        else{
            Yii::app()->user->returnUrl = ('/order/index');
            $this->redirect('/order/login');
        }
    }
    public function actionDeleteorder($id){
        if(Yii::app()->user->isOrderLogged()){
            $email = Yii::app()->session['order_email'];
            $key = Yii::app()->session['order_key'];
            $criteria = new CDbCriteria;
            $criteria->condition = ('t.id = :id and t.email = :email and t.key = :key');
            $criteria->params= array(':id' => $id, ':email' => $email, ':key' => $key);
            $order = Order::model()->find($criteria);



            if(isset($order) && count($order) > 0 )
            {
                $criteria = new CDbCriteria;
                $criteria->condition = 'abbr= :status';
                $criteria->params = array(':status' => 'pending_payment');
                $progress = OrderProgressStage::model()->find($criteria);
                if($order->orderProgress->id_stage == $progress->id){
                    $order->delete();
                    $this->redirect('/order/index');
                }
                else{
                    throw new CHttpException('403', 'You cannot delete paid orders. It is better to contact with administrator.');
                }
            }
            else{
                throw new CHttpException('403', 'You have no rights to do this action.');
            }
        }
        else{
            throw new CHttpException('403', 'You have no rights to do this action.');
        }
    }
    public function actionLogin(){
        $model = new OrderLoginForm;

        if(isset($_POST['OrderLoginForm'])){
            $model->attributes = $_POST['OrderLoginForm'];
            if($model->validate()){
                Yii::app()->user->setOrderPair($model->email, $model->key);
                $this->redirect(Yii::app()->user->returnUrl);
            }
        }
        $this->render('login',array('model'=>$model));
    }
    public function actionLogout(){
        Yii::app()->user->unsetOrderPair();
        $this->redirect('/site/index');
    }
    public function actionAjaxvalidatecontact(){
        $form=new ContactForm;
        if(isset($_POST['ContactForm'])){
            $form->attributes=$_POST['ContactForm'];
            if($form->validate())
            {
                echo '1';
                return;
            }
            else{
                echo CJSON::encode($form->errors);
                return;
            }
        }
        else echo CJSON::encode(array("server_error" => 'Server error occured.'));
    }
    public function actionAjaxvalidateshipping(){
        $form=new ShippingForm;
        if(isset($_POST['ShippingForm'])){
            $form->attributes=$_POST['ShippingForm'];
            if($form->validate())
            {
                echo '1';
                return;
            }
            else{
                echo CJSON::encode($form->errors);
                return;
            }
        }
        else echo CJSON::encode(array("server_error" => 'Server error occured.'));
    }
    public function actionNoshippingtotal(){
        set_time_limit(10);

        if (!isset(Yii::app()->session['cart_items']) || count(Yii::app()->session->get('cart_items'))==0)
        {
            echo CJSON::encode(array('status'=> 'error','errors' => array("server_error" => 'Cart is empty.')));
            Yii::app()->end();
        }


        $totals =  Yii::app()->cart->total();
        $currency = Yii::app()->config->get('GLOBAL.SITE_CURRENCY');
        $currency_str = strtolower($currency);


        if($totals['count_weighted'] > 0) {
            echo CJSON::encode(array('status'=> 'error','errors' => array("server_error" => 'Weighted items in cart.')));

        }
        else {
            echo CJSON::encode(array('status'=> 'success','total' => $totals['total_under_offers'], 'shipping' => 0, 'days' => 0, 'currency_str' => $currency_str));
        }
    }
    public function actionShippingtotal($zip_code, $id_country){
        set_time_limit(10);
        $post_helper= new RussianPostCalc;

        $api_key = Yii::app()->config->get('RUSSIANPOST.API_KEY');
        $api_password = Yii::app()->config->get('RUSSIANPOST.PASSWORD');
        $our_zip_code = Yii::app()->config->get('RUSSIANPOST.OUR_ZIPCODE');

        if (!isset(Yii::app()->session['cart_items']) || count(Yii::app()->session->get('cart_items'))==0)
        {
            echo CJSON::encode(array('status' => 'error','errors' => array("server_error" => 'Cart is empty.')));
            Yii::app()->end();
        }

        //$totals_w = $counter->countCartTotalWeighted();
        $totals = Yii::app()->cart->total();
        $currency = Yii::app()->config->get('GLOBAL.SITE_CURRENCY');
        $exchange_rate = (strtolower($currency) == 'rub') ? 1 : Yii::app()->config->get('GLOBAL.RUB_EXCHANGE_RATE');
        $currency_str = strtolower($currency);
        $criteria = new CDbCriteria;
        $criteria->condition = 'id=:id';
        $criteria->params = array(':id' => $id_country);
        $country = Country::model()->find($criteria);
        if($country->country_name_en=='Russian Federation') $international = 0;
        else $international = 1;

        if($totals['count_weighted'] > 0) {

            if($international==1){
                //!!!!currently this way
                $prices = array(
                    '0.1' => 99.12,
                    '0.25'=>141.6,
                    '0.5' => 252.52,
                    '1'=>428.34,
                    '2'=>770.54
                    );
                $days = 21;
                foreach($prices as $key => $val){
                    $cost= round($val/$exchange_rate,2);
                    if($totals['total_weight'] < floatval($key)) break;
                }
                echo CJSON::encode(array('status'=> 'success','total' => $totals['total_under_offers'], 'shipping' => $cost, 'days' => $days, 'currency_str' => $currency_str));
            }
            else{

                $ret = $post_helper->russianpostcalc_api_calc($api_key, $api_password, $our_zip_code, $zip_code, $totals['total_weight'], 0);

                if (isset($ret['msg']['type']) && $ret['msg']['type'] == "done") {
                    $cost = NULL;
                    $days = NULL;
                    $first = true;
                    foreach($ret['calc'] as $post_method){

                        if($first || $post_method['cost'] < $cost){
                            $cost = round($post_method['cost']/$exchange_rate,2);
                            $days = $post_method['days'];
                            $first=false;
                        }


                    }

                    echo CJSON::encode(array('status'=> 'success','total' => $totals['total_under_offers'], 'shipping' => $cost, 'days' => $days, 'currency_str' => $currency_str));

                } else {
                    echo CJSON::encode(array('status'=> 'error', 'errors' => array('zip_code_error' => 'Invalid zip code.')));
                    //throw new CHttpException(500,'Post api error');
                    Yii::app()->end();
                }
            }


        }
        else echo CJSON::encode(array('status'=> 'success', 'total' => $totals['total_under_offers'], 'shipping' => 0, 'days' => 0));
    }

    public function actionSenditemfile(){
        $mail_helper = new MailHelper;
        var_dump($mail_helper->send_mail('kingaal93@gmail.com', 'Hello', 'Good day', Yii::getPathOfAlias('webroot.images.items.files').DIRECTORY_SEPARATOR.'gf.docx'));
    }
}
