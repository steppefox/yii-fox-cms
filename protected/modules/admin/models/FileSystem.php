<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class FileSystem
{
    
    const FILE_FOLDER = 'files';
    
    public $text;
    public $expanded = true;
    public $id;
    public $hasChildren;
    public $children = array();
    public $itemCount = 0;
    private $treelink='';
    
    private $_path;
    
    public function FileSystem($path)
    {
        $this->_path = $path;
    }
    
    public static function getMediaFolders()
    {
        return array(
            'shared'=>array(
                'name'=>'Shared Files',
                'folder'=>'shared',
            ),
            'products'=>array(
                'name'=>'Product Catalog',
                'folder'=>'product',
                'readonly'=>!Yii::app()->administration->isHQ(),
            ),
            'pages'=>array(
                'name'=>'Pages',
                'folder'=>'content/'.Yii::app()->administration->id,
            ),
        );
    }
    
    public static function createFolder($path)
    {
        $path = self::FILE_FOLDER.DIRECTORY_SEPARATOR.$path;
        
        if(!is_dir($path))
        {
            if(mkdir($path))
                return basename($path);
            else
                return false;
        }
        else
            throw new CException('directory already excist');
    }
    
    public static function deleteFolder($path)
    {
        $path = self::FILE_FOLDER.DIRECTORY_SEPARATOR.$path;

        if(is_dir($path))
        {
            return rmdir($path); //fails when not empty
        }
        else
            throw new CException('directory does not excist');
    }
    
    public static function isDeleteAble($path)
    {
        $dir = @CFile::set(self::FILE_FOLDER.DIRECTORY_SEPARATOR.$path);
        return $dir->isEmpty;
    }
    
    /**
     * Delete a file from the database and filesystem
     * @param string $path = path starting from FILE_FOLDER
     * @param string $filename = name of the file
     */
    public function delete($path, $filename = null)
    {
        $file_name = !empty($filename) ? basename(stripslashes($filename)) : null;
        $file_path = YiiBase::getPathOfAlias('webroot').DIRECTORY_SEPARATOR.self::FILE_FOLDER.DIRECTORY_SEPARATOR.$file_name;
        //$file_path = $this->options['upload_dir'].$file_name;
        $success = is_file($file_path) && $file_name[0] !== '.' && unlink($file_path);
        if ($success)
        {   //Delete all image format that where cached
            foreach($this->options['image_versions'] as $version => $options) {
                $file = $options['upload_dir'].$file_name;
                if (is_file($file)) {
                    unlink($file);
                }
            }
        }
        header('Content-type: application/json');
        echo json_encode($success);
    }
    
    public function addMissingFiles($data)
    {

        $dataProvider=$data->search();
        
        $dir = @CFile::set(self::FILE_FOLDER.DIRECTORY_SEPARATOR.$this->_path);
        if (!$dir->exists)
            throw new CHttpException(404, 'kon map niet vinden');

        if(!$dir->isEmpty)
        {
            foreach($dir->contents as $item)
            {
                $file = @CFile::set($item);
                if($file->isFile)
                {
                    $found = false;
                    foreach($dataProvider->data as $media)
                    {
                        //echo $item. "<br>";
                        //echo $media->fullPath. "<br>";
                        if($item == $media->fullPath)
                            $found = true;
                    }
                    //File not found in database
                    if(!$found)
                        self::addFile($file, 1, $this->_path);
                }
            }
        }
    }
    
    public static function addFile($file, $owner = 1, $path = '')
    {
        $media = new Media;
        $media->name = $file->filename;
        $media->filename = $file->filename.".".$file->extension;
        $media->path = $path;
        $media->file_type = $file->mimetype;
        $media->file_size = $file->getSize(false);
        $media->create_date = date('Y-m-d H:m:s');
        $media->administration_id = $owner; //When added like this it alwayw belongs to headquarter
        if(!$media->save())
        {
            Yii::log(print_r($media->errors, true), 'error');
        }
    }

    public function getTreeItems()
    {
        $path = '';//self::FILE_FOLDER;
        
        list($itemCount, $children) = self::getDirsFromDir($path);
        
        return $children;
   
    }
    
    public static function getTreeDirs($folder)
    {
        $children = self::getTreeView($folder);
        
        return $children;
   
    }
    
    public static function getWriteblePathDropdown()
    {
        $data = self::getMediaFolders();
        
        $dirs = array();
        foreach($data as $mediaFolder)
        {
            if((!isset($mediaFolder['readonly']) || !$mediaFolder['readonly']) && file_exists(self::FILE_FOLDER.DIRECTORY_SEPARATOR.$mediaFolder['folder']))
            {
                $folders = array($mediaFolder['folder']=>$mediaFolder['name']) + self::getDropDown($mediaFolder['folder']);
                $dirs = array_merge($dirs,$folders);
            }
        }
        return $dirs;
    }
    
    private static function getDropDown($main_dir, $level = 1)
    {
        $dirs = scandir(self::FILE_FOLDER.DIRECTORY_SEPARATOR.$main_dir);
        $array = array();
        foreach ($dirs as $file)
        {
            if($file === '.' || $file === '..') {continue;} 
            //Excludes the owner and his children
            $dir = $main_dir.'/'.$file;
            if(is_dir(self::FILE_FOLDER.DIRECTORY_SEPARATOR.$dir))
            {
                $dashes = "";
                for($i = 0; $i < $level; $i++)
                        $dashes .= "--";

                $array[$dir] = $dashes . " " .$file;

                $array = $array + self::getDropDown($dir, $level+1);
            }
        }
        return $array;
    }
    
    private static function getTreeView($main_dir)
    {
        $result = array();

        if(!file_exists(self::FILE_FOLDER.DIRECTORY_SEPARATOR.$main_dir))
             return array();
        $root = scandir(self::FILE_FOLDER.DIRECTORY_SEPARATOR.$main_dir); 
        foreach($root as $file)
        {
            if($file === '.' || $file === '..') {continue;} 
            
            $dir = $main_dir.'/'.$file;
            if(is_dir(self::FILE_FOLDER.DIRECTORY_SEPARATOR.$dir))
            {
                $children = self::getTreeView($dir);
                $path = substr($dir, 6);

                $options=array('href'=>Yii::app()->controller->createUrl('/admin/file/files',array('path'=>urlencode($dir))),'class'=>'folder');
                $nodeText = CHtml::openTag('a', $options);
                $nodeText.= $file;
                $nodeText.= CHtml::closeTag('a');


                $folder = array(
                    'text'=>$nodeText,
                    'id'=>urlencode($dir),
                    'expanded'=>false,
                    'hasChildren'=>!empty($children),
                    'children'=>$children,
                );
                $result[] = $folder;
            }
            
        }

        return $result;
    }
    /*
    private static function getDirsFromDir($main_dir, $treelink)
    {
        $result = array();
        
        //if ($dhandle = opendir(self::FILE_FOLDER.DIRECTORY_SEPARATOR.$dir))
        //{
            $root = scandir(self::FILE_FOLDER.DIRECTORY_SEPARATOR.$main_dir); 
            foreach($root as $file) { //while (false !== ($file = scandir(self::FILE_FOLDER.DIRECTORY_SEPARATOR.$dir))) {
                if ($file !== '.' && $file !== '..') 
                { 
                    $dir = $main_dir.'/'.$file;
                    if(is_dir(self::FILE_FOLDER.DIRECTORY_SEPARATOR.$dir))
                    {
                        $children = self::getDirsFromDir($dir, $treelink);
                        $path = substr($dir, 6);
                        
                        $options=array('href'=>Yii::app()->controller->createUrl($treelink,array('path'=>urlencode($dir))),'class'=>'folder');
                        $nodeText = CHtml::openTag('a', $options);
                        $nodeText.= $file;
                        $nodeText.= CHtml::closeTag('a');
                        
                        
                        $folder = array(
                            'text'=>$nodeText, // . " (".$itemCount.")",
                            'id'=>urlencode($dir),
                            'expanded'=>false,
                            'hasChildren'=>!empty($children),
                            //'children'=>$children,
                        );
                        $result[] = $folder;
                    }
                    else
                    {
                        $itemCount++;
                    }
                }
            }

        return $result;
    } 
    */
    public function getFiles($path)
    {
        //TODO: return all the files in a directory
    }
}
?>
