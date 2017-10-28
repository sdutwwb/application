<?php
namespace app\index\controller;

use \think\Controller;
use app\index\model\User as UserModel;
use app\index\model\Userson as UsersonModel;
use app\index\model\Topic as TopicModel;
use app\index\model\Article as ArticleModel;
use app\index\model\Attention as AttentionModel;
use \think\Validate;
use \think\Session;

class Blog extends Controller
{
	protected $user;
	public function _initialize()
	{
		$this->user 	 = new UserModel();
		$this->userson 	 = new UsersonModel();
		$this->topic 	 = new TopicModel();
		$this->article 	 = new ArticleModel();
		$this->attention = new AttentionModel();
	}
	public function blog()
	{
		$topic = $this->topic->getAlltopic();
		$this->assign(['topic'=>$topic]);
		if (session('?uid')) {
			$uid          = Session::get('uid');
			$data         = $this->user->selectSingle($uid);//得到用户的详情
			$dataintro    = $this->userson->selectIntro($uid);//得到用户介绍信息
			$article      = $this->article->getAllart($uid);//得到用户发表过的所有文章时间排序
			$attentions   = $this->attention->attentions($uid);//得到关注的人数
			$fans         = $this->attention->fans($uid);//得到粉丝的人数
			$articlecount = $this->article->articleCount($uid);//得到用户发表的微博数
			$list         = $article['list'];
			$page         = $article['page'];
			foreach ($list as $key => $value) { 
				$list[$key]['tname'] = $this->topic->topicname($value['tid']);
			}
			$this->assign([
						'islog'       =>1,
						'data'        =>$data,
						'location'    =>'个人中心',
						'list'        =>$list,
						'page'        =>$page,
						'fans'        =>$fans,
						'attentions'  =>$attentions,
						'articlecount'=>$articlecount,
						'dataintro'   =>$dataintro,
			]);
		} else {
			return $this->error('你还未登录', 'index/index');
		}
		return $this->fetch();
	}
	public function postblog()
	{
		$data = $this->request->param();
		$info = $this->article->insertArt($data);
		if ($info) {
			$uid = Session::get('uid');
			$this->user->scoreSelf($uid);
			$this->topic->addSelf($data['tid']);
			$this->success('发表成功', 'blog/blog');
		}else {
			$this->error('发表失败', 'blog/blog');
		}
	}
}