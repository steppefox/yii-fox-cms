<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id
 * @property string $login
 * @property string $password
 * @property string $nicename
 * @property string $email
 * @property string $register_date
 * @property integer $role
 * @property integer $administration_id
 *
 * The followings are the available model relations:
 * @property Administration $administration
 */
class User extends CActiveRecord
{

    public $password_repeat;
    //public $salt = "zouthe";

    const ROLE_USER = 0;
    const ROLE_MODERATOR = 1;
    const ROLE_MANAGER = 2;
    const ROLE_ADMIN = 3;

    public function getRoles()
    {
        return array(
            self::ROLE_USER => 'User',
            self::ROLE_MODERATOR => 'Moderator',
            self::ROLE_MANAGER => 'Manager',
            self::ROLE_ADMIN => 'Administrator',
        );
    }

    public function getRoleText()
    {
        $roles = $this->roles;
        return isset($roles[$this->role]) ? $roles[$this->role] : "unknown type ({$this->role})";
    }

    /**
     * Returns the static model of the specified AR class.
     * @return User the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function getRegisterDateText()
    {
        return Yii::app()->dateFormatter->formatDateTime($this->register_date, 'long');
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'user';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('login, email, role', 'required'),
            array('role, administration_id', 'numerical', 'integerOnly' => true),
            array('login', 'length', 'max' => 60, 'min' => 3),
            array('login', 'unique'),
            array('password, password_repeat', 'length', 'max' => 100, 'min' => 6),
            array('password, password_repeat', 'required', 'on' => 'register'),
            array('nicename', 'length', 'max' => 50),
            array('email', 'length', 'max' => 100),
            array('email', 'email'),
            array('password_repeat', 'compare', 'compareAttribute' => 'password'),
            array('administration_id, register_date', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, login, nicename, email, register_date, role, administration_id', 'safe', 'on' => 'search'),
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
            'administration' => array(self::BELONGS_TO, 'Administration', 'administration_id'),
        );
    }

    

    protected function beforeSave()
    {
        if (parent::beforeSave())
        {
            if(!empty($this->password))
                $this->password = $this->hashPassword($this->password);
            if ($this->isNewRecord)
                $this->register_date = date('Y-m-d H:i:s');
            return true;
        }
        else
            return false;
    }

    public function validatePassword($password)
    {
        return $this->hashPassword($password) === $this->password;
    }

    public function hashPassword($password)
    {
        return md5($password);
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'login' => 'Login',
            'password' => 'Password',
            'password_repeat' => 'Repeat password',
            'nicename' => 'Nicename',
            'email' => 'Email',
            'url' => 'Url',
            'register_date' => 'Register Date',
            'role' => 'Role',
            'administration_id' => 'Administration',
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
        $criteria->compare('login', $this->login, true);
        $criteria->compare('nicename', $this->nicename, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('url', $this->url, true);
        $criteria->compare('register_date', $this->register_date, true);
        $criteria->compare('role', $this->role);
        $criteria->compare('administration_id', $this->administration_id);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
        ));
    }

}