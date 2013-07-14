
 <div id="main">
     <?php
                $this->widget('zii.widgets.grid.CGridView', array(
                    'id' => 'product-grid',
                    'dataProvider' => $model->stockChange() ,
                    'selectableRows'=>2,
                    'columns' => array(
                        array(
                            'header' => Yii::t('backend', 'Image'),
                            'class' => 'CPreviewColumn',
                            'url' => '$data->pixmania->picture_url',
                            'width' => 80,
                            'height' => 80,
                            'htmlOptions'=>array('width'=> "80"),
                        ),
                        array(
                            'class' => 'ButtonColumn',
                            'template' => '{update}',
                            'name' => 'sku',
                            'buttons'=>array(
                                'update' => array(
                                    'label'=>'Bewerken',
                                    'url'=>'Yii::app()->controller->createUrl("/admin/catalog/product/update", array("id"=>$data->id))',
                                ),
                            ),
                        ),
                        'name',
                        array(
                            'header'=>'Pixmania Prijs',
                            'value'=>'$data->pixmania->priceText',
                        ),
                        array(
                            'header'=>'Oude pixmania prijs',
                            'value'=>'$data->stockPriceText',
                        ),
                        array(
                            'header'=>'Prijs',
                            'value'=>'$data->priceText',
                        ),
                        
                        array(
                            'header'=>'Status',
                            'value'=>'$data->statusText',
                        ),
                        array(
                            'header'=>'Beschikbaar',
                            'value'=>'$data->pixmania->availability',
                        ),
                    ),
                ));
        ?>
 </div>
