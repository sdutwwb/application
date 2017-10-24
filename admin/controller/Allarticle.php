<?php
namespace app\admin\controller;

use app\admin\controller\Auth;
use app\admin\model\Article;
use app\admin\model\User;
class Allarticle extends Auth
{
	protected $is_login = ['*'];
	protected $article;
	public function _initialize()
	{
		$this->article = new Article();
	}
	public function order_list()//所有文章列表
	{
		//所有文章列表
		$allArticle = $this->article->getallArticle();
		$this->assign('allArticle',$allArticle);
		
		return $this->fetch();
	}
	public function order_detail()
	{ 
		//单个文章所对应用户信息
		$uid = $this->request->param('uid');  
		$simpleuesr = $this->article->get("$uid")->getuser->Toarray();
		$this->assign('simpleuesr',$simpleuesr);
		//单个文章的内容
		$aid = $this->request->param('aid');
		$simplearticle = $this->article->getsimpleArticle($aid);
		$this->assign('simplearticle',$simplearticle);
		//处理多个图像问题
		$allimage = $this->article->solveimage($simplearticle['aimage']);
		$this->assign('allimage',$allimage);
	
		return $this->fetch();
	}
	public function articleStatus()
	{
		$data = $this->request->param();
		$this->article->setArticleStatus($data);
		$this->success('执行成功','allarticle/order_list');
	}
}
