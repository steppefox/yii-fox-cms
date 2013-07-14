<?php
$this->breadcrumbs=array(
    'Content Categories'=>array('index'),
    'Manage',
);

$this->menu=array(
    array('label'=>'List ContentCategory', 'url'=>array('index')),
    array('label'=>'Create ContentCategory', 'url'=>array('create')),
);

?>
<div class="buttonbar" style="position: relative; top: 0pt;">
	<h2>
		Manage Content Categories
	</h2>
	<div class="row buttons">
		<?php echo CHtml::link('Toevoegen', $this->createUrl('create') , array('class'=>'button')); ?>
	</div>
</div>

<?php if(Yii::app()->user->hasFlash('categorySaved')): ?>
	<div class="alert_success">
		<?php echo Yii::app()->user->getFlash('categorySaved'); ?>
	</div>
	
	<br />
<?php endif; ?>


<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'content-category-grid',
    'dataProvider'=>new CArrayDataProvider($model->getArray()),
    //'filter'=>$model,
    'columns'=>array(
        array(
        	'name' => 'title',
        	'value' => 'str_repeat("&mdash; &nbsp;", $data->level) . " " . $data->title',
        	'type' => 'raw',
        ),
        'alias',
        'description',
        'active',
		'count',
        array(
            'class'=>'CButtonColumn',
        	'template'=>'{update} {delete}',
        ),
    ),
)); ?>