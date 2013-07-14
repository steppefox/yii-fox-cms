<?php
/**
 * The File controller.
 * @author Michael de Hart
 *
 */
class FileController extends AdminController
{

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow',
                'actions' => array('index', 'files', 'addFile', 'filebrowser','PickerSelectFolder','PickerSelectFile', 
                    'link',  'UploadFilesPlupload', 'filePicker', 'treefill2', 'fileTree', 'createFolder', 'deleteFolder'),
                'expression' => '!$user->isGuest && $user->role >= User::ROLE_MODERATOR',
            ),
            array('allow',
                'actions' => array('properties', 'delete'),
                'expression' => '!$user->isGuest && $user->administration == 1',
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }
    
    public function actionIndex()
    {
        $filesystem = new FileSystem('files');

        $model = Media::model()->findByAttributes(array('path'=>'files'));

        $this->render('index', array('model'=>$model, 'filesystem'=>$filesystem));
    }
    
    public function actionFilePicker()
    {
        //Stop JQuery
        Yii::app()->clientScript->scriptMap=array(
            'jquery.js'=>false,
            'jquery.min.js'=>false,
            'jquery-ui.min.js'=>false,
            'jquery.ba-bbq.js'=>false,
        );
        //$filesystem = new FileSystem('files');

        //$models = Media::model()->findByAttributes(array('path'=>'files'));

        $this->renderPartial('gallery', null, false, true);
    }
    
    public function actionFiles($path)
    {
        //Stop JQuery
        Yii::app()->clientScript->scriptMap=array(
            'jquery.js'=>false,
            'jquery.min.js'=>false,
            'jquery-ui.min.js'=>false,
            'jquery.ba-bbq.js'=>false,
        );
        
        $model = new Media('search');
        $model->unsetAttributes();  // clear any default values
        
        $path = urldecode($path);
        
        $model->path = $path;
        
        //TODO: check for missing files? but to who do we add these?
        //$filesystem = new FileSystem($path);
        //$filesystem->addMissingFiles($model);
 
        $this->renderPartial('files', array('model'=>$model), false, true);
    }
    /*
    public function actionPickerSelectFolder($path)
    {
        //Stop JQuery
            Yii::app()->clientScript->scriptMap=array(
                'jquery.js'=>false,
                'jquery.min.js'=>false,
                'jquery-ui.min.js'=>false,
                'jquery.ba-bbq.js'=>false,
            );
            
        $path = urldecode($path);
        $models = Media::model()->findAllByAttributes(array('path'=>$path));
        
        $this->renderPartial('_folderSelect', array('models'=>$models), false, true);
    } */
    
    public function actionAddFile($class)
    {
        if(Yii::app()->request->isAjaxRequest)
        {
            //Stop JQuery
            Yii::app()->clientScript->scriptMap=array(
                'jquery.js'=>false,
                'jquery.min.js'=>false,
                'jquery-ui.min.js'=>false,
                'jquery.ba-bbq.js'=>false,
            );
            if(!isset($_POST['ids']))
                throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
            
            $files = Media::model()->findAllByPk($_POST['ids']);
            
            $models = array();
            foreach($files as $file)
            {
                $model = new $class;
                //$model->id = uniqid();
                $model->media_id = $file->id;
                $model->name = $file->name;
                $model->description = $file->description;
                $models[] = $model;
            }
            $this->renderPartial('mediaLink', array('models'=>$models));
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    } 
    
    public function actionCreateFolder()
    {
        //Stop JQuery
        Yii::app()->clientScript->scriptMap=array(
            'jquery.js'=>false,
            'jquery.min.js'=>false,
            'jquery-ui.min.js'=>false,
            'jquery.ba-bbq.js'=>false,
        );
        
        $model = new FolderForm();
        
        if(Yii::app()->request->isPostRequest && isset($_POST['FolderForm']))
        {
            Yii::app()->clientScript->scriptMap=array('*.js'=>false,);
            //ajax validation
            if(isset($_POST['ajax']) && $_POST['ajax']==='folder-form')
            {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }
            
            $model->attributes = $_POST['FolderForm'];
            if($model->createFolder())
                Yii::app()->end();
        }

        $this->renderPartial('formFolder', array('model'=>$model), false, true);
    }
    
    public function actionDeleteFolder($path)
    {
        if(FileSystem::deleteFolder(urldecode($path)))
        {
            Yii::app()->user->setFlash('success', 'Folder ' . basename(urldecode($path)) . ' was deleted'); 
            $this->redirect(array('index'));
        }
    }

    
    /*
    public function actionTreeFill() 
    {
        header('Content-type: application/json');
        if ($_GET['root'] == 'source')
            $id = urldecode($_GET['start']);
        else
            $id = urldecode($_GET['root']);

        $tree = FileSystem::getTreeDirs($id);
        echo CTreeView::saveDataAsJson($tree);
    }
    
    public function actionTreeFill2()
    {
        header('Content-type: application/json');
        if ($_GET['root'] == 'source')
            $id = urldecode($_GET['start']);
        else
            $id = urldecode($_GET['root']);

        $tree = FileSystem::getTreeDirs($id, '/admin/file/PickerSelectFolder');
        echo CTreeView::saveDataAsJson($tree);
    }
*/
    
    /**
     * Display a file select dialog
     * @param string $path files from what folder should be displayed?
     * @param int $item id of the item the media should go to
     */
    public function actionFileBrowser()
    {
        $path = '';
        
        //Stop JQuery
            Yii::app()->clientScript->scriptMap=array(
                'jquery.js'=>false,
                'jquery.min.js'=>false,
                'jquery-ui.min.js'=>false,
                'jquery.ba-bbq.js'=>false,
            );
        
        $path = urldecode($path);
        $model = new Media('search'); //::model()->findByAttributes(array('path'=>$path));
        $model->unsetAttributes();
        $model->path = $path;
        
        $filesystem = new FileSystem('');
        
        $this->renderPartial('fileBrowser', array('model'=>$model, 'filesystem'=>$filesystem), false, true);
    }

    
    /**
     * Display the selected file properties
     * @param int $id the pk of the media item
     */
    public function actionPickerSelectFile()
    {
        //Stop JQuery
            Yii::app()->clientScript->scriptMap=array(
                'jquery.js'=>false,
                'jquery.min.js'=>false,
                'jquery-ui.min.js'=>false,
                'jquery.ba-bbq.js'=>false,
            );
            
        if(Yii::app()->request->isAjaxRequest)
        {
            if(!isset($_POST['ids']))
                throw new CHttpException (400, 'You request is invalid');
            else
                $id = $_POST['ids'];
            
            if(count($id) > 1)
                $model = Media::model()->findAllByPk($id);
            else
                $model = Media::model()->findByPk($id);

            $this->renderPartial('_fileSelect', array('model'=>$model), false, true);
        }
        else
            throw new CHttpException (500, 'Bad request!');
    }
    
    /*
    public function actionUrlById($id)
    {
        $model = Media::model()->findByPk($id);
        echo $model->getImageUrl('thumb');
    }
     * 
     */
    
    /**
     *
     * @param string $type name of object
     * @param int $item id of the object
     
    public function actionLink($type, $item)
    {
        //TODO: create new objects of type $type and fill it with $_POST en $item
        if(Yii::app()->request->isPostRequest)
        {
            $success = true;
            foreach($_POST as $id => $value)
            {
                $locationMedia = new LocationMedia;
                $locationMedia->location_id = $item;
                $locationMedia->media_id = $id;
                $locationMedia->name = 'test';
                if(!$locationMedia->save())
                    $success = false;
            }
            // print_r($_POST);
            // echo $type;
            // echo $item;
        }
        else
            throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
    } */
    
    /**
     * Removes a linked media object grom the database
     * @param string $type The object that should be deleted
     * @param type $id  come as "itemid _ mediaid" and should be passed to the delete operator
     
    public function actionUnlink($type, $id)
    {
        list($id, $item) = explode("_", $id);
        if(LocationMedia::model()->deleteByPk(array('location_id'=>$id,'media_id'=>$item)))
            echo "done";
        else
            throw new CHttpException(500, 'Oeps er is iets fout gegaan.');
    }
     *
     * @param type $path */


    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        if(Yii::app()->request->isPostRequest)
        {
            // we only allow deletion via POST request
            // DeleteByPK() will not be able to unlink files, because the path is unknown
            $this->loadModel($id)->delete();
            echo 1;
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            //if(!isset($_GET['ajax']))
                //$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }
        else
            throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
    }
    
    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionProperties($id)
    {
        //Stop JQuery
        Yii::app()->clientScript->scriptMap=array(
            'jquery.js'=>false,
            'jquery.min.js'=>false,
            'jquery-ui.min.js'=>false,
            'jquery.ba-bbq.js'=>false,
        );

        $model = $this->loadModel($id);
        $this->performAjaxValidation($model);

        if(isset($_POST['Media']))
        {
            $model->attributes=$_POST['Media'];
            if($model->save())
            {
            	Yii::app()->user->setFlash('mediaSaved','File properties are successfully saved');
                Yii::app()->end();
            }
        }
        if (Yii::app()->request->isAjaxRequest)
        {
            $this->renderPartial('form',array('model'=>$model), false, true);
        }
    }

    public function loadModel($id)
    {
        $model = Media::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }
    
    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='content-category-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
    
    public function actionUploadFilesPlupload($path) {
        // HTTP headers for no cache etc
        header('Content-type: text/plain; charset=UTF-8');
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        $path = urldecode($path);
        // Settings
        $targetDir = YiiBase::getPathOfAlias('webroot') . '/files/' . $path . '/';
        //$targetDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . "plupload";

        $maxFileAge = 60 * 60; // Temp file age in seconds
        // 5 minutes execution time
        @set_time_limit(5 * 60);
        // usleep(5000);
        // Get parameters
        $chunk = isset($_REQUEST["chunk"]) ? $_REQUEST["chunk"] : 0;
        $chunks = isset($_REQUEST["chunks"]) ? $_REQUEST["chunks"] : 0;
        $fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';

        //$fileName = CSlugging::noaccent(utf8_decode($fileName));
        // Clean the fileName for security reasons
        $fileName = preg_replace('/[^\w\._\-\s]+/', '', $fileName);

        

        // Create target dir
        if (!file_exists($targetDir))
            @mkdir($targetDir);

        // Remove old temp files
        if (is_dir($targetDir) && ($dir = opendir($targetDir))) {
            while (($file = readdir($dir)) !== false) {
                $filePath = $targetDir . DIRECTORY_SEPARATOR . $file;

                // Remove temp files if they are older than the max age
                if (preg_match('/\\.tmp$/', $file) && (filemtime($filePath) < time() - $maxFileAge))
                    @unlink($filePath);
            }

            closedir($dir);
        } else
            throw new CHttpException(500, Yii::t('app', "Can't open temporary directory."));

        // Look for the content type header
        if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
            $contentType = $_SERVER["HTTP_CONTENT_TYPE"];

        if (isset($_SERVER["CONTENT_TYPE"]))
            $contentType = $_SERVER["CONTENT_TYPE"];
        // Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
        if (strpos($contentType, "multipart") !== false) {
            
            if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
                // Open temp file
                $out = fopen($targetDir . DIRECTORY_SEPARATOR . $fileName, $chunk == 0 ? "wb" : "ab");
                if ($out) {
                    // Read binary input stream and append it to temp file
                    $in = fopen($_FILES['file']['tmp_name'], "rb");

                    if ($in) {
                        while ($buff = fread($in, 4096))
                            fwrite($out, $buff);
                    } else
                        throw new CHttpException(500, Yii::t('app', "Can't open input stream."));
                    fclose($in);
                    fclose($out);
                    @unlink($_FILES['file']['tmp_name']);
                } else
                    throw new CHttpException(500, Yii::t('app', "Can't open output stream."));
            } else
                throw new CHttpException(500, Yii::t('app', "Can't move uploaded file."));
        } else {
            // Open temp file
            $out = fopen($targetDir . DIRECTORY_SEPARATOR . $fileName, $chunk == 0 ? "wb" : "ab");
            if ($out) {
                // Read binary input stream and append it to temp file
                $in = fopen("php://input", "rb");

                if ($in) {
                    while ($buff = fread($in, 4096))
                        fwrite($out, $buff);
                } else
                    throw new CHttpException(500, Yii::t('app', "Can't open input stream."));
                fclose($in);
                fclose($out);
            } else
                throw new CHttpException(500, Yii::t('app', "Can't open output stream."));
        }

        // After last chunk is received, process the file
        $ret = array('result' => '1');
        if (intval($chunk) + 1 >= intval($chunks)) {

            $originalname = $fileName;
            if (isset($_SERVER['HTTP_CONTENT_DISPOSITION'])) {
                $arr = array();
                preg_match('@^attachment; filename="([^"]+)"@', $_SERVER['HTTP_CONTENT_DISPOSITION'], $arr);
                if (isset($arr[1]))
                    $originalname = $arr[1];
            }

            // **********************************************************************************************
            // Do whatever you need with the uploaded file, which has $originalname as the original file name
            // and is located at $targetDir . DIRECTORY_SEPARATOR . $fileName
            // **********************************************************************************************
            $file = @CFile::set($targetDir . $fileName);
            
            FileSystem::addFile($file, Yii::app()->administration->id, $path);
        }

        // Return response
        die(json_encode($ret));
    }

}