<?php

/**
 * Include the the TCPDF class. IMPORTANT: Don't forget to customize its configuration
 * if needed.
 */
require_once(dirname(__FILE__).'/tcpdf/tcpdf.php');

/**
 * ETcPdf is a simple wrapper for the TCPDF library.
 * @see http://www.tecnick.com/public/code/cp_dpage.php?aiocp_dp=tcpdf
 *
 * @author MetaYii
 * @package application.extensions.tcpdf
 * @since 1.1
 */
class ETcPdf
{
   /**
    * The internal TCPDF object.
    *
    * @var object TCPDF
    */
   private $myTCPDF = null;

  	/**
	 * @param $orientacion: 0:Portlain, 1:Landscape
	 * @param $unit: /cm/mm/
	 * @param $format: /A3/A4/A5/Letter/Legal/array(w,h)
	 */
   public function __construct($orientation, $unit, $format, $unicode, $encoding)
   {
      if ($orientation != 'P' && $orientation != 'L')
         throw new CException(Yii::t('ETcPdf', 'The orientation must be "P" or "L"'));

      if (!in_array($unit, array('pt', 'mm', 'cm', 'in')))
         throw new CException(Yii::t('ETcPdf', 'The unit must be "pt", "in", "cm" or "mm"'));

      if (!is_string($format) && !is_array($format))
         throw new CException(Yii::t('ETcPdf', 'The format must be string or array.'));
      if (is_string($format)) {
         if (!in_array($format, array('A3', 'A4', 'A5', 'Letter', 'Legal')))
            throw new CException(Yii::t('ETcPdf', 'The format must be one of A3, A4, A5, Letter or Legal'));
      }
      else {
         if (!is_numeric($format[0]) && !is_numeric($format[1]))
            throw new CException(Yii::t('ETcPdf', 'The format must be array(w, h)'));
      }

      if (!is_bool($unicode))
         throw new CException(Yii::t('ETcPdf', '"unicode" must be a boolean value'));

		$this->myTCPDF = new TCPDF($orientation, $unit, $format, $unicode, $encoding);
   	//define ("K_PATH_CACHE", Yii::app()->getRuntimePath());
   }

   /**
    * PHP defined magic method
    *
    */
	public function __call($method, $params)
	{
		if (is_object($this->myTCPDF) && get_class($this->myTCPDF)==='TCPDF') return call_user_func_array(array($this->myTCPDF, $method), $params);
		else throw new CException(Yii::t('ETcPdf', 'Can not call a method of a non existent object'));
	}

	public function __set($name, $value)
	{
	   if (is_object($this->myTCPDF) && get_class($this->myTCPDF)==='TCPDF') $this->myTCPDF->$name = $value;
	   else throw new CException(Yii::t('ETcPdf', 'Can not set a property of a non existent object'));
	}

	public function __get($name)
	{
	   if (is_object($this->myTCPDF) && get_class($this->myTCPDF)==='TCPDF') return $this->myTCPDF->$name;
	   else throw new CException(Yii::t('ETcPdf', 'Can not access a property of a non existent object'));
	}

	/**
	 * Cleanup work before serializing.
	 * This is a PHP defined magic method.
	 * @return array the names of instance-variables to serialize.
	 */
	public function __sleep()
	{
	}

	/**
	 * This method will be automatically called when unserialization happens.
	 * This is a PHP defined magic method.
	 */
	public function __wakeup()
	{
	}
}