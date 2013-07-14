<div id="costDialog"></div>

<div id="main">

    <div id="left-panel">
        <?php if (Yii::app()->administration->isHQ()): ?>
            <div class="toolbar">
                <?php echo XHtml::cloudButton(
                        'btnAddFolder', 
                        "Categorie toevoegen", 
                        'ui-icon-circle-plus',
                        $this->createUrl('category/create'),
                        null, 
                        'blue'
                ); ?>
                
                

            </div>
        <?php endif; ?>
        <div class="content">

            <?php
            $this->beginWidget('application.extensions.nestedSortable.ENestedSortable', array(
                "id" => 'tree',
                "data" => $categories,
                "updateUrl" => Yii::app()->controller->createUrl('category/update'),
                "deleteUrl" => Yii::app()->controller->createUrl('category/delete'),
                "onclick" => "function(event) {
                if($(event.target).parent('.ui-button').length==0)
                {
                    $('#tree div').removeClass('ui-selected');
                    $(this).children('div').addClass('ui-selected');
                    var cat_id = $(this).attr('id').substr(5);
                    $.fn.yiiGridView.update('product-grid', {
                            url: '" . Yii::app()->controller->createUrl('index') . "/category_id/' + cat_id
                        });
                
                    return false;
                }
            }",
                'options' => array(
                    'update' => "js:function(event, ui) {
                    $.ajax({
                            type: 'POST',
                            url: '" . Yii::app()->controller->createUrl('category/order') . "',
                            data: $(this).nestedSortable('serialize'),
                            dataType: 'html',
                            error: function(XMLHttpRequest, textStatus, errorThrown){ return false; }
                        });
                  }",
                ),
                "htmlOptions" => array('class' => 'categories'),
            ));
            ?>
            <?php $this->endWidget(); ?>



        </div>
    </div>

    <div id="content" class="has-sidebar">
        <div class="toolbar">
            <div class="right">
                
                <?php echo XHtml::cloudButton(
                        'btnShippingCost', 
                        'Verzend kosten tabel', 
                        'ui-icon-transferthick-e-w',
                        $this->createUrl('create'),
                        "js:function(){ $('#costDialog').load(
                            '" . $this->createUrl('shippingTable') . "',
                            function() { $('#costDialog').dialog('open'); });
                            return false; }"
                ); ?>
                <?php echo XHtml::cloudButton(
                        'btnAddProduct', 
                        Yii::t('backend', 'Add product'), 
                        'ui-icon-circle-plus',
                        $this->createUrl('create'),
                        null, 
                        'blue'
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
            'id' => 'product-grid',
            'dataProvider' => $model->search(),
            'filter' => $model,
            'selectableRows' => 2,
            'columns' => array(
                //array('name'=>'serial_number', 'header'=>'thumb'),
                array(
                    'name' => 'id',
                    'id' => 'productSelect',
                    'class' => 'CCheckBoxColumn',
                //'header' => CHtml::checkBox('productSelect_all',false),
                ),
                array(
                    'header' => Yii::t('backend', 'Image'),
                    'class' => 'CPreviewColumn',
                    'url' => '$data->thumb',
                    'width' => 80,
                    'height' => 80,
                    'htmlOptions' => array('width' => "80"),
                ),
                array(
                    'class' => 'ButtonColumn',
                    'template' => '{update} {delete}',
                    'name' => 'sku',
                    'buttons' => array(
                        'update' => array('visible' => 'Yii::app()->administration->isHQ()'),
                        'delete' => array('visible' => 'Yii::app()->administration->isHQ()'),
                    ),
                ),
                'name',
                'meta_description',
                array(
                    'header' => 'Weight',
                    'value' => '$data->weight . "kg"',
                    'filter' => false,
                ),
                array(
                    'name' => 'price',
                    'value' => '$data->strokedPriceText',
										'filter'=>false,
										'type'=>'html',
                ),
                array(
                    'name' => 'status',
                    'value' => '$data->statusText',
                    'filter' => Product::model()->productStatus,
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
</div>