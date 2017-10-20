<?php
namespace app\index\controller;

use  \think\Controller;
use app\index\model\User as UserModel;
use \think\Validate;

class Index extends Controller
{
	public function index()
	{
		// $this->success('haha','index/index');
		return $this->fetch();
	}
}