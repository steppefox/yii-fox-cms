<?php

/*
 * MediaTree class file.
 *
 * @author Michael de Hart
 * @version 0.1 
 * @link http://www.cloudengineering.nl/
 * @copyright Copyright &copy; 2012 Cloud Engineering
 */
class MediaTree extends CWidget
{
    /**
     * key => array(name, folder, readonly)
     * @var array 
     */
    public $data=array();
    
    public $htmlOptions=array();
    
    public $link;
    
    public $content_id = 'content';
    
    public $controller_action = '/admin/file/treeFill'; //the url that returns JSON for tree
    
    public function init()
    {
        /* $this->widget('zii.widgets.jui.CJuiAccordion', array(
            'panels'=>array(
                'Shared'=>'content for panel 1',
                'Pages'=>'content for panel 2',
                // panel 3 contains the content rendered by a partial view
                'Catalog'=>$this->getFileTree(),
                'Files'=>'test',
            ),
            // additional javascript options for the accordion plugin
            'options'=>array(
                'animated'=>'bounceslide',
            ),
        )); */
        
        echo CHtml::openTag('div', $this->htmlOptions);
        
        echo CHtml::openTag('ul', array('id'=>'mediatree'));
        
        foreach($this->data as $key => $value)
        {
            $path = Yii::getPathOfAlias('webroot')."/files/".$value['folder'];
            if(is_dir($path))
            {
                echo "<li><div class='tree-head'><h2>{$value['name']}</h2><div class='clear'></div></div>";

                
                if($value['folder'] !=null)
                    echo $this->getFileTree($value['folder']);
                echo"</li>";
            }
        }
        
        echo CHtml::closeTag('ul');
        
        echo CHtml::closeTag('div');
        
        
        //render harmonica
        
        //Harmony item have CTreeViews
        
        //Insert valid css
        
        //insert javascript for ajax calls on tree
        $script = "$('#mediatree a').live('click', function() { 
                    $('#mediatree a').removeClass('active');
                    $('#".$this->content_id."').load( $(this).attr('href') );
                    $(this).addClass('active');
                    return false; });";
       
       Yii::app()->clientScript->registerScript('treecalls', $script, CClientScript::POS_READY);
        
        //insert javascrip for toggeling categories
        
        
    }
    
    private function getFileTree($start_folder)
    {
        return $this->widget('CTreeView', array(
            //'data'=>$this->getFileData(),
            'url'=>array($this->controller_action, 'start'=>  urlencode($start_folder)),
            'htmlOptions'=>array('class'=>'treeview-cloud'),
            'animated'=>'normal',
        ), true);
    }
    
    private function getFileData()
    {
        return array(
            array('text'=>'<span class="folder">Public</span>',
                'expanded'=>true,
                'id'=>'category_1',
                'hasChildren'=>true,
                'children'=>array(
                    array(
                        'text'=>'<span class="folder">YL-002</span>',
                        'expanded'=>false,
                        'id'=>'product_5',
                        'hasChildren'=>false,
                    ),
                    array(
                        'text'=>'<a class="folder" href="/doc/api/1.1/CStarRating">YL-001</a>',
                        'expanded'=>false,
                        'id'=>'product_1',
                        'hasChildren'=>false,
                    ),
                ),
            ),
            array('text'=>'<span class="folder">Play</span>',
                'expanded'=>false,
                'id'=>'category_2',
                'hasChildren'=>true,
                'children'=>array(
                    array(
                        'text'=>'<span class="folder">QQ-002</span>',
                        'expanded'=>true,
                        'id'=>'product_5',
                        'hasChildren'=>false,
                    ),
                ),
            ),
        );
    }
}
?>
