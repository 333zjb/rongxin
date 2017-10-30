<?php
/**
 * Created by PhpStorm.
 * User: zhangjianbo
 * Date: 2017/10/30
 * Time: 下午11:16
 */

class User extends Base_controller
{
    public function login(){
        $user_name = $this->pack_input('user_name');
        $password = $this->pack_input('password');

        $model = new Base_model();
        $res = $model->select('user',
                                ['user_id'],
                                ['user_name'=>$user_name,
                                 'password'=>md5($password),
                                 'ORDER' => ['last_login' => "DESC"],
                                    "LIMIT" => 1
                                ]);

        if($res){
            $model->update('user',
                    ['last_login'=>time()],
                    ['user_id'=>$res[0]['user_id']]
                );
            $this->set_session('user_id', $res[0]['user_id']);
            $this->response_suc_msg();
        }
        $this->response_error_msg();
    }

}