<?php

/**
 * FolderForm class.
 * FolderForm is the data structure for keeping
 * create new media folder data. It is used by the 'addFolder' action of 'FileController'.
 */
class FolderForm extends CFormModel
{
	public $name;
	public $path;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			// name, email, subject and body are required
			array('name, path', 'required'),
                        array('name', 'application.modules.admin.components.validators.EFolderValidator'),
			// email has to be a valid email address
			//array('email', 'email'),
		);
	}

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
                    'name'=>Yii::t('lang', 'Folder Name'),
                    'path'=>Yii::t('lang', 'Parent Folder'),
                    
		);
	}
        
        public function createFolder()
        {
            if($this->validate())
            {
                //TODO: check if folder Exists
                //TODO: check for javascript injection
                return FileSystem::createFolder($this->path . '/' . $this->name);
            }
            else
                return false;
        }
}