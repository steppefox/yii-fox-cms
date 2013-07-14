<?php

/**
 * QuestionForm class.
 * QuestionForm is the data structure for keeping
 * question form data. It is used by the 'question' action of 'SiteController'.
 */
class QuestionForm extends CFormModel
{
    public $company;
    public $name;
    public $address;
    public $postalcode;
    public $place;
    public $country;
    public $telephone;
    public $email;
    public $wish_appointment = 1;
    public $wish_phone_contact;
    public $wish_newsletter = 0;
    public $body;

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return array(
            // name, email, subject and body are required
            array('name, address, postalcode, place, country, telephone, email', 'required'),
            // email has to be a valid email address
            array('email', 'email'),
            array('company, body', 'safe'),
            array('wish_appointment, wish_phone_contact, wish_newsletter', 'boolean'),
        );
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
    public function attributeLabels()
    {
        return array(
            'company' => Yii::t('lang', 'Company'),
            'name' => Yii::t('lang', 'Name'),
            'address' => Yii::t('lang', 'Address'),
            'name' => Yii::t('lang', 'Name'),
            'postalcode' => Yii::t('lang', 'Zip code'),
            'place' => Yii::t('lang', 'Place'),
            'country' => Yii::t('lang', 'Country'),
            'telephone' => Yii::t('lang', 'Phone number'),
            'email' => Yii::t('lang', 'E-Mail'),
            'wish_appointment' => Yii::t('lang', 'An appointment with advisor'),
            'wish_phone_contact' => Yii::t('lang', 'Have phone contact'),
            'wish_newsletter' => Yii::t('lang', 'Sign up for the newsletter'),
            'body' => Yii::t('lang', 'Your question'),
        );
    }

}