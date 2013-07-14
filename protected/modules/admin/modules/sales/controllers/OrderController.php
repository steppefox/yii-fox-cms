<?php

/**
 * The Order controller.
 * @author Michael
 *
 */
class OrderController extends AdminController
{

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow',
                'actions' => array('index', 'update', 'invoice', 'print'),
                'expression' => '!$user->isGuest && $user->role >= User::ROLE_MODERATOR',
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionIndex()
    {
        $model = new Order('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Order']))
            $model->attributes = $_GET['Order'];

        $this->render('index', array('model' => $model));
    }

    /**
     * Updates an order.
     * only the status of an order will be updated in backend
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        $history = new OrderHistory;
        $history->order_id = $model->id;
        $history->status = $model->status;

        if (isset($_POST['OrderHistory']))
        {
            $model->setStatus($_POST['OrderHistory']['status']);
            $history->status = $_POST['OrderHistory']['status'];
            $history->customer_notified = $_POST['OrderHistory']['customer_notified'];
            
            if($history->validate() && $model->validate())
            {
                //TODO: save multiple record in a transaction
                $history->save();
                $model->save();
                Yii::app()->user->setFlash('orderSaved', 'The order was successfully updated!');
                $this->redirect(array('index'));
            }
        }

        $this->render('form', array('model'=>$model, 'history'=>$history));
    }

    public function _actionInvoice($id)
    {
        $model = Order::model()->findByPk($id);

        if($model == null)
            throw new CHttpException (404, 'invoice not found');

        $content = $this->renderPartial('application.modules.sales.views.account.print',array('model'=>$model), true);

        $pdf = Yii::createComponent('application.extensions.html2pdf.Ehtml2pdf', 'L', 'A4', 'nl', true, 'UTF-8', array(20, 20, 20, 20));

        $pdf->pdf->SetDisplayMode('fullpage');
        $pdf->pdf->SetTitle('Factuur nr: '. $model->id);
        $pdf->pdf->SetSubject('Factuur');
    	$pdf->WriteHTML($content);
    	$pdf->Output('factuur.pdf');
    }
    
    public function actionInvoice($id)
    {
        $this->renderPartial('application.modules.sales.views.account.print',array(
            'model'=>$this->loadModel($id),
        ));
    }

    public function loadModel($id)
    {
        $model = Order::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

}