<?php
namespace app\index\controller;

use \think\Controller;
use app\index\model\User as UserModel;
use app\index\model\Topic as TopicModel;
use \think\Validate;
use \think\Session;

class Log extends Controller
{
	protected $user;
	public function _initialize()
	{
		$this->user = new UserModel();
		$this->topic = new UserTopic();
	}
	public function log()
	{
		if (session('?uid')) {
			$uid = Session::get('uid');
			$data = $this->user->where('uid', $uid)->find();
			$topic = $this->topic->getAlltopic();
			$this->assign(['islog'=>1, 'data'=>$data], 'topic'=>$topic);
		} else {
			$this->assign('islog', 0);
		}
		return $this->fetch();
	}
	public function checklog()
	{
		$data = $this->request->param();
		if ($data) {
			$email = $this->user->where('email', $data['username'])->find();//邮箱登陆
			$phone = $this->user->where('phone', $data['username'])->find();//手机号登陆
			$password = md5($data['password']);
			if($email) {
				if($email['password'] == $password){
					session('uid', $email['uid']);
					$this->user->updateDateTime($email['uid']);
					$this->success('登录成功', url('index/index'));
				}else {
					$this->error('密码错误,登录失败', url('log/log'));
				}
			}elseif($phone) {
				if($phone['password'] == $password){
					session('uid', $phone['uid']);
					$this->user->updateDateTime($phone['uid']);
					$this->success('登录成功', url('index/index'));
				}else {
					$this->error('密码错误,登录失败', url('log/log'));
				}	
			}else {
				$this->error('登录失败', url('log/log'));	
			}

		} else {
			$this->error("数据异常,即将返回首页",url('index/index'));
		}
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
			echo json_encode(['code'=>3 , 'message' => '亲，验证码错了哟']);
		} else {
			echo json_encode(['code'=>0 , 'message' => '快点点击注册吧']);
		}
	}
}