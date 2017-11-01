<?php
namespace app\admin\controller;

use app\admin\controller\Auth;
use app\admin\model\Admin;
use app\admin\model\Role;
use \think\Session;
class Index extends Auth
{
	protected $is_login = ['*'];
	
	public function index()
	{
		return $this->fetch();
	}
	
	public function menu()
	{
		$adminid = Session::get('adminid');
		//用adminid去查询
		$info = $this->admin->get($adminid);
		$result = $info->adminTorole;
		/*foreach ($result as $key => $value) {
			dump($value['rid']);
		}*/
		$this->assign('result',$result);
		return $this->fetch();
	}
	public function top()
	{
		$adminid = Session::get('adminid');
		$adminname = Admin::get($adminid);
		$this->assign('adminname',$adminname['adminname']);//管理员用户名
		return $this->fetch();
	}
	public function clearSession()//安全退出，清空session
	{
		Session::clear();
		$this->success('退出成功',url('admin/index/index'));
	}
	public function bar()
	{
		return $this->fetch();
	}
}