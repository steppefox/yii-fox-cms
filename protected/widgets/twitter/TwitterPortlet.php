<?php
Yii::import('zii.widgets.CPortlet');
 
class TwitterPortlet extends CPortlet
{
    public $title='Laatste tweets';
    public $maxItems=10;
    public $username='';
    public $tweetCount = 5;
 
    protected function renderContent()
    {
        $this->publishAssets();
        $id = $this->getId();
        
        Yii::app()->clientScript->registerScript(__CLASS__.'#'.$id,"$('#{$id}_t').getTwitter({
		userName: '{$this->username}',
		numTweets: {$this->tweetCount},
		loaderText: 'Loading tweets...',
		slideIn: true,
		showHeading: false,
                showTimestamp: false,
		showProfileLink: false
	});", CClientScript::POS_READY);

        echo '<div id="'.$id.'_t"></div>';
    }
    
    private function publishAssets()
    {
        $assets = dirname(__FILE__).'/assets';
	$baseUrl = Yii::app()->assetManager->publish($assets);
        
        Yii::app()->clientScript->registerScriptFile($baseUrl . '/jquery.twitter.js', CClientScript::POS_HEAD);
	Yii::app()->clientScript->registerCssFile($baseUrl . '/jquery.twitter.css');
    }
}