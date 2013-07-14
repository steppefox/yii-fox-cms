
<div id="main">

    <div id="content">
        <div class="toolbar">
					<div class="right">
           <?php echo XHtml::cloudButton(
                        'btnAddExport', 
                        'Product toevoegen', 
                        'ui-icon-circle-plus',
                        $this->createUrl('create'),
                        null, 
                        'blue'
                ); ?>
						<?php echo XHtml::cloudButton(
                        'btnCreateBeslist', 
                        'Maak Beslist CSV', 
                        'ui-icon-circle-plus',
                        $this->createUrl('toBeslist'),
                        null, 
                        'green'
                ); ?>
						<?php echo XHtml::cloudButton(
                        'btnCreateKieskeurig', 
                        'Maak Kieskeurig CSV', 
                        'ui-icon-circle-plus',
                        $this->createUrl('toKieskeurig'),
                        null, 
                        'green'
                ); ?>
						<?php echo XHtml::cloudButton(
                        'btnCreateVergelijk', 
                        'Maak Vergelijk.nl CSV', 
                        'ui-icon-circle-plus',
                        $this->createUrl('toVergelijk'),
                        null, 
                        'green'
                ); ?>
						
					</div>
        </div>

    <div class="content">

        <?php if (Yii::app()->user->hasFlash('success')): ?>
            <div class="statusbar alert_success">
                <?php echo Yii::app()->user->getFlash('success'); ?>
            </div>
        <?php endif; ?>

        <?php
        $this->widget('zii.widgets.grid.CGridView', array(
            'id' => 'export-grid',
            'dataProvider' => $model->search(),
            'filter' => $model,
            //'selectableRows' => 2,
            'columns' => array(
                array('name'=>'product.sku'),
								array(
                    'class' => 'ButtonColumn',
                    'template' => '{update} {delete}',
                    'name' => 'product.name',
                    'buttons' => array(
                        'update' => array('visible' => 'Yii::app()->administration->isHQ()'),
                        'delete' => array('visible' => 'Yii::app()->administration->isHQ()'),
                    ),
                ),
								array(
										'name'=>'product.status',
										'value'=>'$data->product->statusText',
								),
								array(
										'name'=>'beslist',
										'value'=>'($data->beslist) ? "Aan" : "Uit"',
										'filter'=>array(1=>'Aan', 0=>'Uit'),
								),
								array(
										'name'=>'kieskeurig',
										'value'=>'($data->kieskeurig) ? "Aan" : "Uit"',
										'filter'=>array(1=>'Aan', 0=>'Uit'),
								),
								array(
										'name'=>'vergelijk',
										'value'=>'($data->vergelijk) ? "Aan" : "Uit"',
										'filter'=>array(1=>'Aan', 0=>'Uit'),
								),
            ),
        ));
        ?>
    </div>
    </div>
</div>