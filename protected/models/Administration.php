<?php

/**
 * This is the model class for table "administration".
 *
 * The followings are the available columns in table 'administration':
 * @property integer $id
 * @property string $name
 * @property string $title
 * @property string $place
 * @property string $postalcode
 * @property string $address
 * @property string $email
 * @property string $phone_nb
 * @property string $fax_nb
 * @property string $domain
 * @property string $subdomain
 * @property integer $active
 * @property string $country_code
 * @property boolean $share_projects
 * @property boolean $show_shared_projects
 *
 * The followings are the available model relations:
 */
class Administration extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Administration the static model class
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
		return 'administration';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, language, country_code, title', 'required'),
      //array('map_x, map_y', 'numerical', 'integerOnly'=>true),
			//array('active, show_shared_projects, share_projects', 'boolean'),
			array('active', 'boolean'),
			array('name, title, place, address, email, domain, google_maps_key', 'length', 'max'=>100),
      array('subdomain', 'length', 'max'=>45),
			array('postalcode', 'length', 'max'=>10),
			array('phone_nb, fax_nb', 'length', 'max'=>25),
			array('country_code', 'length', 'max'=>3),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, name, place, postalcode, address, email, phone_nb, fax_nb, domain, country_code, language', 'safe', 'on'=>'search'),
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
                    'users' => array(self::HAS_MANY, 'User', 'administration_id'),
                    'userCount' => array(self::STAT, 'User', 'administration_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
            return array(
                'id' => 'ID',
                'title' => Yii::t('lang', 'Title'),
                'name' => Yii::t('lang', 'Name'),
                'place' => Yii::t('lang', 'Place'),
                'postalcode' => Yii::t('lang', 'Postalcode'),
                'address' => Yii::t('lang', 'Address'),
                'email' => Yii::t('lang', 'Email'),
                'phone_nb' => Yii::t('lang', 'Telephone'),
                'fax_nb' => Yii::t('lang', 'Fax'),
                'domain' => 'Website Url',
                'active' => Yii::t('lang', 'Active'),
                'country_code' => Yii::t('backend', 'Country Code'),
                'show_shared_projects' => Yii::t('backend', 'Show shared projects'),
                'share_projects' => Yii::t('backend', 'Share my projects'),
            );
	}

        public function getLink()
        {
            $dot = (!empty($this->subdomain)) ? "." : "";
            return "http://".$this->subdomain.$dot.$this->domain;
        }

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('place',$this->place,true);
		$criteria->compare('postalcode',$this->postalcode,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('phone_nb',$this->phone_nb,true);
		$criteria->compare('fax_nb',$this->fax_nb,true);
		$criteria->compare('domain',$this->domain,true);
		$criteria->compare('active',$this->active);
		$criteria->compare('country_code',$this->country_code,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
			'pagination'=>array(
		        'pageSize'=>200,
		    ),
		));
	}
}