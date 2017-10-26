<?php
namespace app\admin\controller;

use  \think\Controller;
use app\admin\model\Admin;
use app\admin\model\Article;
use app\admin\model\Adminmessage;
use app\admin\model\Replyuser;
use app\admin\model\Role;
use \think\Validate;

use \think\Session;
class Alladmin extends Auth
{
	protected $is_login = ['*'];
	protected $admin;
	protected $adminmessage;
	protected $replyuser;
	protected $role;
	public function _initialize()
	{
		$this->admin = new Admin;
		$this->adminmessage = new Adminmessage;
		$this->replyuser = new Replyuser;
		$this->role = new Role;
	}
	public function admin_list()//admin基本信息
	{
		$like = $this->request->param();
		$data = $this->admin->getAlladmin($like);
		$list = $data['list'];
		if(isset($data['page'])){//文章太少不需要分页
			$page = $data['page'];
			$this->assign('page',$page);
		}
		$this->assign('list', $list);
		return $this->fetch();
	}
	
	public function admin_rank()
	{
		//第一层页面
		$data = $this->request->param();
		$info = $this->admin->get($data['adminid']);
		$adminrole = $info->adminTorole;//多对多查询

		$this->assign('adminname',$data['adminname']);
		$this->assign('adminrole',$adminrole);
		$this->assign('adminid',$data['adminid']);


		//第二层页面
		$rolePer = $this->role->getAdminPer($adminrole);//得到角色的权限
		$this->assign('rolePer',$rolePer);
		return $this->fetch();
	}
	
	public function add_admin_rank()//权限处理页面
	{
		$data = $this->request->param();
		$this->assign('adminname',$data['adminname']);
		$this->assign('adminid',$data['adminid']);
		//先查已经有的权限
		$info = $this->admin->get($data['adminid']);
		$adminrole = $info->adminTorole;//多对多查询
		$this->assign('rolePer',$adminrole);
		//查询没有的权限
		$nonRolePer = $this->role->getAdminNonePer($adminrole);
		$this->assign('nonRolePer',$nonRolePer); 
		return $this->fetch();
	}
	public function add_adminRole()//增加权限
	{
		$data = $this->request->param();
		$rid = $data['rid'];
		$info = $this->admin->get($data['adminid']);
		if($info->adminTorole()->attach($rid)){
			$this->success('添加成功',url('alladmin/admin_list'));
		}else{
			$this->error('添加失败',url('alladmin/admin_list'));
		}
		
	}
	public function reduce_adminRole()//减少权限
	{
		$data = $this->request->param();
		$rid = $data['rid'];
		$info = $this->admin->get($data['adminid']);
		$info->adminTorole()->detach($rid);
		$this->success('删除成功',url('alladmin/admin_list'));
	}
	public function add_admin_ajaxname()//添加管理员nameajax处理
	{
		$adminname = $this->request->param('adminname');
		if(empty($adminname)){
			echo json_encode(['code'=>0,'message'=>'昵称不能为空']);
		}else{
			$nameresult = $this->admin->where('adminname',$adminname)->find();
			if($nameresult){
				echo json_encode(['code'=>1,'message'=>'管理员昵称已经存在请更换']);
			}else{
				echo json_encode(['code'=>2,'message'=>'管理员昵称可以使用']);
			}
		}
		
	}
	public function add_admin_ajaxemail()//添加管理员emailajax处理
	{
		$email = $this->request->param('adminemail');
		$validate = new Validate([
			'email' => 'email'
			]);
		$data = [
			'email' => $email,
		];
		if (!$validate->check($data)) {
			echo json_encode(['code'=>1 , 'message' => '亲，你输入的邮箱格式不正确哦']);
		}else{
			$result = $this->admin->where('adminemail',$email)->find();
			if($result){
				echo json_encode(['code'=>2,'message'=>'邮箱已经存在请更换']);
			}else{
				echo json_encode(['code'=>3,'message'=>'邮箱可以使用']);
			}
		}

		
	}
	public function add_admin_ajaxtel()//添加管理员emailajax处理
	{
		$tel = $this->request->param('admintel');
		$validate = new Validate(['mobilephone'=>'require|max:11|/^1[3-8]{1}[0-9]{9}$/']);

		$data = [
			'mobilephone' => $tel,
		];
		if (!$validate->check($data)) {
			echo json_encode(['code'=>1 , 'message' => '亲，你输入的手机号格式不正确哦']);
		}else{
			$result = $this->admin->where('admintel',$tel)->find();
			if($result){
				echo json_encode(['code'=>2,'message'=>'手机号已经存在请更换']);
			}else{
				echo json_encode(['code'=>3,'message'=>'手机号可以使用']);
			}
		}

		
	}
	public function add_admin()//添加管理员页面
	{
		return $this->fetch();
	}
	public function add_admin_insert()
	{
		$data = $this->request->param();
		$this->admin->data($data);
		if($this->admin->allowField(true)->save()){
			$this->success('添加成功',url('alladmin/add_admin'));
		}
	}
	public function admin_message()//admin的所有留言查询
	{
		$like = $this->request->param();
		$data = $this->adminmessage->getAllAdminMessage($like);
		$list = $data['list'];
		if(isset($data['page'])){//文章太少不需要分页
			$page = $data['page'];
			$this->assign('page',$page);
		}
		$this->assign('list', $list);
		return $this->fetch();
	}

	public function add_message()//admin增加留言
	{
		$mess = $this->request->param('messcontent');
		if($mess){
			$adminid = Session::get('adminid');
			$result = $this->adminmessage->addmessage($mess,$adminid);
			if($result){
				$this->success('留言成功',url('alladmin/admin_message'));
			}else{
				$this->success('留言失败',url('alladmin/admin_message'));
			}
			
		}else{
			return $this->fetch();
		}
		
	}

}