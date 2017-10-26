<?php
namespace app\index\controller;

use \think\Controller;
use app\index\model\User as UserModel;
use app\index\model\Topic as TopicModel;
use app\index\model\Article as ArticleModel;
use \think\Validate;
use \think\Session;

class Details extends Controller
{
	protected $user;
	public function _initialize()
	{
		$this->user = new UserModel();
		$this->topic = new TopicModel();
		$this->article = new  ArticleModel();
	}
	public function details()
	{
		$topic = $this->topic->getAlltopic();
		$this->assign(['topic'=>$topic, 'location'=>'最新新闻']);
		if (session('?uid')) {
			$uid = Session::get('uid');
			$data = $this->user->where('uid', $uid)->find();
			$this->assign(['islog'=>1, 'data'=>$data, 'location'=>'详情', 'private'=>1]);
		} else {
			$this->assign(['islog'=> 0]);
		}
		return $this->fetch();
	}
	//文章删除
	public function delete()
	{
		$aid = $this->request->param('aid');
		$info = $this->article->deleteArt($aid);
		if ($info) {
			$this->success('删除成功', 'blog/blog');
		} else {
			$this->error('删除失败', 'blog/blog');
		}
	}
}