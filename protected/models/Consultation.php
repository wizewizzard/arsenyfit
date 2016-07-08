<?php

/**
 * This is the model class for table "{{consultation}}".
 *
 * The followings are the available columns in table '{{consultation}}':
 * @property integer $id
 * @property integer $id_calendar_time
 * @property string $offset
 * @property string $duration
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $other_contact_info
 * @property string $key
 * @property string $commentary
 * @property integer $payment_status
 *
 * The followings are the available model relations:
 * @property ConsultationCalendar $idCalendarTime
 */
class Consultation extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{consultation}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_calendar_time, offset, duration, first_name, last_name, email, payment_status', 'required'),
			array('id_calendar_time, payment_status', 'numerical', 'integerOnly'=>true),
			array('first_name, last_name, email', 'length', 'max'=>128),
			array('other_contact_info', 'length', 'max'=>200),
			array('key', 'length', 'max'=>8),
			array('commentary', 'safe'),
			array('email', 'email'),
			array('offset', 'checkIntersection', 'duration'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_calendar_time, offset, duration, first_name, last_name, email, other_contact_info, key, commentary, payment_status', 'safe', 'on'=>'search'),
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
			'idCalendarTime' => array(self::BELONGS_TO, 'ConsultationCalendar', 'id_calendar_time'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_calendar_time' => 'Id Calendar Time',
			'offset' => 'Offset',
			'duration' => 'Duration',
			'first_name' => 'First Name',
			'last_name' => 'Last Name',
			'email' => 'Email',
			'other_contact_info' => 'Other Contact Info',
			'key' => 'Key',
			'commentary' => 'Commentary',
			'payment_status' => 'Payment Status',
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
		$criteria->compare('id_calendar_time',$this->id_calendar_time);
		$criteria->compare('offset',$this->offset,true);
		$criteria->compare('duration',$this->duration,true);
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('other_contact_info',$this->other_contact_info,true);
		$criteria->compare('key',$this->key,true);
		$criteria->compare('commentary',$this->commentary,true);
		$criteria->compare('payment_status',$this->payment_status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Consultation the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	public function checkIntersection($attribute,$params){
		date_default_timezone_set('UTC');
		$offset = new DateTime($this->offset);
		$duration = new DateTime ($this->duration);
		$offset->setTimezone(new DateTimeZone('UTC'));
		$duration->setTimezone(new DateTimeZone('UTC'));
		$offset_stsmp = $offset->getTimestamp()-strtotime('TODAY');
		$duration_stsmp = $duration->getTimestamp()-strtotime('TODAY');
		//$this->addError('e1', $offset->format('Y-m-d H:i:s').' '.$duration_stsmp);
		//$this->addError('timebooked', $offset_stsmp);
		//$this->addError('timebooked', $duration_stsmp);
		$criteria = new CDbCriteria;
		$criteria->condition = 'id=:id';
		$criteria->params = array(':id' => $this->id_calendar_time);
		$calendar_consultation = ConsultationCalendar::model()->find($criteria);
		if(!empty($calendar_consultation)){
			foreach($calendar_consultation->consultations as $consultation){
				$offset_c = new DateTime($consultation->offset);
				$duration_c = new DateTime ($consultation->duration);

				$offset_c_stsmp = $offset_c->getTimestamp()-strtotime('TODAY');
				$duration_c_stsmp = $duration_c->getTimestamp()-strtotime('TODAY');
				//$this->addError('timebooked', $offset_c_stsmp);
				//$this->addError('timebooked', $duration_c_stsmp);
				//var_dump($consultation->offset < $offset && $offset < $consultation->offset + $consultation->duration);
				//$this->addError($consultation->id, $consultation->offset + $consultation->duration);
				//if(date_diff($offset_c, $offset) < 0 && date_diff($offset_c, $offset))
				if($offset_stsmp < $offset_c_stsmp+ $duration_c_stsmp && $offset_stsmp+$duration_stsmp > $offset_c_stsmp){

					$this->addError('timebooked', 'Time is already booked.');
				}
			}
		}
		else
		$this->addError('noconsultationfound', 'There is no consultation planned for this time.');
	}
}
