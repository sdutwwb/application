<?php
namespace app\index\controller;

use  \think\Controller;
use app\index\model\User as UserModel;
use \think\Validate;
class User extends Controller
{
	protected $user;
	public function _initialize()
	{
		$this->user = new UserModel();
	}
	public function regmail()
	{
		return $this->fetch();
	}
	//检查验证码
	public function checkreg()
	{
		
		$arr = $this->request->param('code');
		$validate = new Validate([
			'captcha|验证码'=>'require|captcha'
		]);
		$data = [
			'captcha'=>input('code'),
		];
		if (!$validate->check($data)) {
			dump(input('code'));
			dump($validate->getError());
		} else {
			$this->success('注册成功', 'index/index');
		}
	}
	public function regphone()
	{
		return $this->fetch();
	}
}