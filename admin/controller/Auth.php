<?php
namespace app\admin\controller;

use think\Controller;
use app\index\model\User;
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
		return session('?id');
	}
	public function dologin()
	{
		$info = User::where('nickname', $this->request->param('name'))->find();
		if ($info) {
			session('id', $info->id);
			$this->success('登录成功', url('admin/admin/index'));
		} else {
			$this->error('登录失败', url('admin/auth/login'));
		}
	}
}