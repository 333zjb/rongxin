<?php
/**
 * Created by PhpStorm.
 * User: zhangjianbo
 * Date: 2017/10/31
 * Time: 上午12:13
 */

class Admin_controller extends Base_controller
{
    protected $user_id;
    public function __construct()
    {
        parent::__construct();
        $this->user_id = $this->get_session('user_id');
        if($this->user_id === ''){
            $this->response_error_msg(null, '请登录');
        }
    }
}