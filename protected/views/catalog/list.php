<?
	$modelsCount = count($models);
	$counter = 0;
?>
<?php foreach ($models as $modelKey => $model): ?>
	<?$counter++;?>
	<?php if (($counter%3)==1): ?>
	<div class="row">
	<?php endif ?>
		<div class="span4">
			<div class="well">
				<div class="catalog-list-ItemImage">

				</div>
				<div class="catalog-list-ItemTitle">
					<a href="<?=$this->createUrl('catalog/show',array('id'=>$model->id))?>">
						<?=$model->at('title')?>
					</a>
				</div>
				<div class="catalog-list-ItemDescription">
					<?=$model->at('description')?>
				</div>
				<div class="catalog-list-ItemMenu">
					<div class="pull-left catalog-list-ItemPrice">
						<?=Catalog::getNicePrice($model->getPrice())?> тг.
					</div>
					<a href="<?=$this->createUrl('catalog/show',array('id'=>$model->id))?>" class="btn btn-success pull-right">
						Купить
					</a>
					<div class="clear"></div>
				</div>
			</div>
		</div>
	<?php if (($counter%3)==0 || $counter==$modelsCount): ?>
	</div>
	<?php endif ?>
<?php endforeach ?>