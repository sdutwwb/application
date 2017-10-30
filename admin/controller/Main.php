<?php
namespace app\admin\controller;

use  \think\Controller;
use app\admin\model\User as UserModel;
use app\admin\model\Article;
use app\admin\model\Usermessage;
use app\admin\model\Replyuser;
use app\admin\model\Topic;
use \think\Validate;
use \think\Session;
class Main extends Auth
{
	protected $is_login = ['*'];
	protected $user;
	protected $usermessage;
	protected $replyuser;
	protected $article;
	protected $topic;
	public function _initialize()
	{
		$this->user = new UserModel;
		$this->usermessage = new Usermessage;
		$this->replyuser = new Replyuser;
		$this->article = new Article;
		$this->topic = new Topic;
	}
	public function main()//展示主页面
	{
		//博文的统计
		$status0 = $this->article->getArtCount(0);//已通过
		$status1 = $this->article->getArtCount(1);//审核中
		$status2 = $this->article->getArtCount(2);//未通过
		$status3 = $this->article->getArtCount(3);//通过并推荐
		$this->assign([
			'status0'=>$status0,
			'status1'=>$status1,
			'status2'=>$status2,
			'status3'=>$status3,
			]);

		//话题文章的统计
		$topicCount = $this->topic->getTopiccount();
		$this->assign('topicCount',$topicCount);

		//用户数量的统计
		$member0 = $this->user->getMemberCount(0);//普通用户
		$member1 = $this->user->getMemberCount(1);//会员

		$newmember1 = $this->user->getNewCount(0);//每日新增和每月新增会员数
		$newmember0 = $this->user->getNewCount(1);//每日新增和每月新增普通用户数
		$this->assign([
			'member1'=>$member1,
			'member0'=>$member0,
			'newmember0'=>$newmember0,
			'newmember1'=>$newmember1,
			]);


		//当前后台在线人数
		$online_log = "count.dat"; //保存人数的文件,
		$timeout = 30;//30秒内没动作者,认为掉线
		$entries = file($online_log);
		$temp = array();

		for ($i=0;$i<count($entries);$i++) {
		$entry = explode(",",trim($entries[$i]));
		if (($entry[0] != $_SERVER["REMOTE_ADDR"]) && ($entry[1] > time())) {
			array_push($temp,$entry[0].",".$entry[1]."n"); //取出其他浏览者的信息,并去掉超时者,保存进$temp
			}
		}

		array_push($temp,$_SERVER["REMOTE_ADDR"].",".(time() + ($timeout))."n"); //更新浏览者的时间
		$users_online = count($temp); //计算在线人数

		$entries = implode("",$temp);
		//写入文件
		$fp = fopen($online_log,"w");
		flock($fp,LOCK_EX); //flock() 不能在NFS以及其他的一些网络文件系统中正常工作
		fputs($fp,$entries);
		flock($fp,LOCK_UN);
		fclose($fp);
		echo "当前在线".$users_online."人";

		//用户留言数

		$message0 = $this->usermessage->getMessage(0);
		$message1 = $this->usermessage->getMessage(1);
		$this->assign([
			'message0'=>$message0,
			'message1'=>$message1,
			]);
		return $this->fetch();
	}
}