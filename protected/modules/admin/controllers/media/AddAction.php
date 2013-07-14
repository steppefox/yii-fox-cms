<?php

/**
 * This action is called by the uploadify widget
 * Add images or download to a content item
 * It doesn't upload it yet. this is done on save.
 * File will remain in tmp till save button is pressed
 */
class AddAction extends CAction
{

    public $modelName; //String: The model name for adding media
    public $typeId; //Integer: The content type id

    public function run()
    {
        //Yii::log('PHP SESSIE LOG:  $_REQUEST = ' . $_REQUEST['PHPSESSID'] . ' $_POST = '. $_POST['PHPSESSID']. ' $_COOKIE = ' . $_COOKIE['PHPSESSID'], 'warning');

        $modelName = $this->modelName;
        $typeId = $this->typeId;

        if (empty($modelName) || empty($typeId))
            throw new CHttpException(500, 'Wrong call');

        if (!isset(Yii::app()->session[$modelName]))
            throw new CHttpException(404, 'Media could not be added, no product item loaded');

        if (!empty($_FILES))
        {
            $tempFile = $_FILES['Filedata']['tmp_name'];
            $tempStore = $_REQUEST['folder'] . '/temp/' . Yii::app()->session->sessionID . "/"; // sessionID is directory name for temp file
            $tempFilepath = $tempStore . str_replace(' ', '_', $_FILES['Filedata']['name']); // needed for thumb creation

            $path_info = pathinfo($_FILES['Filedata']['name']);
            $extensie = $path_info['extension'];
            $filename = $path_info['filename']; // PHP >= 5.2

            $media = new Media;
            $media->content_type = $typeId;
            $media->filename = str_replace(' ', '_', $_FILES['Filedata']['name']);
            $media->type = 0; // 0 = default for every content type
            $media->name = $filename;
            $media->url = $tempFilepath;
            Yii::app()->session[$modelName]->addHasManyRelation("mediaItems", $media, $media->id);

            //Create dir and upload image (to temp folder)
            //umask(0000);
            if (!is_dir(Yii::getPathOfAlias('webroot') . $tempStore))
                mkdir(Yii::getPathOfAlias('webroot') . $tempStore, 0777, true);
            chmod(Yii::getPathOfAlias('webroot') . $tempStore, 0777);
            
            move_uploaded_file($tempFile, Yii::getPathOfAlias('webroot') . $tempFilepath);
            chmod(Yii::getPathOfAlias('webroot') . $tempFilepath, 0777);
            
            $extensie = strtolower($extensie);
            if($extensie == "jpg" || $extensie == "gif" || $extensie == "png")
                $this->resizeImage(Yii::getPathOfAlias('webroot') . $tempFilepath, $tempStore);
        }
        echo "1";
    }

    /**
     * Resize the uploaded image to maximun width and height if bigger
     * Save the new image and remove original uploaded file
     */
    private function resizeImage($uploadedfile, $destination)
    {
        list($width,$height)=getimagesize($uploadedfile);
        $newwidth=Yii::app()->params['max_image_width'];
        $newheight=($height/$width)*$newwidth;
        /*
        if($newheight > Yii::app()->params['max_image_height'])
        {
            $newheight=Yii::app()->params['max_image_height'];
            $newwidth=($width/$height)*$newheight;
        }
        */
        if ($newwidth < $width || $newheight < $height)
        {
            Yii::app()->thumb->setThumbsDirectory($destination);
            $smallImage = Yii::app()->thumb->load($uploadedfile)->resize($newwidth, $newheight)->save();
            
        }
    }

}

?>
