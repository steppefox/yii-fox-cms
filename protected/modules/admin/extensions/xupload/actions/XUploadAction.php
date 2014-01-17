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
                // Если нужно удалить файлы у существующей модели
                if($id){
                    $model = $this->formModel->findByPk($id);
                    $json = json_decode($model->{$this->fileAttribute},true);

                    foreach ($json as $key => $image) {
                        if($image==$name){
                            unset($json[$key]);
                        }
                    }
                    $model->{$this->fileAttribute} = json_encode(array_values($json));
                    $model->save();
                }else{
                    $model = $this->formModel;
                    $files = Yii::app()->user->getState($this->stateVariable,array());
                    foreach ($files as $key => $image) {
                        if($image['name']==$name){
                            unset($files[$key]);
                        }
                    }
                    Yii::app()->user->setState($this->stateVariable,array_values($files));
                }
                $options = (method_exists($model, 'options'))?$model->options():false;
                if($options && is_array($options) && isset($options[$field])){
                    foreach ($options[$field] as $sizeKeyname => $sizeValue) {
                        if(is_file($this->path.'/'.$this->formClass.'/'.$sizeKeyname.'/'.$name)){
                            unlink($this->path.'/'.$this->formClass.'/'.$sizeKeyname.'/'.$name);
                        }
                    }
                    unlink($this->path.'/'.$this->formClass.'/original/'.$name);
                }else{
                    if(is_file($this->path.'/'.$this->formClass.'/'.$name)){
                        unlink($this->path.'/'.$this->formClass.'/'.$name);
                    }
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
                foreach ((array)$filesList as $mdhash => $file) {
                    $fileStatus = true;
                    if(is_file($this->path."/".$this->formClass."/original/".$file)){
                        $originalPath = $this->path."/".$this->formClass."/original/".$file;
                        $fullPath = $this->publicPath."/".$this->formClass."/original/".$file;
                        $thumbnailPath = $this->publicPath."/".$this->formClass."/thumbnail/".$file;
                    }elseif(is_file($this->path."/".$this->formClass."/".$file)){
                        $originalPath = $this->path."/".$this->formClass."/".$file;
                        $fullPath = $this->publicPath."/".$this->formClass."/".$file;
                        $thumbnailPath="data:image/png;base64,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";
                        //$thumbnailPath = Yii::app()->getBaseUrl().'/public/image/file-icon.png';
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
                $fileInfo = FileHelper::saveFile($model,$uploadedFile,$this->fileAttribute);
                if(is_file($this->path."/".$this->formClass."/original/".$fileInfo['filename'])){
                    $originalPath = $this->publicPath."/".$this->formClass."/original/".$fileInfo['filename'];
                    $thumbnailPath = $this->publicPath."/".$this->formClass."/thumbnail/".$fileInfo['filename'];
                }else{
                    $originalPath = $this->publicPath."/".$this->formClass."/".$fileInfo['filename'];
                    $thumbnailPath="data:image/png;base64,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";
                    //$thumbnailPath = Yii::app()->getBaseUrl().'/public/image/file-icon.png';
                }
                $file = array(
                    "name" => $fileInfo['filename'],
                    "type" => $uploadedFile->getType(),
                    "size" => $uploadedFile->getSize(),
                    "url" => $originalPath,
                    "hash"=>$fileInfo['hash'],
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
                    // $model->{$this->fileAttribute} = json_encode($json);
                    // $model->save();
                    $model->saveAttributes(array($this->fileAttribute=>json_encode($json)));
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