
<div id="main">

    <div id="content">
        <div class="toolbar">
           
        </div>

    <div class="content">

        <?php if (Yii::app()->user->hasFlash('success')): ?>
            <div class="statusbar alert_success">
                <?php echo Yii::app()->user->getFlash('success'); ?>
            </div>
        <?php endif; ?>


        <?php
        $this->widget('zii.widgets.grid.CGridView', array(
            'id' => 'review-grid',
            'dataProvider' => $model->search(),
            'filter' => $model,
            //'selectableRows' => 2,
            'columns' => array(
                //array('name'=>'serial_number', 'header'=>'thumb'),
                array(
                    'name' => 'id',
                    'id' => 'productSelect',
                    //'class' => 'CCheckBoxColumn',
                //'header' => CHtml::checkBox('productSelect_all',false),
                ),
								array(
                    'class' => 'ButtonColumn',
                    'template' => '{update} {delete}',
                    'name' => 'author',
                    'buttons' => array(
                        'update' => array('visible' => 'Yii::app()->administration->isHQ()'),
                        'delete' => array('visible' => 'Yii::app()->administration->isHQ()'),
                    ),
                ),
								'product.name',
								'create_date',
                'rate',
                'ip',
                array(
                    'header' => 'approved',
                    'value' => '($data->approved==1) ? "Ja" : "Nee"',
                    'filter' => false,
                ),
            ),
        ));
        ?>
    </div>
    </div>
</div>