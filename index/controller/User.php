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
	//检查邮箱
	public function checkemail()
	{
		
		$email = $this->request->param('email');
		$validate = new Validate([
			'email' => 'email'
			]);
		$data = [
			'email' => $email,
		];
		if (!$validate->check($data)) {
			echo json_encode(['code'=>1 , 'message' => '亲，你输入的邮箱格式不正确哦']);
		} else {
			$data = $this->user->where('user_name', $email)->find();
			if ($data) {
				echo json_encode(['code'=>2 , 'message' => '亲，您输入的邮箱已被注册了哟']);
			} else {
				echo json_encode(['code'=>0 , 'message' => '亲，您输入的邮箱可用了哟']);
			}
		}
	}
	//验证码验证
	public function checkcode()
	{
		$code = $this->request->param('code');
		$validate = new Validate([
			'captcha|验证码'=>'require|captcha'
		]);
		$data = [
			'captcha'=>$code,
		];
		if (!$validate->check($data)) {
			echo json_encode(['code'=>3 , 'message' => '亲，验证码错了哟']);
		} else {
			echo json_encode(['code'=>0 , 'message' => '快点点击注册吧']);
		}
	}
	//插入数据
	public function checkreg()
	{

	}
	public function regphone()
	{
		return $this->fetch();
	}
}