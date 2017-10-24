<?php
namespace app\index\controller;

use \think\Controller;
use app\index\model\User as UserModel;
use \think\Validate;
use \think\Session;

class Log extends Controller
{
	protected $user;
	public function _initialize()
	{
		$this->user = new UserModel();
	}
	public function log()
	{
		return $this->fetch();
	}
	public function checklog()
	{
		return $this->success('登录成功', 'index/index');
	}
}