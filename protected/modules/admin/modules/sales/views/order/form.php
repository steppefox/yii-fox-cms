<div id="main">

    <div class="toolbar">
        <div class="left">
            <?php
            $this->widget('zii.widgets.jui.CJuiButton', array(
                'name' => 'btnBack',
                'buttonType' => 'link',
                'url' => $this->createUrl('index'),
                'caption' => Yii::t('backend', 'Back'),
                'options' => array('icons' => array('primary' => 'ui-icon-circle-triangle-w')),
                    )
            );
            ?>
        </div>
        <div class="right">
        <?php echo XHtml::cloudButton(
                'btnSave', 
                Yii::t('backend', 'Save'), 
                'ui-icon-disk', null, 
                'js:function(){ $("form#order-form").submit(); return false; }', 
                'green'
        ); ?>
        </div>
    </div>

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'order-form',
        'enableAjaxValidation' => false,
            ));
    ?>

    <!--  <div id="main">-->


    <div id="content_form">
        <div class="form">
            <?php echo CHtml::errorSummary(array($model, $history)); ?>


            <div class="one_half">
                <div class="section">
                    <div class="section-header">Bestelling informatie</div>
                    <div class="section-content">

                        <div class="row">
                            <?php echo $form->label($model, 'customer.name'); ?>
                            <?php echo $model->customer->name; ?>
                        </div>

                        <div class="row">
                            <?php echo $form->label($model, 'customer.email'); ?>
                            <?php echo $model->customer->email; ?>
                        </div>

                        <div class="row">
                            <?php echo $form->label($model, 'shipping_methode'); ?>
                            <?php echo $model->shippingText; ?>
                            <?php //echo $form->dropDownList($model, 'shipping_methode', $model->shippingMethodes); ?>
                        </div>
                        <div class="row">
                            <?php echo $form->label($model, 'invoice_id'); ?>
                            <?php echo ($model->invoice_id) ? $model->invoice_id : "<span style='color: red'>(Niet gefactureerd)</span>"; ?>
                            <?php //echo $form->dropDownList($model, 'shipping_methode', $model->shippingMethodes); ?>
                        </div>

                        <div class="row">
                            <?php echo $form->label($model, 'subTotalPrice'); ?>
                            <?php echo $model->subTotalPriceText; ?>
                        </div>
                        <div class="row">
                            <?php echo CHtml::label("Verzendkosten", null); ?>
                            <?php echo Yii::app()->numberFormatter->formatCurrency($model->getShippingCosts(), "EUR"); ?>
                        </div>
                    </div>
                </div>
                <br />
                <div class="section">
                    <div class="section-header">Adres gegevens</div>
                    <div class="section-content">

                        <div class="row">
                            <?php echo $form->label($model, 'shipping_phone_nb'); ?>
                            <?php echo $model->shipping_phone_nb; ?>
                            <?php //echo $form->textField($model,'shipping_address',array('size'=>60,'maxlength'=>128)); ?>
                            <?php //echo $form->error($model,'shipping_address'); ?>
                        </div>
                        <div class="row">
                            <?php echo $form->label($model, 'shipping_address'); ?>
                            <?php echo $model->shipping_address; ?>
                            <?php //echo $form->textField($model,'shipping_address',array('size'=>60,'maxlength'=>128)); ?>
                            <?php //echo $form->error($model,'shipping_address'); ?>
                        </div>

                        <div class="row">
                            <?php echo $form->label($model, 'shipping_postalcode'); ?>
                            <?php echo $model->shipping_postalcode; ?>
                            <?php //echo $form->textField($model,'shipping_postalcode',array('size'=>10,'maxlength'=>10)); ?>
                            <?php //echo $form->error($model,'shipping_postalcode'); ?>
                        </div>

                        <div class="row">
                            <?php echo $form->label($model, 'shipping_city'); ?>
                            <?php echo $model->shipping_city; ?>
                            <?php //echo $form->textField($model,'shipping_city',array('size'=>60,'maxlength'=>128)); ?>
                            <?php //echo $form->error($model,'shipping_city'); ?>
                        </div>

                        <div class="row">
                            <?php echo $form->label($model, 'shipping_country_code'); ?>
                            <?php echo Country::getByID($model->shipping_country_code); ?>
                            <?php //echo $form->textField($model,'shipping_country_code',array('size'=>2,'maxlength'=>2)); ?>
                            <?php //echo $form->error($model,'shipping_country_code'); ?>
                        </div>
                    </div>
                </div>

            </div>


            <div class="one_half">
                <div class="section">
                    <div class="section-header">Status wijzigen</div>
                    <div class="section-content">
                        <div class="row">
                            <?php echo $form->labelEx($model, 'payment_status'); ?>
                            <?php echo $model->payment_status; ?>
                            <div class="clearboth">&nbsp;</div>
                        </div>
                        <div class="row">
                            <?php echo $form->labelEx($history, 'status'); ?>
                            <?php echo $form->dropDownList($history, 'status', $model->statusTypes); ?>
                        </div>
                        <div class="row">
                            <?php echo $form->labelEx($history, 'customer_notified'); ?>
                            <?php echo $form->checkBox($history, 'customer_notified'); ?>
                        </div>
                    </div>
                </div>
                <br />
                <div class="section">
                    <div class="section-header">Status geschiedenis</div>
                    <div class="section-content">

                        <?php
                        $this->widget('zii.widgets.grid.CGridView', array(
                            'id' => 'order-detail-grid',
                            'dataProvider' => new CArrayDataProvider($model->orderHistories),
                            //'filter'=>$model,
                            'columns' => array(
                                array(
                                    'name' => 'date_added',
                                    'header' => 'Datum',
                                    'value' => 'Yii::app()->dateFormatter->formatDateTime($data->date_added, "long")',
                                ),
                                array(
                                    'name' => 'status',
                                    'type' => 'raw',
                                    'value' => 'Order::model()->statusTypes[$data->status]',
                                ),
                                array(
                                    'name' => 'customer_notified',
                                    'header' => 'Klant geattendeerd',
                                    'value' => '($data->customer_notified) ? "Ja" : "Nee"',
                                ),
                            ),
                        ));
                        ?>
                    </div>
                </div>
                <br />
                <div class="section">
                    <div class="section-header">Producten</div>

                    <div class="section-content">

                        <?php
                        $this->widget('zii.widgets.grid.CGridView', array(
                            'id' => 'order-detail-grid',
                            'dataProvider' => new CArrayDataProvider($model->orderDetails),
                            //'filter'=>$model,
                            'columns' => array(
                                /* array(
                                  'header' => 'Preview',
                                  'class' => 'CPreviewColumn',
                                  'url' => '$data->url',
                                  'alt' => '$data->description',
                                  ), */
																'sku',
                                array(
                                    'name' => 'name',
                                    'header' => 'Product naam',
                                ),
                                array(
                                    'name' => 'quantity',
                                    'header' => 'Aantal',
                                ),
                                array(
                                    'name' => 'price',
                                    'header' => 'Prijs',
                                    'value' => '$data->priceText',
                                ),
                                array(
                                    'name' => 'totalPrice',
                                    'header' => 'Totaal prijs',
                                    'value' => '$data->totalText',
                                ),
                            /*
                              array(
                              'class' => 'CButtonColumn',
                              'template' => '{delete}',
                              'deleteButtonUrl' => 'Yii::app()->controller->createUrl("deleteDetail", array("id"=>$data->id))',
                              ), */
                            ),
                        ));
                        ?>

                    </div>
                </div>



            </div>
        </div>

        <?php $this->endWidget(); ?>
    </div>
</div>

