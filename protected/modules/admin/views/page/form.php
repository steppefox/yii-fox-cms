<div class="form">
<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>$this->targetModel.'-form',
    'enableAjaxValidation'=>true,
    'type'=>'horizontal',
    'clientOptions' => array(
        'validateOnSubmit' => true,
        'validateOnChange' => false,
        'validateOnType' => false,
    ),
    'focus'=>array($model,'title_ru'),
    'htmlOptions' => array(
        'class' => 'form form-horizontal',
        'enctype'=>'multipart/form-data'
    ),
)); ?>

<div class="form-actions">
    <button class="btn btn-success" type="submit">
        <?=$model->isNewRecord ? 'Добавить' : 'Сохранить'; ?>
    </button>
    <span class="text_button_padding">или</span>
    <?=CHtml::link('назад', array('list')); ?>
</div>
    <?php echo $form->textFieldRow($model, 'title_ru', array('class'=>'span7')); ?>
    <?php echo $form->textFieldRow($model, 'description_ru', array('class'=>'span7')); ?>
    <?php //echo $form->textAreaRow($model, 'text_ru', array('class'=>'span7')); ?>
    <div class="control-group">
        <label class="control-label" style="padding-top:0;">
            <?=CHtml::activeLabelEx($model,'text_ru');?>
        </label>
        <div class="controls">
            <? $this->widget('ImperaviRedactorWidget', array(
                'model' => $model,
                'attribute' => 'text_ru',
                //'name' => 'my_input_name',
                'options' => array(
                    'lang' => 'ru',
                    'toolbar' => true,
                    'iframe' => true,
                    'css' => 'wym.css',
                ),
                'htmlOptions'=>array(
                    'style'=>'height:200px',
                )
            ));?>
            <span class="help-inline error" id="Page_url_em_" style="display: none;"></span>
        </div>
    </div>
    <?php echo $form->checkBoxRow($model,'status');?>
<div class="form-actions">
    <button class="btn btn-success" type="submit">
        <?=$model->isNewRecord ? 'Добавить' : 'Сохранить'; ?>
    </button>
    <span class="text_button_padding">или</span>
    <?=CHtml::link('назад', array('list')); ?>
</div>

<?php $this->endWidget(); ?>
</div><!-- form -->