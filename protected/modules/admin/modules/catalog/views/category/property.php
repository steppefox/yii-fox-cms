
<li class="sortableItem">
                
    <span class="sortId"><?php echo $property->id; ?></span>
    <?php echo CHtml::activeTextField($property, "[$property->property_group_id][$property->id]name", array('maxlength'=> 100, 'size'=>30)); ?>
     <?php if($property->isNewRecord): ?>
    <?php echo CHtml::link(Yii::t('zii', 'Del'), '#', array('class'=>'delProp')); ?>
    <?php else: ?>
    <?php echo CHtml::activeCheckBox($property, "[$property->property_group_id][$property->id]markedDeleted") ?>
    <?php endif; ?>
</li>
