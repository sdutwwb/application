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
	//文章详情
	public function details()
	{
		$topic = $this->topic->getAlltopic();
		$this->assign(['topic'=>$topic]);
		$aid = $this->request->param()['aid'];
		$this->article->addSelf($aid);//增加微博的阅读量一次
		if (session('?uid')) {
			$uid = Session::get('uid');
			$data = $this->user->where('uid', $uid)->find();
			$details = $this->article->getDetails($aid);//得到微博的详情
			$topicname = $this->topic->topicname($details['tid']);//得到微博所属话题名字
			$this->assign([
				'islog'    =>1,
				'data'     =>$data,
				'location' =>'详情',
				'private'  =>1,
				'details'  =>$details,
				'topicname'=>$topicname,
		]);
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