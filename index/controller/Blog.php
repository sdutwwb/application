<?php
namespace app\index\controller;

use \think\Controller;
use app\index\model\User as UserModel;
use app\index\model\Userson as UsersonModel;
use app\index\model\Fav as FavModel;
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
		$this->fav 	     = new FavModel();
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
			$dataintro    = $this->userson->selectIntro($uid)->toArray();//得到用户介绍信息
			$article      = $this->article->getAllart($uid);//得到用户发表过的所有文章时间排序
			$attentions   = $this->attention->attentions($uid);//得到关注的人数
			$fans         = $this->attention->fans($uid);//得到粉丝的人数
			$articlecount = $this->article->articleCount($uid);//得到用户发表的微博数
			$attentionList= $this->attention->attentioned($uid)['list'];//得到用户关注的人id,关注时间list
			$attentionPage= $this->attention->attentioned($uid)['page'];//得到用户关注的人id,关注时间page
			$attentioner  = $this->user->attentioner($attentionList);//得到关注人的具体详情
			$fansList     = $this->attention->attentioner($uid)['list'];//得到粉丝的id，关注时间list
			$fansPage     = $this->attention->attentioner($uid)['page'];//得到粉丝的is，关注时间Page
			$fanser       = $this->user->fanser($fansList);//得到粉丝的具体详情
			$selectFav    = $this->fav->selectFav($uid)['list'];//用户收藏的微博
			$selectFavPage= $this->fav->selectFav($uid)['page'];//用户收藏的微博分页
			if (!empty($selectFav)) {
				$selectFav   = $this->article->getFavUser($selectFav);//将被收藏人的头像和昵称存入收藏中
			}
			$list         = $article['list'];
			$page         = $article['page'];
			foreach ($list as $key => $value) { 
				$list[$key]['tname'] = $this->topic->topicname($value['tid']);
			}
			$this->assign([
						'islog'        =>1,
						'data'         =>$data,
						'location'     =>'个人中心',
						'list'         =>$list,
						'page'         =>$page,
						'fans'         =>$fans,
						'attentions'   =>$attentions,
						'articlecount' =>$articlecount,
						'dataintro'    =>$dataintro,
						'attentions'   =>$attentions,
						'attentioner'  =>$attentioner,
						'attentionpage'=>$attentionPage,
						'fanser'       =>$fanser,
						'fansPage'     =>$fansPage,
						'selectFav'    =>$selectFav,
						'selectFavPage'=>$selectFavPage,
			]);
		} else {
			return $this->error('你还未登录', 'index/index');
		}
		return $this->fetch();
	}
	public function postblog()//发表博文
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
	public function uploadHead()//上传头像
	{
		$uid = Session::get('uid');
		$fileimg = request()->file('image');
		$img = $this->user->getLoad($fileimg);
		if($this->user->save(['uimage'=>$img],['uid'=>$uid])){
			$this->success('上传头像成功','blog/blog','',1);
		}
	}
	public function attention()//关注的人
	{
		$uid = Session::get('uid');
		$attentions = $this->attention->attentioned($uid);
	}
	public function other()//进入其他人主页
	{
		$data = $this->request->param();
		dump($data);
	}
}