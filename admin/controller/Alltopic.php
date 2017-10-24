<?php
namespace app\admin\controller;

use app\admin\controller\Auth;
use app\admin\model\Article;
use app\admin\model\User;
use app\admin\model\Topic;
class Alltopic extends Auth
{
	protected $is_login = ['*'];
	protected $topic;
	public function _initialize()
	{
		$this->topic = new topic();
	}
	public function product_category()//所有话题
	{
		$data = $this->topic->getAllTopic();
		$list = $data['list'];
		if(isset($data['page'])){//文章太少不需要分页
			$page = $data['page'];
			$this->assign('page',$page);
		}
		$this->assign('list', $list);
		return $this->fetch();
	}
	public function add_category()//增加新话题
	{
		$data = $this->request->param();
		if($this->topic->addcate($data)){
			$this->success('添加成功',url('product_category'));
		}
		return $this->fetch();
	}
	public function operate_category()//拿到已有话题信息
	{
		$tid = $this->request->param('tid');
		$simplecate = $this->topic->getSimplecate($tid);
		$this->assign('simplecate',$simplecate);
		return $this->fetch();
	}
	public function updatecate()
	{
		$data = $this->request->param();
		if($this->topic->setUpdatecate($data)){
			$this->success('修改成功',url('product_category'));
		}else{
			$this->error('修改失败',url('product_category'));
		}
	}
}