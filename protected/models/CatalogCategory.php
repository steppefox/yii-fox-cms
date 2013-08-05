<?php

class CatalogCategory extends CActiveRecord
{
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'data_CatalogCategory';
    }

    public function rules()
    {
        return array(
            array('title_ru', 'required'),
            array('status,parent_id,created_at,updated_at', 'numerical', 'integerOnly' => true),
            array('title_ru', 'length', 'max' => 255),
            array('image','length','max'=>65535),
            array('id, title_ru, status, parent_id', 'safe', 'on' => 'search'),
        );
    }

    public function relations()
    {
        return array(
            'parentCategory' => array(self::BELONGS_TO, __CLASS__, 'parent_id'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'parent_id'=>Yii::t('backend', 'Родительская категория'),
            'title_ru' => Yii::t('backend', 'Название категории'),
            'image'=> Yii::t('backend', 'Изображение'),
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
        $criteria->compare('parent_id', $this->parent_id);
        $criteria->compare('status', $this->status);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
            'pagination'=>array(
                'pageSize'=>30,
            ),
        ));
    }

    public function getTitle(){
        $title = 'title_'.Yii::app()->language;
        return $this[$title];
    }

    public static function getAdminCategoryListArray(){
        $res = array(0=>'Не выбрано');
        $models = self::model()->findAll();
        foreach ($models as $model) {
            $res[$model->id] = $model->title;
        }
        return $res;
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