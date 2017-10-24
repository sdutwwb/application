<?php
namespace app\admin\controller;

use think\Controller;
use app\admin\model\Admin;
class Auth extends Controller
{
	protected $is_login = [''];
	public function _initialize()
	{
		if (!$this->checklogin() && in_array('*', $this->is_login)) {
			$this->error('没有登录请登录', url('admin/auth/login'));
		}
	}
	public function login()
	{
		return $this->fetch();
	}
	public function checklogin()
	{
		return session('?adminid');
	}
	public function dologin()
	{
		/*session('adminid', 3);
		$this->success('登录成功', url('index/index'));*/
		$admin = Admin::where('adminname', $this->request->param('adminname'))->find();//账号登陆
		$email = Admin::where('adminemail', $this->request->param('adminname'))->find();//邮箱登陆
		$password = $this->request->param('password');
		if($admin){
			if($admin['adminpassword'] == $password){
				session('adminid', $admin['adminid']);
				$this->success('登录成功', url('admin/index/index'));
			}else{
				$this->error('登录失败', url('auth/login'));
			}
		}elseif($email){
			if($email['adminpassword'] == $password){
				session('adminid', $email['adminid']);
				$this->success('登录成功', url('admin/index/index'));
			}else{
				$this->error('登录失败', url('auth/login'));
			}
		}else{
			$this->error('登录失败', url('auth/login'));	
		}
	}
}