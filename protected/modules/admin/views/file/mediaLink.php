<?php foreach($models as $model): ?>

<?php $class = $model->metaData->relations['mediaLinks']->className; ?>

<tr>
    <td><?php echo ($model->media != null) ? CHtml::image($model->media->getImageUrl('thumb')) : "Error"; ?>
        <?php echo CHtml::activeHiddenField($model, "[$model->id]media_id"); ?>
    </td>
    <td><?php echo CHtml::activeTextField($model, "[$model->id]name") . CHtml::error($model,"name"); ?></td>
    <td><?php echo CHtml::activeTextArea($model, "[$model->id]description", array("cols"=>"40")); ?></td>
    <td><?php echo CHtml::activeDropDownList($model, "[$model->id]type", $model::getMediaTypes()); ?></td>
    <td><?php if($model->isNewRecord): ?>
                <?php echo CHtml::link(Yii::t('backend', 'Delete'), '#', array('class'=>"delete-file")); ?>
            <?php else: ?>
                <?php echo CHtml::activeCheckbox($model, "[$model->id]markedDeleted"); ?>
            <?php endif; ?>
    </td>
</tr>

<?php endforeach; ?>