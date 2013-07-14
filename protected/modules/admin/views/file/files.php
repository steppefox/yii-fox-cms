<?php 

$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'upload-dialog',
    // additional javascript options for the dialog plugin
    'options' => array(
        'width' => '800px',
        'resizable' => true,
        'modal' => true,
        'title' => 'Bestanden toevoegen',
        'autoOpen' => false,
        'buttons' => array(
            Yii::t('backend', 'Close') => "js:function() { 
                $(this).dialog('close'); }",
        ),
        'open'=>"js:function(event, ui) { $('#uploader > div.plupload').css('z-index','99999');   }"
    ),
));
$this->widget('application.modules.admin.widgets.plupload.PluploadWidget', array(
         'config' => array(
             //'container'=>'uploader',
             'runtimes' => 'html5,flash,gears,html4',
             'url' => $this->createUrl('UploadFilesPlupload', array('path'=>  urlencode($model->path))),
             'max_file_size' => str_replace("M", "mb", ini_get('upload_max_filesize')),
             //'max_file_size' => 32*1024*1024,
             'chunk_size' => '1mb',
             //'unique_names' => true,
             'filters' => array(
                  array('title' => Yii::t('app', 'Allowed files'), 'extensions' => 'jpg,jpeg,gif,png,dwg,pdf,csv,xls,doc'),
              ),
             //'language' => Yii::app()->language,
             'max_file_number' => 100,
             'autostart' => false,
             //'jquery_ui' => true,
             'reset_after_upload' => true,
         ),
         'callbacks' => array(
             'StateChanged' => "function(up) { if(up.state == plupload.STOPPED) $('#content').load( '".$this->createUrl('files', array('path'=>  urlencode($model->path)))."' ); }",
             //'FileUploaded' => "function(up,file,response){ $('#content').load( '".$this->createUrl('files', array('path'=>  urlencode($model->path)))."' ); console.log(response.response);}",
         ),
         'id' => 'uploader'
      ));
$this->endWidget(); 

?>

<div class="toolbar">
        <div class="right">
            <?php if(count($model->search()->data) == 0 && FileSystem::isDeleteAble($model->path))
                echo XHtml::cloudButton(
                    'btnDeleteFolder', 
                    Yii::t('zii', 'Delete Folder'), 
                    'ui-icon-trash', 
                    $this->createUrl('/admin/file/deleteFolder', array('path'=>  urlencode ($model->path) )), 
                    "js:function(){ return confirm('".Yii::t('zii','Are you sure you want to delete this item?')."'); }", 
                    'red'
            ); ?>
            
            <?php 
            $basename = explode("/", $model->path);
            //echo $basename[0];
            if($basename[0] != 'product' || Yii::app()->administration->isHQ())
                echo XHtml::cloudButton(
                    'btnUploadBrowse', 
                    Yii::t('backend', 'Upload Files'), 
                    'ui-icon-arrowthickstop-1-n',
                    null, 
                    "js:function(){ $('#upload-dialog').dialog('open'); }", 
                    'blue'
                );
                ?>
            </div>
    </div>

<div class="content">

<?php Yii::app()->clientScript->registerScript('selectable-media', "jQuery('#select-media-grid').selectable({
    'filter':'li',
    'stop':function(event, ui) {
            var result = $( '#selected-file' ).empty();
            var count = 0;
            
            var index=[];
            $( '.ui-selected', this ).each(function() {
                    index[count] = $(this).attr('rel');
                    count++;
            });

            if(count > 0)
                $.post('".$this->createUrl('/admin/file/PickerSelectFile')."', {ids: index} , function(data) { $('#selected-file').html(data); } );
        }});"); ?>
    
<ol id="select-media-grid">
    <?php foreach($model->search()->data as $data): ?>
    
    <li class="select-box" rel="<?php echo $data->id; ?>"><img src="<?php echo $data->thumbUrl; ?>"><span class="media-label"><?php echo $data->filename; ?></span> </li>
    
    <?php endforeach; ?>
</ol>
<?php
/*
$this->widget('zii.widgets.jui.CJuiSelectable', array(
    'id'=>'select-media-grid',
    //'items'=>CHtml::listData($model->search()->data, 'id', 'thumbUrl'),
    //'itemTemplate'=>'<li rel="{id}" class="select-box"><img src="{content}"></li>',
    // additional javascript options for the selectable plugin
    'options'=>array( //Fix multiple select
        'filter'=> 'li',
        'stop'=>"js:function(event, ui) {
            var result = $( '#selected-file' ).empty();
            var count = 0;
            
            var index=[];
            $( '.ui-selected', this ).each(function() {
                    index[count] = $(this).attr('rel');
                    count++;
            });

            if(count > 0)
                $.post('".$this->createUrl('/admin/file/PickerSelectFile')."', {ids: index} , function(data) { $('#selected-file').html(data); } );
            
            //$('#selected-file').load('".$this->createUrl('/admin/file/PickerSelectFile')."/id/' + index);
        }",
    ),
)); */
?>

</div>