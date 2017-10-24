<?php
namespace app\index\controller;

use \think\Controller;
use app\index\model\User as UserModel;
use \think\Validate;
use \think\Session;

class Index extends Controller
{
	protected $user;
	public function _initialize()
	{
		$this->user = new UserModel();
	}
	public function index()
	{
		if (session('?uid')) {
			$uid = Session::get('uid');
			$data = $this->user->where('uid', $uid)->find();
			$this->assign(['islog'=>1, 'data'=>$data]);
		} else {
			$this->assign('islog', 0);
		}
		return $this->fetch();
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
			echo json_encode(['code'=>1]);
		} else {
			echo json_encode(['code'=>0]);
		}
	}
	//验证用户
	public function checkuser()
	{
		$data = $this->request->param();
		dump($data);
		echo 1;
	}
	//退出登录
	public function exit()
	{
		Session::clear();
		$this->success('退出登录成功', 'index/index');
	}
}