<?php
/**
 * Created by PhpStorm.
 * User: zhangjianbo
 * Date: 2017/10/31
 * Time: 下午5:02
 */

class News extends Base_controller
{
    public function show(){
        $cur_page = $this->pack_input('cur_page');
        $page_size = 7;
        $base_model = new Base_model();

        $top_data = $base_model->select('news', '*', [
            'is_delete'=>0,
            'ORDER'=>['is_first'=>'DESC', 'modify_time'=>'DESC'],
            'LIMIT'=>1
        ]);

        if(!$top_data){
           $this->response_error_msg(null, '暂无数据');
        }

        $count = $base_model->count('news', ['is_delete'=>0]);
        $page_num = ceil(($count-1)/7);
        $page_num = ($page_num === 0 ? 1 : $page_num);

        $data = $base_model->select('news', '*', [
            'is_delete'=>0,
            'news_id[!]'=>$top_data[0]['news_id'],
            'ORDER'=>['is_first'=>'DESC', 'modify_time'=>'DESC'],
            'LIMIT'=>[($cur_page-1)*$page_size, $page_size]
        ]);

        $result['cur_page'] = $cur_page;
        $result['page_num'] = $page_num;
        $result['top_data'] = $top_data[0];
        $result['list'] = $data;
        $this->response_suc_msg($result);
    }
}