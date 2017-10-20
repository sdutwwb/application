<?php
namespace app\index\controller;

use \think\Controller;
use app\index\model\User as UserModel;
use \think\Validate;

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
}