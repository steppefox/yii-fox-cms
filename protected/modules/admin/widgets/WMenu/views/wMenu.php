<header id="header">
<?
$this->widget('bootstrap.widgets.TbNavbar', array(
	'brand' => Yii::app()->name,
	'brandUrl'=> Yii::app()->getBaseUrl(true),
	'items' => array(
		array(
			'class' => 'bootstrap.widgets.TbMenu',
			'items' => array(
				array('label'=>'Главная', 'url'=>Yii::app()->controller->createUrl('default/index')),
				array('label'=>'Модули', 'url'=>'#','items'=>array(
					array('label'=>'Навигация', 'icon'=>'tag','url'=>$this->controller->createUrl('navigation/list')),
					array('label'=>'Новости', 'icon'=>'tag','url'=>$this->controller->createUrl('news/list')),
					array('label'=>'Текстовые страницы', 'icon'=>'file','url'=>$this->controller->createUrl('page/list')),
					array('label'=>'Слайдер', 'icon'=>'tag','url'=>$this->controller->createUrl('slider/list')),
					array('label'=>'Партнеры', 'icon'=>'tag','url'=>$this->controller->createUrl('partner/list')),
					array('label'=>'Сертификаты', 'icon'=>'tag','url'=>$this->controller->createUrl('sertificate/list')),
					array('label'=>'Системные настройки', 'icon'=>'cog','url'=>$this->controller->createUrl('setting/list')),
				)),
				array('label'=>'Продукция', 'url'=>'#','items'=>array(
					array('label'=>'Категории', 'icon'=>'tag','url'=>$this->controller->createUrl('catalogCategory/list')),
					array('label'=>'Каталог', 'icon'=>'tag','url'=>$this->controller->createUrl('catalog/list')),
				)),
			)
		),
		array(
			'class' => 'bootstrap.widgets.TbMenu',
			'htmlOptions'=>array(
				'class'=>'pull-right'
			),
			'items' => array(
				array('label'=>'На сайт','icon'=>'signout','url'=>Yii::app()->getBaseUrl(true)),
				array('label'=>'Выход','icon'=>'signout','url'=>Yii::app()->createUrl('site/logout')),
			)
		)
	)
));
?>
<div class="clear"></div>
</header>