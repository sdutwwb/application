<?php
namespace app\admin\controller;

use  \think\Controller;
use app\admin\model\User as UserModel;
use app\admin\model\Article;
use app\admin\model\Usermessage;
use app\admin\model\Replyuser;
use \think\Validate;
use \think\Session;
class User extends Auth
{
	protected $is_login = ['*'];
	protected $user;
	protected $usermessage;
	protected $replyuser;
	public function _initialize()
	{
		$this->user = new UserModel;
		$this->usermessage = new Usermessage;
		$this->replyuser = new Replyuser;
	}
	public function selectArticle()
	{
		/*$info = $this->user->get(1);
		$info1 = $info->onetomanyarticle;
		dump($info1); */
	}
	public function user_list()//拿到用户的信息
	{
		$like = $this->request->param();
		$data = $this->user->getAlluser($like);
		$list = $data['list'];
		if(isset($data['page'])){//文章太少不需要分页
			$page = $data['page'];
			$this->assign('page',$page);
		}
		$member = $like['member'];
		$this->assign('member',$member);
		$this->assign('list', $list);
		return $this->fetch();
	}
	public function user_message()//留言便利
	{
		$like = $this->request->param();
		$data = $this->usermessage->getAllusermess($like);
		$list = $data['list'];
		if(isset($data['page'])){//留言太少不需要分页
			$page = $data['page'];
			$this->assign('page',$page);
		}
		$member = $like['member'];
		$this->assign('member',$member);
		$this->assign('list', $list);
		return $this->fetch();
	}
	public function reply_message()//管理员的回复
	{
		$data = $this->request->param();
		$simpleuser = $this->usermessage->getsimpleuser($data);
		$ucontent = $data['ucontent'];
		$umid = $data['umid'];
		$this->assign('umid',$umid);
		$this->assign('ucontent',$ucontent);//用户留言
		$this->assign('simpleuser',$simpleuser);//用户的用户名
		//查找留言的回复
		$allReply = $this->replyuser->getReplyuser($data);
		$this->assign('allReply',$allReply);
		return $this->fetch();
	}
	public function adminWritereply()
	{
		$data = $this->request->param();
		$adminid = Session::get('adminid');
		if($this->replyuser->insertReplyuser($data,$adminid)){
			$this->usermessage->where('umid',$data['umid'])->update(['mstatus'=>0]);
			$this->success('回复成功');
		}
	}
}