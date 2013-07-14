<?php

/**
 * This is the model class for table "media".
 *
 * The followings are the available columns in table 'media':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $filename
 * @property integer $type
 * @property integer $content_key
 * @property integer $content_type
 * @property integer $administration_id
 *
 * The followings are the available model relations:
 * @property Content $content
 */
class Media extends CActiveRecord
{
    
    const TYPE_IMAGE = 0;
    const TYPE_DOCUMENT = 1;
    const TYPE_SOUND = 2;
    const TYPE_VIDEO = 3;

    /**
     * Returns the static model of the specified AR class.
     * @return Media the static model class
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
        return 'media';
    }
    
    public function getIsOwner()
    {
        return $this->administration_id == Yii::app()->administration->id;
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, administration_id, filename, file_type, file_size, path', 'required'),
            array('file_size', 'numerical'),
            array('administration_id', 'numerical', 'integerOnly'=>true),
            array('name', 'length', 'max' => 100),
            array('filename', 'length', 'max' => 255),
            array('file_type, description', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('name, path, create_date, type', 'safe', 'on' => 'search'),
        );
    }
    
    public function getExists()
    {
        return @CFile::set(FileSystem::FILE_FOLDER.DIRECTORY_SEPARATOR.$this->path.DIRECTORY_SEPARATOR.$this->filename)->exists;
    }
    
    public function getFullPath()
    {
        return @CFile::set(FileSystem::FILE_FOLDER.DIRECTORY_SEPARATOR.$this->path.DIRECTORY_SEPARATOR.$this->filename)->realPath;
    }
    
    private function formatFileSize($format)
    {
        $bytes = $this->file_size;
        $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');

        $bytes = max($bytes, 0);
        $expo = floor(($bytes ? log($bytes) : 0) / log(1024));
        $expo = min($expo, count($units)-1);

        $bytes /= pow(1024, $expo);

        return Yii::app()->numberFormatter->format($format, $bytes).' '.$units[$expo];
    }
    
    public function getFileSize()
    {
        return $this->formatFileSize('0.00');
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => 'Naam',
            'description' => 'Description',
            'url' => 'Url',
            'type' => 'Type',
            'content_key' => 'Content key',
            'administration_id' => 'Owner',
        );
    }
    
    public $thumbUrl = '';        
    public function afterFind()
    {
        $this->thumbUrl = $this->getImageUrl('thumb');
    }
    
    public function beforeDelete()
    {
        if (!$this->isOwner)
        {
            throw new CHttpException(403,'You are not authorized to delete this item.');
            return false;
        }
        else
            return parent::beforeDelete();
    }

    public function behaviors() {
            return array(
                    'MediaImgBehavior' => array(
                            'class' => 'FileARBehavior',
                            'attribute' => 'filename', // this must exist
                            'extensions' => 'png,gif,jpg', // possible extensions, comma separated
                            'attributeForName' => 'filename',
                            'attributeForPath' => 'path',
                            'relativeWebRootFolder' => 'files', // this folder must exist
                            'processedImagesFolder' => 'images',
                            'formats' => Yii::app()->params->image_formats,
                    )
            );
    }
    
    public function afterConstruct()
    {
        $this->id = uniqid(); //use temp id till save
    }
    protected function beforeSave()
    {
        if (parent::beforeSave())
        {
            if ($this->isNewRecord)
                $this->primaryKey = null; //Remove any temporary uniq id
            return true;
        }
        else
            return false;
    }
    
    public function display()
    {
        return CHtml::image($this->getImageUrl('thumb'), $this->description);
    }
    
    public function getCreateDateText()
    {
        if($this->isNewRecord)
            return "-";
        else
            return Yii::app()->dateFormatter->formatDateTime($this->create_date, 'long');
    }
    
    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;


        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('filename', $this->filename, true);
        $criteria->compare('path', $this->path);
        $criteria->compare('create_date', $this->create_date);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
            'pagination'=>array(
                'pageSize'=>5000
            ),
        ));
    }
    
    public function getLinks()
    {
        return array(); //get product links and content links
    }


}