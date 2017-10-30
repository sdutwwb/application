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
		$data = $this->request->param();
		if(!isset($data['id'])){
			$this->category->data($data);
			$this->category->save();
		}else{
			$result = $this->category->diguiDelete($data['id']);
		}
		$list = $this->category->select();
		$data = $this->category->tree($list);
		$this->assign('data',$data);
		return $this->fetch();
		
	}
}