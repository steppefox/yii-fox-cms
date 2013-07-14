<?php

class ImportController extends AdminController {

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow',
                'actions'=>array('updatePixmaniaList'),
                'users'=>array('*'),
            ),
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'import', 'updatePixmaniaList'),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionUpdatePixmaniaList()
    {
				// 10 minutes execution time
				@set_time_limit(10 * 60);
        $file = fopen('http://www.pixmania-pro.com/pixprofeeds/3bb2df9149982cbedf344b333b70cdbd/1', 'r');
        
        $sth = Yii::app()->db->createCommand();
        $sth->truncateTable('pixmania');
        
        while (($line = fgetcsv($file, 100000, ";")) != FALSE)
        {
            $sth = Yii::app()->db->createCommand();
            $sth->insert('pixmania', array(
                'category'=>$line[0],
                'sub_category'=>$line[1],
                'subsub_category'=>$line[2],
                'code'=>$line[3],
                'brand'=>$line[4],
                'title'=>$line[5],
                'description'=>$line[6],
                'price_discount'=>$line[7],
                'delivery_costs'=>$line[8],
                'price_before_discount'=>$line[9],
                'picture_url'=>$line[10],
                'availability'=>$line[11],
                'volumetric_weight'=>$line[13],
                'weight'=>$line[12],
            ));
        }
        
        echo "Update uitgevoerd!";
    }

    public function actionDownloadCsv() {
        $test = 'http://www.pixmania.com/comp/PixPro/top_vente/fichiers/nl/revendeur_nl.csv';
        //$real = 'http://www.pixmania.com/comp/PixPro/fichiers/nl/revendeur_nl.csv';
        $real = 'http://www.pixmania-pro.com/pixprofeeds/3bb2df9149982cbedf344b333b70cdbd/1';

        $local = Yii::getPathOfAlias('webroot') . "/assets/revendeur_nl.csv";

        $cp = curl_init($real);
        $fp = fopen($local, "w");

        curl_setopt($cp, CURLOPT_FILE, $fp);
        curl_setopt($cp, CURLOPT_HEADER, 0);

        echo curl_exec($cp);
        curl_close($cp);
        fclose($fp);
    }

    public function actionLoadToDatabase() {
        //SQL: LOAD DATA LOCAL INFILE 'C:\\xampplite\\tmp\\phpDF.tmp' REPLACE INTO TABLE `pixmania` FIELDS TERMINATED BY ';' ESCAPED BY '\\' LINES TERMINATED BY '\r\n'
        $path = addslashes(Yii::getPathOfAlias('webroot') . "/assets/revendeur_nl.csv");
        $sql = "LOAD DATA INFILE '$path' REPLACE INTO TABLE `pixmania` FIELDS TERMINATED BY ';' ESCAPED BY '\\\\' LINES TERMINATED BY '\\r\\n'";
        Yii::app()->db->createCommand($sql)->execute();

        echo "should be inserted";
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionImport($code) {
        $pixmodel = Pixmania::model()->findByPk($code);

        $model = new Product;

        $model->sku = $pixmodel->code;
        $model->name = $pixmodel->title;
        $model->description = $pixmodel->description;
        $model->stock_price = $pixmodel->price_discount;
        $model->weight = $pixmodel->volumetric_weight;
        $model->manufacturer = ucfirst(strtolower($pixmodel->brand));

        if (isset($_POST['Product'])) {
            $model->attributes = $_POST['Product'];
            if ($model->withRelated->save(array('mediaLinks', 'relatedProducts', 'propertyLinks'))) {
                Yii::app()->user->setFlash('success', 'The product was successfully saved!');
                $this->redirect(array('index'));
            }
        }

        $this->render('application.modules.admin.modules.catalog.views.product.form', array(
            'model' => $model,
        ));
    }

    /**
     * Lists all models of importation
     */
    public function actionIndex() {
        $model = new Pixmania('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Pixmania']))
            $model->attributes = $_GET['Pixmania'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

}