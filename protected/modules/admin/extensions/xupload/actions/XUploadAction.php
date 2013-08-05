<?php

/**
 * XUploadAction
 * =============
 * Basic upload functionality for an action used by the xupload extension.
 *
 * XUploadAction is used together with XUpload and XUploadForm to provide file upload funcionality to any application
 *
 * You must configure properties of XUploadAction to customize the folders of the uploaded files.
 *
 * Using XUploadAction involves the following steps:
 *
 * 1. Override CController::actions() and register an action of class XUploadAction with ID 'upload', and configure its
 * properties:
 * ~~~
 * [php]
 * class MyController extends CController
 * {
 *     public function actions()
 *     {
 *         return array(
 *             'upload'=>array(
 *                 'class'=>'xupload.actions.XUploadAction',
 *                 'path' =>Yii::app() -> getBasePath() . "/../uploads",
 *                 'publicPath' => Yii::app() -> getBaseUrl() . "/uploads",
 *                 'subfolderVar' => "parent_id",
 *             ),
 *         );
 *     }
 * }
 *
 * 2. In the form model, declare an attribute to store the uploaded file data, and declare the attribute to be validated
 * by the 'file' validator.
 * 3. In the controller view, insert a XUpload widget.
 *
 * ###Resources
 * - [xupload](http://www.yiiframework.com/extension/xupload)
 *
 * @version 0.3
 * @author Asgaroth (http://www.yiiframework.com/user/1883/)
 */
class XUploadAction extends CAction {

    /**
     * XUploadForm (or subclass of it) to be used.  Defaults to XUploadForm
     * @see XUploadAction::init()
     * @var string
     * @since 0.5
     */
    public $formClass = 'xupload.models.XUploadForm';

    /**
     * Name of the model attribute referring to the uploaded file.
     * Defaults to 'file', the default value in XUploadForm
     * @var string
     * @since 0.5
     */
    public $fileAttribute = 'file';

    /**
     * Name of the model attribute used to store mimeType information.
     * Defaults to 'mime_type', the default value in XUploadForm
     * @var string
     * @since 0.5
     */
    public $mimeTypeAttribute = 'mime_type';

    /**
     * Name of the model attribute used to store file size.
     * Defaults to 'size', the default value in XUploadForm
     * @var string
     * @since 0.5
     */
    public $sizeAttribute = 'size';

    /**
     * Name of the model attribute used to store the file's display name.
     * Defaults to 'name', the default value in XUploadForm
     * @var string
     * @since 0.5
     */
    public $displayNameAttribute = 'name';

    /**
     * Name of the model attribute used to store the file filesystem name.
     * Defaults to 'filename', the default value in XUploadForm
     * @var string
     * @since 0.5
     */
    public $fileNameAttribute = 'filename';

    /**
     * The query string variable name where the subfolder name will be taken from.
     * If false, no subfolder will be used.
     * Defaults to null meaning the subfolder to be used will be the result of date("mdY").
     *
     * @see XUploadAction::init().
     * @var string
     * @since 0.2
     */
    public $subfolderVar;

    /**
     * Path of the main uploading folder.
     * @see XUploadAction::init()
     * @var string
     * @since 0.1
     */
    public $path;

    /**
     * Public path of the main uploading folder.
     * @see XUploadAction::init()
     * @var string
     * @since 0.1
     */
    public $publicPath;

    /**
     * @var boolean dictates whether to use sha1 to hash the file names
     * along with time and the user id to make it much harder for malicious users
     * to attempt to delete another user's file
     */
    public $secureFileNames = false;

    /**
     * Name of the state variable the file array is stored in
     * @see XUploadAction::init()
     * @var string
     * @since 0.5
     */
    public $stateVariable;

    /**
     * The resolved subfolder to upload the file to
     * @var string
     * @since 0.2
     */
    private $_subfolder = "";

    /**
     * The form model we'll be saving our files to
     * @var CModel (or subclass)
     * @since 0.5
     */
    private $_formModel;

    /**
     * Initialize the propeties of pthis action, if they are not set.
     *
     * @since 0.1
     */
    public function init( ) {

        if( !isset( $this->path ) ) {
            $this->path = realpath( Yii::app( )->getBasePath( )."/../upload" );
        }

        if( !is_dir( $this->path ) ) {
            mkdir( $this->path, 0777, true );
            chmod ( $this->path , 0777 );
            //throw new CHttpException(500, "{$this->path} does not exists.");
        } else if( !is_writable( $this->path ) ) {
            chmod( $this->path, 0777 );
            //throw new CHttpException(500, "{$this->path} is not writable.");
        }
        // if( $this->subfolderVar !== null ) {
        //     $this->_subfolder = Yii::app( )->request->getQuery( $this->subfolderVar, date( "mdY" ) );
        // } else if($this->subfolderVar !== false ) {
        //     $this->_subfolder = date( "mdY" );
        // }


        if($model = Yii::app()->request->getQuery("model")){
            $this->formClass = $model;
        }

        if($attribute = Yii::app()->request->getQuery("attribute")){
            $this->fileAttribute = $attribute;
        }

        if( !isset($this->_formModel)) {
            $this->formModel = Yii::createComponent(array('class'=>$this->formClass));
        }

        $this->stateVariable = $this->fileAttribute.$this->formClass."Upload";

        if($this->secureFileNames) {
            $this->formModel->secureFileNames = true;
        }
    }

    /**
     * The main action that handles the file upload request.
     * @since 0.1
     * @author Asgaroth
     */
    public function run( ) {

        $this->sendHeaders();

        $this->handleDeleting() or $this->handleUploading();
    }
    protected function sendHeaders()
    {
        header('Vary: Accept');
        if (isset($_SERVER['HTTP_ACCEPT']) && (strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)) {
            header('Content-type: application/json');
        } else {
            header('Content-type: text/plain');
        }
    }
    /**
     * Removes temporary file from its directory and from the session
     *
     * @return bool Whether deleting was meant by request
     */
    protected function handleDeleting()
    {
        $this->init();
        if (isset($_GET["_method"]) && $_GET["_method"] == "delete") {
            $name = Yii::app()->request->getQuery("name");
            $id = Yii::app()->request->getQuery("id");
            $field = Yii::app()->request->getQuery('attribute');
            if($name){
                if($id){
                    $model = $this->formModel->findByPk($id);
                    $json = json_decode($model->{$this->fileAttribute},true);
                    $options = (method_exists($model, 'options'))?$model->options():false;

                    foreach ($json as $key => $image) {
                        if($image==$name){
                            unset($json[$key]);
                        }
                    }
                    $model->{$this->fileAttribute} = json_encode(array_values($json));
                    $model->save();
                    if($options && is_array($options) && isset($options[$field])){
                        foreach ($options[$field] as $sizeKeyname => $sizeValue) {
                            if(is_file($this->path.'/'.$this->formClass.'/'.$sizeKeyname.'/'.$name)){
                                unlink($this->path.'/'.$this->formClass.'/'.$sizeKeyname.'/'.$name);
                            }
                        }
                    }else{
                        if(is_file($this->path.'/'.$this->formClass.'/'.$name)){
                            unlink($this->path.'/'.$this->formClass.'/'.$name);
                        }
                    }
                }else{
                    $files = Yii::app()->user->getState($this->stateVariable,array());
                    foreach ($files as $key => $image) {
                        if($image['name']==$name){
                            unset($files[$key]);
                        }
                    }
                    Yii::app()->user->setState($this->stateVariable,array_values($files));
                }

                return true;
            }

            return true;
        }
        return false;
    }

    /**
     * Uploads file to temporary directory
     *
     * @throws CHttpException
     */
    protected function handleUploading()
    {
        $this->init();
        if(Yii::app()->request->getQuery("load")){
            //Подгрузка уже сохраненных файлов
            if($id = Yii::app()->request->getQuery("id")){
                $model = $this->formModel->findByPk((int)$id);
                $filesList = json_decode($model->{$this->fileAttribute},true);
                $files = array();
                foreach ($filesList as $mdhash => $file) {
                    $fileStatus = true;
                    if(is_file($this->path."/".$this->formClass."/original/".$file)){
                        $originalPath = $this->path."/".$this->formClass."/original/".$file;
                        $fullPath = $this->publicPath."/".$this->formClass."/original/".$file;
                        $thumbnailPath = $this->publicPath."/".$this->formClass."/tm/".$file;
                    }elseif(is_file($this->path."/".$this->formClass."/".$file)){
                        $originalPath = $this->path."/".$this->formClass."/".$file;
                        $fullPath = $this->publicPath."/".$this->formClass."/".$file;
                        $thumbnailPath = Yii::app()->getBaseUrl().'/public/image/file-icon.png';
                    }else{
                        $fileStatus = false;
                    }
                    if($fileStatus){
                        $info = pathinfo($originalPath);
                        $size = filesize($originalPath);
                        //$files[] = $info;
                        $files[] = array(
                            "name" => $file,
                            "size" => $size,
                            "url" => $fullPath,
                            "thumbnail_url" => $thumbnailPath,
                            "delete_url" => Yii::app()->controller->createUrl(Yii::app()->controller->id."/upload",array(
                                "name"=>$file,
                                "_method"=>"delete",
                                "model"=>$this->formClass,
                                "attribute"=>$this->fileAttribute,
                                "id"=>$id
                            )),
                            "delete_type" => "POST",
                        );
                    }
                }
                echo json_encode($files);
            }else{
                echo json_encode(Yii::app()->user->getState($this->stateVariable,array()));
            }
        }else{
            // Закачка новых файлов
            $model = $this->formModel;
            if($id = Yii::app()->request->getQuery("id")){
                $model = $model->findByPk($id);
            }
            $model->setScenario("upload");
            $uploadedFile = CUploadedFile::getInstance($model, $this->fileAttribute);
            if ($uploadedFile !== null) {
                $fileInfo = AdminController::saveFile($model,$uploadedFile,$this->fileAttribute);
                if(is_file($this->path."/".$this->formClass."/original/".$fileInfo['filename'])){
                    $originalPath = $this->publicPath."/".$this->formClass."/original/".$fileInfo['filename'];
                    $thumbnailPath = $this->publicPath."/".$this->formClass."/tm/".$fileInfo['filename'];
                }else{
                    $originalPath = $this->publicPath."/".$this->formClass."/".$fileInfo['filename'];
                    $thumbnailPath = Yii::app()->getBaseUrl().'/public/image/file-icon.png';
                }
                $file = array(
                    "name" => $fileInfo['filename'],
                    "type" => $uploadedFile->getType(),
                    "size" => $uploadedFile->getSize(),
                    "url" => $originalPath,
                    "thumbnail_url" => $thumbnailPath,
                    "delete_url" => Yii::app()->controller->createUrl(Yii::app()->controller->id."/upload",array(
                        "name"=>$fileInfo['filename'],
                        "_method"=>"delete",
                        "model"=>$this->formClass,
                        "attribute"=>$this->fileAttribute,
                        "id"=>$id
                    )),
                    "delete_type" => "POST"
                );

                if(!$model->isNewRecord){
                    $json = json_decode($model->{$this->fileAttribute},true);
                    $json[$fileInfo['hash']] = $fileInfo['filename'];

                    $model->{$this->fileAttribute} = json_encode($json);
                    $model->save();
                }else{
                    $files = Yii::app()->user->getState($this->stateVariable,array());
                    $files[] = $file;
                    Yii::app()->user->setState($this->stateVariable,$files);
                }
                echo json_encode(array($file));
            } else {
                throw new CHttpException(500, "Could not upload file");
            }
        }
    }

    /**
     * We store info in session to make sure we only delete files we intended to
     * Other code can override this though to do other things with state, thumbnail generation, etc.
     * @since 0.5
     * @author acorncom
     * @return boolean|string Returns a boolean unless there is an error, in which case it returns the error message
     */
    protected function beforeReturn() {
        $path = $this->getPath();

        // Now we need to save our file info to the user's session
        $userFiles = Yii::app( )->user->getState( $this->stateVariable, array());

        $userFiles[$this->formModel->{$this->fileNameAttribute}] = array(
            "path" => $path.$this->formModel->{$this->fileNameAttribute},
            //the same file or a thumb version that you generated
            "thumb" => $path.$this->formModel->{$this->fileNameAttribute},
            "filename" => $this->formModel->{$this->fileNameAttribute},
            'size' => $this->formModel->{$this->sizeAttribute},
            'mime' => $this->formModel->{$this->mimeTypeAttribute},
            'name' => $this->formModel->{$this->displayNameAttribute},
        );
        Yii::app( )->user->setState( $this->stateVariable, $userFiles );

        return true;
    }

    /**
     * Returns the file URL for our file
     * @param $fileName
     * @return string
     */
    protected function getFileUrl($fileName) {
        return $this->getPublicPath()."original/".$fileName;
    }

    /**
     * Returns the file's path on the filesystem
     * @return string
     */
    protected function getPath() {
        $path = ($this->_subfolder != "") ? "{$this->path}/{$this->_subfolder}/" : "{$this->path}/";
        return $path;
    }

    /**
     * Returns the file's relative URL path
     * @return string
     */
    protected function getPublicPath() {
        return ($this->_subfolder != "") ? "{$this->publicPath}/{$this->_subfolder}/" : "{$this->publicPath}/";
    }

    /**
     * Deletes our file.
     * @param $file
     * @since 0.5
     * @return bool
     */
    protected function deleteFile($file) {
        return unlink($file['path']);
    }

    /**
     * Our form model setter.  Allows us to pass in a instantiated form model with options set
     * @param $model
     */
    public function setFormModel($model) {
        $this->_formModel = $model;
    }

    public function getFormModel() {
        return $this->_formModel;
    }

    /**
     * Allows file existence checking prior to deleting
     * @param $file
     * @return bool
     */
    protected function fileExists($file) {
        return is_file( $file['path'] );
    }
}