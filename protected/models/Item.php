<?php
Yii::import("application.extensions.myclasses.*");
/**
 * This is the model class for table "{{item}}".
 *
 * The followings are the available columns in table '{{item}}':
 * @property integer $id
 * @property integer $id_type
 * @property integer $id_subtype
 * @property string $title
 * @property string $description
 * @property string $img_name
 * @property double $price
 * @property double $weight
 * @property string $date
 * @property integer $preview_flag
 *
 * The followings are the available model relations:
 * @property ItemClassification $idType
 * @property ItemClassification $idSubtype
 * @property ItemFile[] $itemFiles
 * @property Offer[] $offers
 * @property OrderItem[] $orderItems
 */
class Item extends CActiveRecord
{
	public $images;
	public $images_to_delete;
	public $files_attached;
	public $files_to_delete;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{item}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, id_type, price, preview_flag', 'required'),
			//array('images', 'required', 'on' => 'insert'),
			array('id_type, id_subtype, preview_flag', 'numerical', 'integerOnly'=>true),
			array('price, weight', 'numerical'),
			array('title', 'length', 'max'=>50),
			array('description, files_to_delete, images_to_delete', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_type, id_subtype, title, description, weight, img_name, price, date, preview_flag', 'safe', 'on'=>'search'),
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
			'idType' => array(self::BELONGS_TO, 'ItemClassification', 'id_type'),
			'idSubtype' => array(self::BELONGS_TO, 'ItemClassification', 'id_subtype'),
			'itemFiles' => array(self::HAS_MANY, 'ItemFile', 'id_item'),
			'offers' => array(self::HAS_MANY, 'Offer', 'id_item'),
			'orderItems' => array(self::HAS_MANY, 'OrderItem', 'id_item'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_type' => 'Id Type',
			'id_subtype' => 'Id Subtype',
			'title' => 'Title',
			'description' => 'Description',
			'img_name' => 'Img Name',
			'price' => 'Price',
			'date' => 'Date',
			'weight' => 'Weight',
			'preview_flag' => 'Preview Flag',
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
		$criteria->compare('id_type',$this->id_type);
		$criteria->compare('id_subtype',$this->id_subtype);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('img_name',$this->img_name,true);
		$criteria->compare('price',$this->price);
		$criteria->compare('weight',$this->weight);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('preview_flag',$this->preview_flag);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Item the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    protected function afterSave() {
        parent::afterSave();

		if($this->scenario == 'update'){
			$this->files_to_delete = trim(preg_replace('/\s{2,}/', ' ', $this->files_to_delete));

			$files_to_delete_ids = explode(" ", $this->files_to_delete);

			foreach ($files_to_delete_ids as $file_id){
				$item_file = ItemFile::model()->findByPk($file_id);
				if(!empty($item_file)){
					$item_file->delete();
				}
			}
		}

		foreach ($this->files_attached as $file) {
			$item_file = new ItemFile;
			$item_file->id_item = $this->id;
			date_default_timezone_set('UTC');
			$item_file->uploaded  = date('Y-m-d H:i:s');

				$helper = new ImageHelper;
				$cur_file_name = $file->getName();
				if($file->saveAs(Yii::getPathOfAlias('webroot.files').DIRECTORY_SEPARATOR.$cur_file_name)){
					$item_file->filename = $cur_file_name;
					$item_file->save();
				};
		}
        Yii::app()->cache->flush();
        Yii::app()->config->init();
    }

	protected function beforeSave(){
		if(!parent::beforeSave())
			return false;
		if(($this->scenario=='insert')){
			$this->deleteDocument(); // старый документ удалим, потому что загружаем новый
			$this->img_name = "";
			foreach ($this->images as $image => $pic){
				$helper = new ImageHelper;
				$cur_img_name = $helper->generateImgName().'.'.$pic->getExtensionName();

				$pic->saveAs(
					Yii::getPathOfAlias('webroot.images.items.original').DIRECTORY_SEPARATOR.$cur_img_name);

				$helper->load(Yii::getPathOfAlias('webroot.images.items.original').DIRECTORY_SEPARATOR.$cur_img_name);

				$width=$helper->getWidth();
				$height=$helper->getHeight();

				$max_allowed_w = Yii::app()->config->get('GALLERY.IMAGE_THUMBNAIL_WIDTH');
				$max_allowed_h = Yii::app()->config->get('GALLERY.IMAGE_THUMBNAIL_HEIGHT');

				if($width > $height)$k=$width/$max_allowed_w;
				else $k=$height/$max_allowed_h;

				if($k > 1) {
					$helper->resize($width / $k, $height / $k);
					$helper->save(Yii::getPathOfAlias('webroot.images.items.thumbnails') . DIRECTORY_SEPARATOR . $cur_img_name);
				}
				else {
					$helper->save(Yii::getPathOfAlias('webroot.images.items.thumbnails') . DIRECTORY_SEPARATOR . $cur_img_name);
				}
				$this->img_name.=$cur_img_name.' ';
			}
			//save txt files

			$this->img_name = trim(preg_replace('/\s{2,}/', ' ', $this->img_name));
			date_default_timezone_set('UTC');
			$this->date = date('Y-m-d H:i:s');
		}
		else if($this->scenario=='update'){
			//$this->deleteDocument();
			//$this->img_name = "";

			$this->images_to_delete = trim(preg_replace('/\s{2,}/', ' ', $this->images_to_delete));

			$img_to_delete_names = explode(" ", $this->images_to_delete);

			foreach ($img_to_delete_names as $img){
				$documentPath = Yii::getPathOfAlias('webroot.images.items.thumbnails') . DIRECTORY_SEPARATOR .
					$img;
				if (is_file($documentPath))
					unlink($documentPath);
				$documentPath = Yii::getPathOfAlias('webroot.images.items.original') . DIRECTORY_SEPARATOR .
					$img;
				if (is_file($documentPath))
					unlink($documentPath);

				$this->removeImage($img);
			}

			foreach ($this->images as $image => $pic){
				if (is_file(Yii::getPathOfAlias('webroot.images.items.original').DIRECTORY_SEPARATOR.$pic)){
					continue;
				}
				$helper = new ImageHelper;
				$cur_img_name = $helper->generateImgName().'.'.$pic->getExtensionName();

				$pic->saveAs(
					Yii::getPathOfAlias('webroot.images.items.original').DIRECTORY_SEPARATOR.$cur_img_name);

				$helper->load(Yii::getPathOfAlias('webroot.images.items.original').DIRECTORY_SEPARATOR.$cur_img_name);

				$width=$helper->getWidth();
				$height=$helper->getHeight();

				$max_allowed_w = Yii::app()->config->get('GALLERY.IMAGE_THUMBNAIL_WIDTH');
				$max_allowed_h = Yii::app()->config->get('GALLERY.IMAGE_THUMBNAIL_HEIGHT');

				if($width > $height)$k=$width/$max_allowed_w;
				else $k=$height/$max_allowed_h;

				if($k > 1) {
					$helper->resize($width / $k, $height / $k);
					$helper->save(Yii::getPathOfAlias('webroot.images.items.thumbnails') . DIRECTORY_SEPARATOR . $cur_img_name);
				}
				else {
					$helper->save(Yii::getPathOfAlias('webroot.images.items.thumbnails') . DIRECTORY_SEPARATOR . $cur_img_name);
				}
				$this->img_name.=' '.$cur_img_name.' ';
			}

			$this->img_name = trim(preg_replace('/\s{2,}/', ' ', $this->img_name));
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
		//разбить на имена картинок, для каждой удалить файл в обеих папках
		$imag_names = explode(" ", $this->img_name);
		foreach($imag_names as $img_name ) {
			$documentPath = Yii::getPathOfAlias('webroot.images.items.thumbnails') . DIRECTORY_SEPARATOR .
				$img_name;
			if (is_file($documentPath))
				unlink($documentPath);
			$documentPath = Yii::getPathOfAlias('webroot.images.items.original') . DIRECTORY_SEPARATOR .
				$img_name;
			if (is_file($documentPath))
				unlink($documentPath);
		}
		$files = $this->itemFiles;
		if(isset($files) && !empty($files)) {
			foreach($files as $file){
				$documentPath = Yii::getPathOfAlias('webroot.files') . DIRECTORY_SEPARATOR .
					$file->filename;
				if (is_file($documentPath))
					unlink($documentPath);
			}
}}

	public function removeImage($img_name){
		$this->img_name = str_replace($img_name, "", $this->img_name);
		$this->img_name = trim(preg_replace('/\s{2,}/', ' ', $this->img_name));
	}
	public function getImagesArray(){
		return explode(" ", $this->img_name);
	}
	public function hasFilesAttached(){
		if(!empty($this->itemFiles)) return true;
		return false;
	}
}
