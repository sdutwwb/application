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
		//所有文章列表分页显示
		 $list = $this->article->paginate(10);
		 // 获取分页显示
		 $page = $list->render();
		 $this->assign('list', $list);
		 $this->assign('page', $page);
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
	public function articleStatus()//处理文章状态
	{
		$data = $this->request->param();
		$this->article->setArticleStatus($data);
		$this->success('执行成功','allarticle/order_list');
	}
	public function product_list()
	{
		$status = $this->request->param('status');
		if(empty($status)){
			$status = 0;
		}
		$list = $this->article->where('status',$status)->paginate(10);
		//dump($this->article->getlastsql());
		 // 获取分页显示
		 $page = $list->render();
		 $this->assign('list', $list);
		 $this->assign('page', $page);
		return $this->fetch();
	}

}
