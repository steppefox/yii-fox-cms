<?php

class Catalog extends CActiveRecord
{
    public function getName()
    {
        return $this->title;
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'data_Catalog';
    }

    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('title_ru', 'required'),
            array('status, parent_CatalogCategory_id,price,created_at,updated_at', 'numerical', 'integerOnly' => true),
            array('title_ru,description_ru', 'length', 'max' => 255),
            array('image,text_ru','length','max'=>65535),
            array('id, title_ru, description_ru, text_ru, status, parent_CatalogCategory_id', 'safe', 'on' => 'search'),
        );
    }

    public function relations()
    {
        return array(
            'content' => array(self::BELONGS_TO, 'Category', 'parent_CatalogCategory_id'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'title_ru' => Yii::t('backend', 'Наименование товара'),
            'description_ru' => Yii::t('backend', 'Краткое описание'),
            'text_ru' => Yii::t('backend', 'Полное описание'),
            'image'=> Yii::t('backend', 'Изображение'),
            'price'=> Yii::t('backend', 'Цена'),
            'created_at'=> Yii::t('backend', 'Дата создания'),
            'updated_at'=> Yii::t('backend', 'Дата изменения'),
            'status'=> Yii::t('backend', 'Видимость'),
        );
    }

    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('title_ru', $this->title_ru, true);
        $criteria->compare('description_ru', $this->description_ru, true);
        $criteria->compare('text_ru', $this->text_ru, true);
        $criteria->compare('parent_CatalogCategory_id', $this->parent_CatalogCategory_id);
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
}