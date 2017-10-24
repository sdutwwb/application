<?php
namespace app\index\controller;

use \think\Controller;
use app\index\model\User as UserModel;
use \think\Validate;
use \think\Session;

class Mmzh extends Controller
{
	protected $user;
	public function _initialize()
	{
		$this->user = new UserModel();
	}
	public function mmzh()
	{
		return $this->fetch();
	}
	//重置密码
	public function setmm()
	{
		$data = $this->request->param();
		if ($data) {
			$this->assign('phone', $data['phone']);
			return $this->fetch();
		}else {
			$this->error('抱歉，页面跳转错误', 'index/mmzh');
		}
		
	}
}