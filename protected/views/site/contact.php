<?php $this->pageTitle='Contact'; ?>

<h1>Neem gerust contact met ons op</h1>

<?php if(Yii::app()->user->hasFlash('contact')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('contact'); ?>
</div>

<?php else: ?>


<div class="one_half">
   <h3><?php echo Yii::app()->administration->name; ?></h3>
    <p>
        <?php echo Yii::app()->administration->address; ?><br />
        <?php echo Yii::app()->administration->postalcode . " &nbsp;" . Yii::app()->administration->place; ?><br />
        <?php echo Country::getById(Yii::app()->administration->country_code); ?><br />
        <?php echo Yii::t('lang', 'Phone number'); ?>: <?php echo Yii::app()->administration->phone_nb; ?><br />
        <?php echo Yii::t('lang', 'E-Mail'); ?>: <a href="mailto:<?php echo Yii::app()->administration->email; ?>"><?php echo Yii::app()->administration->email; ?></a>
    </p>
    <?php
            $this->widget('application.extensions.gmap.GMap', array(
                'id' => 'gmap', //id of the <div> container created
                'height' => '400px', // height of the gmap
                'width' => '285px', // width of the gmap
                'key' => Yii::app()->administration->google_maps_key, //goole API key, should be obtained for each site,it's free
                'label' => Yii::app()->administration->name, //text written in the text bubble
                'address' => array(
                    'address' => Yii::app()->administration->address, //address of the place
                    'city' => Yii::app()->administration->place, //city
                //'state' => 'CA'//state
                //'country' => 'USA' - country
                //'zip' => Yii::app()->administration->postalcode, // - zip or postal code
                )
            ));
    ?>
    <br />
</div>
<div class="one_half last">
<div class="form">

<?php $form=$this->beginWidget('CActiveForm'); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->label($model,'name'); ?>
		<?php echo $form->textField($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'email'); ?>
		<?php echo $form->textField($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'body'); ?>
		<?php echo $form->textArea($model,'body',array('rows'=>6, 'cols'=>60)); ?>
	</div>

	<div class="row submit">
		<?php echo CHtml::submitButton('Verzenden'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
</div>
<?php endif; ?>