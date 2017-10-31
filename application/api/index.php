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
            'user_name'=>'bob',
            'password'=>md5('123456'),
            'last_login'=>time()
        ));
    }

    public function tt(){
        $base_model = new Base_model();
        $data = $base_model->select('user', '*', [
            'ORDER'=>['last_login'=>'DESC', 'user_id'=>'ASC'],
            'LIMIT'=>1
        ]);
        var_dump($data);
    }
    public function upload(){
        $a = $this->pack_input('aa');
        $handle = new upload($_FILES['image_field']);
        if ($handle->uploaded) {
            $handle->file_new_name_body   = 'image_resized';
            $handle->image_resize         = true;
            $handle->image_x              = 100;
            $handle->image_ratio_y        = true;
            $handle->process(UPLOAD_PATH);
            if ($handle->processed) {
                echo $a;
                $handle->clean();
            } else {
                echo 'error : ' . $handle->error;
            }
        }
    }
}