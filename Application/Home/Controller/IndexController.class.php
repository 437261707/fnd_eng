<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        $model = D('Video');
        $post = $model->where("id=1")->find();
        $this->assign('post', $post);
        $this->display();
    }
    public function health(){
        $this->display();
    }
    public function plat(){
        $model = D('Post');
        $where['status'] = 1;
        $count  = $model->where($where)->count();
        $Page = new \Extend\Page($count,10);
        $show = $Page->show();
        $post = $model->limit($Page->firstRow.','.$Page->listRows)->where($where)->order('id DESC')->select();
        $this->assign('model', $post);
        $this->assign('page',$show);
        $this->display();
    }

    public function platdetail($id){
        $model = D('post')->where("id= %d",$id)->find();
        $this->assign('model', $model);//页面数据
        $this->display();
    }
    public function product(){
        $this->display();
    }
    public function water(){
        $this->display();
    }
    public function shop(){
        $this->display();
    }
}