<?php
$form = $this->beginWidget('CActiveForm', array(
            'id' => 'customer-form',
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
        <?php echo XHtml::cloudButton(
                'btnSave', 
                Yii::t('backend', 'Save'), 
                'ui-icon-disk', null, 
                'js:function(){ $("form#customer-form").submit(); return false; }', 
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
    </div>
</div>

<div id="content_form">
    <div class="form">
    <?php echo CHtml::errorSummary($model); ?>
        <div class="one_half">
            <div class="section">
                <div class="section-header">Persoonlijke informatie</div>
            <div class="section-content">

    <div class="row">
        <?php echo $form->labelEx($model,'company'); ?>
        <?php echo $form->textField($model,'company',array('size'=>40,'maxlength'=>100)); ?>
        <?php echo $form->error($model,'company'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'name'); ?>
        <?php echo $form->textField($model,'name',array('size'=>40,'maxlength'=>150)); ?>
        <?php echo $form->error($model,'name'); ?>
    </div>

   <div class="row">
        <?php echo $form->labelEx($model,'email'); ?>
        <?php echo $form->textField($model,'email',array('size'=>40,'maxlength'=>128)); ?>
        <?php echo $form->error($model,'email'); ?>
    </div>

   <div class="row">
        <?php echo $form->labelEx($model,'phone_nb'); ?>
        <?php echo $form->textField($model,'phone_nb',array('size'=>25,'maxlength'=>45)); ?>
        <?php echo $form->error($model,'phone_nb'); ?>
    </div>

            </div>
            </div>
        </div>
        <div class="one_half">
            <div class="section">
                <div class="section-header">Adres gegevens</div>
                <div class="section-content">

    <div class="row">
        <?php echo $form->labelEx($model,'address'); ?>
        <?php echo $form->textField($model,'address',array('size'=>40,'maxlength'=>128)); ?>
        <?php echo $form->error($model,'address'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'postalcode'); ?>
        <?php echo $form->textField($model,'postalcode',array('size'=>10,'maxlength'=>10)); ?>
        <?php echo $form->error($model,'postalcode'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'city'); ?>
        <?php echo $form->textField($model,'city',array('size'=>40,'maxlength'=>128)); ?>
        <?php echo $form->error($model,'city'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'country_code'); ?>
        <?php echo $form->dropDownList($model, 'country_code', array('nl'=>'Nederland')); ?>
        <?php echo $form->error($model,'country_code'); ?>
    </div>

    


                </div>
            </div>
            </div>

            <div class="one_column">
                <div class="section">
                    <div class="section-header">Extra info</div>
                <div class="section-content">

    <div class="row">
        <?php echo $form->labelEx($model,'register_date'); ?>
        <?php echo Yii::app()->dateFormatter->formatDateTime($model->register_date, "long"); ?>
        <?php echo $form->error($model,'register_date'); ?>
    </div>

    <div class="row">
        <?php //echo $form->labelEx($model,'newsletter'); ?>
        <?php //echo $form->checkBox($model,'newsletter'); ?>
        <?php //echo $form->error($model,'newsletter'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'ip'); ?>
        <?php echo $model->ip; ?>
        <?php echo $form->error($model,'ip'); ?>
    </div>

    <div class="row">
        <?php //echo $form->labelEx($model,'status'); ?>
        <?php //echo $form->dropDownList($model,'status',$model->statusTypes); ?>
        <?php //echo $form->error($model,'status'); ?>
    </div>

                </div>
                </div>
            </div>


                                </div>
                            </div>
<?php $this->endWidget(); ?>

    </div>


</div>