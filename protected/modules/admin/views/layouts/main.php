<!DOCTYPE html>
<?$angularFix = ($this->angularApplication)?'xmlns:ng="http://angularjs.org" id="ng-app" ng-app="'.$this->angularApplication.'" ':''?>
<!--[if lt IE 7]>      <html <?=$angularFix?>class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html <?=$angularFix?>class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html <?=$angularFix?>class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html <?=$angularFix?>class="no-js"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="language" content="ru" />
	<meta name="description" content="">


	<?php if ($this->angularApplication): ?>
		<!--[if lte IE 8]>
	    	<script src="<?=Yii::app()->getBaseUrl(true)?>/public/js/json2.js"></script>
	    <![endif]-->
	<?php endif ?>

	<link rel="stylesheet" type="text/css" href="/public/admin/css/main.css"/>
	<title>
		<?php echo CHtml::encode($this->pageTitle); ?>
	</title>

</head>

<body>
	<div id="wrap">
		<? $this->widget('admin.widgets.WMenu.WMenu'); ?>

      	<!-- Begin page content -->
      	<div class="container" id="content">
	    	<?php echo $content; ?>
      	</div>
      	<div id="push"></div>
    </div>

	<footer id="footer">
		<div class="container">
			Made by Amantay Eldar
		</div>
	</footer>
</body>
</html>