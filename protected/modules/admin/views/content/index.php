<?php
$this->breadcrumbs = array(
    'Contents' => array('index'),
    'Manage',
);

$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'categoryDialog',
    // additional javascript options for the dialog plugin
    'options' => array(
        'width' => 450,
        'resizable' => 'false',
        'modal' => 'true',
        'title' => Yii::t('catalog', 'Category'),
        'autoOpen' => false,
        'buttons' => array(
            Yii::t('backend', 'Save') => "js:function() { $('form#content-category-form').submit(); }",
            Yii::t('backend', 'Cancel') => 'js:function() { $(this).dialog("close"); }',
        ),
    ),
));

$this->endWidget('zii.widgets.jui.CJuiDialog');
?>
<div id="main">

    <div id="left-panel">
        
    
    <div class="toolbar">
        <?php if(Yii::app()->user->id == 1): ?>
        <?php
        $this->widget('zii.widgets.jui.CJuiButton',
                array(
                    'name' => 'btnAddFolder',
                    'buttonType' => 'link',
                    'url' => '#',
                    'caption' => Yii::t('backend', 'Add'),
                    'options' => array('icons' => array('primary' => 'ui-icon-circle-plus')),
                    'onclick' => "js:function(){ $('#categoryDialog').load(
                        '" . $this->createUrl('category/create') . "?parentid='+ jQuery(\".jstree-clicked\").parent().attr('rel'),
                        function() { $('#categoryDialog').dialog('open'); }
                );
                return false;}",
                )
        ); ?>
        <?php
        $this->widget('zii.widgets.jui.CJuiButton',
                array(
                    'name' => 'btnEditFolder',
                    'buttonType' => 'link',
                    'url' => '#',
                    'caption' => Yii::t('backend', 'Edit'),
                    'options' => array('icons' => array('primary' => 'ui-icon-pencil')),
                    'onclick' => "js:function(){ $('#categoryDialog').load(
                        '" . $this->createUrl('category/update') . "?id='+ $('#tree').find('.ui-selected').parent().attr('id').substr(5),
                        function() { $('#categoryDialog').dialog('open'); }
                );
                return false; }",
                )
        );
        ?>
        <?php endif; ?>
    </div>
    <div class="content">

        <?php $this->widget('application.extensions.nestedSortable.ENestedSortable', array(
            "id"=>'tree',
            "data"=>Category::model()->getTree(),

            "onclick"=>"js:function(event) {
                $('#tree div').removeClass('ui-selected');
                $(this).children('div').addClass('ui-selected');
                var cat_id = $(this).attr('id').substr(5);
                    $.fn.yiiGridView.update('content-grid', {
                            url: '".$this->createUrl('index')."/category_id/' + cat_id
                        });
                    return false;
                  }",
            "htmlOptions"=>array('class'=>'categories'),
        )); 
        ?>

    </div>

</div>

    <div id="content" class="has-sidebar">

        <div class="toolbar">
            <div class="right">
                <?php echo XHtml::cloudButton(
                        'btnAddContent', 
                        Yii::t('backend', 'New page'),
                        'ui-icon-circle-plus',
                        $this->createUrl('create'),
                        null, 
                        'blue'
                ); ?>
            </div>
        </div>
        
        <div class="content">
            
        <?php if (Yii::app()->user->hasFlash('contentSaved')): ?>
                <div class="statusbar alert_success">
        <?php echo Yii::app()->user->getFlash('contentSaved'); ?>
                </div>
        <?php endif; ?>

        <?php
                $this->widget('zii.widgets.grid.CGridView', array(
                    'id' => 'content-grid',
                    'dataProvider' => $model->search(),
                    'filter' => $model,
                    'columns' => array(
                        //'id',
                        array(
                            'class' => 'ButtonColumn',
                            'template' => '{update} {delete}',
                            'name' => 'title',
                            'buttons'=>array(
                                'delete'=>array('visible'=>'!$data->static'),
                            ),
                        ),
                        //'alias',
                        'meta_description',
                        array(
                            'name' => 'create_date',
                            'value' => '$data->createDateText',
                        ),
                        array(
                            'name' => 'status',
                            'value' => '$data->statusText',
                            'filter' => Content::model()->statusOptions,
                        ),
                    /*
                      'update_date',
                      'meta_keywords',
                      'status',
                     */
                    ),
                ));
        ?>
    </div>
</div>

</div>