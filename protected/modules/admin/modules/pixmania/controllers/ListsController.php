<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class ListsController extends AdminController 
{
    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow',  // allow all users to perform 'index' and 'view' actions
                'actions'=>array('priceChange', 'stockChange', 'notFound'),
                'users'=>array('@'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }
    /**
     * List all model that have a different price 
     */
    public function actionPriceChange()
    {
        $model=new Pixmania('search');
        
        $this->render('priceRaise',array(
            'model'=>$model,
        ));
    }
    
    public function actionStockChange()
    {
        $model=new Product('search');
        
        $this->render('stockChange',array(
            'model'=>$model,
        ));
    }
    
    public function actionNotFound()
    {
        $model=new Product('search');
        
        $this->render('notFound',array(
            'model'=>$model,
        ));
    }
}
?>
