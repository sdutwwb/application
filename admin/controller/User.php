<?php
namespace app\admin\controller;

use  \think\Controller;
use app\admin\model\User as UserModel;
use app\admin\model\Article;
use \think\Validate;
use \think\Session;
class User extends Controller
{
	protected $user;
	public function _initialize()
	{
		
		$this->user = new UserModel;
	}
	public function selectArticle()
	{
		/*$info = $this->user->get(1);
		$info1 = $info->onetomanyarticle;
		dump($info1); */
	}
}