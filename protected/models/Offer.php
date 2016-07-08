<?php

/**
 * This is the model class for table "{{offer}}".
 *
 * The followings are the available columns in table '{{offer}}':
 * @property integer $id
 * @property integer $id_item
 * @property string $title
 * @property string $description
 * @property string $template
 *
 * The followings are the available model relations:
 * @property Item $idItem
 */
class Offer extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{offer}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_item, title, template', 'required'),
			array('id_item', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>50),
			array('description', 'length', 'max'=>500),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_item, title, description, template', 'safe', 'on'=>'search'),
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
			'idItem' => array(self::BELONGS_TO, 'Item', 'id_item'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_item' => 'Id Item',
			'title' => 'Title',
			'description' => 'Description',
			'template' => 'Template',
		);
	}

	public function isSuitable($item, $count, $total){
		$flag = 1;

		$price = $item -> price;
		$item_total = $price * $count;
		$conditions_pattern = "/<CONDITIONS>(.*?)<\/CONDITIONS>/";
		preg_match($conditions_pattern, $this->template, $matches);
		$conditions =  $matches[1];
		$condition_pattern = "/<CONDITION>(.*?)<\/CONDITION>/";
		preg_match_all($condition_pattern, $conditions, $matches);
		//return $matches[1][0];
		$i=0;
		//return !empty($matches[1][$i]);
		while($flag == 1 && !empty($matches[1][$i])){
			//return 'haha';
			$condition = $matches[1][$i++];
			$param_pattern = "/<PARAM>(.*?)<\/PARAM>/";
			$sign_pattern = "/<SIGN>(.*?)<\/SIGN>/";
			$value_pattern= "/<VALUE>(.*?)<\/VALUE>/";
			preg_match($param_pattern, $condition, $matches);
			$param = $matches[1];
			//return $param;
			preg_match($sign_pattern, $condition, $matches);
			$sign = $matches[1];
			preg_match($value_pattern, $condition, $matches);
			$value = $matches[1];
			if(empty($param) || empty ($sign) || empty($value)) return -1;
			switch($param){
				case 'count':
					switch($sign){
						case '<': if($count < $value) $flag = true; else $flag = 0; break;
						case '<=': if($count <= $value) $flag = true; else $flag = 0; break;
						case '=': if($count == $value) $flag = true; else $flag = 0; break;
						case '>': if($count > $value) $flag = true; else $flag = 0; break;
						case '>=': if($count >= $value) $flag = true; else $flag = 0; break;
						default: return -1;
					}
					break;
				case 'price':
					switch($sign){
						case '<': if($price < $value) $flag = true; else $flag = 0; break;
						case '<=': if($price < $value) $flag = true; else $flag = 0; break;
						case '=': if($price < $value) $flag = true; else $flag = 0; break;
						case '>': if($price < $value) $flag = true; else $flag = 0; break;
						case '>=': if($price < $value) $flag = true; else $flag = 0; break;
						default: return -1;
					}
					break;
				case 'item_total':
					switch($sign){
						case '<': if($item_total < $value) $flag = true; else $flag = 0; break;
						case '<=': if($item_total < $value) $flag = true; else $flag = 0; break;
						case '=': if($item_total < $value) $flag = true; else $flag = 0; break;
						case '>': if($item_total < $value) $flag = true; else $flag = 0; break;
						case '>=': if($item_total < $value) $flag = true; else $flag = 0; break;
						default: return -1;
					}
					break;
				case 'total':
					switch($sign){
						case '<': if($total < $value) $flag = true; else $flag = 0; break;
						case '<=': if($total < $value) $flag = true; else $flag = 0; break;
						case '=': if($total < $value) $flag = true; else $flag = 0; break;
						case '>': if($total < $value) $flag = true; else $flag = 0; break;
						case '>=': if($total < $value) $flag = true; else $flag = 0; break;
						default: return -1;
					}
					break;
			}
			//preg_match($condition_pattern, $conditions, $matches);
		}
		return $flag;

	}

	public function apply($item, $count, $total){
		$item_total = $item->price * $count;
		//return $item_total;

		if($this->isSuitable($item, $count, $total) != 1) return $item_total;
		$result_pattern = "/<RESULT>(.*?)<\/RESULT>/";
		$apply_for_pattern = "/<APPLYFOR>(.*?)<\/APPLYFOR>/";
		$value_pattern = "/<VALUE>(.*?)<\/VALUE>/";

		preg_match($result_pattern, $this->template, $matches);
		$result =  $matches[1];

		preg_match($apply_for_pattern, $result, $matches);
		$for_each_x_item =  intval($matches[1]);

		preg_match($value_pattern, $result, $matches);
		$value =  floatval($matches[1])/100;

		//return $result;
			$items_under_offer_count = intval($count/$for_each_x_item);
		$item_total += ($value * $item->price) * $items_under_offer_count;
		return $item_total;
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
		$criteria->compare('id_item',$this->id_item);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('template',$this->template,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Offer the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
