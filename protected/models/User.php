<?php

class User extends CActiveRecord
{

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'data_User';
    }

    public function rules()
    {
        return array(
            array('login, email', 'required'),
            array('login', 'length', 'max' => 60, 'min' => 3),
            array('login', 'unique'),
            array('password', 'length', 'max' => 100, 'min' => 6),
            array('nicename', 'length', 'max' => 50),
            array('email', 'length', 'max' => 100),
            array('email', 'email'),
            array('id, login, nicename, email', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(

        );
    }



    protected function beforeSave()
    {
        if (parent::beforeSave())
        {
            return true;
        }
        else
            return false;
    }

    public function validatePassword($password)
    {
        return $this->hashPassword($password) === $this->password;
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
            'nicename' => 'Nicename',
            'email' => 'Email',
        );
    }

    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('login', $this->login, true);
        $criteria->compare('nicename', $this->nicename, true);
        $criteria->compare('email', $this->email, true);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
        ));
    }

}