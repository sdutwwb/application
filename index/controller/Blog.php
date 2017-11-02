<?php
namespace app\index\controller;

use \think\Controller;
use app\index\model\User as UserModel;
use app\index\model\Userson as UsersonModel;
use app\index\model\Fav as FavModel;
use app\index\model\Topic as TopicModel;
use app\index\model\Article as ArticleModel;
use app\index\model\Attention as AttentionModel;
use app\index\model\Advertise;
use \think\Validate;
use \think\Session;

class Blog extends Controller
{
	protected $user;
	public function _initialize()
	{
		$this->user 	 = new UserModel();
		$this->userson 	 = new UsersonModel();
		$this->topic 	 = new TopicModel();
		$this->fav 	     = new FavModel();
		$this->article 	 = new ArticleModel();
		$this->attention = new AttentionModel();
		$this->advertise = new Advertise();
	}
	public function blog()
	{
		$topic = $this->topic->getAlltopic();
		$private  = 0;
		$uid = $this->request->param('uid');
		if (session('?uid')) {
			$uuid = Session::get('uid');
			$data = $this->user->selectSingle($uuid);//
			$this->assign('data', $data);
			if (Session::get('uid') == $uid) {
				$private = 1;
			}else {
				if (empty($uid)) {
					$uid = Session::get('uid');
				}
			}
			$this->assign(['islog'=> 1]);
		} else {
			$this->assign(['islog'=> 0]);
		}
		if ($private==1) {
			$article      = $this->article->getAllart($uid);//得到用户发表过的所有文章时间排序
		}else {
			$article      = $this->article->getAllpublic($uid);//得到用户公开发表过的所有文章时间排序
		}
			$datas        = $this->user->selectSingle($uid);//得到用户的详情
			$dataintro    = $this->userson->selectIntro($uid);//得到用户介绍信息
			$attentions   = $this->attention->attentions($uid);//得到关注的人数
			$fans         = $this->attention->fans($uid);//得到粉丝的人数
			$articlecount = $this->article->articleCount($uid);//得到用户发表的微博数
			$attentionList= $this->attention->attentioned($uid)['list'];//得到用户关注的人id,关注时间list
			$attentionPage= $this->attention->attentioned($uid)['page'];//得到用户关注的人id,关注时间page
			$attentioner  = $this->user->attentioner($attentionList);//得到关注人的具体详情
			$fansList     = $this->attention->attentioner($uid)['list'];//得到粉丝的id，关注时间list
			$fansPage     = $this->attention->attentioner($uid)['page'];//得到粉丝的is，关注时间Page
			$fanser       = $this->user->fanser($fansList);//得到粉丝的具体详情
			$selectFav    = $this->fav->selectFav($uid)['list'];//用户收藏的微博
			$selectFavPage= $this->fav->selectFav($uid)['page'];//用户收藏的微博分页
			//进入别人的微博判断关注与否
			$isAttention  = $this->attention->where('attuid',$uid)->find();
			if (!empty($selectFav)) {
				$selectFav   = $this->article->getFavUser($selectFav);//将被收藏人的头像和昵称存入收藏中
			}
			$list         = $article['list'];
			$page         = $article['page'];
			foreach ($list as $key => $value) { 
				$list[$key]['tname'] = $this->topic->topicname($value['tid']);
			}
			$this->assign([
						'topic'        =>$topic,
						'datas'        =>$datas,
						'private'      =>$private,
						'list'         =>$list,
						'page'         =>$page,
						'fans'         =>$fans,
						'attentions'   =>$attentions,
						'articlecount' =>$articlecount,
						'dataintro'    =>$dataintro,
						'attentions'   =>$attentions,
						'attentioner'  =>$attentioner,
						'attentionpage'=>$attentionPage,
						'fanser'       =>$fanser,
						'fansPage'     =>$fansPage,
						'selectFav'    =>$selectFav,
						'selectFavPage'=>$selectFavPage,
						'isAttention'  =>$isAttention,
			]);
		return $this->fetch();
	}
	public function postblog()//发表博文
	{
		$data = $this->request->param();
		$info = $this->article->insertArt($data);
		if ($info) {
			$uid = Session::get('uid');
			$this->user->scoreSelf($uid);
			$this->topic->addSelf($data['tid']);
			$this->success('发表成功', 'blog/blog');
		}else {
			$this->error('发表失败', 'blog/blog');
		}
	}
	public function uploadHead()//上传头像
	{
		$uid = Session::get('uid');
		$fileimg = request()->file('image');
		$img = $this->user->getLoad($fileimg);
		if($this->user->save(['uimage'=>$img],['uid'=>$uid])){
			$this->success('上传头像成功','blog/blog','',1);
		}
	}
	public function attention()//关注的人
	{
		$uid = Session::get('uid');
		$attentions = $this->attention->attentioned($uid);
	}
	public function other()//进入其他人主页
	{
		$data = $this->request->param();
		dump($data);
	}
	public function setAttention()//处理关注
	{
		$uid = Session::get('uid');//取得用户的id
		$data = $this->request->param();
		$attuid = $data['attuid'];
		if(isset($data['lid'])){//取消关注
			$this->attention->destroy(['attuid'=>$attuid]);
			$this->success('取消关注成功',url('blog/blog',array('uid'=>$attuid)),'',1);
		}else{//加关注
			$this->attention->data([
					'uid'=>$uid,
					'attuid'=>$attuid
				]);
			if($this->attention->save()){
				$this->success('关注成功',url('blog/blog',array('uid'=>$attuid)),'',1);
			}
		}
	}
	public function advertising()//增加广告
	{
		$uid = Session::get('uid');
		$data = $this->request->param();
		$fileimg = request()->file('adverimage');
		if(!empty($data['advername'])){
			if(!empty($fileimg)){
				$image = $this->advertise->uploadImg($fileimg);
				$data['adverimage'] = $image;
			}
			$data['uid'] = $uid;
			$this->advertise->data($data);
			if($this->advertise->save()){
				$this->success('添加成功',url('blog/blog',array('uid'=>$uid)),'',1);
			}
		}
		return $this->fetch();
	}
	public function sendPayOrd()
	{
		include 'yeepayCommon.php';
		$data = $this->request->param();
		dump($data);die;
		$data = array();
		#业务类型
		$data['p0_Cmd']				= "Buy";
		#	商户订单号,选填.
		$data['p1_MerId']     = $p1_MerId;
		##若不为""，提交的订单号必须在自身账户交易中唯一;为""时，易宝支付会自动生成随机的商户订单号.
		$data['p2_Order']			= $this->request->param('p2_Order');
		#	支付金额,必填.
		##单位:元，精确到分.
		$data['p3_Amt']			  = $this->request->param('p3_Amt');
		#	交易币种,固定值"CNY".
		$data['p4_Cur']				= "CNY";
		#	商户接收支付成功数据的地址,支付成功后易宝支付会向该地址发送两次成功通知.
		$data['p8_Url']			  = '';	
		#签名串
		$hmac                         = HmacMd5(implode($data),$merchantKey);
		$this->assign('data',$data);
		$this->assign('reqURL_onLine',$reqURL_onLine);
		$this->assign('hmac',$hmac);
		$this->assign('p1_MerId',$p1_MerId);

		return $this->fetch();
	}

}