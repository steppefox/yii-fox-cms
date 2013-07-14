<?php

class EFolderValidator extends CValidator {

    public $allowEmpty = true;
    /**
     * Validates the attribute of the object.
     * If there is any error, the error message is added to the object.
     * @param CModel the object being validated
     * @param string the attribute being validated
     */
    protected function validateAttribute($object, $attribute)
   {
      $valid = false;
      
      if (is_object($object) && isset($object->$attribute))
      {
          $valid = $this->checkChars($object->$attribute);
      }
      if (!$valid) {
         $message = $this->message !== null ? $this->message : Yii::t('validator', 'The folder name has illegal characters');
         $this->addError($object, $attribute, $message);
      }
   }
   
   /**
    * Check if it contains just the allowed characters 
    * @link http://tools.ietf.org/html/rfc3986#section-2
    *
    * @param string $uri the URI
    * @return boolean
    */
   private function checkChars($uri)
   {
      if (empty($uri)) {
         return $this->allowEmpty;
      }
      if (preg_match("/[^a-z0-9 \[\]\(\)\.\-\_\$]/i", $uri)){
         return false;
      }
      return true;
   } 
}
?>
