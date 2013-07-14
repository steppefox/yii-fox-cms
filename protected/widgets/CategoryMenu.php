<?php
//Yii::import('zii.widgets.CPortlet');

class CategoryMenu extends CWidget
{
    public $alias;
    public $layout = "/page/content";
    public $limit = 10;
    public $sort = 'create_date DESC';

    public function run()
    {
        $category = Category::model()->with('content:published')->findByAttributes(array('alias'=>$this->alias));
        if($category == null){
            //throw new CException ('Category could nog be found by alias: ' . $this->alias, 500);
        }else{
            $this->render('categoryMenu', array('category'=>$category, 'limit'=>$this->limit));
        }
    }

    public function findContent()
    {
        return Content::model()->with('categories')->together()->findAll(array(
            'condition'=>'categories.alias = \''.$this->alias. '\'',
            'order'=>$this->sort,
            'limit'=>$this->limit,
        ));

    }
}