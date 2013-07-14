<div id="main">
    
<?php
$form = $this->beginWidget('CActiveForm', array(
            'id' => 'content-form',
            'enableAjaxValidation' => false,
        ));
?>
    
<div class="toolbar">
    <div class="left">
        <?php echo XHtml::cloudButton(
                'btnCancel', 
                Yii::t('backend', 'Back'), 
                'ui-icon-circle-triangle-w',
                $this->createUrl('index'), 
                null, 
                'blue'
        ); ?>
    </div>
    <div class="right">
        <?php echo XHtml::cloudButton(
                'btnSave', 
                Yii::t('backend', 'Save'), 
                'ui-icon-disk', null, 
                'js:function(){ $("form#content-form").submit(); return false; }', 
                'green'
        ); ?>
        <?php echo XHtml::cloudButton(
                'btnCancel', 
                Yii::t('backend', 'Cancel'), 
                'ui-icon-cancel',
                $this->createUrl('index'), 
                null, 
                'blue'
        ); ?>
        <?php if(!$model->static && !$model->isNewRecord)
            echo XHtml::cloudButton(
                'btnDelete', 
                Yii::t('zii', 'Delete'), 
                'ui-icon-trash', 
                $this->createUrl('delete', array('id'=>$model->primaryKey)), 
                "js:function(){ return confirm('".Yii::t('zii','Are you sure you want to delete this item?')."'); }", 
                'red'
        ); ?>
    </div>
</div>


<div id="content_form">
    <div class="form">
        <div class="one_column right_bar">
            <div class="has-rightbar">

            <div class="section">
                <div class="section-header"><?php echo ($model->isNewRecord) ? Yii::t('backend', "New page") : Yii::t('backend', "Edit page"); ?></div>
                <div class="section-content">

                    <div class="row">
                        <?php echo $form->labelEx($model, 'title'); ?>
                        <?php
                        echo $form->textField($model, 'title', array(
                            'style' => 'width: 50%;',
                            'size' => 60, 'maxlength' => 100,
                            'onkeyup' => "$('#Content_alias[type!=\"hidden\"]').val($('#Content_title').val().toLowerCase().replace(/[^a-zA-Z 0-9]+/g,'').replace(/ /g,'-'))",
                                )
                        );
                        ?>
                        <?php echo $form->error($model, 'title'); ?>
                    </div>
                    <?php if ($model->static || !$model->isNewRecord): ?>
                        <?php echo $form->hiddenField($model, 'alias'); ?>
                    <?php else: ?>
                    <div class="row">
                        <?php echo $form->labelEx($model, 'alias'); ?>
                        <?php echo $form->textField($model, 'alias', array('size' => 30, 'maxlength' => 100)); ?>
                        <?php echo $form->error($model, 'alias'); ?>
                    </div>
                    <?php endif; ?>
                    <?php if ($this->module->hasModule('a4kcatalog') && $model->hasAttribute('product_id')): ?>
                            <div class="row">
                        <?php echo $form->labelEx($model, 'product_id'); ?>
                        <?php echo $form->dropDownList($model, 'product_id', CHtml::listData(Product::model()->findAll(), 'id', 'serial_number', 'category.name'), array('prompt' => 'Geen')); ?>
                        <?php echo $form->error($model, 'product_id'); ?>
                        </div>
                    <?php endif; ?>

                </div>
            </div>

            <div class="section">
                <div class="section-header"><?php echo Yii::t('backend', 'Content'); ?></div>
                <div class="section-content">

                            <div class="row">
                        <?php echo $form->error($model, 'description'); ?>
                        <?php
                            $this->widget('application.extensions.cleditor.ECLEditor', array(
                                'model' => $model,
                                'attribute' => 'description',
                                'options' => array(
                                    'height' => 600,
                                    'width' => '100%',
                                    'useCSS' => true,
                                ),
                            ));
                        ?>

                        <?php //echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50));  ?>
                        
                        </div>

                </div>
            </div>
            </div>
        </div>

        <div class="sidebar-column">

            <div class="section">
                <div class="section-header"><?php echo Yii::t('backend', 'Properties'); ?></div>
                <div class="section-content">
                    <div class="row">
                    <?php echo $form->labelEx($model, 'status'); ?>
                    <?php echo $form->dropDownList($model, 'status', $model->getStatusOptions()); ?>
                    <?php echo $form->error($model, 'status'); ?>
                    </div>

                <?php if(!Yii::app()->user->isGuest && Yii::app()->user->role >= User::ROLE_ADMIN && Yii::app()->user->administration == 1): ?>
                    <div class="row">
                        <?php echo $form->labelEx($model, 'static'); ?>
                        <?php echo $form->checkBox($model, 'static'); ?>
                        <?php echo $form->error($model, 'static'); ?>
                    </div>
                <?php endif; ?>

                        <div class="row">
                    <?php echo $form->labelEx($model, 'create_date'); ?>
                    <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'model'=>$model,
                        'attribute'=>'create_date',
                        'options'=>array(
                                'dateFormat'=>'dd-mm-yy',
                        ),
                        'htmlOptions'=>array('readonly'=>'readonly', 'size' => 14),
                    ));
                    ?>
                    <?php echo $form->error($model, 'create_date'); ?>
                        </div>

                        <div class="row">
                    <?php echo $form->labelEx($model, 'update_date'); ?>
                    <?php echo $model->updateDateText; ?>
                    <?php echo $form->error($model, 'update_date'); ?>
                        </div>
                </div>
            </div>

            <div class="section">
                <div class="section-header"><?php echo Yii::t('backend', 'Categories'); ?></div>
                <div class="section-content">
                    <div class="row">

                <?php
                        $this->widget('admin.components.CategorySelectWidget', array(
                            'id' => 'categories_select',
                            'model' => $model,
                            'attribute' => 'categories',
                            'height' => '180px',
                            'width' => '250px',
                        ));
                ?>
                        <br class="clear" />
                <?php echo $form->error($model, 'categories'); ?>
                    </div>
                </div>
            </div>

            <div class="section">
                <div class="section-header"><?php echo Yii::t('backend', 'SEO'); ?></div>
                <div class="section-content">
                        <div class="row">
                    <?php echo XHtml::hintLabel($model, 'meta_title'); ?>
                    <?php //echo $form->labelEx($model, 'meta_title'); ?>
                    <?php echo $form->textField($model, 'meta_title', array('maxlength' => 100,'style'=>'width: 100%')); ?>
                    <?php echo $form->error($model, 'meta_title'); ?>
                        </div>
                        <div class="row">
                    <?php echo XHtml::hintLabel($model, 'meta_description'); ?>
                    <?php //echo $form->labelEx($model, 'meta_description'); ?>
                    <?php echo $form->textArea($model, 'meta_description', array('rows' => 6, 'cols' => 30)); ?>
                    <?php echo $form->error($model, 'meta_description'); ?>
                        </div>

                        <div class="row">
                    <?php echo XHtml::hintLabel($model, 'meta_keywords'); ?>
                    <?php //echo $form->labelEx($model, 'meta_keywords'); ?>
                    <?php echo $form->textArea($model, 'meta_keywords', array('rows' => 6, 'cols' => 30)); ?>
                    <?php echo $form->error($model, 'meta_keywords'); ?>
                        </div>
                </div>
            </div>
                </div>
        
    <?php $this->renderPartial("application.modules.admin.views.file._manager", array('model'=>$model, 'relation'=>'mediaLinks')); ?>
        
        <br /><br />
    </div>
        </div><!-- form -->
    
<?php $this->endWidget(); ?>
               
</div>