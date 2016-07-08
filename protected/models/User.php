<?php

/**
 * This is the model class for table "{{user}}".
 *
 * The followings are the available columns in table '{{user}}':
 * @property integer $id
 * @property integer $id_group
 * @property string $username
 * @property string $password
 * @property string $email
 *
 * The followings are the available model relations:
 * @property Order[] $orders
 * @property UserGroup $idGroup
 */
class User extends CActiveRecord
{
	public $password_confirm;
	public $new_password;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{user}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_group, username, password, email', 'required'),
			array('password_confirm', 'required', 'on' => 'insert'),
			array('username, email', 'unique'),
			array('email', 'email'),
			array('id_group', 'numerical', 'integerOnly'=>true),
			array('username', 'length', 'max'=>20),
			array('password', 'length', 'max'=>200),
			array('password, new_password', 'length', 'min'=>5),
			array('email', 'length', 'max'=>128),
			array('password', 'compare', 'compareAttribute'=>'password_confirm', 'on' => 'insert'),
			array('new_password', 'compare', 'compareAttribute'=>'password_confirm', 'on' => 'update'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_group, username, password, email', 'safe', 'on'=>'search'),
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
			'orders' => array(self::HAS_MANY, 'Order', 'id_user'),
			'idGroup' => array(self::BELONGS_TO, 'UserGroup', 'id_group'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_group' => 'Id Group',
			'username' => 'Username',
			'password' => 'Password',
			'email' => 'Email',
			'new_password' => 'New password',
			'password_confirm' => 'Confirm password'
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
		$criteria->compare('id_group',$this->id_group);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('email',$this->email,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	protected function beforeSave(){
		if(!parent::beforeSave())
			return false;
		if($this->scenario=='insert') {
			$this->password = $this->hashPassword($this->password);
		}
		else if($this->scenario == 'update'){
			if(isset($this->new_password) && strlen($this->new_password) > 0){
				$this->password = $this->hashPassword($this->new_password);
			}
		}
		return true;
	}
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function validatePassword($password)
	{
		return CPasswordHelper::verifyPassword($password,$this->password);
	}

	public function hashPassword($password)
	{
		return CPasswordHelper::hashPassword($password);
	}
}
