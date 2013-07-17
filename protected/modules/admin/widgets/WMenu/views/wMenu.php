<header id="header">
<?
$this->widget('bootstrap.widgets.TbNavbar', array(
	'brand' => Yii::app()->name,
	'items' => array(
		array(
			'class' => 'bootstrap.widgets.TbMenu',
			'items' => array(
				array('label'=>'Каталог', 'url'=>'#','items'=>array(
					array('label'=>'Категории', 'icon'=>'tag','url'=>$this->controller->createUrl('catalogCategory/list')),
					array('label'=>'Товары', 'icon'=>'shopping-cart','url'=>$this->controller->createUrl('catalog/list')),
				)),
				array('label'=>'Разное','url'=>'#','items'=>array(
					array('label'=>'Текстовые страницы', 'icon'=>'file','url'=>$this->controller->createUrl('page/list')),
				)),
			)
		),
		array(
			'class' => 'bootstrap.widgets.TbMenu',
			'htmlOptions'=>array(
				'class'=>'pull-right'
			),
			'items' => array(
				array('label'=>'Выход','icon'=>'signout','url'=>Yii::app()->createUrl('site/logout')),
			)
		)
	)
));
?>
<div class="clear"></div>
</header>