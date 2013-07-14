<?php

/**
 * This controller takes care of rendering all static pages
 */
class SiteController extends Controller
{

    /**
     * Render the homepage
     */
    public function actionIndex()
    {

        // $this->pageTitle = (!empty($model->meta_title)) ? $model->meta_title : $model->title;
        // $this->metaKeywords = $model->meta_keywords;
        // $this->metaDescription = $model->meta_description;

        $this->render('index', array('model'=>$model));
    }

    public function actionSitemap()
    {
			$criteria = new CDbCriteria(array('select'=>'t.alias, t.title'));
			$categories = Category::model()->with(array('content'=>array('select'=>'title, alias')) )->findAll($criteria);
			echo "<ul>";
			foreach($categories as $cat)
			{
				echo "<li>".$cat->name."</li>";
				foreach($cat->content as $content)
				{
					echo $content->getUrl();
				}
			}
			echo "</ul>";
        //Load all categories and content name + alias

				//Load all shop categories
			$this->render('sitemap', array('categories'=>$categories, 'shop'=>$shop));
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        if ($error = Yii::app()->errorHandler->error)
        {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

}