<?php

class CatalogController extends Controller
{
    public $defaultAction = 'list';
    public function actionList(){

        $crit = new CDbCriteria();
        $crit->condition = 't.status = 1';
        $crit->order = 't.created_at DESC';
        $pages = new CPagination(Catalog::model()->count($crit));
        $pages->pageSize = 20;
        $pages->applyLimit($crit);
        $models = (array)Catalog::model()->findAll($crit);
        $this->render('list',compact('models'));
    }


    public function actionCategory($id)
    {
    	$crit = new CDbCriteria();
    	$crit->condition = 't.status = 1 AND parent_CatalogCategory_id = :p_parent';
    	$crit->params = array(':p_parent'=>(int)$id);
    	$crit->order = 't.created_at DESC';
    	$pages = new CPagination(Catalog::model()->count($crit));
        $pages->pageSize = 20;
        $pages->applyLimit($crit);
        $models = (array)Catalog::model()->findAll($crit);
        $this->render('list',compact('models'));
    }

    public function actionShow($id){
        $crit = new CDbCriteria();
        $crit->condition = 'status = 1 AND id=:p_id';
        $crit->params = array(':p_id'=>(int)$id);
        $model = Catalog::model()->find($crit);
        if($model){
            $this->render('show',compact('model'));
        }else{
            throw new CHttpException(404,'Нет такого товара');

        }
    }
}