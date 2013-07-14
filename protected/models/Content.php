<?php

/**
 * This is the model class for table "content".
 *
 * The followings are the available columns in table 'content':
 * @property integer $id
 * @property string $title
 * @property string $alias
 * @property string $description
 * @property string $meta_description
 * @property string $create_date
 * @property string $update_date
 * @property string $meta_title
 * @property string $meta_keywords
 * @property integer $status
 * @property boolean $static
 *
 * The followings are the available model relations:
 * @property Media[] $medias
 */
class Content extends XActiveRecord
{
    const CONTENT_TYPE = 1;

    const STATUS_PUBLISHED=0;
    const STATUS_DRAFT=1;
    const STATUS_ARCHIVED=2;
    //const STATUS_SHARED=3;

    private $showall = false;
    
    /**
     * Returns the static model of the specified AR class.
     * @return Content the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    protected function afterConstruct()
    {
        if(!Yii::app()->user->isGuest)
            $this->administration_id = Yii::app()->user->administration;

        $this->create_date = date('Y-m-d H:m:s');
    }

    protected function afterFind()
    {
            parent::afterFind();
            $this->create_date = Yii::app()->dateFormatter->format('dd-MM-yyyy', $this->create_date);
    }

    public function getImage($type)
    {
        foreach ($this->media as $media)
        {
            if ($media->type == $type)
            {
                return $media;
            }
        }
        return false;
    }

    public function getThumb()
    {
        $media = $this->mediaLinks;
        foreach ($media as $id => $item)
            if ($item->type == ContentMedia::MEDIA_HEADER_IMAGE)
                return $item->media->getImageUrl('thumb');

        return false;
    }

    public function getShortDescription($limit = 100)
    {
        $varlength = strlen($this->description);
        if($limit < $varlength)
        {
            $string = str_replace('&nbsp;', ' ', $this->description);
            $string = trim(strip_tags($string,"<br>"));
            //return $string;
            return substr($string, 0, $limit) . '...';
        }
        return strip_tags(str_replace('<br>', ' ', $this->description));
    }

    /**
     * Items for the dropdown where the user cna select the status of the content
     */
    public function getStatusOptions()
    {
        return array(
            self::STATUS_PUBLISHED => Yii::t('backend', 'Published'),
            self::STATUS_DRAFT => Yii::t('backend', 'Draft'),
            self::STATUS_ARCHIVED => Yii::t('backend', 'Archived'),
        //self::STATUS_SHARED=>'Globaly published',
        );
    }

    public function getStatusText()
    {
        $statusOptions = $this->statusOptions;
        return isset($statusOptions[$this->status]) ? $statusOptions[$this->status] : "unknown status ({$this->status})";
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'content';
    }

    /**
     * This is a validation rule for the rules() function
     */
    public function select_one()
    {
        if($this->categories == array())
            $this->addError('categories',Yii::t('backend', 'Select at least one category'));
    }
    
    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('administration_id, title, alias, description, status, create_date', 'required'),
            array('administration_id, status', 'numerical', 'integerOnly' => true),
            array('title, alias, meta_title', 'length', 'max' => 100),
            array('static', 'boolean'),
            array('categories', 'select_one'),
            array('meta_description, meta_keywords, template, product_id', 'safe'),
            array('alias', 'match', 'pattern'=>'/^[a-z0-9-]+$/', 'message'=>'Alleen kleine letters, nummers en streepjes (-)'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, title, alias, description, summary, create_date, update_date, meta_keywords, status', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'mediaLinks'=>array(self::HAS_MANY,'ContentMedia','content_id'),
            'mediaItems'=>array(self::HAS_MANY,'Media','media_id','through'=>'mediaLinks'),
            
            //'mediaItems' => array(self::HAS_MANY, 'Media', 'content_key', 'condition' => "content_type = 1", 'index' => 'id'),
            'categories' => array(self::MANY_MANY, 'Category', 'content_category(content_id, category_id)'),
            'product' => array(self::BELONGS_TO, 'Product', 'product_id', 'with'=>'translation'),
            //TODO: turn project on when using this class for action4kids
            'administration' => array(self::BELONGS_TO, 'Administration', 'administration_id'),
        //'comments' => array(self::HAS_MANY, 'Comment', 'content_id', 'order'=>'comments.create_time DESC'),
        //'commentCount' => array(self::STAT, 'Comment', 'content_id'),
        );
    }

    public function defaultScope()
    {
        if(isset($_GET['alias']) && $_GET['alias'] == "projects" || isset($_GET['category']) && $_GET['category'] == "projects") //TODO: cleanup danger
            return array();
        else
            return array('condition' => "administration_id='" . Yii::app()->administration->id . "'",);
    }

    public function scopes()
    {
        return array(
            'published'=>array(
                'condition'=>'status='.self::STATUS_PUBLISHED,
            ),
        );
    }
    public function showall()
    {
        $this->showall = true;
        return $this;
    }
    
    public function isPublished()
    {
        $this->status == self::STATUS_PUBLISHED;
    }

    public function behaviors()
    {
        return array(
            'withRelated' => array('class' => 'application.components.WithRelatedBehavior',),
            //'CAdvancedArBehavior' => array('class' => 'application.components.CAdvancedArBehavior')
            //'CSaveRelationsBehavior' => array(
             //   'class' => 'application.components.CSaveRelationsBehavior',
                //'deleteRelatedRecords'=>false
              //  )
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
            'description' => Yii::t('backend', 'Content'),
            'meta_description' => Yii::t('backend', 'Description'),
            'create_date' => Yii::t('backend', 'Create date'),
            'update_date' => Yii::t('backend', 'Update date'),
            'meta_keywords' => Yii::t('backend', 'Keywords'),
            'meta_title' => 'Meta '. Yii::t('backend', 'Title'),
            'status' => Yii::t('backend', 'Status'),
            'product_id' => Yii::t('backend', 'Linked with:'),
        );
    }
    
    protected function beforeValidate()
    {
        if(parent::beforeValidate())
        {
            //Read media item from post
            if(isset($_POST['ContentMedia']))
            {
                $mediaItems = array();
                $media =(is_array(@$_POST['ContentMedia'])) ? $_POST['ContentMedia'] : array();
                foreach($media as $key => $atts)
                {
                    $mediaLink = new ContentMedia();
                    $mediaLink->markedDeleted = $atts['markedDeleted'];
                    if(strpos($key,'-'))
                        $mediaLink->isNewRecord = false;
                    $mediaLink->attributes = $atts;
                    $mediaLink->content_id = $this->id;
                    $mediaItems[] = $mediaLink;
                }
                $this->mediaLinks = $mediaItems;
            }
            if(isset($_POST['Categories']))
            {
                $categories = (is_array(@$_POST['Categories'])) ? $_POST['Categories'] : array();
                $this->categories = Category::model()->findAllByPk($categories);
            }
            if($this->isNewRecord)
                $this->alias = CSlugging::slug($this->title);
            return true;
        }
        else
            return false;
    }

    /**
     * This is invoked before the record is saved.
     * @return boolean whether the record should be saved.
     */
    protected function beforeSave()
    {
        if (parent::beforeSave())
        {
            if ($this->isNewRecord)
            {
                $this->administration_id = Yii::app()->administration->id;
                $this->create_date = $this->update_date = date('Y-m-d H:m:s');
                //$this->user_id=Yii::app()->user->id; //TODO: uncomment when users work
            }
            else
            {
        	$timestamp = CDateTimeParser::parse($this->create_date, 'dd-MM-yyyy');
                $this->create_date = CTimestamp::formatDate('Y-m-d', $timestamp);
                
                //$this->update_date = date('Y-m-d H:m:s');
            }
            return true;
        }
        else
            return false;
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

        $criteria->join = "LEFT JOIN content_category ON content_category.content_id=id";
        if(empty($this->searchCategory))
            $criteria->addCondition('category_id IS NULL');
        else
            $criteria->compare('category_id', $this->searchCategory);

        //$criteria->compare('administration_id', $this->administration_id, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('meta_description', $this->meta_description, true);
        $criteria->compare('create_date', $this->create_date, true);
        $criteria->compare('meta_keywords', $this->meta_keywords, true);
        $criteria->compare('status', $this->status);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
        ));
    }
    public $searchCategory;

    public function getCreateDateText()
    {
        if($this->isNewRecord)
            return "-";
        else
            return Yii::app()->dateFormatter->formatDateTime($this->create_date, 'long', null);
    }

    public function getUpdateDateText()
    {
        if($this->isNewRecord)
            return "-";
        else
            return Yii::app()->dateFormatter->formatDateTime($this->update_date, 'long');
    }

    /*
    public function getMedia()
    {
        $details = $this->getRelation('mediaItems');
        //$details = $this->mediaItems;
        $result = array();
        foreach ($details as $id => $item)
        {
            if (!$item->markDeleted)
                $result[] = $item;
        }
        return $result;
    }
     * 
     */

    public function getUrl($category = null)
    {
        //$category='test';
        if(empty($category) && isset($_GET['alias']))
            $category = $_GET['alias'];
				if($category == null)
            $category = $this->categories[0]->alias;
        return Yii::app()->controller->createUrl('/page/content/', array('category'=>$category, 'alias'=>$this->alias));
    }

    /**
     * @param integer the maximum number of comments that should be returned
     * @return array the most recently added comments
     */
    public function findRecentItems($limit=10)
    {
        return $this->findAll(array(
            'condition' => 't.status=' . self::STATUS_PUBLISHED,
            'order' => 't.create_date DESC',
            'limit' => $limit,
        ));
    }
    
    public function findRecentProjects($limit=10)
    {
        return $this->findAll(array(
            'condition' => 't.status=' . self::STATUS_PUBLISHED,
            'order' => 't.create_date DESC',
            'limit' => $limit,
        ));
    }
}