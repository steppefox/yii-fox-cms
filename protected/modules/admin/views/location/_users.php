<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'content-grid',
	'dataProvider'=>new CArrayDataProvider($users),
	//'filter'=>$model,
	'template'=>'{items}',
	'columns'=>array(
		//'id',
		array(
			'name'=>'nicename',
			'class'=>'ButtonColumn',
			'template'=>'{update} {delete}',
                        'buttons'=>array(
                            'delete'=>array(
                                'visible'=>'$data->id != Yii::app()->user->id',
                                'url'=>'Yii::app()->controller->createUrl("user/delete", array("id" => $data->primaryKey))',
                                
                            ),
                            'update'=>array(
                                'click'=>"js:function(){ $('#userDialog').load( $(this).attr('href'), function(){ $('#userDialog').dialog('open'); } ); return false }",
                                'url'=>'Yii::app()->controller->createUrl("user/update", array("id" => $data->primaryKey))',
                            ),
                         ),
		),
		'login',
		'email',
		array(
			'name'=>'register_date',
			'value'=>'$data->registerDateText',
		),
		array(
			'name'=>'role',
			'value'=>'$data->roleText',
		),
		/*
		'update_date',
		'meta_keywords',
		'status',
		*/
	),
)); ?>