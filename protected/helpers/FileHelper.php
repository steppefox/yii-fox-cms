<?php

class FileHelper extends CComponent {
    const UPLOAD_PATH = "upload";

    public static function loadFile($model,$field){
        $deleteList = $_POST[get_class($model).'-'.$field.'-delete'];
        $deleteHashes = array();
        // Грёбаный фикс от XUpload
        if(!$model->isNewRecord){
            $model = CActiveRecord::model(get_class($model))->findByPk($model->id);
        }

        if(is_array($model->{$field})){
            $currentJson = $model->{$field};
        }else{
            $currentJson = (array)json_decode($model->{$field},true);
        }

        if($deleteList){
            foreach ($deleteList as $hash => $deleteStatus) {
                if($deleteStatus==1){
                    $deleteHashes[] = $hash;
                }
            }
        }

        foreach ($currentJson as $hash => $filename) {
            if(in_array($hash, $deleteHashes)){
                self::removeFiles($model,$field,false,$filename);
                unset($currentJson[$hash]);
            }
        }

        $sources = CUploadedFile::getInstancesByName(get_class($model).'['.$field.']');

        foreach ($sources as $source) {
            $model->{$field} = $source;
            if($source!=null && $model->validate(array($field))){
                $savedInfo = self::saveFile($model,$source,$field);
                $currentJson[$savedInfo['hash']] = $savedInfo['filename'];
            }
        }

        if($model->isNewRecord){
            $uploadedFiles = (array)Yii::app()->user->getState('image'.get_class($model)."Upload");
            foreach ($uploadedFiles as $uploadedFile) {
                $currentJson[$uploadedFile['hash']] = $uploadedFile['name'];
            }
            Yii::app()->user->setState('image'.get_class($model)."Upload",array());
        }

        return $currentJson;
    }

	public static function saveFile( $model, $source, $field) {
        $modelClass = get_class( $model );
        if(!is_dir( self::UPLOAD_PATH.'/' . $modelClass)){
            mkdir( self::UPLOAD_PATH.'/' . $modelClass, 0777, true );
        }else{
            if((substr(sprintf('%o', fileperms(self::UPLOAD_PATH.'/'.$modelClass)), -4))!='0777'){
                chmod(self::UPLOAD_PATH.'/'.$modelClass, 0777);
            }
        }

        $options = array();
        if(method_exists($model, 'options')){
            $options = $model->options(); //Запрос опций прописанных в модели
            $options = (isset($options[$field]))?$options[$field]:array(); // Опции поля
        }
        $images = array();

        //---------------------------------------------------------

        $UPimage = $source;
        if ($UPimage !== NULL){
            $path = pathinfo( $UPimage->name );
            if ( $model->id ){
                $id = $model->id;
            }else{
                $id = 'temp'.md5(time());
            }
            //fileHash - не обнулять, он используется как ключ на выходе из функции
            $fileHash = md5(file_get_contents($UPimage->getTempName()));
            $id .= '-'.$fileHash;
            $fileName = $modelClass . '-' .$field .'-' . $id .  '.' . strtolower( $path["extension"] );
            if (strstr($UPimage->type,'image') && $options && is_array($options)){
                Yii::import('ext.image.*');
                $originalFilePath = self::UPLOAD_PATH.'/'.$modelClass.'/original';
                if(!is_dir($originalFilePath)){
                    mkdir($originalFilePath, 0777, true );
                }
                $UPimage->saveAs( $originalFilePath."/".$fileName );
                $imageOrig = new Image( $originalFilePath."/".$fileName );
                foreach ($options as $sizeKeyname => $size){
                    $image = $imageOrig;
                    if (!is_dir(self::UPLOAD_PATH.'/'.$modelClass.'/'.$sizeKeyname)){
                        mkdir( self::UPLOAD_PATH.'/' . $modelClass.'/'.$sizeKeyname, 0777, true );
                    }else{
                        if((substr(sprintf('%o', fileperms(self::UPLOAD_PATH.'/'.$modelClass.'/'.$sizeKeyname)), -4))!='0777'){
                           chmod(self::UPLOAD_PATH.'/'.$modelClass.'/'.$sizeKeyname, 0777);
                        }
                    }
                    $image = self::resizeImage($image,$size['width'],$size['height'],$size['type']);

                    $image->save( self::UPLOAD_PATH.'/'.$modelClass.'/'.$sizeKeyname . '/'.$fileName );
                    chmod(self::UPLOAD_PATH.'/'.$modelClass.'/'.$sizeKeyname.'/'.$fileName, 0777);
                }
                //unlink( $originalFilePath );
                unset ( $image );
                unset ( $imageOrig );
            }else{
                //Если для файла не было настроек или он не изображение
                $UPimage->saveAs( self::UPLOAD_PATH.'/' . $modelClass . '/' . $fileName );
            }

            return array(
                'filename'=>$fileName,
                'hash'=>$fileHash,
            );
        }else{
            // 2013-12-20 10-49 steppefox: Фикс для Feedback Add
            // throw new Exception("File Source is Null");
        }
    }

    public static function resizeImage($image, $width, $height, $type="resize"){
        $w = $image->width;
        $h = $image->height;

        $r = ($width/$height); //соотношение сторон необходимого изображения
        /*Проверка, если <, значит оригинальное изображение выше (длиннее) или уже по соотношению,
         * чем нужное, нужно обрезать его по высоте
         */

        if(($w/$h)<$r) $rt = true; else $rt = false;
        if($type === 'crop' ){ //Если выбрана Обрезка изображения
            if ( $w > $width || $h > $height ) { //Если изображение больше по одному из параметров
                if ( $w>$h ) { //Изображение горизонтальное
                    if ( $rt )
                        $image->resize( $width, $height, 4 );
                    else
                        $image->resize( $width, $height, 3 );
                        $image->crop( $width, $height );
                } else {
                    if ( !$rt ){
                        $nw = $h*$r;
                        $image->crop($nw, $h, 0, round(($w-$nw)/2));
                        $image->resize( $width, $height, 3 );
                    }else{
                        $nw = $w; //new width
                        $nh = $h; // new height
                        while($nw>$width&&(($nw/$r)>$h)){
                            $nw=$nw-1;
                        }
                        $image->crop($nw, $nw/$r, 0 );
                        $image->resize( $width, $height, 3 );
                    }
                }
            }
        } elseif ( $w > $width || $h > $height){
            $image->resize( $width, $height );
        }
        return $image;
    }

    /**
     * removeFiles
     *
     * @param $model          Модель
     * @param $field          Поле с файлами
     * @param $removeAllFiles Флаг удаления всех файлов
     * @param $targetFile           Поле для удаления конкретного файла
     */
    public static function removeFiles($model, $field, $removeAllFiles=true, $targetFile=false) {
        $removedFilesCount = 0;


        // Выдергиваем список всех файлов
        if(is_array($model->{$field})){
            $json = $model->{$field};
        }else{
            $json = json_decode($model->{$field},true);
        }

        $removeFilesList = array();
        if($removeAllFiles==TRUE){
            $removeFilesList = $json;
            $json = array();
        }elseif($removeAllFiles==FALSE && $targetFile!=''){
            $removeFilesList[] = $targetFile;
            foreach ($json as $key => $image) {
                if($image==$targetFile){
                    unset($json[$key]);
                }
            }
        }

        // Сохраняем изменения в поле модели
        $model->saveAttributes(array($field=>json_encode($json)));

        $formClass = get_class($model);
        $options = (method_exists($model, 'options'))?$model->options():false;
        foreach ($removeFilesList as $name) {
            if($options && is_array($options) && isset($options[$field])){
                foreach ($options[$field] as $sizeKeyname => $sizeValue) {
                    if(is_file(self::UPLOAD_PATH.'/'.$formClass.'/'.$sizeKeyname.'/'.$name)){
                        unlink(self::UPLOAD_PATH.'/'.$formClass.'/'.$sizeKeyname.'/'.$name);
                    }
                }
                unlink(self::UPLOAD_PATH.'/'.$formClass.'/original/'.$name);
            }else{
                if(is_file(self::UPLOAD_PATH.'/'.$formClass.'/'.$name)){
                    unlink(self::UPLOAD_PATH.'/'.$formClass.'/'.$name);
                }
            }

            $removedFilesCount++;
        }

        return $removedFilesCount;
    }

}