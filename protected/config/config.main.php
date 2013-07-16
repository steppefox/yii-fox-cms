<?php
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'ApplicationName',
    'defaultController'=>'Site',
    'language' => 'ru', //'en_gb',
    'preload' => array('log', 'administration'),
    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.widgets.*',
        'application.modules.admin.modules.rights.*',
        'application.modules.admin.modules.rights.components.*',
    ),
    'modules' => array(
        'admin'=>array(
            'modules' => array(
                'rights'=>array(
                    'install' => true,
                    'userNameColumn'=>'login',
                    'appLayout'=>'application.modules.admin.views.layouts.main'
                )
            )
        ),
        'gii'=>array(
            'class'=>'system.gii.GiiModule',
            'password'=>'lemonade',
            // 'ipFilters'=>array(...a list of IPs...),
            // 'newFileMode'=>0666,
            // 'newDirMode'=>0777,
        ),
    ),
    // application components
    'components' => array(
        'shoppingCart' => array(
            'class' => 'application.modules.sales.components.EShoppingCart',
        ),
        'image'=>array(
            'class'=>'ext.image.CImageComponent',
            'driver'=>'GD', // GD or ImageMagick
        ),
        'user' => array(
            // enable cookie-based authentication
            'class' => 'RWebUser',
            'allowAutoLogin' => true,
            'loginUrl'=>array('/site/login'),
        ),
        'authManager' => array(//add this
                'class' => 'RDbAuthManager',//add this
        ),//add this
		'assetManager' => array(
             'linkAssets' => true,
        ),
        // uncomment the following to enable URLs in path-format

        'urlManager' => array(
            'urlFormat' => 'path',
            'showScriptName' => false,
            'caseSensitive' => true,
            'rules' => array(
                'admin'=>'admin/',
                'gii'=>'gii/',
                'p/<name:\w+>' => 'page/show',
                // '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ),
        'db' => array(
            'connectionString' => 'mysql:host=localhost;dbname=dbname',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => '123',
            'charset' => 'utf8',
            // 'schemaCachingDuration' => 3600,
            'enableParamLogging'=>true,
        ),
        // 'cache'=>array(
        //     'class'=>'system.caching.CDummyCache', //CApcCache',
        // ),
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'session'=>array(
            'class'=>'CHttpSession',
            'cookieMode' => 'allow',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                //'categories'=>'application.components.CSaveRelatedBehavior',
                ),
                array(
                    'class' => 'CWebLogRoute',
                    'categories' => 'system.db.CDbCommand',
                    'showInFireBug' => true,
                ),
            ),
        ),
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(

    ),
);