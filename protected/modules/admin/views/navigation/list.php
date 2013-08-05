<div class="form-actions">
	<a href="<?=$this->createUrl('create')?>" class="btn btn-success">
		<i class="icon-plus"></i>&nbsp;Добавить
	</a>
</div>
<?php
$model = new $this->targetModel;
$this->widget('bootstrap.widgets.TbExtendedGridView', array(
	'fixedHeader' => true,
	'headerOffset' => 40, // 40px is the height of the main navigation at bootstrap
	'type' => 'striped',
	'dataProvider' => $model->search(),
	'template' => "{pager}{items}",
	// 'columns' => $gridColumns,
	'columns'=>array(
		'id',
        'title_ru',
        array(
            'class'=>'CButtonColumn',
            'htmlOptions'=>array(
            	'style'=>'width:70px',
            ),
            'template'=>'{update}{delete}'
        ),
    ),
));

?>