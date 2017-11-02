<?php
namespace app\index\controller;

use \think\Controller;
use app\index\model\Websites as WebsitesModel;


class Aixie extends Controller
{
	public function _initialize()
	{
		$this->user = new UserModel();
		$this->topic = new TopicModel();
		$this->article = new Article();
		$this->category = new Category();
		$this->advertise = new Advertise();
		$this->loving = new Loving();
		$this->web = new WebsitesModel;
		$data = $this->web->where('id', 1)->find();
		dump($data);die;
		if ($data['isclose']==1) {
			$this->error('没有登录请登录', url('index/aixie/banip'));
		}
	}
	public function banip()
	{
		return $this->fetch();
	}
}
