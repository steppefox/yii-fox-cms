
<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'form-selected',
    'enableAjaxValidation'=>false,
)); ?>

<?php if (is_array($model)): ?>
    <?php echo count($model) . " selected items"; ?>
    <?php foreach ($model as $image): ?>

        <div class="row" style="height: 40px;">
            <?php echo CHtml::image($image->getImageUrl('thumb'), $image->name, array('width' => '40', 'height' => 40, 'style' => 'float:left;')); ?>
            <?php echo CHtml::hiddenField('ids[]', $image->id); ?>
            <?php echo $image->filename; ?>
        </div>

    <?php endforeach; ?>

<?php else: ?>

    <div class="toolbar">
        <div class="right">
            <?php echo XHtml::cloudButton(
                    'btnDownload', 
                    Yii::t('backend', 'Download'), 
                    'ui-icon-disk', 
                    $model->getFileUrl(), 
                    '', 
                    'green'
            ); ?>
            <?php if ($model->isOwner)
                echo XHtml::cloudButton(
                    'btnDelete', 
                    Yii::t('zii', 'Delete'), 
                    'ui-icon-trash', 
                    $this->createUrl('/admin/file/delete', array('id' => $model->primaryKey)), 
                    "js:function(){ if(confirm('" . Yii::t('zii', 'Are you sure you want to delete this item?') . "'))
                        {
                             $.ajax({
                                type: 'POST',
                                url: $(this).attr('href') ,
                                dataType: 'html',
                                success: function(data){ $('#select-media-grid').children('.ui-selected').fadeOut(); },
                                error: function(XMLHttpRequest, textStatus, errorThrown){ alert(XMLHttpRequest.responseText); }
                            });
                        }
                        return false;}", 
                    'red'
            ); ?>
        </div>

    </div>

    <div class="content selectedFile">
        <div class="form">
            <div class="displayImage" style="text-align: center;">
                <?php echo CHtml::image($model->getImageUrl('thumb')); ?>
                <?php echo CHtml::hiddenField('ids[]', $model->id); ?>
                <?php echo CHtml::hiddenField('media_url', $model->getImageUrl('thumb')); ?>
            </div>

            <div class="row">
                <?php echo CHtml::activeLabel($model, 'filename'); ?>
                <?php echo $model->filename; ?>
            </div>
            <div class="row">
                <?php echo CHtml::label('Link adres', null); ?>
                <?php echo CHtml::textArea('link', Yii::app()->getBaseUrl(true) . $model->getFileUrl(), array('rows'=>5, 'cols'=>30, 'readonly'=>'readonly')); ?>
            </div>
            
            <div class="row">
                <?php echo CHtml::activeLabel($model, 'filesize'); ?>
                <?php echo $model->filesize; ?>
            </div>
            <div class="row">
                <?php echo CHtml::activeLabel($model, 'create_date'); ?>
                <?php echo $model->createDateText; ?>
            </div>
            <div class="row">
                <?php echo $form->labelEx($model, 'name'); ?>
                <?php echo $form->textField($model, 'name', array('size' => 32, 'maxlength' => 100)); ?>
                <?php echo $form->error($model, 'name'); ?>
            </div>

            <div class="row">
                <?php echo $form->labelEx($model, 'description'); ?>
                <?php echo $form->textArea($model, 'description', array('rows' => 4, 'cols' => 30)); ?>
                <?php echo $form->error($model, 'description'); ?>
            </div>
        
            <fieldset>
                <legend>Koppelingen</legend>
            
        
        <?php foreach($model->links as $link): ?>
            <?php if($cache['exists']): ?>
                <div class="row">
                    <label><?php echo $link['name']; ?>:</label><?php echo CHtml::link(Yii::t('zii', 'Delete'), $link['url'], array('target'=>'_blank', 'class'=>'button')); ?>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
                
            </fieldset>
            
            
            <fieldset>
                <legend>Thumbnail caching</legend>
            
        
        <?php foreach($model->cache as $cache): ?>
            <?php if($cache['exists']): ?>
                <div class="row">
                    <label><?php echo $cache['name']; ?>:</label><?php echo CHtml::link(Yii::t('zii', 'View'), $cache['url'], array('target'=>'_blank', 'class'=>'button')); ?>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
            </fieldset>
        </div>
    </div>

<?php endif; ?>

<?php $this->endWidget('CActiveForm'); ?>