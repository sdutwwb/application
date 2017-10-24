<?php
namespace app\index\controller;

use \think\Controller;
use app\index\model\User as UserModel;
use \think\Validate;
use \think\Session;

class About extends Controller
{
	protected $user;
	public function _initialize()
	{
		$this->user = new UserModel();
	}
	public function about()
	{
		return $this->fetch();
	}
}