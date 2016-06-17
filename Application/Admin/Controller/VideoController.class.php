<?php
namespace Admin\Controller;
use Admin\Controller;

class VideoController extends BaseController{

    public function index(){
        $this->display();
    }
    public function add(){
        $this->display();
    }
    public function update(){
        //在这里添加图片路径信息
        $video = $this->uploadify();
        if($video){
            $User = D("Video");
            $User-> where("id=1")->setField('video',$video);
            $this->success("更新成功", U('video/index'));
        }
        else {
            $this->error("更新失败");
        }
    }

    function uploadify()
    {
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     838860800 ;// 设置附件上传大小
        $upload->rootPath  =     './Public/uploads/'; // 设置附件上传根目录
        $upload->uploadReplace = true;
        $upload->saveRule = 'uniqid';                     //设置上传文件命名规则,修改了UploadFile上传类

        $upload->autoSub = true;                      //是否使用子目录保存上传文件
        $upload->subType = 'date';                      //子目录创建方式，默认为hash，可以设置为hash或者date
        $upload->dateFormat = 'Ym';                     //子目录方式为date的时候指定日期格式
        //完整的头像路径
        $path = '';
        $upload->savePath = $path;
        // 上传文件
        $info   =   $upload->upload();
        //var_dump($info);exit;
        if(!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
        }else {// 上传成功 获取上传文件信息
            //下面就是要保存路径了
            $image = $info['video']['savepath'] . $info['video']['savename'];
            return $image;
        }
    }
}
