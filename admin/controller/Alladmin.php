<?php
namespace app\admin\controller;

use  \think\Controller;
use app\admin\model\Admin;
use app\admin\model\Article;
use app\admin\model\Adminmessage;
use app\admin\model\Replyuser;
use \think\Validate;
use \think\Session;
class Alladmin extends Controller
{
	protected $admin;
	protected $adminmessage;
	protected $replyuser;
	public function _initialize()
	{
		$this->admin = new Admin;
		$this->adminmessage = new Adminmessage;
		$this->replyuser = new Replyuser;
	}
	public function admin_list()
	{
		return $this->fetch();
	}
	public function add_admin()
	{
		return $this->fetch();
	}
	public function admin_rank()
	{
		return $this->fetch();
	}
	public function admin_message()
	{
		return $this->fetch();
	}
	public function add_admin_rank()
	{
		return $this->fetch();
	}
}