<?php

class Page extends CActiveRecord
{
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'data_Page';
    }

    public function rules()
    {
        return array(
            array('title_ru,name', 'required'),
            array('name','unique'),
            array('status, created_at,updated_at', 'numerical', 'integerOnly' => true),
            array('title_ru,name,description_ru', 'length', 'max' => 255),
            array('image,text_ru','length','max'=>65535),
            array('id, title_ru, description_ru, text_ru, status', 'safe', 'on' => 'search'),
        );
    }

    public function relations()
    {
        return array();
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name'=>Yii::t('backend', 'Уникальное имя'),
            'title_ru' => Yii::t('backend', 'Заголовок страницы'),
            'description_ru' => Yii::t('backend', 'Краткое описание'),
            'text_ru' => Yii::t('backend', 'Полное описание'),
            'image'=> Yii::t('backend', 'Изображение'),
            'created_at'=> Yii::t('backend', 'Дата создания'),
            'updated_at'=> Yii::t('backend', 'Дата изменения'),
            'status'=> Yii::t('backend', 'Видимость'),
        );
    }

    public function getTitle(){
        $title = 'title_'.Yii::app()->language;
        return $this[$title];
    }

    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('title_ru', $this->title_ru, true);
        $criteria->compare('description_ru', $this->description_ru, true);
        $criteria->compare('text_ru', $this->text_ru, true);
        $criteria->compare('status', $this->status);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
            'pagination'=>array(
                'pageSize'=>30,
            ),
        ));
    }

    public function beforeValidate(){
        return true;
    }

    public function beforeSave(){
        if($this->created_at==0){
            $this->created_at=time();
        }
        $this->updated_at = time();
        return true;
    }
}