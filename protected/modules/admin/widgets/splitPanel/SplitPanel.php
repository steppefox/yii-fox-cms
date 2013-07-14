<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class SplitPanel extends CWidget
{
    public $clientOptions=array();
    public $htmlOptions=array();
    
    public function run()
    {
        echo "</div>";
        
        $cs=Yii::app()->clientScript;
        
        $assets = dirname(__FILE__).'/assets';
            $baseUrl = Yii::app()->assetManager->publish($assets);
            Yii::app()->clientScript->registerScriptFile($baseUrl . '/jquery.splitter.js', CClientScript::POS_HEAD);
            
        $options=$this->clientOptions;
        $options=CJavaScript::encode($options);
            
        $id=$this->id;
        $cs->registerScript(__CLASS__.'#'.$id,"\$('#$id').splitter($options);");
        
    }
    
    public function init()
    {
        if(!isset($this->htmlOptions['id']))
                $this->htmlOptions['id']=$this->id;
        
        $this->htmlOptions['class'] = 'ui-splitpanel';
        
        echo CHtml::tag('div', $this->htmlOptions, false, false);
    }
}
?>
