<?php
/**
 * Created by PhpStorm.
 * User: zhangjianbo
 * Date: 2017/10/30
 * Time: 上午10:21
 */

class Index extends Base_controller
{
    public function test(){
        $a = $this->pack_input('a');
        $this->response_suc_msg($a);
    }

    public function init_user(){
        $model = new Base_model();
        $model->insert('user', array(
            'user_name'=>'admin',
            'password'=>md5('123456'),
            'last_login'=>time()
        ));
    }

}