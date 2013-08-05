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
	// 'responsiveTable' => true,
	'template' => "{pager}{items}",
	// 'columns' => $gridColumns,
	'columns'=>array(
		'id',
        'title_ru',
        'description_ru',
        // 'content:html',   // display the 'content' attribute as purified HTML
        array(            // display 'create_time' using an expression
            'name'=>'created_at',
            'value'=>'date("d-m-Y", $data->created_at)',
        ),
        array(            // display 'author.username' using an expression
            'name'=>'updated_at',
            'value'=>'date("d-m-Y", $data->updated_at)',
        ),
        array(            // display a column with "view", "update" and "delete" buttons
            'class'=>'CButtonColumn',
            'htmlOptions'=>array(
            	'style'=>'width:70px',
            ),
            'template'=>'{update}{delete}'
        ),
    ),
));

?>