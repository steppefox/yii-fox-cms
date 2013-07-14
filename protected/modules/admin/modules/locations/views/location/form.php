<div id="main">




<div class="toolbar">
    <div class="left">
        <?php
        $this->widget('zii.widgets.jui.CJuiButton',
                array(
                    'name' => 'btnBack',
                    'buttonType' => 'link',
                    'url' => $this->createUrl('index'),
                    'caption' => Yii::t('backend', 'Back'),
                    'options' => array('icons' => array('primary' => 'ui-icon-circle-triangle-w')),
                )
        );
        ?>
    </div>
    <div class="right">
        <?php
        $this->widget('zii.widgets.jui.CJuiButton',
                array(
                    'name' => 'btnSave',
                    'buttonType' => 'button',
                    'caption' => Yii::t('backend', 'Save'),
                    'options' => array('icons' => array('primary' => 'ui-icon-disk')),
                    'onclick' => 'js:function(){$("form#location-form").submit(); return false;}',
                )
        );
        ?>
    </div>
</div>

    <?php
$form = $this->beginWidget('CActiveForm', array(
            'id' => 'location-form',
            'enableAjaxValidation' => false,
        ));
?>
    
<div id="content_form">
    <div class="form">
    <?php echo CHtml::errorSummary($model); ?>
        <div class="one_half">
            <div class="section">
                <div class="section-header">Adres gegevens</div>
            <div class="section-content">
                <div class="row">
                    <?php echo $form->labelEx($model, 'title'); ?>
                    <?php echo $form->textField($model, 'title', array(
                            'style' => 'width: 50%;',
                            'size' => 40, 'maxlength' => 45,
                            'onkeyup' => "$('#Location_alias[type!=\"hidden\"]').val($('#Location_title').val().toLowerCase().replace(/[^a-zA-Z 0-9]+/g,'').replace(/ /g,'-'))",
                                )); ?>
                    <?php echo $form->error($model, 'title'); ?>
                </div>
                <div class="row">
                    <?php echo $form->labelEx($model, 'alias'); ?>
                    <?php echo Yii::app()->request->hostInfo . Yii::app()->request->baseUrl; ?>/vestigingen/ <?php echo $form->textField($model, 'alias', array('size' => 30, 'maxlength' => 100)); ?>
                    <?php echo $form->error($model, 'alias'); ?>
                </div>
                <div class="row">
                    <?php echo $form->labelEx($model, 'address'); ?>
                    <?php echo $form->textField($model, 'address', array('size' => 30, 'maxlength' => 45)); ?>
                    <?php echo $form->error($model, 'address'); ?>
                </div>
                <div class="row">
                    <?php echo $form->labelEx($model, 'zipcode'); ?>
                    <?php echo $form->textField($model, 'zipcode', array('size' => 7, 'maxlength' => 45)); ?>
                    <?php echo $form->error($model, 'zipcode'); ?>
                </div>
                <div class="row">
                    <?php echo $form->labelEx($model, 'place'); ?>
                    <?php echo $form->textField($model, 'place', array('size' => 30, 'maxlength' => 45)); ?>
                    <?php echo $form->error($model, 'place'); ?>
                </div>
                <div class="row">
                    <?php echo $form->labelEx($model, 'phone_nb'); ?>
                    <?php echo $form->textField($model, 'phone_nb', array('size' => 12, 'maxlength' => 45)); ?>
                    <?php echo $form->error($model, 'phone_nb'); ?>
                </div>
                <div class="row">
                    <?php echo $form->labelEx($model, 'fax_nb'); ?>
                    <?php echo $form->textField($model, 'fax_nb', array('size' => 12, 'maxlength' => 45)); ?>
                    <?php echo $form->error($model, 'fax_nb'); ?>
                </div>
                <div class="row">
                    <?php echo $form->labelEx($model, 'email'); ?>
                    <?php echo $form->textField($model, 'email', array('size' => 30, 'maxlength' => 100)); ?>
                    <?php echo $form->error($model, 'email'); ?>
                </div>
            </div>
        </div>
            </div>
        <div class="one_half">
            <div class="section">
                <div class="section-header">Openingstijden</div>
                <div class="section-content">
                    <div class="row">
                        <?php echo $form->labelEx($model, 'oh_monday'); ?>
                        <?php echo $form->textField($model, 'oh_monday', array('size' => 30, 'maxlength' => 45)); ?>
                        <?php echo $form->error($model, 'oh_monday'); ?>
                    </div>
                    <div class="row">
                        <?php echo $form->labelEx($model, 'oh_tuesday'); ?>
                        <?php echo $form->textField($model, 'oh_tuesday', array('size' => 30, 'maxlength' => 45)); ?>
                        <?php echo $form->error($model, 'oh_tuesday'); ?>
                    </div>
                    <div class="row">
                        <?php echo $form->labelEx($model, 'oh_wednesday'); ?>
                        <?php echo $form->textField($model, 'oh_wednesday', array('size' => 30, 'maxlength' => 45)); ?>
                        <?php echo $form->error($model, 'oh_wednesday'); ?>
                    </div>
                    <div class="row">
                        <?php echo $form->labelEx($model, 'oh_thursday'); ?>
                        <?php echo $form->textField($model, 'oh_thursday', array('size' => 30, 'maxlength' => 45)); ?>
                        <?php echo $form->error($model, 'oh_thursday'); ?>
                    </div>
                    <div class="row">
                        <?php echo $form->labelEx($model, 'oh_friday'); ?>
                        <?php echo $form->textField($model, 'oh_friday', array('size' => 30, 'maxlength' => 45)); ?>
                        <?php echo $form->error($model, 'oh_friday'); ?>
                    </div>
                    <div class="row">
                        <?php echo $form->labelEx($model, 'oh_saturday'); ?>
                        <?php echo $form->textField($model, 'oh_saturday', array('size' => 30, 'maxlength' => 45)); ?>
                        <?php echo $form->error($model, 'oh_saturday'); ?>
                    </div>
                    <div class="row">
                        <?php echo $form->labelEx($model, 'oh_sunday'); ?>
                        <?php echo $form->textField($model, 'oh_sunday', array('size' => 30, 'maxlength' => 45)); ?>
                        <?php echo $form->error($model, 'oh_sunday'); ?>
                    </div>
                </div>
            </div>
            </div>

            <div class="one_column">
                <div class="section">
                        <div class="section-header"><?php echo Yii::t('backend', 'Meer informatie'); ?></div>
                <div class="section-content">
                    <div class="row">
                        <?php
                            $this->widget('application.extensions.cleditor.ECLEditor', array(
                                'model' => $model,
                                'attribute' => 'more_info',
                                'options' => array(
                                    'height' => 400,
                                    'width' => '100%',
                                    'useCSS' => true,
                                ),
                            ));
                        ?>
                    </div>
                </div>
                </div>
            </div>

<div class="one_column">
    <div class="section">
        <div class="section-header"><?php echo Yii::t('backend', 'Images and Downloads'); ?></div>
        <div class="section-content">

            <div class="row buttons">
                
                <?php 
      $media = new LocationMedia;
      
      $this->widget('application.extensions.uploadify.MUploadify',array(
          'name'=>'file',
          'script'=>$this->createUrl('upload', array('id'=>$model->id)),
          'buttonText' => Yii::t('backend', 'Bladeren...'),
          'auto'=>true,
          'width' => 150,
          'sizeLimit' => 32*1024*1024, // MAX 32MB file size
          //'someOption'=>'someValue',

              'onError' => 'js: function(evt,queueId,fileObj,errorObj){ console.log("Error: " + errorObj.type + "\nInfo: " + errorObj.info); }',
              'onAllComplete' => "js: function(){ $.fn.yiiGridView.update('media-grid'); }",
              //'onCancel' => 'function(evt,queueId,fileObj,data){alert("Cancelled");}',
          
        ));
      ?>
 

            </div>

            <?php
            
                $this->widget('zii.widgets.grid.CGridView', array(
                    'id' => 'media-grid',
                    'dataProvider' => new CArrayDataProvider($model->locationMedia),
                    //'filter'=>$model,
                    'columns' => array(
                        
                        array(
                            'header' => 'Voorbeeld',
                            'type' => 'raw',
                            'value' => 'CHtml::link( CHtml::image($data->media->getImageUrl("thumb"), $data->description), $data->media->getFileUrl(), array("target"=>"_blank") )
                                .CHtml::activeHiddenField($data, "[$data->id]media_id")
                                .CHtml::activeHiddenField($data, "[$data->id]location_id")',
                        ),
                        array(
                            'name' => 'name',
                            'type' => 'raw',
                            'value' => 'CHtml::activeTextField($data, "[$data->id]name") . CHtml::error($data,"name")',
                        ),
                        array(
                            'name' => 'description',
                            'type' => 'raw',
                            'value' => 'CHtml::activeTextArea($data, "[$data->id]description", array("cols"=>"40"))',
                        ),
                        array(
                            'header' => 'type',
                            'type' => 'raw',
                            'value' => 'CHtml::activeDropDownList($data, "[$data->id]type", $data->getMediaTypes())', //TODO: really WTF Yii!
                        ),
                        array(
                            'class' => 'CButtonColumn',
                            'template' => '{delete}',
                            'deleteButtonUrl' => 'Yii::app()->controller->createUrl("deleteFile", array("id"=>$data->media_id))',
                        ),
                    ),
                ));
            ?>

        </div>
        <br /><br />
    </div>
</div>


                                </div>
                            </div>
<?php $this->endWidget(); ?>
</div>
