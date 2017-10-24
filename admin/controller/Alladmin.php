<?php
namespace app\admin\controller;

use  \think\Controller;
use app\admin\model\Admin;
use app\admin\model\Article;
use app\admin\model\Adminmessage;
use app\admin\model\Replyuser;
use app\admin\model\Role;
use \think\Validate;
use \think\Db;
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
	public function add_admin()
	{
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
	
	public function add_admin_rank()
	{
		$data = $this->request->param();
		$this->assign('adminname',$data['adminname']);
		$this->assign('adminid',$data['adminid']);
		//先查已经有的权限
		$info = $this->admin->get($data['adminid']);
		$adminrole = $info->adminTorole;//多对多查询
		$this->assign('rolePer',$adminrole);
		//查询没有的权限
		$rid = [];
		foreach ($adminrole as $key => $value) {
			$rid[$key] = $value['rid'];
		}
		$data = $this->role->where('rid','<>','2')->where('rid','<>','1')->select();
		$sql = "select * from wb_role where";
		foreach ($rid as $key => $value) {
			
		}
		$data = Db::query("select * from wb_role where rid=1");
		dump($data);
		return $this->fetch();
	}
	public function admin_message()
	{
		return $this->fetch();
	}

}