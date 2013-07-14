 <div id="main">
     <?php
                $this->widget('zii.widgets.grid.CGridView', array(
                    'id' => 'product-grid',
                    'dataProvider' => $model->priceChange() ,
                    'selectableRows'=>2,
                    'columns' => array(
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
                            'template' => '{update}',
                            'name' => 'code',
                            'buttons'=>array(
                                'update' => array(
                                    'label'=>'Bewerken',
                                    'url'=>'Yii::app()->controller->createUrl("/admin/catalog/product/update", array("id"=>$data->product->id, "pixp"=>$data->price_discount))',
                                ),
                            ),
                        ),
                        'title',
                        array(
                            'header'=>'Nieuwe Pixmania prijs',
                            'value'=>'$data->price_discount',
                        ),
                        'product.stock_price',
                        array(
                            'header'=>'Verkoop prijs',
                            'value'=>'$data->product->priceText',
                        ),
                        array(
                            'header'=>'Beschikbaar',
                            'value'=>'$data->availability',
                        ),
                    ),
                ));
        ?>
 </div>