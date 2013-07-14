<tr>
    <td><?php echo $form->textField($model, "[$model->id]weight", array('maxlength'=> 5, 'size'=>5)); ?>kg</td>
    <td>&euro;<?php echo $form->textField($model, "[$model->id]price", array('maxlength'=> 5, 'size'=>6)); ?></td>
    <td>
        <?php if($model->isNewRecord): ?>
        <?php echo CHtml::link(Yii::t('zii', 'Delete'), '#', array('class'=>'delShippingRule')); ?>
        <?php else: ?>
        <?php echo $form->checkBox($model, "[$model->id]markedDeleted") ?>
        <?php endif; ?>
    </td>
</tr>