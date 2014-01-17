<?php

class TestController extends Controller
{

    public function actionAdmin(){
        $crit = new CDbCriteria();
        $crit->addCondition('login = :p_login');
        $crit->params = array(':p_login'=>'root');
        $user = User::model()->find($crit);
        if(!$user){
            $user = new User;
            $user->email = 'root@steppefox.kz';
        }
        $user->login = 'root';
        $user->status = 1;
        $user->password = CPasswordHelper::hashPassword('1111');
        $user->save();
    }

    public function actionUser(){
        $crit = new CDbCriteria();
        $crit->addCondition('login = :p_login');
        $crit->params = array(':p_login'=>'user');
        $user = User::model()->find($crit);
        if(!$user){
            $user = new User;
            $user->email = 'user@steppefox.kz';
        }
        $user->login = 'user';
        $user->status = 1;
        $user->password = CPasswordHelper::hashPassword('1111');
        $user->save();
    }

}