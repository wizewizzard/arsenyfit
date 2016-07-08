<?php

/**
 * This is the model class for table "{{consultation_calendar}}".
 *
 * The followings are the available columns in table '{{consultation_calendar}}':
 * @property integer $id
 * @property string $time_from
 * @property string $time_until
 *
 * The followings are the available model relations:
 * @property Consultation[] $consultations
 */
class ConsultationCalendar extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{consultation_calendar}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('time_from, time_until', 'required'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, time_from, time_until', 'safe', 'on'=>'search'),
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
			'consultations' => array(self::HAS_MANY, 'Consultation', 'id_calendar_time'),
			'consultations_srtd' => array(self::HAS_MANY, 'Consultation', 'id_calendar_time',
				'order' => 'offset asc'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'time_from' => 'Time From',
			'time_until' => 'Time Until',
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
		$criteria->compare('time_from',$this->time_from,true);
		$criteria->compare('time_until',$this->time_until,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ConsultationCalendar the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	protected function beforeSave(){
		if(!parent::beforeSave())
			return false;
		date_default_timezone_set('UTC');
		$time_from = new DateTime($this->time_from.' +06');
		$time_until = new DateTime ($this->time_until.' +06');

		$time_from->setTimezone(new DateTimeZone('UTC'));
		$time_until->setTimezone(new DateTimeZone('UTC'));
		$this->time_from = $time_from->format('Y-m-d H:i:s');
		$this->time_until = $time_until->format('Y-m-d H:i:s');
		return true;
	}
}
