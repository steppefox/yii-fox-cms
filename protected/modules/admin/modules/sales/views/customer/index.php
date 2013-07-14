
<div id="main">

<div class="toolbar">
    <div class="left">
        Klanten overzicht
    </div>
    <div class="right">
        <?php
        $this->widget('zii.widgets.jui.CJuiButton',
                array(
                    'name' => 'btnAddCustomer',
                    'buttonType' => 'link',
                    'url' => $this->createUrl('create'),
                    'caption' => 'Klant toevoegen',
                    'options' => array('icons' => array('primary' => 'ui-icon-circle-plus')),
                )
        ); ?>
    </div>
</div>

        <div id="content_form" class="">

        <?php if (Yii::app()->user->hasFlash('customerSaved')): ?>
                <div class="statusbar alert_success">
        <?php echo Yii::app()->user->getFlash('customerSaved'); ?>
                </div>
        <?php endif; ?>

        <?php
                $this->widget('zii.widgets.grid.CGridView', array(
                    'id' => 'customer-grid',
                    'dataProvider' => $model->search(),
                    'filter' => $model,
                    'columns' => array(
                        //array('name'=>'serial_number', 'header'=>'thumb'),

                        array(
                            'class' => 'ButtonColumn',
                            'template' => '{update}',
                            'name' => 'name',
                            'buttons'=>array('update'),
                        ),
                        'email',
                        'phone_nb',
                        'register_date',
                        array(
                            'name' => 'status',
                            'value' => '$data->statusText',
                            'filter' => Customer::model()->statusTypes,
                        ),
                    ),
                ));
        ?>
    </div>
</div>