<?php
namespace app\index\controller;

use \think\Controller;
use app\index\model\User as UserModel;
use app\index\model\Topic as TopicModel;
use \think\Validate;
use \think\Session;

class News extends Controller
{
	protected $user;
	public function _initialize()
	{
		$this->user = new UserModel();
		$this->topic = new TopicModel();
	}
	public function news()
	{
		$topic = $this->topic->getAlltopic();
		$this->assign(['topic'=>$topic, 'location'=>'最新新闻']);
		if (session('?uid')) {
			$uid = Session::get('uid');
			$data = $this->user->where('uid', $uid)->find();
			$this->assign(['islog'=>1, 'data'=>$data]);
		} else {
			$this->assign(['islog'=>0]);
		}
		return $this->fetch();
	}
}