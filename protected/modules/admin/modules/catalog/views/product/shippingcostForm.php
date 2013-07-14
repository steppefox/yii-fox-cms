<?php 
$this->beginWidget('zii.widgets.jui.CJuiDialog',array(
                'id'=>'costDialog',
                'options'=>array(
                    'title'=>'Verzend kosten',
                    'autoOpen'=>false,
                    'modal'=>'true',
                    'width'=>'400px',
                    'height'=>'auto',
                    'buttons' => array(
                        Yii::t('backend', 'Save') => "js:function() {
                            $.post('".$this->createUrl('shippingTable')."', $('#cost-form').serialize(),
                                function(data) {
                                    if(data)
                                        $('#costDialog').html(data);
                                    else
                                        $('#costDialog').dialog('close');
                            });
                        }",
                        Yii::t('backend', 'Cancel') => 'js:function() { $(this).dialog("close"); }',
                    ),
                ),
                )); ?>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'cost-form',
    'enableAjaxValidation'=>true,
)); 
//I have enableAjaxValidation set to true so i can validate on the fly the
?>

    <?php echo $form->errorSummary($models); ?>
    <table id="shipping" class="items">
        <thead>
        <tr><th>Gewicht</th><th>Prijs</th><th>Verwijder</th></tr>
        </thead>
        <tbody>
        <?php foreach($models as $i=>$model): ?>
            <?php $this->renderPartial('shippingRule', array('model'=>$model, 'form'=>$form)); ?>        
        <?php endforeach; ?>
        </tbody>
    </table>
    

    <div class="row buttons">
        <?php Yii::app()->clientScript->registerScript('delete-price', "$('.delShippingRule').live('click', function() { $(this).closest('tr').remove(); return false; })"); ?>
        <?php echo CHtml::link('Regel toevoegen', $this->createUrl('addShippingRule'), array(
            'onclick'=>"$.ajax({url: $(this).attr('href'),success: function(data) { $('#shipping tbody').prepend(data); }}); return false;",
            'class'=>'button',
        )); ?>
        
    </div>
    
<?php $this->endWidget(); ?>
</div>

<?php $this->endWidget('zii.widgets.jui.CJuiDialog');?>