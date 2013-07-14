<ul>
    <?php $i = 0; ?>
    <?php foreach($category->content as $item): ?>
        <?php $i++; ?>
        <li>
                <?php echo CHtml::link(CHtml::encode($item->title), $item->getUrl($category->alias )); ?>
        </li>
        <?php if ($i > $limit) break; ?>
    <?php endforeach; ?>
</ul>