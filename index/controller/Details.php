<?php
namespace app\index\controller;

use \think\Controller;
use app\index\model\User as UserModel;
use app\index\model\Topic as TopicModel;
use app\index\model\Zan as ZanModel;
use app\index\model\Copy as CopyModel;
use app\index\model\Reply as ReplyModel;
use app\index\model\Comment as CommentModel;
use app\index\model\Article as ArticleModel;
use \think\Validate;
use \think\Session;

class Details extends Controller
{
	protected $user;
	public function _initialize()
	{
		$this->user = new UserModel();
		$this->zan = new ZanModel();
		$this->copy = new CopyModel();
		$this->reply = new ReplyModel();
		$this->topic = new TopicModel();
		$this->reply = new ReplyModel();
		$this->article = new  ArticleModel();
		$this->comment = new CommentModel();
	}
	//文章详情
	public function details()
	{
		$uid   = $this->request->param()['uid'];
		$private  = 0;
		$topic = $this->topic->getAlltopic();
		$this->assign(['topic'=>$topic]);
		$aid = $this->request->param()['aid'];
		$this->article->addSelf($aid);//增加微博的阅读量一次
		if (session('?uid')) {
			if (Session::get('uid') == $uid) {
				$private = 1;
			}
			$this->assign(['islog'=> 1]);
		} else {
			$this->assign(['islog'=> 0]);
		}
			$data      = $this->user->where('uid', $uid)->find();
			$details   = $this->article->getDetails($aid);//得到微博的详情
			$topicname = $this->topic->topicname($details['tid']);//得到微博所属话题名字
			$info = $this->article->get($aid);
			$comments  = $info->comment();//得到微博的所有评论(分页形式)
			$list      = $comments['list'];
			$list      = $this->user->getUserAll($list);//将评论人昵称和头像拿到
			$reply = $this->comment->commentCount($aid);
			$this->article->updateReply(['aid'=>$aid, 'reply'=>$reply]);
			if (!empty($list)) {
				foreach ($list as $key => $value) {
					$list[$key]['reply'] = $this->user->getUserAll($this->reply->getReply($value['pid']));
					
				}
			}
			$page      = $comments['page'];
			$this->assign([
				'data'     =>$data,
				'location' =>'详情',
				'private'  =>$private,
				'details'  =>$details,
				'topicname'=>$topicname,
				'list'     =>$list,
				'page'     =>$page,
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
	//转发
	public function copy()
	{
		$uid = Session::get('uid');
		$data = $this->request->param();
		$data['uid'] = $uid;
		$info = $this->copy->copy($data);//查询是否转发过
		if (!$info) {
			$this->copy->insertCopy($data);//插入用户转发的微博
			$this->article->addCopy($data['aid']);//微博转发的自增
		}
		$copy = $this->article->getDetails($data['aid'])['copy'];//微博转发
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