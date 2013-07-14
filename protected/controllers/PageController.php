<?php
/**
 * This controller renders all the content the right way.
 */
class PageController extends Controller
{
    /**
     * Renders every static content item as a page
     */
    public function actionContent($category, $alias)
    {
        $model = Content::model()->published()->findByAttributes(array('alias' => $alias));

        if ($model == null)
            throw new CHttpException(404, 'De pagina die u probeert te bezoek bestaat niet (meer)');

        if ($keywords = trim($model->meta_keywords))
            Yii::app()->clientScript->registerMetaTag($keywords, 'keywords');

        if ($description = trim($model->meta_description))
            Yii::app()->clientScript->registerMetaTag($description, 'description');

        if ($title = (!empty($model->meta_title)) ? $model->meta_title : $model->title)
            $this->pageTitle = $title;
        
        $layout = 'content';
        $path = Yii::app()->theme->baseUrl. '/views/page/page-'.$category.".php";
        if(file_exists("..".$path)) //TODO: only for local server actions
             $layout = 'page-'.$category;
        
        
        $this->render($layout, array('model' => $model));
    }
    
    public function actionLocation($alias)
    {
        
    }

    /**
     * Renders all content items in a category in a blog like way
     */
    public function actionCategory($alias)
    {
        $alias = Yii::t('lang', $alias, array(), 'nl', 'en');
        
        $layout = 'category';
        $path = Yii::app()->theme->baseUrl. '/views/page/'.$alias.".php";
        if(file_exists("..".$path)) //TODO: only for local server actions
             $layout = $alias;
        
        $model = Category::model()->with('content:published')->findByAttributes(array('alias' => $alias));
        if ($model == null)
            throw new CHttpException(404, 'The requested page does not excists');

        $this->render($layout, array(
            'model' => $model,
        ));
    }

    public function actionProjects()
    {
        $project_category = Category::model()->findByAttributes(array('alias' => 'projects'));
        if ($project_category == null)
            throw new CHttpException(404, 'The requested page does not excists');

        if(Yii::app()->administration->show_shared_projects)
            $items = Content::model()->resetScope()->published()->with('categories')->together()->findAll(array(
                'condition'=>"categories.alias = 'projects'",
                'order'=>'create_date DESC',
            ));
        else {
            $items = Content::model()->published()->with('categories')->together()->findAll(array(
                'condition'=>"categories.alias = 'projects'",
                'order'=>'create_date DESC',
            ));
        }

        $this->render('projects', array('models' => $project_category, 'items'=>$items));
    }
}

