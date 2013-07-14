<?php

/**
 * This is the model class for table "category".
 *
 * The followings are the available columns in table 'category':
 * @property integer $id
 * @property string $title
 * @property string $alias
 * @property integer $count
 * @property integer $parent_id
 */
class Category extends CActiveRecord
{
    public function getName()
    {
        return $this->title;
    }
    /**
     * Returns the static model of the specified AR class.
     * @return Content the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'category';
    }
    
    public function hasContent()
    {
        return !empty($this->content);
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('title, alias', 'required'),
            array('active', 'numerical', 'integerOnly' => true),
            array('title, alias', 'length', 'max' => 100),
            array('description, parent_id', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, title, alias, count, description, active, parent_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'content' => array(self::MANY_MANY, 'Content', 'content_category(category_id, content_id)', 'order' => 'content.create_date DESC'),
            'itemCount' => array(self::STAT, 'Content', 'content_category(category_id, content_id)'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'title' => Yii::t('backend', 'Title'),
            'alias' => Yii::t('backend', 'Page link'),
            'description' => Yii::t('backend', 'Description'),
            'active' => Yii::t('backend', 'Active'),
            'parent_id' => Yii::t('backend', 'Parent category'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('alias', $this->alias, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('active', $this->active, true);
        $criteria->compare('parent_id', $this->parent_id);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
        ));
    }

    public $children;

    public function behaviors()
    {
        return array('CAdjacencyBehavior' => array(
                'class' => 'application.components.CAdjacencyBehavior'
        ));
    }
}