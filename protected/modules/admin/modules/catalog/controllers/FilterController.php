�PNG

   IHDR   0   0   W��   	pHYs     ��    cHRM  z%  ��  ��  ��  u0  �`  :�  o�_�F  .IDATx��klE���Q���Z�p�"KS���	j�DBl ���j�#�ɤ(_��h4!!�@$h�&<���mڊ�i��� ��/Z�ݻ��.��ٻwC{6���9gf�sΙsfWH)�Ȥ0�i�$��; 8��>�H�N KJyͪ��֏߾��T'�����E�����@��4�t���G�J$%�n�Yđ��EJH$99�8ř,�!��0��|�2�� 4ʇBB ��"3!�|w�߬뺎4t l �X�pD�1�CWZ��f,R5C� ���ۿ��hv5�j[������7���B3���HC�w��l���-f������"��?���Հ/�K�����ظb՟�Υ�Kq��R�0��OMܿKu��W��y�/_���{l-�-��Sy��%j��pƝdedR��+��+n�k,�`ެb6�~US9�=�P�oO�\>������g;�G�M����*(l^S�@���BGWG\'(,��>���F�+���i|��Q.^���P0�j���O�v���"���gG�)oχS�g�z���0=��3g!Y��\�w�t�f𩾨P��<e����s�U_t�L-S�il;K�G���p���}�=�H$����F�@��z�L�d����v�|C���K�9��u��q�3���ȷ$�t'5��i����)��Ao~�|����d���9��ٹ�����	Kù� ��|���kI.����f*��JeGM���6{�d^�؁SqX��Vt3$@��/r�ǽ��d)��mŐ2�/R�jQGpJ�!%R���a֥Y7��s��T&�{�lY]���#��#�ܬ�L�-o�90�LX� aHZ��>��W��[�א��=b2�<��%��T��B�I0@����.�����*#f�+�UWT�繚�>.w���'p�{ok�7M�/����ʕ�(��aY�Ct�FGc�ӍSq���D �~����v[�X�>�yP|o��l{ⅰ�o(�^�o�!��f!����"��\��b6��RPP�~��D0̼�.2FA�yzweܼ&'#���gS0-!{��cѳ��~�)�G�ÈD��I�@�w��Cgs���-]CŃ�(󔒛��"��C�]>ϑ��8t�K�-�8#4�K։�PW_OAc[#��2ov	).7��=oK=Wz�X���l4)�Tu��Ե֏탮H�2�U��ts����� l�@fZ��4���cI`��<�_�t`!�7����M��2��O0���?�� &L���� ��-ƃ:h    IEND�B`�                                                                                                   Yii::app()->user->setFlash('success','Filter is succesvol aangepast.');
                }
            }

            $this->renderPartial('form',array(
                    'model'=>$model,
                    'detail'=>$detail,
            ));
	}
        
        /**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 */
	public function loadModel($id)
	{
		if($model===null)
		{
			if(isset($id))
				$model =  PropertyGroup::model()->findbyPk($id);
			if($model===null)
				throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && ($_POST['ajax']==='filter-form'))
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}

?>
