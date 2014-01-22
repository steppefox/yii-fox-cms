<?php

class News extends CActiveRecord
{

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'data_News';
	}

	public static function modelTitle() {
		return 'News';
	}

	public function rules()
	{
		return array(
			array('title_ru, text_ru', 'required'),
			array('is_visible', 'numerical', 'integerOnly'=>true),
			array('parent_NewsCategory_id, created_at, updated_at', 'length', 'max'=>10),
			array('title_ru,title_kz,title_en,title_ko', 'length', 'max'=>255),
			array('description_ru,description_kz,description_en,description_ko', 'length', 'max'=>255),
			array('text_ru,text_kz,text_en,text_ko', 'length', 'max'=>65535),
			array('image', 'length', 'max'=>255),
			array('id, parent_NewsCategory_id, title_ru, is_visible, created_at, updated_at', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'parent_NewsCategory_id' => 'Parent News Category',
			'title_ru' => 'Title Ru',
			'is_visible' => 'Is Visible',
			'created_at' => 'Created At',
			'updated_at' => 'Updated At',
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('parent_NewsCategory_id',$this->parent_NewsCategory_id,true);
		$criteria->compare('title_ru',$this->title_ru,true);
		$criteria->compare('is_visible',$this->is_visible);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('updated_at',$this->updated_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function beforeSave(){
        if($this->created_at==0){
            $this->created_at=time();
        }
        $this->updated_at = time();
        return true;
    }

	public function getTitle(){
		$lang=Yii::app()->language;
		$f='title_'.$lang;
		return $this->$f;
	}


	public function options(){
		return array(

		);
	}

}