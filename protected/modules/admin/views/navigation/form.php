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

<?php /*
$arr['0']='Верхний уровень';
$cat_list = Navigation::model()->findAll(array('condition'=>'parent_id = 0'.((!$model->isNewRecord)?' AND id!='.$model->id:'')));
foreach($cat_list as $cat){
    $arr[$cat->id] = $cat->title_ru;
}
echo $form->dropDownListRow($model, 'parent_id',$arr,array('class'=>'span12 nchosen'));*/?>
<?php echo $form->dropDownListRow($model,'type',array('1'=>'Верхнее меню','2'=>'Нижнее меню'),array('class'=>'span4'))?>
<?$urlList = array(''=>'Выберите страницу');?>
<?$pages = Page::model()->findAll();
foreach ($pages as $page) {
    $urlList['/p/'.$page->name]=$page->title;
}
?>
<div style="margin-left: 180px;">
    <?=CHtml::dropDownList('no_model_urlList','',$urlList,array('onchange'=>"document.getElementById('Navigation_url').value=this.value;"))?>
</div>
<?php echo $form->textFieldRow($model, 'url', array('size' => 60, 'maxlength' => 150, 'class'=>'span12')); ?>
<?php echo $form->textFieldRow($model, 'title_ru', array('size' => 50, 'maxlength' => 50, 'class'=>'span12')); ?>
<?php echo $form->textFieldRow($model, 'weight',array('class'=>'span12')); ?>
<?php echo $form->checkBoxRow($model, 'status'); ?>

<div class="form-actions">
    <button class="btn btn-success" type="submit">
        <?=$model->isNewRecord ? 'Добавить' : 'Сохранить'; ?>
    </button>
    <span class="text_button_padding">или</span>
    <?=CHtml::link('назад', array('list')); ?>
</div>

<?php $this->endWidget(); ?>
</div><!-- form -->