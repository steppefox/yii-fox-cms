<?php $this->pageTitle = "Pick a location"; ?>

<h1>Pick a location</h1>
<div class="locations">
<?php foreach($administrations as $i => $administration): ?>


    <div class="one_third <?php echo (($i+1) % 3 == 0) ? "last" : ""; ?>">
        <a href="http://<?php echo $administration->subdomain . "." . $administration->domain . Yii::app()->baseUrl; ?>"><h4><?php echo $administration->name; ?></h4></a>
        <?php echo $administration->address; ?><br />
        <?php echo $administration->postalcode . " &nbsp;" . $administration->place; ?><br />
        <?php echo Country::getById($administration->country_code); ?><br />
        <?php echo Yii::t('lang', 'Phone number'); ?>: <?php echo $administration->phone_nb; ?><br />
        <?php echo Yii::t('lang', 'E-Mail'); ?>: <a href="mailto:<?php echo $administration->email; ?>"><?php echo $administration->email; ?></a>
    </div>
        
<?php endforeach; ?>
</div>