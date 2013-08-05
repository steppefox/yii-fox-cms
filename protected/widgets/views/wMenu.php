<header id="header">
	<nav id="mainmenu">
		<div class="navbar">
		  	<div class="navbar-inner">
		    	<a class="brand" href="<?=Yii::app()->getBaseUrl(true)?>">
					<?=Yii::app()->name?>
		    	</a>
		    	<ul class="nav">
		    		<?php foreach ($models as $modelKey => $model): ?>
		      		<li<?=($_SERVER['REQUEST_URI']==$model->url)?' class="active"':''?>>
		      			<a href="<?=$model->url ?>">
		      				<?=$model->title ?>
		      			</a>
		      		</li>
		    		<?php endforeach ?>
		    	</ul>
		  	</div>
		</div>
	</nav>
</header>