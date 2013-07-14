
<div id="main">

<div class="toolbar">
    <div class="left">
        <?php
        $this->widget('zii.widgets.jui.CJuiButton',
                array(
                    'name' => 'btnBack',
                    'buttonType' => 'link',
                    'url' => $this->createUrl('index', array('category_id'=>$model->category_id) ),
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
                'js:function(){ $("form#product-form").submit(); return false; }', 
                'green'
        ); ?>
        <?php echo XHtml::cloudButton(
                'btnCancel', 
                Yii::t('backend', 'Cancel'), 
                'ui-icon-cancel',
                $this->createUrl('index'), 
                null, 
                'blue'
        ); ?>
        <?php if(!$model->isNewRecord)
            echo XHtml::cloudButton(
                'btnDelete', 
                Yii::t('zii', 'Delete'), 
                'ui-icon-trash', 
                $this->createUrl('delete', array('id'=>$model->primaryKey)), 
                "js:function(){ return confirm('".Yii::t('zii','Are you sure you want to delete this item?')."'); }", 
                'red'
        ); ?>
    </div>

</div>

<?php
$form = $this->beginWidget('CActiveForm', array(
            'id' => 'product-form',
            'enableAjaxValidation' => false,
        ));
?>

<div id="content_form">
    <div class="form">
        <div class="one_column right_bar">
            <div class="has-rightbar">

            <div class="section">
                <div class="section-header"><?php echo ($model->isNewRecord) ? Yii::t('backend', "New page") : Yii::t('backend', "Edit page"); ?></div>
                <div class="section-content">

                    <div class="row">
        <?php echo $form->labelEx($model,'sku'); ?>
        <?php echo $form->textField($model,'sku',array('size'=>45,'maxlength'=>45)); ?>
        <?php echo $form->error($model,'sku'); ?>
    </div>
                    
    <div class="row">
        <?php echo $form->labelEx($model, 'category_id'); ?>
        <?php echo $form->dropDownList($model, 'category_id', ProductCategory::model()->getDropDownTree(), array('prompt' => '--Select one--')); ?>
        <?php echo $form->error($model, 'category_id'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'name'); ?>
        <?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>150)); ?>
        <?php echo $form->error($model,'name'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'manufacturer'); ?>
        <?php echo $form->textField($model,'manufacturer',array('size'=>45,'maxlength'=>45)); ?>
        <?php echo $form->error($model,'manufacturer'); ?>
    </div>

                </div>
            </div>

            <div class="section">
                <div class="section-header"><?php echo Yii::t('backend', 'Content'); ?></div>
                <div class="section-content">

                            <div class="row">
                        <?php echo $model->getAttributeLabel('description'); ?><Br>
                        <?php echo $form->error($model, 'description'); ?>
                        <?php
                            $this->widget('application.extensions.cleditor.ECLEditor', array(
                                'model' => $model,
                                'attribute' => 'description',
                                'options' => array(
                                    'height' => 300,
                                    'width' => '100%',
                                    'useCSS' => true,
                                ),
                            ));
                        ?>

                        <?php //echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50));  ?>
                        
                        </div>
                    <div class="row">
                        <?php echo $model->getAttributeLabel('description2'); ?><Br>
                        <?php echo $form->error($model, 'description2'); ?>
                        <?php
                            $this->widget('application.extensions.cleditor.ECLEditor', array(
                                'model' => $model,
                                'attribute' => 'description2',
                                'options' => array(
                                    'height' => 300,
                                    'width' => '100%',
                                    'useCSS' => true,
                                ),
                            ));
                        ?>

                        <?php //echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50));  ?>
                        
                        </div>

                </div>
            </div>
            
                <div class="section">
                <div class="section-header">Filters</div>
                <div class="section-content">
                    
                    <?php if($model->category == null): ?>
                    <div class="row">
                        Het product moet eerst opgeslagen worden in een categorie<Br><Br>
                        <Strong>TODO:</Strong> filters asyncroom laden zodra een categorie geselecteerd word
                    </div>
                    <?php else: ?>

                    <?php echo CHtml::errorSummary($model->propertyLinks); ?>
                    <?php foreach($model->category->propertyGroups as $filter): ?>
                        <div class="row">
                            <?php echo CHtml::label($filter->name, "Properties_$filter->id"); ?>
                            
                            <?php if($filter->type == PropertyGroup::TYPE_CHOICE || $filter->type == PropertyGroup::TYPE_SELECT): ?>
                                <?php echo CHtml::dropDownList("Properties[$filter->id]", $filter->getSelected($model->propertyLinks), CHtml::listData($filter->properties, 'id', 'name'), array('prompt'=>'(leeg)')); ?>
                            <?php elseif($filter->type == PropertyGroup::TYPE_MULTIPLE): ?>
                            <div class="checkboxlist">
                                <?php echo CHtml::checkBoxList("Properties[$filter->id]", $filter->getValues($model->propertyLinks), CHtml::listData($filter->properties, 'id', 'name') ); ?>
                            </div>
                            <?php endif; ?>
                            
                        </div>
                    <?php endforeach; ?>
                    
                    <?php endif; ?>
                </div>
            </div>
                
                
                
            </div>
        </div>

        <div class="sidebar-column">

            <div class="section">
                <div class="section-header">Prijs</div>
                <div class="section-content">
									<?php Yii::app()->clientScript->registerScript('vatCalculation', "
										var vats = ".  json_encode($model->getVatPercentage('all')).";
										var vat_percentage = 1+ vats[$('.vat_amount').val()];
									$('input.vatinc').change(function() {
									  var price_ex = Math.round($(this).val()/vat_percentage*100)/100;
										$(this).closest('tr').find('input.vatex').val(price_ex);
									});	
									$('input.vatex').change(function() {
									  var price_inc = Math.round($(this).val()*vat_percentage*100)/100;
										$(this).closest('tr').find('input.vatinc').val(price_inc);
									});
									$('.vat_amount').change(function(){
										$('input.vatex').each(function(){
											var vat_percentage = 1+ vats[$('.vat_amount').val()];
											var price_inc = Math.round($(this).closest('tr').find('input.vatinc').val()/vat_percentage*100)/100;
											$(this).val( price_inc );
										});
									});
									", CClientScript::POS_READY); ?>
								<table>
									<tr>
										<th></th>
										<th>Incl</th>
										<th>Excl</th>
									</tr>
									<tr>
										<td><?php echo $form->labelEx($model,'price'); ?><?php echo $form->error($model,'price'); ?></td>
										<td>&euro; <?php echo $form->textField($model,'price',array('size'=>5,'maxlength'=>7, 'class'=>'vatinc')); ?></td>
										<td>&euro; <?php echo $form->textField($model,'priceExVat',array('size'=>5,'maxlength'=>7, 'class'=>'vatex')); ?></td>
									</tr>
									<tr>
										<td><?php echo $form->labelEx($model,'sale_price'); ?><?php echo $form->error($model,'sale_price'); ?></td>
										<td>&euro; <?php echo $form->textField($model,'sale_price',array('size'=>5,'maxlength'=>7, 'class'=>'vatinc')); ?></td>
										<td>&euro; <?php echo $form->textField($model,'salePriceExVat',array('size'=>5,'maxlength'=>7, 'class'=>'vatex')); ?></td>
									</tr>
									<tr>
										<td><?php echo $form->labelEx($model,'stock_price'); ?><?php echo $form->error($model,'stock_price'); ?></td>
										<td>&euro; <?php echo $form->textField($model,'stock_price',array('size'=>5,'disabled'=>'disabled', 'class'=>'vatinc')); ?></td>
										<td>&euro; <?php echo $form->textField($model,'stockPriceExVat',array('size'=>5,'disabled'=>'disabled', 'class'=>'vatex')); ?></td>
									</tr>
								</table>
                    
                <div class="row">
                    <?php echo $form->labelEx($model,'weight'); ?>
                    <?php echo $form->textField($model,'weight',array('size'=>10,'maxlength'=>10)); ?> kg
                    <?php echo $form->error($model,'weight'); ?>
                </div>

                <div class="row">
                    <?php echo $form->labelEx($model,'btw_group'); ?>
                    <?php echo $form->dropDownList($model, 'btw_group', $model->getBTWGroupOptions(), array('class'=>'vat_amount')); ?>
                    <?php echo $form->error($model,'btw_group'); ?>
                </div>
           </div>
        </div> 
        <div class="section">
            <div class="section-header"><?php echo Yii::t('backend', 'Properties'); ?></div>
            <div class="section-content">
                <div class="row">
                    <?php echo XHtml::hintLabel($model,'is_bargain', 'Vink dit aan om het item onder kassakoopjes te plaatsen'); ?>
                    <?php echo $form->checkBox($model,'is_bargain'); ?>
                    <?php echo $form->error($model,'is_bargain'); ?>
                </div>
                <div class="row">
                    <?php echo $form->labelEx($model, 'status'); ?>
                    <?php echo $form->dropDownList($model, 'status', $model->getProductStatus()); ?>
                    <?php echo $form->error($model, 'status'); ?>
                </div>
                <div class="row">
                    <?php echo $form->labelEx($model, 'create_date'); ?>
                    <?php echo $model->createDateText; ?>
                    <?php echo $form->error($model, 'create_date'); ?>
                </div>

                <div class="row">
                    <?php echo $form->labelEx($model, 'update_date'); ?>
                    <?php echo $model->updateDateText; ?>
                    <?php echo $form->error($model, 'update_date'); ?>
                </div>
                
            </div>
        </div> 
            
            <div class="section">
                <div class="section-header">Verwante producten</div>
                <div class="section-content">
                    <div class="row">
                        <?php $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                                'name'=>'addRelatedProduct',
                                'source'=>$this->createUrl('/admin/catalog/product/autoCompleteRelated'),
                                // additional javascript options for the autocomplete plugin
                                'options'=>array(
                                    'showAnim'=>'fold',
                                    'minLength'=>2,
                                    'select'=>"js:function( event, ui ) {
                                        $('#relatedProductList').prepend('<li>'+
                                            '<input type=\"hidden\" value=\"'+ ui.item.id +'\" name=\"RelatedProduct[]\">'+
                                            ui.item.label +'<a class=\"close button ui-button delete-rel\" href=\"#\">'+
                                            '<span class=\"ui-icon ui-icon-closethick\"></span></a>'+
                                            '</li>');
                                        return false;
                                    }"
                                ),
                            'htmlOptions'=>array('style'=>'width: 96%'),
                        )); ?>
                        <?php Yii::app()->clientScript->registerScript('delete-related', "$('.delete-rel').live('click', function() { $(this).parent().remove(); })"); ?>
                         <div class="scrollbox" style="width: 100%;">
                             <ul id="relatedProductList" style="height: 270px;">
                                 <?php foreach($model->relatedProducts as $relatedProduct): ?>
                                 
                                    <li>
                                        <input name="RelatedProduct[]" type="hidden" value="<?php echo $relatedProduct->id; ?>">
                                        <?php echo $relatedProduct->name; ?>
                                        <a href="#" class="close button ui-button delete-rel">
                                        <span class="ui-icon ui-icon-closethick"></span></a>
                                    </li>
                                 
                                 <?php endforeach; ?>
                                 
                             </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section">
                <div class="section-header"><?php echo Yii::t('backend', 'SEO'); ?></div>
                <div class="section-content">
                        <div class="row">
                    <?php echo XHtml::hintLabel($model, 'meta_title'); ?>
                    <?php //echo $form->labelEx($model, 'meta_title'); ?>
                    <?php echo $form->textField($model, 'meta_title', array('maxlength' => 100,'style'=>'width: 90%')); ?>
                    <?php echo $form->error($model, 'meta_title'); ?>
                        </div>
                        <div class="row">
                    <?php echo XHtml::hintLabel($model, 'meta_description'); ?>
                    <?php //echo $form->labelEx($model, 'meta_description'); ?>
                    <?php echo $form->textArea($model, 'meta_description', array('rows' => 6, 'cols' => 30)); ?>
                    <?php echo $form->error($model, 'meta_description'); ?>
                        </div>

                        <div class="row">
                    <?php echo XHtml::hintLabel($model, 'meta_keywords'); ?>
                    <?php //echo $form->labelEx($model, 'meta_keywords'); ?>
                    <?php echo $form->textArea($model, 'meta_keywords', array('rows' => 6, 'cols' => 30)); ?>
                    <?php echo $form->error($model, 'meta_keywords'); ?>
                        </div>
                </div>
            </div>
                </div>
        
    <?php $this->renderPartial("application.modules.admin.views.file._manager", array('model'=>$model, 'relation'=>'mediaLinks')); ?>
        
        <br /><br />
    </div>
        </div><!-- form -->
    
<?php $this->endWidget(); ?>
               
</div>


</div>