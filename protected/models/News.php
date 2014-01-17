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
			array('title_ru', 'required'),
			array('is_visible', 'numerical', 'integerOnly'=>true),
			array('parent_NewsCategory_id', 'length', 'max'=>10),
			array('title_ru', 'length', 'max'=>255),
			array('updated_at,created_at', 'numerical', 'integerOnly'=>true),
			array('id, parent_NewsCategory_id, title_ru, is_visible', 'safe', 'on'=>'search'),
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
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('parent_NewsCategory_id',$this->parent_NewsCategory_id,true);
		$criteria->compare('title_ru',$this->title_ru,true);
		$criteria->compare('is_visible',$this->is_visible);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
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