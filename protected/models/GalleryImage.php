<?php
Yii::import("application.extensions.myclasses.*");
/**
 * This is the model class for table "{{gallery_image}}".
 *
 * The followings are the available columns in table '{{gallery_image}}':
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $img_name
 * @property string $date
 */
class GalleryImage extends CActiveRecord
{
	public $image;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{gallery_image}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('image', 'required'),
			array('title', 'length', 'max'=>50),
			array('description', 'length', 'max'=>100),
			array('img_name', 'length', 'max'=>30),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, description, img_name, date', 'safe', 'on'=>'search'),
			array('image', 'file', 'types'=>'jpg, gif, png', 'safe' => false),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Title',
			'description' => 'Description',
			'img_name' => 'Img Name',
			'date' => 'Date',
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('img_name',$this->img_name,true);
		$criteria->compare('date',$this->date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return GalleryImage the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	protected function beforeSave(){
		if(!parent::beforeSave())
			return false;
		if(($this->scenario=='insert' || $this->scenario=='update')){
			$this->deleteDocument(); // старый документ удалим, потому что загружаем новый

			//$this->image=$image;
			$helper = new ImageHelper;
			$this->img_name = $helper->generateImgName().'.'.$this->image->getExtensionName();
			$this->image->saveAs(
				Yii::getPathOfAlias('webroot.images.gallery.original').DIRECTORY_SEPARATOR.$this->img_name);

			$helper->load(Yii::getPathOfAlias('webroot.images.gallery.original').DIRECTORY_SEPARATOR.$this->img_name);

			$width=$helper->getWidth();
			$height=$helper->getHeight();

			$max_allowed_w = Yii::app()->config->get('GALLERY.IMAGE_THUMBNAIL_WIDTH');
			$max_allowed_h = Yii::app()->config->get('GALLERY.IMAGE_THUMBNAIL_HEIGHT');

			if($width > $height) $k=$width/$max_allowed_w;
			else $k=$height/$max_allowed_h;

			if($k > 1) {
				$helper->resize($width / $k, $height / $k);
				$helper->save(Yii::getPathOfAlias('webroot.images.gallery.thumbnails') . DIRECTORY_SEPARATOR . $this->img_name);
			}
			else {
				$helper->save(Yii::getPathOfAlias('webroot.images.gallery.thumbnails') . DIRECTORY_SEPARATOR . $this->img_name);
			}
			date_default_timezone_set('UTC');
			$this->date = date('Y-m-d H:i:s');
		}
		return true;
	}

	protected function beforeDelete(){
		if(!parent::beforeDelete())
			return false;
		$this->deleteDocument();
		return true;
	}

	public function deleteDocument(){
		$documentPath=Yii::getPathOfAlias('webroot.images.gallery.thumbnails').DIRECTORY_SEPARATOR.
			$this->img_name;
		if(is_file($documentPath))
			unlink($documentPath);
		$documentPath=Yii::getPathOfAlias('webroot.images.gallery.original').DIRECTORY_SEPARATOR.
			$this->img_name;
		if(is_file($documentPath))
			unlink($documentPath);
	}
}
