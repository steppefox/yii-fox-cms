<?php Yii::app()->clientScript->registerScript("toggle", '
  $(".user_list").hide();
  $(".me_users").show();
  $(".collapsable").click(function()
  {
    $(this).toggleClass("collapsed");
    $(this).parent().parent().next(".user_list").slideToggle(600);
    return false;
  });'); ?>
  
<?php $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    	'id'=>'userDialog',
    	'options'=>array(
			'width'=>500,
			'resizable'=>'false',
			'modal'=>'true',
        	'title'=>Yii::t('catalog', 'User'),
        	'autoOpen'=>false,
			'buttons'=>array(
				Yii::t('general', 'Save')=>"js:function() { $('form#user-form').submit(); }",
				Yii::t('general', 'Cancel')=>'js:function() { $(this).dialog("close"); }',
			),
    	),
	));

$this->endWidget('zii.widgets.jui.CJuiDialog'); ?>

<?php $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    	'id'=>'locationDialog',
    	'options'=>array(
			'width'=>500,
			'resizable'=>'false',
			'modal'=>'true',
        	'title'=>Yii::t('catalog', 'Location'),
        	'autoOpen'=>false,
			'buttons'=>array(
				Yii::t('general', 'Save')=>"js:function() { $('form#location-form').submit(); }",
				Yii::t('general', 'Cancel')=>'js:function() { $(this).dialog("close"); }',
			),
    	),
	));

$this->endWidget('zii.widgets.jui.CJuiDialog'); ?>

<div id="main">
    <div class="toolbar">
        <div class="left">
            Location overview
        </div>
        <div class="right">


            <?php
            if(Yii::app()->administration->isHQ())
            $this->widget('zii.widgets.jui.CJuiButton',
                    array(
                        'name' => 'btnAddLocation',
                        'buttonType' => 'link',
                        'url' => $this->createUrl('create'),
                        'caption' => Yii::t('backend', 'Add location'),
                        'options' => array('icons' => array('primary' => 'ui-icon-circle-plus')),
                        'onclick'=>"js:function(){ $('#locationDialog').load($(this).attr('href'), function() { $('#locationDialog').dialog('open'); } ); return false; }",
                    )
            );
            ?>
        </div>
    </div>
    
	<div id="content_form">

<?php if(Yii::app()->user->hasFlash('locationSaved')): ?>
	<div class="statusbar alert_success">
		<?php echo Yii::app()->user->getFlash('locationSaved'); ?>
	</div>
<?php endif; ?>

<?php foreach($model->search()->data as $model): ?>
<div class="location">
	<div class="titlebar">
		<div style="float: left; width: 64px; margin-top: 20px;">
			<?php $active = ($model->active) ? "online" : "offline"; ?>
			<img title="<?php echo $active; ?>" alt="<?php echo $active; ?>" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/icons/<?php echo $active; ?>.png" />
		</div>
		<div style="float: left; white-space:nowrap; width: 40%;">
			<img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/icons/flags/<?php echo strtolower($model->country_code); ?>.png" />
			<h1><?php echo $model->name; ?></h1><br>
			<div class="button-row">
				<?php echo CHtml::link("Edit", $this->createUrl('update', array('id'=>$model->primaryKey)) , array('class'=>'button')); ?>
				<?php echo CHtml::link(($model->active) ? "Deactivate" : "Activate", array('enable', 'id'=>$model->primaryKey), array('class'=>'button')); ?>
				<?php echo CHtml::link("Add user", "#" , array('class'=>'button','onclick'=>"
				$('#userDialog').load('".$this->createUrl('user/create', array('administration_id'=>$model->id))."', function() { $('#userDialog').dialog('open'); } ); 
   				return false;",)); ?>
			</div>
		</div>
		<div style="float: right; text-align: right;">
			<h2><?php echo $model->link; ?></h2><br />
			Users <span><?php echo $model->userCount; ?></span>
			<a class="collapsable toggle <?php if($model->id == Yii::app()->administration->id) echo "collapsed"; ?>" href="#"><?php echo Yii::t('backend', 'View'); ?></a>
		</div>
	</div>
	
	<div class="user_list <?php if($model->id == Yii::app()->administration->id) echo "me_users"; ?>">
		<?php $this->renderPartial('_users', array('users'=>$model->users)); ?>
	</div>
</div>
		
<?php endforeach; ?>

	</div>
	
</div>