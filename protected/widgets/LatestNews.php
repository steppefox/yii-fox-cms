<?php
Yii::import('zii.widgets.CPortlet');
 
class LatestNews extends CPortlet
{
    public $title='Latest News';
    public $maxItems=10;
 
    public function getLatestNews()
    {
        return Content::model()->findRecentItems($this->maxItems);
    }
 
    protected function renderContent()
    {
        $this->render('latestNews');
    }
}