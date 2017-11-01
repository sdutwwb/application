<?php
namespace app\index\controller;

use \think\Controller;
use app\index\model\User as UserModel;
use app\index\model\Topic as TopicModel;
use app\index\model\Article;
use app\index\model\Category;
use \think\Validate;
use \think\Session;
use \think\Db;
class Index extends Controller
{
	protected $user;
	protected $topic;
	protected $article;
	public function _initialize()
	{
		$this->user = new UserModel();
		$this->topic = new TopicModel();
		$this->article = new Article();
		$this->category = new Category();
	}
	public function index()
	{
		$list = $this->category->select();//无限极分类
		$data = $this->category->tree($list);
		$this->assign('data',$data);

		$topic = $this->topic->getAlltopic();
		$this->assign(['topic'=>$topic, 'location'=>'最新新闻']);
		if (session('?uid')) {
			$uid = Session::get('uid');
			$data = $this->user->where('uid', $uid)->find();
			$this->assign(['islog'=>1, 'data'=>$data]);
		} else {
			$this->assign(['islog'=> 0]);
		}

		//板块查询和模糊查询
		$like = $this->request->param();
		if(isset($like['like'])){
			$where['uname'] = ['like',$like];
			$result = $this->user->where($where)->find();
			if(!empty($result)){
				$data = $this->user->get($result['uid']);
				$result = $data->userToArt;
			}else{
				$result = $this->article->getTopicArt($like);
			}
			//dump($result);
			foreach ($result as $key => $value) {
				$data = $this->article->get($value['aid']);
				$result[$key]['uname'] = $data->belongsToUname->uname;
				$result[$key]['uimage'] = $data->belongsToUname->uimage;
				$result[$key]['uid'] = $data->belongsToUname->uid;
			}
			$this->assign('result',$result);

		}
		if(isset($like['tid'])){
			$result = $this->article->getTopicArt($like);
			//dump($result);
			foreach ($result as $key => $value) {
				$data = $this->article->get($value['aid']);
				$result[$key]['uname'] = $data->belongsToUname->uname;
				$result[$key]['uimage'] = $data->belongsToUname->uimage;
				$result[$key]['uid'] = $data->belongsToUname->uid;
			}
			$this->assign('result',$result);
		}

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
	//退出登录
	public function exit()
	{	
		Session::clear('think');
		$this->success('退出登录成功', 'index/index');
	}
	public function status0Art()//通过ajax拿通过审核的博文进行瀑布流展示
	{
		$limit = $this->request->param('limit');
		$list = Db::query("select * from wb_article where public = 1 order by pubtime desc limit $limit,6");
		
		if(empty($list)){
			echo 1;
		}else{
			foreach ($list as $key => $value) {
				$data = $this->article->get($value['aid']);
				$list[$key]['uname'] = $data->belongsToUname->uname;
				$list[$key]['uimage'] = $data->belongsToUname->uimage;
				$list[$key]['uid'] = $data->belongsToUname->uid;
			}
			$this->assign('list',$list);
			return $this->fetch('artlist');
		}
	}
}