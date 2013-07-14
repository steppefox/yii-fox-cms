
<div id="main">

<div class="toolbar">
        <div class="left">
            <h2>Bestellingen overzicht</h2>
        </div>
        <div class="right">

<?php //button for selecting time range ?>
        </div>
    </div>

        <div id="content_form" class="">

        <?php if (Yii::app()->user->hasFlash('orderSaved')): ?>
            <div class="statusbar alert_success">
                <?php echo Yii::app()->user->getFlash('orderSaved'); ?>
            </div>
        <?php endif; ?>

        <?php
                $this->widget('zii.widgets.grid.CGridView', array(
                    'id' => 'order-grid',
                    'dataProvider' => $model->search(),
                    'filter' => $model,
                    'columns' => array(
                        array(
                            'header'=>'Order nr',
                            'value'=>'$data->id',
                            'htmlOptions'=>array('style'=>'width: 60px;')
                        ),
                        array(
                            'class' => 'ButtonColumn',
                            'template' => '{update} | {factuur}',
                            'name' => 'customer.name',
                            'buttons'=>array(
                                'factuur' => array(
                                    'label'=>'Factuur',
                                    'url'=>'Yii::app()->controller->createUrl("invoice", array("id"=>$data->id))',
                                    'visible'=>'!empty($data->invoice_id)',
                                ),
                            ),
                        ),
                        array(
                            'name' => 'status',
                            'value' => '$data->statusText',
                            'filter' => Order::model()->statusTypes,
                        ),
                        'payment_status',
                        array(
                            'name'=>'create_date',
                            'value'=> 'Yii::app()->dateFormatter->formatDateTime($data->create_date, "long")',
                        ),
                        array(
                            'name'=>'shipping_methode',
                            'filter'=> Order::model()->shippingMethodes,
                            'value'=> '$data->shippingText',
                        ),
                        array(
                            'name'=>'totalPrice',
                            'value'=>'$data->totalPriceText',
                        ),
                    ),
                ));
        ?>
    </div>
</div>