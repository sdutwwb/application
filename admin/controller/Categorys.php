<?php
namespace app\admin\controller;

use  \think\Controller;
use app\admin\model\Category;
use \think\Validate;
use \think\Session;
use \think\Cache;
class Categorys extends Auth
{
	protected $is_login = ['*'];
	protected $category;
	
	public function _initialize()
	{
		$this->category = new Category;
	}
	public function add_category()
	{
		return $this->fetch();
	}
}