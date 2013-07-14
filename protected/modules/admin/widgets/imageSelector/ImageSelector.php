<?php

/*
 * ImageSelector class file.
 *
 * @author Michael de Hart
 * @version 0.1 
 * @link http://www.cloudengineering.nl/
 * @copyright Copyright &copy; 2011 Cloud Engineering
 */
class ImageSelector extends CInputWidget
{
    
    public function init()
    {
        list($name,$id)=$this->resolveNameID();
        if(isset($this->htmlOptions['id']))
                $id=$this->htmlOptions['id'];
        else
                $this->htmlOptions['id']=$id;
        if(isset($this->htmlOptions['name']))
                $name=$this->htmlOptions['name'];

        $this->registerClientScript();

        $imgTag ='';
        
        if($this->hasModel())
        {
            echo CHtml::activeHiddenField($this->model,$this->attribute,$this->htmlOptions);
            $value = CHtml::resolveValue($this->model,$this->attribute);
            if(!empty($value))
            {
                $imgModel = Media::model()->findByPk($value);
                $imgTag = ($imgModel != null) ? '<img src="'. $imgModel->getImageUrl('thumb') .'" />' : '';
            }
        }
        else
            echo CHtml::hiddenField($name,$this->value,$this->htmlOptions) . "ERROR";
        
             
        echo '<div class="imageSelectHolder">'.$imgTag.'</div><br />';
        
        echo CHtml::ajaxLink('Select image', array('/admin/file/filePicker'), array(
            'update'=>'#req_res',
            'complete'=>"js: function(xhr, textStatus){ $('#req_res').dialog('open') } "));
        
        Yii::app()->controller->beginWidget('zii.widgets.jui.CJuiDialog', array(
            'id' => 'req_res',
            // additional javascript options for the dialog plugin
            'options' => array(
                'width' => '80%',
                'height' => '500',
                'resizable' => true,
                'modal' => true,
                'title' => 'Bestanden toevoegen',
                'autoOpen' => false,
                'buttons' => array(
                    Yii::t('backend', 'Select') => "js:function() {
                        $('#".$id."').val($('#ids').val());
                        $('div.imageSelectHolder').html('<img src=\"'+ $('#media_url').val()+ '\" />'); $(this).dialog('close'); 
                    }",
                    Yii::t('backend', 'Cancel') => 'js:function() { $(this).dialog("close"); }',
                ),
            ),
        ));
        Yii::app()->controller->endWidget();
    }

    public function run()
    {
        //$this->renderHtml();
    }
    
    protected function registerClientScript()
    {
        // ...publish CSS or JavaScript file here...
        $cs=Yii::app()->clientScript;
        $cs->registerCssFile($cssFile);
        $cs->registerScriptFile($jsFile);
    }

}
?>
