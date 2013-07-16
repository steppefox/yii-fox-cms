<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="language" content="ru" />

	<title>
		<?php echo CHtml::encode($this->pageTitle); ?>
	</title>

	<meta name="description" content="">
    <meta name="viewport" content="width=device-width">


	<?php foreach ($this->registerCss as $css):?>
	<link rel="stylesheet" href="<?=$css['path']?>" media="<?=$css['media']?$css['media']:'all'?>" />
	<?php endforeach;?>

	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="./css/ie.css" media="screen, projection" />
	<![endif]-->

	<?php foreach ($this->registerJs['header'] as $js): ?>
		<script type="text/javascript" src="<?=$js?>"></script>
	<?php endforeach ?>

</head>

<body>
	<div id="wrap">
		<header id="header">
			<nav id="mainmenu">
				<div class="navbar">
				  	<div class="navbar-inner">
				    	<a class="brand" href="#">Title</a>
				    	<ul class="nav">
				      		<li class="active"><a href="#">Home</a></li>
				      		<li><a href="#">Link</a></li>
				      		<li><a href="#">Link</a></li>
				    	</ul>
				  	</div>
				</div>
			</nav>
		</header>

      	<!-- Begin page content -->
      	<div class="container" id="content">
	    	<?php echo $content; ?>
      	</div>
      	<div id="push"></div>
    </div>

	<footer id="footer">
		<div class="container">
			Made by Amantay Eldar <?=Yii::app()->user->id.' '.Yii::app()->user->name?>
		</div>
	</footer>
</body>
</html>