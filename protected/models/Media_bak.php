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
 *
 * The followings are the available model relations:
 * @property Content $content
 */
class Media_bak extends CActiveRecord
{

    public $markDeleted;

    const TYPE_IMAGE = 0;
    const TYPE_DOCUMENT = 1;
    const TYPE_SOUND = 2;
    const TYPE_VIDEO = 3;

    const CONTENT_TYPE_CONTENT = 1;
    const CONTENT_TYPE_PRODUCT = 2;
    const CONTENT_TYPE_RINGTONE = 3;

    /**
     * Returns the static model of the specified AR class.
     * @return Media the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
    public function relations()
    {
        return array(
            'content' => array(self::BELONGS_TO, 'Content', 'content_key'),
            'product' => array(self::BELONGS_TO, 'Product', 'content_key', 'condition' => "content_type = 2"), 
        );
    }

    /**
     * This array translates the content type ids for this application to strings
     */
    public function getContentTypes()
    {
        return array(
            self::CONTENT_TYPE_CONTENT => 'Content',
            self::CONTENT_TYPE_PRODUCT => 'Product',
            self::CONTENT_TYPE_RINGTONE => 'Ringtone',
        );
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'media_bak';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, filename, content_type', 'required'),
            array('content_type', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 100),
            array('filename', 'length', 'max' => 255),
            array('type, description', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('name, description, url, type, content_key, content_type', 'safe', 'on' => 'search'),
        );
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
        );
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
        $criteria->compare('url', $this->url, true);
        $criteria->compare('type', $this->type);
        $criteria->compare('content_key', $this->content_key);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
        ));
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

    public function getUrl()
    {
        return "/media/" . $this->contentTypes[$this->content_type] . "_" . $this->content_key . "/" . $this->filename;
    }
    public function setUrl($value)
    {
        $this->url = $value;
    }

    /**
     * Move the uploaded file from a temporarily directory, to the final destination after saving
     */
    protected function afterSave()
    {
        parent::afterSave();
        if ($this->isNewRecord) //if new record and save was successfull
        {
            $temp_file = Yii::getPathOfAlias('webroot') . "/media/temp/" . Yii::app()->session->sessionID . "/" . $this->filename;
            $destination_dir = Yii::getPathOfAlias('webroot') . "/media/" . $this->contentTypes[$this->content_type] . "_" . $this->content_key . "/";

            //Make directory for uploaded file
            if (!is_dir($destination_dir))
                mkdir($destination_dir, 0777, true);
            //Move uploaded files to there valid location
            rename($temp_file, $destination_dir . $this->filename);
        }
    }

    /**
     * Delete the media item from file system after deleting the record in the database
     */
    protected function afterDelete()
    {
        parent::afterDelete();
        @unlink(Yii::getPathOfAlias('webroot') . $this->url);
    }

}