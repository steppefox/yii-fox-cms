<?php

/**
 * This is the model class for table "content_has_media".
 *
 * The followings are the available columns in table 'content_has_media':
 * @property integer $content_id
 * @property integer $media_id
 * @property integer $type
 * @property string $name
 * @property string $description
 */
class ContentMedia extends XActiveRecord
{
    
    const MEDIA_HEADER_IMAGE = 1;
    const MEDIA_FOOTER_IMAGE = 2;
    const MEDIA_GALLERY_IMAGE = 3;
    const MEDIA_DOWNLOAD = 4;
    const MEDIA_UNPUBLISHED = 0; // Default
    
        public $file;
    
        public function getId()
        {
            return implode('-', $this->primaryKey);
        }
        
	/**
	 * Returns the static model of the specified AR class.
	 * @return ContentMedia the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'content_has_media';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('content_id, media_id', 'required'),
			array('content_id, media_id, type', 'numerical', 'integerOnly'=>true),
                    array('name', 'length', 'max' => 100),
                    array('description', 'safe'),
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
                    'media'=>array(self::BELONGS_TO,'Media','media_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
            return array(
                    'content_id' => 'Content',
                    'media_id' => 'Media',
                    'type' => 'Type',
                'name' => 'Naam',
                'description' => 'Description',
            );
	}
        
        
        public static function getMediaTypes()
        {
            return array(
                self::MEDIA_UNPUBLISHED => Yii::t('backend', 'Unpublished'),
                self::MEDIA_HEADER_IMAGE => Yii::t('backend', 'Header image'),
                //self::MEDIA_FOOTER_IMAGE => Yii::t('backend', 'Footer image'),
                self::MEDIA_GALLERY_IMAGE => Yii::t('backend', 'Gallery'),
                //self::MEDIA_DOWNLOAD => Yii::t('backend', 'Download'),
            );
        }


}