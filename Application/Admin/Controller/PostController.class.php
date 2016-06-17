<?php
namespace Admin\Controller;
use Admin\Controller;
/**
 * 文章管理
 */
class PostController extends BaseController
{
    /**
     * 文章列表
     * @return [type] [description]
     */
    public function index($key="")
    {
        if($key === ""){
            $model = D('PostView');
        }else{
            $where['post.title'] = array('like',"%$key%");
            $where['member.username'] = array('like',"%$key%");
            $where['category.title'] = array('like',"%$key%");
            $where['_logic'] = 'or';
            $model = D('PostView')->where($where);
        }

        $count  = $model->where($where)->count();// 查询满足要求的总记录数
        $Page = new \Extend\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出
        $post = $model->limit($Page->firstRow.','.$Page->listRows)->where($where)->order('post.id DESC')->select();
        $this->assign('model', $post);//这是页面数据
        $this->assign('page',$show);//这是分页的
        $this->display();
    }
    /**
     * 添加文章
     */
    public function add()
    {
        //默认显示添加表单
        if (!IS_POST) {
            $this->assign("category",getSortedCategory(M('category')->select()));
            $this->display();
        }
        if (IS_POST) {
            //如果用户提交数据
            $model = D("Post");
            $model->time = time();
            $model->user_id = 1;
            if (!$model->create()) {
                // 如果创建失败 表示验证没有通过 输出错误提示信息
                $this->error($model->getError());
                exit();
            } else {
                if ($model->add()) {
                    $this->success("添加成功", U('post/index'));
                } else {
                    $this->error("添加失败");
                }
            }
        }
    }
    /**
     * 更新文章信息
     * @param  [type] $id [文章ID]
     * @return [type]     [description]
     */
    public function update($id)
    {
        $id = intval($id);
        //默认显示添加表单
        if (!IS_POST) {
            $model = M('post')->where("id= %d",$id)->find();
            $this->assign("category",getSortedCategory(M('category')->select()));
            $this->assign('post',$model);
            $this->display();
        }
        if (IS_POST) {
            $model = D("Post");
            if (!$model->create()) {
                $this->error($model->getError());
            }else{
                if ($model->save()) {
                    $this->success("更新成功", U('post/index'));
                } else {
                    $this->error("更新失败");
                }
            }
        }
    }

    public function addimage($id)
    {
        //默认显示添加表单
        $this->assign("id",$id);
        $this->display();
    }

    public function image($id)
    {
        //在这里添加图片路径信息
        $image = $this->uploadify();
        if($image){
            $User = D("Post");
            $User-> where("id= %d",$id)->setField('image',$image);
            $this->success("更新成功", U('post/index'));
        }
         else {
            $this->error("更新失败");
        }
    }
    /**
     * 删除文章
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function delete($id)
    {
        $id = intval($id);
        $model = M('post');
        $result = $model->where("id= %d",$id)->delete();
        if($result){
            $this->success("删除成功", U('post/index'));
        }else{
            $this->error("删除失败");
        }
    }
    public function push($id) {//post到前台
        $id = intval($id);
        if (IS_GET) {
            $status = M('post') -> where("id= %d",$id) -> getField('status');
            if ($status === '0') {
                $data['status'] = 1;
            } else {
                $data['status'] = 0;
            }
            $result = M('post') -> where("id= %d",$id) -> save($data);
            if ($result && $data['status'] === 1) {
                $this -> success("发布成功", U('post/index'));
            } elseif ($result && $data['status'] === 0) {
                $this -> success("撤销成功", U('post/index'));
            } else {
                $this -> error("操作失败");
            }
        } else {
            pass;

        }
    }

    function uploadify()
    {
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     './Public/Uploads/'; // 设置附件上传根目录
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
        if(!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
        }else {// 上传成功 获取上传文件信息
            //下面就是要保存路径了
            $image = $info['image']['savepath'] . $info['image']['savename'];
            return $image;
        }
    }
}
