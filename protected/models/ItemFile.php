<?php

/**
 * This is the model class for table "{{item_file}}".
 *
 * The followings are the available columns in table '{{item_file}}':
 * @property integer $id
 * @property integer $id_item
 * @property string $filename
 * @property string $uploaded
 * @property string $secret_key
 *
 * The followings are the available model relations:
 * @property Item $idItem
 */
class ItemFile extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{item_file}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_item, filename, uploaded', 'required'),
			array('id_item', 'numerical', 'integerOnly'=>true),
			array('filename, secret_key', 'length', 'max'=>256),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_item, filename, uploaded, secret_key', 'safe', 'on'=>'search'),
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
			'filename' => 'Filename',
			'uploaded' => 'Uploaded',
			'secret_key' => 'Secret Key',
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
		$criteria->compare('id_item',$this->id_item);
		$criteria->compare('filename',$this->filename,true);
		$criteria->compare('uploaded',$this->uploaded,true);
		$criteria->compare('secret_key',$this->secret_key,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ItemFile the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	protected function beforeSave(){
		if(!parent::beforeSave())
			return false;
		$this->secret_key = $this->generateKey(20);
		return true;
	}
	protected function beforeDelete(){
		if(!parent::beforeDelete())
			return false;
		$this->deleteDocument();
		return true;
	}
	public function deleteDocument(){
		$documentPath = Yii::getPathOfAlias('webroot.files') . DIRECTORY_SEPARATOR .
		$this->filename;
		if (is_file($documentPath))
		unlink($documentPath);
	}

	protected function generateKey($length=20){
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
}
