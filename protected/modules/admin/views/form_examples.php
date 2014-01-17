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
        'model' => $model,
        'attribute' => 'image',
        'multiple' => true,
        'showForm'=>false,
    ));
?>