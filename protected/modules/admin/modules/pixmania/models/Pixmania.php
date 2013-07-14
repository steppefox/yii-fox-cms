<?php

/**
 * This is the model class for table "pixmania".
 *
 * The followings are the available columns in table 'pixmania':
 * @property string $category
 * @property string $sub_category
 * @property string $subsub_category
 * @property string $code
 * @property string $brand
 * @property string $title
 * @property string $description
 * @property string $price_discount
 * @property string $delivery_costs
 * @property string $price_before_discount
 * @property string $picture_url
 * @property string $availability
 * @property string $volumetric_weight
 * @property string $weight
 */
class Pixmania extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Pixmania the static model class
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
        return 'pixmania';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('code, title', 'required'),
            array('category, sub_category, subsub_category, brand, title, picture_url', 'length', 'max'=>255),
            array('code, price_discount, delivery_costs, price_before_discount, availability, volumetric_weight, weight', 'length', 'max'=>45),
            array('description', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('category, sub_category, subsub_category, code, brand, title, description, price_discount, delivery_costs, price_before_discount, picture_url, availability, volumetric_weight, weight', 'safe', 'on'=>'search'),
        );
    }
    
    public function getPriceText()
    {
        return Yii::app()->numberFormatter->formatCurrency($this->price_discount, 'EUR');
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'product' => array(self::HAS_ONE, 'Product', 'sku'),
        );
    }
    
    public function getHasProductText()
    {
        return ($this->product != null);
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'category' => 'Category',
            'sub_category' => 'Sub Category',
            'subsub_category' => 'Subsub Category',
            'code' => 'Code',
            'brand' => 'Merk',
            'title' => 'Titel',
            'description' => 'Beschrijving',
            'price_discount' => 'Price Discount',
            'delivery_costs' => 'Delivery Costs',
            'price_before_discount' => 'Price Before Discount',
            'picture_url' => 'Picture Url',
            'availability' => 'Availability',
            'volumetric_weight' => 'Volumetric Weight',
            'weight' => 'Weight',
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

        $criteria=new CDbCriteria;
        //$criteria->with = array('product');

        //$criteria->compare('price_discount',$this->product->stock_price,true);
        $criteria->compare('category',$this->category,true);
        $criteria->compare('sub_category',$this->sub_category,true);
        $criteria->compare('subsub_category',$this->subsub_category,true);
        $criteria->compare('code',$this->code,true);
        $criteria->compare('brand',$this->brand,true);
        $criteria->compare('title',$this->title,true);
        $criteria->compare('description',$this->description,true);
        $criteria->compare('price_discount',$this->price_discount,true);
        $criteria->compare('delivery_costs',$this->delivery_costs,true);
        $criteria->compare('price_before_discount',$this->price_before_discount,true);
        $criteria->compare('picture_url',$this->picture_url,true);
        $criteria->compare('availability',$this->availability,true);
        $criteria->compare('volumetric_weight',$this->volumetric_weight,true);
        $criteria->compare('weight',$this->weight,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
    
    public function priceChange()
    {
         $criteria=new CDbCriteria;
        $criteria->with = array('product');
        $criteria->together = true;
        $criteria->condition = 't.price_discount > product.stock_price + 2 OR t.price_discount < product.stock_price - 2';
        
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
} 