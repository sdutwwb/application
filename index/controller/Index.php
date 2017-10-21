<?php
namespace app\index\controller;

use \think\Controller;
use app\index\model\User as UserModel;
use \think\Validate;
use \think\Session;

class Index extends Controller
{
	public function index()
	{
		return $this->fetch();
	}
	public function blog()
	{
		return $this->fetch();
	}
	public function blog_post()
	{
		return $this->fetch();
	}
	public function contact()
	{
		return $this->fetch();
	}
	public function detail()
	{
		return $this->fetch();
	}
	public function listing()
	{
		return $this->fetch();
	}
	public function news()
	{
		return $this->fetch();
	}
	public function about()
	{
		return $this->fetch();
	}
	public function mmzh()
	{
		return $this->fetch();
	}
	public function log()
	{
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
	//
	public function checkx()
	{
		$this->success('dsad');
	}
}