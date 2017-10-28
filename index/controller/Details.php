<?php
namespace app\index\controller;

use \think\Controller;
use app\index\model\User as UserModel;
use app\index\model\Topic as TopicModel;
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
		$this->reply = new ReplyModel();
		$this->topic = new TopicModel();
		$this->reply = new ReplyModel();
		$this->article = new  ArticleModel();
		$this->comment = new CommentModel();
	}
	//文章详情
	public function details()
	{
		$topic = $this->topic->getAlltopic();
		$this->assign(['topic'=>$topic]);
		$aid = $this->request->param()['aid'];
		$this->article->addSelf($aid);//增加微博的阅读量一次
		if (session('?uid')) {
			$uid       = Session::get('uid');
			$data      = $this->user->where('uid', $uid)->find();
			$details   = $this->article->getDetails($aid);//得到微博的详情
			$topicname = $this->topic->topicname($details['tid']);//得到微博所属话题名字
			$info = $this->article->get($aid);
			$comments  = $info->comment();//得到微博的所有评论(分页形式)
			$list      = $comments['list'];
			$list      = $this->user->getUserAll($list);//将评论人昵称和头像拿到
			
			if (!empty($list)) {
				foreach ($list as $key => $value) {
					$list[$key]['reply'] = $this->user->getUserAll($this->reply->getReply($value['pid']));
					
				}
			}
			$page      = $comments['page'];
			$this->assign([
				'islog'    =>1,
				'data'     =>$data,
				'location' =>'详情',
				'private'  =>1,
				'details'  =>$details,
				'topicname'=>$topicname,
				'list'     =>$list,
				'page'     =>$page,
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
		$this->comment->data($data);
		$this->comment->save();
		$this->redirect('details/details', ['aid'=>$aid], 1, '回复成功');
	}
}