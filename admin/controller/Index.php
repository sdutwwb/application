<?php
namespace app\admin\controller;

use app\admin\controller\Auth;

class Index extends Auth
{
	protected $is_login = ['*'];
	
	public function index()
	{
		return $this->fetch();
	}
	public function main()
	{
		return $this->fetch();
	}
	public function menu()
	{
		return $this->fetch();
	}
	public function top()
	{
		return $this->fetch();
	}
	public function bar()
	{
		return $this->fetch();
	}
}