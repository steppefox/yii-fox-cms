<ul>
	<?php foreach($this->getLatestNews() as $news): ?>
	<li><?php echo $news->createDateText; ?>:<br>
		<?php echo CHtml::link(CHtml::encode($news->title), $news->getUrl()); ?>
	</li>
	<?php endforeach; ?>
</ul>