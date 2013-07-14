
<div id="main">
    
<div id="left-panel">
    <div class="toolbar">
        lijsten
        
    </div>
    <div class="content">

        <?php echo CHtml::link('Niet op voorraad', $this->createUrl('lists/stockChange')); ?><br />
        <?php echo CHtml::link('Prijs wijziging', $this->createUrl('lists/priceChange')); ?><br />
        <?php echo CHtml::link('Uit de lijst', $this->createUrl('lists/notFound')); ?><br />

    </div>
</div>



        <div id="content" class="has-sidebar">

					<div class="toolbar">
            <div class="right">
                <?php echo XHtml::cloudButton(
                        'btnAddContent', 
                        Yii::t('backend', 'Import pixmania lijst handmatig'),
                        'ui-icon-circle-plus',
                        $this->createUrl('updatePixmaniaList'),
                        null, 
                        'blue'
                ); ?>
            </div>
        </div>
					
        <?php if (Yii::app()->user->hasFlash('success')): ?>
                <div class="statusbar alert_success">
                    <?php echo Yii::app()->user->getFlash('success'); ?>
                </div>
        <?php endif; ?>


        <?php
                $this->widget('zii.widgets.grid.CGridView', array(
                    'id' => 'product-grid',
                    'dataProvider' => $model->search(),
                    'filter' => $model,
                    'selectableRows'=>2,
                    'columns' => array(
                        //array('name'=>'serial_number', 'header'=>'thumb'),
                        array(
                            'class'=>'CCheckBoxColumn',
                            'checked'=>'$data->hasProductText',
                            'header'=>'In winkel',
                            'selectableRows'=>1,
                            'checkBoxHtmlOptions'=>array('disabled'=>'disabled')
                        ),
                        array(
                            'header' => Yii::t('backend', 'Image'),
                            'class' => 'CPreviewColumn',
                            'url' => '$data->picture_url',
                            'width' => 80,
                            'height' => 80,
                            'htmlOptions'=>array('width'=> "80"),
                        ),
                        array(
                            'class' => 'ButtonColumn',
                            'template' => '{import}',
                            'name' => 'code',
                            'buttons'=>array(
                                'import' => array(
                                    'label'=>'Importeren',
                                    'url'=>'Yii::app()->controller->createUrl("import", array("code"=>$data->code))',
                                ),
                            ),
                        ),
                        'title',
                        'description',
                        array(
                            'header' => 'Weight',
                            'value' => '$data->volumetric_weight . "kg"',
                            'filter' => false,
                        ),
                        array(
                            'header'=>'prijs',
                            'value'=>'$data->price_discount',
                        ),
                        array(
                            'header'=>'Beschikbaar',
                            'value'=>'$data->availability',
                        ),
                        
                    //'create_date',
                    /*
                      'update_date',
                      'meta_keywords',
                      'status',
                     */
                    ),
                ));
        ?>

    </div>
</div>
