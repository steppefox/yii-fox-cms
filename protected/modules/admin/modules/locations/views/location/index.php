
<div id="main">
    <div class="toolbar">
        <div class="left">
            <h3>Vestigingen overzicht</h3>
        </div>
        <div class="right">
            <?php
            $this->widget('zii.widgets.jui.CJuiButton',
                    array(
                        'name' => 'btnAddLocation',
                        'buttonType' => 'link',
                        'url' => $this->createUrl('create'),
                        'caption' => 'Vestiging toevoegen',
                        'options' => array('icons' => array('primary' => 'ui-icon-circle-plus')),
                    )
            ); ?>
        </div>
    </div>



        <?php if (Yii::app()->user->hasFlash('locationSaved')): ?>
                <div class="statusbar alert_success">
        <?php echo Yii::app()->user->getFlash('locationSaved'); ?>
                </div>
        <?php endif; ?>

        <?php
        $provider=$model->search();
        $provider->pagination->pageSize=1000;

                $this->widget('zii.widgets.grid.CGridView', array(
                    'id' => 'product-grid',
                    'dataProvider' => $provider,
                    'filter' => $model,
                    'columns' => array(
                        //array('name'=>'serial_number', 'header'=>'thumb'),
                        array(
                            'class' => 'ButtonColumn',
                            'template' => '{update} {delete}',
                            'name' => 'title',
                        ),
                        'address',
                        'zipcode',
                        'place',
                        'phone_nb',
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