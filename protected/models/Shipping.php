<?php
Yii::import("application.extensions.myclasses.*");
require_once('RussianPostCalc.php');
require_once('CartTotalCounter.php');
/**
 * This is the model class for table "{{shipping}}".
 *
 * The followings are the available columns in table '{{shipping}}':
 * @property integer $id
 * @property integer $id_order
 * @property string $address
 * @property string $apt_num
 * @property string $state
 * @property string $zip_code
 * @property integer $id_country
 * @property string $city
 * @property double $cost
 *
 * The followings are the available model relations:
 * @property Country $idCountry
 * @property Order $idOrder
 */
class Shipping extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{shipping}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_order, address, apt_num, state, zip_code, id_country, city, cost', 'required'),
			array('id_order, id_country', 'numerical', 'integerOnly'=>true),
			array('cost', 'numerical'),
			array('address, apt_num, state, zip_code, city', 'length', 'max'=>128),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_order, address, apt_num, state, zip_code, id_country, city, cost', 'safe', 'on'=>'search'),
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
			'idCountry' => array(self::BELONGS_TO, 'Country', 'id_country'),
			'idOrder' => array(self::BELONGS_TO, 'Order', 'id_order'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_order' => 'Id Order',
			'address' => 'Address',
			'apt_num' => 'Apt Num',
			'state' => 'State',
			'zip_code' => 'Zip Code',
			'id_country' => 'Id Country',
			'city' => 'City',
			'cost' => 'Cost',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('id_order',$this->id_order);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('apt_num',$this->apt_num,true);
		$criteria->compare('state',$this->state,true);
		$criteria->compare('zip_code',$this->zip_code,true);
		$criteria->compare('id_country',$this->id_country);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('cost',$this->cost);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Shipping the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	public function fillModelData($form, $totals){
		$this->address = $form->address;
		$this->apt_num = $form->apt_num;
		$this->state = $form->state;
		$this->zip_code = $form->zip_code;

		$criteria = new CDbCriteria;
		$criteria->condition = "id=:id";
		$criteria->params = array(':id' => $form->country);
		$country = Country::model()->find($criteria);
		if(count($country) == 0){
			throw new Exception('There is no such country in DB.');
		}
		else{
			$this->id_country = $form->country;
		}
		$this->city = $form->city;


		if($country->country_name_en=='Russian Federation') $international = 0;
		else $international = 1;


		$api_key = Yii::app()->config->get('RUSSIANPOST.API_KEY');
		$api_password = Yii::app()->config->get('RUSSIANPOST.PASSWORD');
		$our_zip_code = Yii::app()->config->get('RUSSIANPOST.OUR_ZIPCODE');
		$post_helper= new RussianPostCalc;

		$currency = Yii::app()->config->get('GLOBAL.SITE_CURRENCY');
		$exchange_rate = (strtolower($currency) == 'rub') ? 1 : Yii::app()->config->get('GLOBAL.RUB_EXCHANGE_RATE');
		$currency_str = strtolower($currency);
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
			$this->cost = $cost;
		}
		else{

			$ret = $post_helper->russianpostcalc_api_calc($api_key, $api_password, $our_zip_code, $this->zip_code, $totals['total_weight'], 0);

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
				$this->cost = $cost;
			} else {
				throw new Exception('Zip_code_error. Invalid zip code.');
				//throw new CHttpException(500,'Post api error');
			}
		}




	}
}
