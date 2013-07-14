<?php
$form = $this->beginWidget('CActiveForm', array(
            'id' => 'location-form',
            'enableAjaxValidation' => false,
        ));
?>
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
        if(Yii::app()->administration->isHQ())
        $this->widget('zii.widgets.jui.CJuiButton',
                array(
                    'name' => 'btnDelete',
                    'buttonType' => 'link',
                    'url' => $this->createUrl('delete', array('id'=>$model->primaryKey)),
                    'caption' => Yii::t('zii', 'Delete'),
                    'options' => array('icons' => array('primary' => 'ui-icon-trash')),
                    'onclick' => "js:function(){ return confirm('".Yii::t('zii','Are you sure you want to delete this item?')."'); }",
                )
        );
        ?>

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
        <?php //echo CHtml::submitButton('Opslaan');  ?>
    </div>
</div>

<div id="content_form">
    <div class="form">
        <div class="one_column right_bar">
        <div class="has-rightbar">

            <div class="section">
                <div class="section-header"><?php echo ($model->isNewRecord) ? Yii::t('backend', "New location") : Yii::t('backend', "Edit location"); ?></div>
                <div class="section-content">

                    <div class="row">
                        <?php echo $form->labelEx($model, 'title'); ?>
                        <?php echo $form->textField($model, 'title', array('maxlength' => 100,)
                        );
                        ?>
                        <?php echo $form->error($model, 'title'); ?>
                    </div>
                    <?php if(Yii::app()->administration->isHQ()): ?>
                    <div class="row">
                        <?php echo $form->labelEx($model,'domain'); ?>
                        <?php echo $form->textField($model,'subdomain',array('maxlength'=>45, 'size'=>6)); ?>.<?php echo $form->textField($model,'domain',array('maxlength'=>100)); ?>
                        <?php echo $form->error($model,'domain'); ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="section">
                <div class="section-header"><?php echo Yii::t('backend', 'Contact details'); ?></div>
                <div class="section-content">

                    <div class="row">
                        <?php echo $form->labelEx($model, 'name'); ?>
                        <?php echo $form->textField($model,'name',array('maxlength'=>100, 'size'=>50)); ?>
                        <?php echo $form->error($model, 'name'); ?>
                    </div>
                    <div class="row">
                        <?php echo $form->labelEx($model,'address'); ?>
                        <?php echo $form->textField($model,'address',array('maxlength'=>100, 'size'=>50)); ?>
                        <?php echo $form->error($model,'address'); ?>
                    </div>
                    <div class="row">
                        <?php // echo $form->labelEx($model,'postalcode'); ?>
                        <?php echo $form->labelEx($model, 'place'); ?>
                        <?php echo $form->textField($model,'place',array('maxlength'=>100, 'size'=>40)); ?>
                        <?php echo $form->error($model, 'place'); ?>
                    </div>

                    <div class="row">
                        <?php // echo $form->labelEx($model,'postalcode'); ?>
                        <?php echo $form->labelEx($model, 'postalcode'); ?>
                        <?php echo $form->textField($model,'postalcode',array('maxlength'=>10, 'size'=>10)); ?>
                        <?php echo $form->error($model,'postalcode'); ?>
                    </div>

                    <div class="row">
                        <?php echo $form->labelEx($model,'country_code'); ?>
                        <?php echo $form->dropDownList($model,'country_code',Country::getDropDown()); ?>
                        <?php echo $form->error($model,'country_code'); ?>
                    </div>
                    <div class="row">
                        <?php echo $form->labelEx($model,'email'); ?>
                        <?php echo $form->textField($model,'email',array('maxlength'=>100, 'size'=>40)); ?>
                        <?php echo $form->error($model,'email'); ?>
                    </div>

                    <div class="row">
                        <?php echo $form->labelEx($model,'phone_nb'); ?>
                        <?php echo $form->textField($model,'phone_nb',array('maxlength'=>25)); ?>
                        <?php echo $form->error($model,'phone_nb'); ?>
                    </div>
                    <div class="row">
                        <?php echo $form->labelEx($model,'fax_nb'); ?>
                        <?php echo $form->textField($model,'fax_nb',array('maxlength'=>25)); ?>
                        <?php echo $form->error($model,'fax_nb'); ?>
                    </div>
                </div>
            </div>
      </div>
     </div>

     <div class="sidebar-column">
        <div class="section">
            <div class="section-header"><?php echo Yii::t('backend', 'Properties'); ?></div>
            <div class="section-content">
                <div class="row checkbox">
                    <?php echo $form->checkBox($model, 'active'); ?>
                    <?php echo $form->labelEx($model, 'active'); ?>
                    <?php echo $form->error($model, 'active'); ?>
                </div>
                <div class="row checkbox">
                    <?php echo $form->labelEx($model,'google_maps_key'); ?>
                    <?php echo $form->textArea($model,'google_maps_key', array('rows'=>4, 'cols'=>30)); ?>
                    <?php echo $form->error($model,'google_maps_key'); ?>
                    <?php echo CHtml::link('Generate API key', 'http://code.google.com/intl/nl/apis/maps/signup.html', array('target'=>"_blank")); ?>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-header"><?php echo Yii::t('backend', 'Language'); ?></div>
            <div class="section-content">
                <div class="row">
                    <div class="compactRadioGroup">
                    <?php echo $form->radioButtonList($model, 'language', Country::getLanguages()); ?>
                    </div>
                    <?php echo $form->error($model, 'language'); ?>
                </div>
            </div>
        </div>

        </div>

        <div class="one_column">
            <div class="section">
                <div class="section-header">Map <?php //echo Yii::t('backend', 'Location'); ?></div>
                <div class="section-content">
                    <div class="row checkbox">
                        <?php //echo $form->labelEx($model,'map_x'); ?>
                        <?php //echo $form->textField($model,'map_x', array('size'=>3)); ?>x
                        <?php //echo $form->textField($model,'map_y', array('size'=>3)); ?>y
                        <?php //echo $form->error($model,'map_x'); ?><?php echo $form->error($model,'map_y'); ?>
                        
                    </div>
                        <?php //echo CHtml::image(Yii::app()->theme->baseUrl. '/images/europe_map.gif'); ?>
                </div>
            </div>
        </div>
    
</div>
<?php $this->endWidget(); ?>

</div><!-- form -->
</div>