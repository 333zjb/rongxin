<?php
/**
 * Created by PhpStorm.
 * User: zhangjianbo
 * Date: 2017/10/31
 * Time: 下午4:11
 */

class News extends Admin_controller
{
    public $base_model;
    public function __construct()
    {
        parent::__construct();
        $this->base_model = new Base_model();
    }

    public function create(){
        $data['type'] = $this->pack_input('type', 1);
        $data['key_word'] = $this->pack_input('key_word');
        $data['is_first'] = $this->pack_input('is_first', 0);
        $data['add_time'] = time();
        $data['modify_time'] = time();
        $data['title'] = $this->pack_input('title');
        $data['abstract'] = $this->pack_input('abstract');
        $data['content'] = $this->pack_input('content');
        $data['editor'] = $this->pack_input('editor', '融网智信公关部');
        $data['origin'] = $this->pack_input('origin', '融网智信');
        $data['is_delete'] = 0;

        $this->base_model->insert('news', $data);
        $this->response_suc_msg(null, '添加成功');
    }

    public function update(){
        $news_id = $this->pack_input('news_id');
        $data['type'] = $this->pack_input('type', 1);
        $data['key_word'] = $this->pack_input('key_word');
        $data['is_first'] = $this->pack_input('is_first', 0);
        $data['modify_time'] = time();
        $data['title'] = $this->pack_input('title');
        $data['abstract'] = $this->pack_input('abstract');
        $data['content'] = $this->pack_input('content');
        $data['editor'] = $this->pack_input('editor', '融网智信公关部');
        $data['origin'] = $this->pack_input('origin', '融网智信');
        $data['is_delete'] = $this->pack_input('is_delete', 0);

        $this->base_model->update('news', $data, ['news_id'=>$news_id]);
        $this->response_suc_msg(null, '更新成功');
    }

    public function delete(){
        $news_id = $this->pack_input('news_id');
        $this->base_model->update('news', ['is_delete'=>1], ['news_id'=>$news_id]);
        $this->response_suc_msg(null, '删除成功');
    }

}