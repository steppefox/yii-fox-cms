<?php

/**
 * This action is called by the uploadify widget
 * It uploads the file and adds a media object to the correct session
 */
class DeleteAction extends CAction
{
    public $modelName = null; //The model name for adding media

    public function run()
    {
        $modelName = $this->modelName;

        if(empty($modelName))
             throw new CHttpException(500, 'Wrong call');

        if (Yii::app()->request->isPostRequest)
        {
            // we only allow deletion via POST request
            if (!isset($_GET['id']))
                throw new CHttpException(500, 'No id for media to delete given');

            Yii::app()->session[$modelName]->deleteHasManyRelation("mediaItems", $_GET['id']);

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }
}

?>
