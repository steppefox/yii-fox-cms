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
    <?php
        $categoryList = CatalogCategory::getAdminCategoryListArray();
    ?>
    <?php echo $form->textFieldRow($model, 'title_ru', array('class'=>'span7')); ?>
    <?php echo $form->dropDownListRow($model,'parent_CatalogCategory_id',$categoryList,array('span'=>'span7'));?>
    <?php echo $form->textFieldRow($model, 'description_ru', array('class'=>'span7')); ?>
    <?=$form->redactorRow($model,'text_ru',array('options'=>array(
        'imageUpload'=>$this->createUrl('json/image')
    )));?>


    <?
        // $this->widget('xupload.XUpload', array(
        //     'model' => $model,
        //     'attribute' => 'image',
        //     'multiple' => true,
        //     'showForm' => false,
        // ));
    ?>

    <?
        echo $form->singleFileFieldRow($model,'image');
    ?>

    <?php echo $form->textFieldRow($model, 'price', array('class'=>'span7')); ?>
    <?php echo $form->checkBoxRow($model,'is_visible');?>
<div class="form-actions">
    <button class="btn btn-success" type="submit">
        <?=$model->isNewRecord ? 'Добавить' : 'Сохранить'; ?>
    </button>
    <span class="text_button_padding">или</span>
    <?=CHtml::link('назад', array('list')); ?>
</div>

<?php $this->endWidget(); ?>
</div><!-- form -->