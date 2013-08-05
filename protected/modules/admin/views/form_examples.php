<? $this->widget('ImperaviRedactorWidget', array(
    'model' => $model,
    'attribute' => 'text_ru',
    //'name' => 'my_input_name',
    'options' => array(
        'lang' => 'ru',
        'toolbar' => true,
        'iframe' => true,
        'css' => 'wym.css',
    ),
    'htmlOptions'=>array(
        'style'=>'height:200px',
    )
));?>

<?php
    $this->widget('xupload.XUpload', array(
        'url' => $this->createUrl($this->id."/upload",array("id" => (int)$model->id,"attribute" => "image","model" => get_class($model))),
        'model' => $model,
        'attribute' => 'image',
        'multiple' => true,
        'showForm'=>true,
    ));
?>