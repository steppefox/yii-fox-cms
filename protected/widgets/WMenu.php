<?php
class WMenu extends CWidget
{
	public function run(){
		$crit = new CDbCriteria();
		$crit->condition = 'status = 1 AND type=:p_type';
		$crit->params = array(':p_type'=>Navigation::TOP_MENU);
		$models = Navigation::model()->findAll($crit);
		$this->render('wMenu',compact('models'));
	}
}