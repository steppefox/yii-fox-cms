<?php

class Navigation extends CActiveRecord
{
	const TOP_MENU = 1;
	const BOT_MENU = 2;
	const LEFT_MENU = 3;
	const RIGHT_MENU = 4;

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function modelTitle()
	{
		return 'Навигация';
	}

	public function tableName()
	{
		return 'data_Navigation';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('ancestors, url, title_ru, type, parent_id', 'required'),
			array('parent_id, weight, status', 'numerical', 'integerOnly'=>true),
			array('type', 'numerical', 'integerOnly'=>true,'message'=>'Выберите меню'),
			array('ancestors', 'length', 'max'=>255),
			array('url', 'length', 'max'=>150),
			array('title_ru', 'length', 'max'=>50),
			array('id, parent_id, ancestors, type, url, title_ru, weight, status', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
            'parent'=> array(self::BELONGS_TO, __CLASS__, 'parent_id'),
            'child'=>array(self::HAS_MANY, __CLASS__, 'parent_id'),
            'childCount'   => array(self::STAT, __CLASS__, 'parent_id')
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'parent_id' => 'Уровень',
			'ancestors' => 'Предки',
			'type' => 'Выберите меню',
			'url' => 'Адрес',
			'title_ru' => 'Заголовок',
			'weight' => 'Порядок',
			'status' => 'Видимость',
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('parent_id',$this->parent_id);
		$criteria->compare('ancestors',$this->ancestors,true);
		// $criteria->compare('type',$this->type);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('title_ru',$this->title_ru,true);
		$criteria->compare('status',$this->status);
		$criteria->order = 'weight ASC';
        $criteria->with = 'childCount';
        $pagination = array('pageSize'=> 30);
        return new CActiveDataProvider($this,array(
            'criteria'   => $criteria,
            'pagination' => $pagination
        ));
	}

	public static function getMeTree(){
    	function findTree($arr=array(),$id=0,$s=''){
	        $ms=self::model()->findAll(array('condition'=> '`parent_id`='.$id,'order'=>'title_ru','select'=>array('id','title_ru')));
	        foreach ($ms as $m){
	            $arr[]=array('title_ru'=>$s.$m->title_ru,'id'=>$m->id);
	            if(self::model()->count(array('condition'=> '`parent_id`='.$m->id,'order'=>'title_ru','select'=>array('id','title_ru')))>0){
	                $arr=findTree($arr,$m->id,($s.$m->title_ru.' - '));
	            }
	        }
	        return $arr;
	    }
	    $catsList=findTree(
	        array(0=>array('title_ru'=>'Веберите меню','id'=>0)),0
	    );
	    return $catsList;
    }

    public function getTitle(){
    	$title = 'title_'.Yii::app()->language;
    	return $this[$title];
    }

    public function getParents() {
        return json_decode((string)$this->ancestors,true);
    }

    protected $_oldA;
    public function beforeValidate() {

    	$p    = array();
        $this->_oldA = $this->ancestors;
        if ($this->parent_id > 0) {
            $p   = $this->parent->parents;
            $p[] = (string)$this->parent_id;
        }
        $this->ancestors = json_encode((array)$p);

        return true;
    }

    public function afterSave(){
    	if ($this->_oldA != $this->ancestors && !$this->isNewRecord) {
	        //Получаем все страницы в которых узлом фигурировала текущая модель
	        $pages = $this->child;
	        if($pages){
		        foreach ($pages as $page) {
		        	$page->save();
		        }
	        }
	    }
    }

	public function options()
	{
        return array();
	}
}