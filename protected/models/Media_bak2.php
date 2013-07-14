<?php

/**
 * This is the model class for table "media".
 *
 * The followings are the available columns in table 'media':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $filename
 * @property string $path
 * @property string $create_date
 * @property integer $path
 * @property integer $file_type
 * @property integer $file_size
 *
 * The followings are the available model relations:
 * @property Content $content
 */
class Media extends CActiveRecord
{
    
    const FILE_FOLDER = 'files';
    const IMAGE_FOLDER = 'images';
    
    const TYPE_IMAGE = 0;
    const TYPE_DOCUMENT = 1;
    const TYPE_SOUND = 2;
    const TYPE_VIDEO = 3;
    
    const VALID_EXTENSIONS = 'png,gif,jpg'; //possible extension comma seperated

    public $file;
    
    private $_file_name;
    private $_file_extension;
    
    /**
     * Returns the static model of the specified AR class.
     * @return Media the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
    protected function getRealFilename()
    {
        return pathinfo($this->filename, PATHINFO_FILENAME);
    }
    
    protected function getExtension()
    {
        return pathinfo($this->filename, PATHINFO_EXTENSION);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'media';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, filename, file_type, file_size, path', 'required'),
            array('file_size', 'numerical'),
            array('name', 'length', 'max' => 100),
            array('filename', 'length', 'max' => 255),
            array('file_type, description', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('name, path, create_date, type', 'safe', 'on' => 'search'),
        );
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
        );
    }
    
    /**
     * Delete the file and all the cache
     */
    protected function afterDelete()
    {
        parent::afterDelete();
        Yii::log('delete file called', 'error');
        $this->deleteFiles();
        //$this->deleteImageCache();
    }
    
    /**
     * Save an uploaded file if given, after removing possible other files.
     */
    public function afterSave()
    {
        $file = $this->file; //CUploadedFile::getInstance($this->owner, $this->attribute);
        if($file && strpos(self::VALID_EXTENSIONS, strtolower($this->getExtension())) !== FALSE)
        {
            $path = $this->getFolderPath();
            $fname = $this->getRealFilename();
            if (!is_dir($path))
            {
                $old = umask(0);
                mkdir($path,0777, true);
                umask($old); 
            }
            $file->saveAs($path.DIRECTORY_SEPARATOR.$fname.'.'.$file->extensionName);
        }
    }
    
    /*
     * get existing files matching fname with all suffixes
     */
    protected function getAnyExistingFilesName($path, $fname) {
            $suffixes = array();
            foreach ($this->getImageFormats() as $f) {
                    $s = $f['suffix'];
                    if (!empty($f)) $suffixes[] = $s;
            }
            // this use the glob GLOB_BRACE option
            return $this->getExistingFilesName($path, $fname.'{'.join(',', $suffixes).'}');
    }
    protected function getExistingFilesName($path, $fname) {
            return glob($path.DIRECTORY_SEPARATOR.$fname.'.{'.str_replace(' ', '', $this->getExtension()).'}', GLOB_NOSORT | GLOB_BRACE);
    }
    
    /**
     * Delete the files retrieved by getExistingFilesName
     */
    protected function deleteFiles()
    {
        //Delete file
        unlink($this->getFilePath());
        
        //Delete cached images
        $fs = $this->getAnyExistingFilesName($this->getImagePath(), $this->getRealFilename());
        foreach ($fs as $f) unlink($f);
        
        
    }
    
    public function display()
    {
        return CHtml::image(Yii::app()->baseUrl."/".$this->path . "/" . $this->filename, $this->description, array("width" => 150, "height" => 120));
    }
    
    protected function getFolderPath() {
        return Yii::getPathOfAlias('webroot').DIRECTORY_SEPARATOR.self::FILE_FOLDER.DIRECTORY_SEPARATOR.str_replace("/",DIRECTORY_SEPARATOR ,$this->path); //relativeWebRootFolder;
    }
    protected function getImagePath() {
        return Yii::getPathOfAlias('webroot').DIRECTORY_SEPARATOR.self::IMAGE_FOLDER.DIRECTORY_SEPARATOR.str_replace("/",DIRECTORY_SEPARATOR ,$this->path);
    }
    protected function getImageFilePath(){
        return $this->getImagePath().DIRECTORY_SEPARATOR.$this->getRealFilename().$suffix.".".$this->getExtension();
    }
    protected function getFilePath() {
        return $this->getFolderPath().DIRECTORY_SEPARATOR.$this->getRealFilename().".".$this->getExtension();
    }
    protected function getImageFormats() {
        return Yii::app()->params->image_formats;
    }
    public function getFileUrl() {
        return Yii::app()->baseUrl.'/'.self::FILE_FOLDER."/".$this->path.'/'.$this->getRealFilename().".".$this->getExtension();
    }
    
    /**
     * Create a thumbnail of the image and return the url to this thumbnai
     * @param string $format the format of the thumbnail
     * @return string url to file 
     */
    public function getImageUrl($format = 'normal')
    {
        $filepath = $this->getFilePath();
        if(strpos(self::VALID_EXTENSIONS, strtolower($this->getExtension())) !== FALSE)
        {
            $formats = $this->getImageFormats();
            $suffix = $formats[$format]['suffix'];
            $imagefilepath = $this->getImageFilePath();
            //$fs = $this->getExistingFileName($path, $this->getFileName().$suffix);
            if(!file_exists($imagefilepath))
                $this->createImage($filepath, $format);

            return Yii::app()->baseUrl.'/'.self::IMAGE_FOLDER."/".$this->path.'/'.$this->getRealFilename().$suffix.".".$this->getExtension();
        }
        else // it's not an image
        {
            $path = Yii::app()->theme->baseUrl.'/images/filetypes/';
            if(file_exists('../'.$path.$this->getExtension().'.png'))
                return $path.$this->getExtension().'.png';
            else
                return $path.'unknown.png';
        }
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
            'sort'=>array('defaultOrder'=>'t.filename'),
        ));
    }

    protected function createImage($filepath, $format)
    {
        $imagepath = $this->getImagePath();
        $formats = $this->getImageFormats();
        $suffix = $formats[$format]['suffix'];

        //$size = $this->formats[$format]['process']['resize'];
        $max_height = $formats[$format]['max_height'];
        $max_width = $formats[$format]['max_width'];

        //$image = Yii::app()->thumb->load($filepath);
        $image = Yii::app()->image->load($filepath);
        if($formats[$format]['action'] == 'resize_h')
            $image->resize($max_width, $max_height, Image::HEIGHT);
        if($formats[$format]['action'] == 'resize_w')
            $image->resize($max_width, $max_height, Image::WIDTH);

        $fname = $this->getRealFilename().$suffix.".".$this->getExtension();
        $helemaal = $imagepath.'/'.$fname;
        //echo $filesource;
        $image->save($helemaal);
    }
}