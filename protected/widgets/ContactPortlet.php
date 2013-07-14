<?php
Yii::import('zii.widgets.CPortlet');
 
class ContactPortlet extends CPortlet
{
    public $title='Contact';
    public $maxItems=10;
 
    protected function renderContent()
    {
        $contactform = new ContactForm();
		if(isset($_POST['ContactForm']))
		{
			$contactform->attributes=$_POST['ContactForm'];
			
			if(isset($_POST['ajax']) && $_POST['ajax']==='contact-form') //Ajax validation
			{
				echo CActiveForm::validate($contactform);
				Yii::app()->end();
			}
			
			if($contactform->validate() && $this->send($contactform))
				$this->controller->refresh();
		}
		$this->render('contactPortlet',array('model'=>$contactform));
    }
    
	/**
	 * Send an email the the adminEmail adres
	 * @param ContactForm the contact form
	 * @return boolean whether the email is send successful
	 */
	protected function send($contactform)
	{
		$headers="From: {$model->email}\r\nReply-To: {$model->email}";
		if(mail(Yii::app()->params['email'],"Contact form: Yii::app()->name",$model->body,$headers))
		{
			Yii::app()->user->setFlash('contact','Bedankt voor uw email. Wij nemen zo snel mogelijk contact met u op.');
			return true;
		}
		else
			$contactform->addError('name', 'Om technische redenen kon de email niet via dit formulier verzonden worden');
	}
}