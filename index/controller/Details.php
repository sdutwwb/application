<?php
namespace app\index\controller;

use \think\Controller;
use app\index\model\User as UserModel;
use app\index\model\Userson as UsersonModel;
use app\index\model\Topic as TopicModel;
use app\index\model\Zan as ZanModel;
use app\index\model\Copy as CopyModel;
use app\index\model\Fav as FavModel;
use app\index\model\Advertise as AdvModel;
use app\index\model\Reply as ReplyModel;
use app\index\model\Comment as CommentModel;
use app\index\model\Article as ArticleModel;
use app\index\model\Attention as AttentionModel;
use \think\Validate;
use \think\Session;

class Details extends Controller
{
	protected $user;
	public function _initialize()
	{
		$this->user = new UserModel();
		$this->userson 	 = new UsersonModel();
		$this->zan = new ZanModel();
		$this->copy = new CopyModel();
		$this->reply = new ReplyModel();
		$this->topic = new TopicModel();
		$this->reply = new ReplyModel();
		$this->adv = new AdvModel();
		$this->article = new  ArticleModel();
		$this->advertise = new Advertise();
		$this->comment = new CommentModel();
		$this->fav 	     = new FavModel();
		$this->attention = new AttentionModel();
	}
	//文章详情
	public function details()
	{
		$topic = $this->topic->getAlltopic();
		$aid   = $this->request->param('aid');  
		$private  = 0;
		$uid = $this->request->param('uid');
		if (session('?uid')) {
			$uuid = Session::get('uid');
			$data = $this->user->selectSingle($uuid);//
			$this->assign('data', $data);
			if (Session::get('uid') == $uid) {
				$private = 1;
			}else {
				if (empty($uid)) {
					$uid = Session::get('uid');
				}
			}
			$this->assign(['islog'=> 1]);
		}else {
			$this->assign(['islog'=> 0]);
		}
			$datas     = $this->user->selectSingle($uid);//得到用户的详情
			$advertise = $this->adv->selectSingle($uid);//得到用户的广告
			$details   = $this->article->getDetails($aid);//得到微博的详情
			$topicname = $this->topic->topicname($details['tid']);//得到微博所属话题名字
			$info      = $this->article->get($aid);
			$comments  = $info->comment();//得到微博的所有评论(分页形式)
			$list      = $comments['list'];
			$list      = $this->user->getUserAll($list);//将评论人昵称和头像拿到
			$reply     = $this->comment->commentCount($aid);
			$attentions   = $this->attention->attentions($uid);//得到关注的人数
			$fans         = $this->attention->fans($uid);//得到粉丝的人数
			$articlecount = $this->article->articleCount($uid);//得到用户发表的微博数
			$dataintro    = $this->userson->selectIntro($uid);//得到用户介绍信息
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
			$this->article->updateReply(['aid'=>$aid, 'reply'=>$reply]);
			if (!empty($list)) {
				foreach ($list as $key => $value) {
					$list[$key]['reply'] = $this->user->getUserAll($this->reply->getReply($value['pid']));
					
				}
			}
			$page      = $comments['page'];
			$this->assign([
				'datas'        =>$datas,
				'private'      =>$private,
				'details'      =>$details,
				'advertise'    =>$advertise,
				'topic'        =>$topic,
				'topicname'    =>$topicname,
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
	//提交回复
	public function reply()
	{
		$uid = Session::get('uid');
		$data = $this->request->param();
		$data['uid'] = $uid;
		$aid = $data['aid'];
		$this->reply->data($data);
		$this->reply->allowField(true)->save();
		$this->redirect('details/details', ['aid'=>$aid], 1, '回复成功');
	}
	//提交评论
	public function insertComment()
	{
		$uid = Session::get('uid');
		$data = $this->request->param();
		$data['uid'] = $uid;
		$aid = $data['aid'];
		if ($data['ccontent']!='') {
			$this->comment->data($data);
			$this->comment->save();
		}
		//异步刷新
		$info = $this->article->get($aid);
		$comments  = $info->comment();//得到微博的所有评论(分页形式)
		$list      = $comments['list'];
		$list      = $this->user->getUserAll($list);//将评论人昵称和头像拿到
		if (!empty($list)) {
				foreach ($list as $key => $value) {
					$list[$key]['reply'] = $this->user->getUserAll($this->reply->getReply($value['pid']));
					//将用户的昵称和头像压入$list
				}
			}
		$page      = $comments['page'];
		$reply = $this->comment->commentCount($aid);
		$this->assign(['list'=>$list, 'page'=>$page]);
		return $this->fetch('detailslist');
	}
	//赞
	public function zan()
	{
		$uid = Session::get('uid');
		$aid = $this->request->param('aid');
		$info = $this->zan->zan(['uid'=>$uid, 'aid'=>$aid]);//查询是否点过赞
		if (!$info) {
			$this->zan->insertZan(['uid'=>$uid, 'aid'=>$aid]);//插入点赞的用户微博
			$this->article->addZan($aid)['favour'];//微博赞的自增
		}
		$zan = $this->article->getDetails($aid)['favour'];//微博赞的数目
		echo json_encode($zan);		
	}
	//收藏
	public function fav()
	{
		$uid = Session::get('uid');
		$data = $this->request->param();
		$data['uid'] = $uid;
		$info = $this->fav->fav($data);//查询是否收藏过
		if (!$info) {
			$this->fav->insertFav($data);//插入用户收藏的微博
			$this->article->addFav($data['aid']);//微博收藏的自增
		}
		$copy = $this->article->getDetails($data['aid'])['fav'];//微博转发
		echo json_encode($copy);		
	}
	//删除评论
	public function deletep()
	{
		$pid = $this->request->param('pid');
		$aid = $this->request->param('aid');
		$this->comment->deletep($pid);//删除评论
		$this->reply->deleteR($pid);//删除评论中的回复
		//异步刷新
		$info = $this->article->get($aid);
		$comments  = $info->comment();//得到微博的所有评论(分页形式)
		$list      = $comments['list'];
		$list      = $this->user->getUserAll($list);//将评论人昵称和头像拿到
		if (!empty($list)) {
				foreach ($list as $key => $value) {
					$list[$key]['reply'] = $this->user->getUserAll($this->reply->getReply($value['pid']));
					//将用户的昵称和头像压入$list
				}
			}
		$page      = $comments['page'];
		$reply = $this->comment->commentCount($aid);
		$this->assign(['list'=>$list, 'page'=>$page]);
		return $this->fetch('detailslist');
	}
}