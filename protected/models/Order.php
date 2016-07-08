<?php
Yii::import("application.extensions.myclasses.*");
require_once('RussianPostCalc.php');
require_once('CartTotalCounter.php');
/**
 * This is the model class for table "{{order}}".
 *
 * The followings are the available columns in table '{{order}}':
 * @property integer $id
 * @property string $date
 * @property double $total
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property integer $shipping_needed
 * @property string $commentaries
 * @property string $key
 * @property integer $payment_status
 *
 * The followings are the available model relations:
 * @property OrderItem[] $orderItems
 * @property OrderProgress[] $orderProgresses
 * @property Shipping[] $shippings
 */
class Order extends CActiveRecord
{
	public $stage_search;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{order}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('total, first_name, last_name, email, shipping_needed, payment_status', 'required'),
			array('shipping_needed, payment_status', 'numerical', 'integerOnly'=>true),
			array('total', 'numerical'),
			array('email', 'email'),
			array('first_name, last_name, email', 'length', 'max'=>128),
			array('key', 'length', 'max'=>256),
			array('key', 'length', 'min'=>5),
			array('commentaries', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, date, total, first_name, last_name, email, shipping_needed, commentaries, key, payment_status, stage_search', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'orderItems' => array(self::HAS_MANY, 'OrderItem', 'id_order'),
			'orderProgress' => array(self::HAS_ONE, 'OrderProgress', 'id_order'),
			'shipping' => array(self::HAS_ONE, 'Shipping', 'id_order'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'date' => 'Date',
			'total' => 'Total',
			'first_name' => 'First Name',
			'last_name' => 'Last Name',
			'email' => 'Email',
			'shipping_needed' => 'Shipping Needed',
			'commentaries' => 'Commentaries',
			'key' => 'Key',
			'payment_status' => 'Payment Status',
			'stage_search' => 'Status'
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;
		$criteria->with = array( 'orderProgress' );
		$criteria->compare('id',$this->id);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('total',$this->total);
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('shipping_needed',$this->shipping_needed);
		$criteria->compare('commentaries',$this->commentaries,true);
		$criteria->compare('key',$this->key,true);
		$criteria->compare('payment_status',$this->payment_status);
		$criteria->compare('orderProgress.id_stage',$this->stage_search, true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Order the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	public function fillModelData($form, $totals, $aform){
		$this->first_name = $form->first_name;
		$this->last_name = $form->last_name;
		$this->email = $form->email;
		$this->key = $form->key;
		$this->commentaries = $aform->commentaries;
		if($totals['count_weighted'] > 0){
			$this->shipping_needed=1;
		}
		else $this->shipping_needed = 0;
		$this->payment_status =0;
		$this->total = $totals['total_under_offers'];
	}
	protected function beforeSave(){
		if(!parent::beforeSave())
			return false;
		date_default_timezone_set('UTC');
		$this->date = date('Y-m-d');


		return true;
}
	protected function afterSave(){
		parent::afterSave();


		$order_progress = new OrderProgress;
		$order_progress->id_order = $this->id;
		$criteria = new CDbCriteria;
		$criteria->condition = 'abbr= :status';
		$criteria->params = array(':status' => 'pending_payment');
		$progress = OrderProgressStage::model()->find($criteria);
		$order_progress->id_stage = $progress->id;
		date_default_timezone_set('UTC');
		$order_progress->changed = date('Y-m-d H:i:s');
		if($order_progress->save()){

			//$this->orderProgress = $order_progress;
		}
	}
	public function getItemsListStr(){
		$str="";
		foreach($this->orderItems as $item){
			$str.=$item->idItem->title.' ';
		}
		return trim($str);;
	}
}
