<div class="form-actions">
	<a href="<?=$this->createUrl('form')?>" class="btn btn-success">
		<i class="icon-plus"></i>&nbsp;Добавить
	</a>
</div>

<?php $model = new $this->targetModel;
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
        // 'content:html',
        array(
            'name'=>'created_at',
            'value'=>'date("d-m-Y", $data->created_at)',
        ),
        array(
            'name'=>'updated_at',
            'value'=>'date("d-m-Y", $data->updated_at)',
        ),
        array(
            'class'=>'CButtonColumn',
            'htmlOptions'=>array(
            	'style'=>'width:200px',
            ),
            'buttons'=>array(
                'update'=>array(
                    'label'=>'Ред.',
                    'url'=>function($data){
                        return Yii::app()->controller->createUrl('form',array('id'=>$data->id));
                    },
                    'imageUrl'=>false,
                    'options'=>array(
                        'class'=>'btn btn-success btn-mini',
                    ),
                ),
                'delete'=>array(
                    'label'=>'Уд.',
                    // 'url'=>$this->createUrl('form',array('id'=>$model->id)),
                    'imageUrl'=>false,
                    'options'=>array(
                        'class'=>'btn btn-danger btn-mini',
                        'style'=>'margin-left:20px;',
                    ),
                ),
            ),
            'template'=>'{update}{delete}'
        ),
    ),
));

?>