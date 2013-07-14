<?php   
    Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/plupload.full.js');
    Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.plupload.queue.js');
?>

<div id="left-panel" class="no-toolbar">
    <div class="toolbar">
        
        <?php 
            $this->widget('zii.widgets.jui.CJuiButton', array(
                'name' => 'btnAddFolder',
                'buttonType' => 'link',
                'url' => '#',
                'caption' => Yii::t('backend', 'Add Folder'),
                'options' => array('icons' => array('primary' => 'ui-icon-plus')),
                'onclick' => "js:function(){ $('#folderDialog1').load(
                        '" . $this->createUrl('/admin/file/createFolder') . "',
                        function() { $('#folderDialog').dialog('open'); });
                        return false; }",
                )
            );
        ?>
        <div id="folderDialog1"></div>
        
    </div>

    <div class="content">
        
        <?php
        //insert javascript for ajax calls on tree
        $script = "$('#mediatree a').live('click', function() { 
                    $('#mediatree a').removeClass('active');
                    $('#content').load( $(this).attr('href') );
                    $(this).addClass('active');
                    return false; });";
       
       Yii::app()->clientScript->registerScript('treecalls', $script, CClientScript::POS_READY); ?>
        
        <ul id='mediatree'>
        <?php foreach(FileSystem::getMediaFolders() as $key => $value): ?>
        
        <li><div class='tree-head'><h2><?php echo $value['name']; ?></h2><div class='clear'></div></div>
        
            <?php if($value['folder'] !=null): ?>
            
            <?php $this->widget('CTreeView', array(
                'data'=>FileSystem::getTreeDirs($value['folder']),
                //'url'=>array('/admin/file/treeFill', 'start'=>  urlencode($value['folder'])),
                'htmlOptions'=>array('class'=>'treeview-cloud'),
                'animated'=>'normal',
                ));
            ?>
            
            <?php endif; ?>
        </li>
        <?php endforeach; ?>
        </ul>

    </div>
</div>


    


    <div id="content" class="has-sidebar has-sidebar2">
        
        <?php if (Yii::app()->user->hasFlash('success')): ?>
                <div class="statusbar alert_success">
        <?php echo Yii::app()->user->getFlash('success'); ?>
                </div>
        <?php endif; ?>

        <?php //$this->renderPartial('_grid'); ?>
            
        </div>

<div id="right-panel" style="width: 290px;">

    <div id="selected-file">
        
    </div>
    
</div>