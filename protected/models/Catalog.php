<?php

class Catalog extends CActiveRecord
{
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
        return array(
            array('title_ru', 'required'),
            array('parent_CatalogCategory_id,price,created_at,updated_at', 'numerical', 'integerOnly' => true),
            array('title_ru,description_ru', 'length', 'max' => 255),
            array('image,text_ru','length','max'=>65535),
            array('is_visible, is_static','numerical','integerOnly'=>true),
            array('id, title_ru, description_ru, text_ru, parent_CatalogCategory_id', 'safe', 'on' => 'search'),
        );
    }

    public function relations()
    {
        return array(
            'parentCategory' => array(self::BELONGS_TO, 'Category', 'parent_CatalogCategory_id'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'parent_Action_id'=>Yii::t('backend', 'Акция'),
            'parent_CatalogCategory_id'=>Yii::t('backend', 'Категория'),
            'title_ru' => Yii::t('backend', 'Наименование товара'),
            'description_ru' => Yii::t('backend', 'Краткое описание'),
            'text_ru' => Yii::t('backend', 'Полное описание'),
            'image'=> Yii::t('backend', 'Изображение'),
            'price'=> Yii::t('backend', 'Цена'),
            'created_at'=> Yii::t('backend', 'Дата создания'),
            'updated_at'=> Yii::t('backend', 'Дата изменения'),
            'is_visible'=> Yii::t('backend', 'Видимость'),
        );
    }

    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('title_ru', $this->title_ru, true);
        // $criteria->compare('description_ru', $this->description_ru, true);
        // $criteria->compare('text_ru', $this->text_ru, true);
        // $criteria->compare('parent_CatalogCategory_id', $this->parent_CatalogCategory_id);
        $criteria->compare('is_visible', $this->is_visible);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
            'pagination'=>array(
                'pageSize'=>30,
            ),
        ));
    }

    public function getPrice(){
        $price = $this->price;
        return $price;
    }

    public static function getNicePrice($price){
        return preg_replace('/\B(?=(\d{3})+(?!\d))/', ' ', $price);
    }

    public function at($attribute){
        $attribute = $attribute.'_'.Yii::app()->language;
        return $this[$attribute];
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

    public function options()
    {
        return array(
            'image' => array(
                'full' => array(
                    'width' => 440,
                    'height' => 520,
                    'type' => 'crop'
                ),
                'big' => array(
                    'width' => 220,
                    'height' => 260,
                    'type' => 'crop'
                ),
                'sm'=> array(
                    'width' => 134,
                    'height' => 134,
                    'type' => 'crop'
                ),
                'thumbnail'=>array(
                    'width' => 150,
                    'height' => 150,
                    'type' => 'crop'
                ),
            ),
        );
    }


}