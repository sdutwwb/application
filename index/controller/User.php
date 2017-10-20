<?php
namespace app\index\controller;

use  \think\Controller;
use app\index\model\User as UserModel;
use \think\Validate;
use \think\Session;
class User extends Controller
{
	protected $user;
	public function _initialize()
	{
		$this->user = new UserModel();
	}
	public function regmail()
	{
		return $this->fetch();
	}
	//检查邮箱
	public function checkemail()
	{
		
		$email = $this->request->param('email');
		$validate = new Validate([
			'email' => 'email'
			]);
		$data = [
			'email' => $email,
		];
		if (!$validate->check($data)) {
			echo json_encode(['code'=>1 , 'message' => '亲，你输入的邮箱格式不正确哦']);
		} else {
			$data = $this->user->where('user_name', $email)->find();
			if ($data) {
				echo json_encode(['code'=>2 , 'message' => '亲，您输入的邮箱已被注册了哟']);
			} else {
				echo json_encode(['code'=>0 , 'message' => '亲，您输入的邮箱可用了哟']);
			}
		}
	}
	//手机号验证
	public function checkphone()
	{
		$phone = $this->request->param('phone');
		$data = $this->user->where('user_name', $phone)->find();
			if ($data) {
				echo json_encode(['code'=>2 , 'message' => '亲,您输入的手机号已被注册了哟']);
			} else {
				echo json_encode(['code'=>0 , 'message' => '亲,您输入的手机号可用了哟']);
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
	//手机激活码验证
	public function checkpricode()
	{
		$pricode = $this->request->param('pricode');
		if (Session::has('yzm')) {
			if (Session::get('yzm') == $pricode) {
				echo json_encode(['code'=>0 , 'message' => '快点点击注册吧']);
			} else {
				echo json_encode(['code'=>3 , 'message' => '亲，验证码错了哟']);
			}
		} else {
			echo json_encode(['code'=>1 , 'message' => '还没点击获取手机验证码按钮哦']);
		}
	}
	//插入数据
	public function checkreg()
	{
		$this->success('注册成功', 'index/index');
		//dump($this->request->param());
	}
	public function regphone()
	{
		return $this->fetch();
	}
	//手机激活码
	public function ucpaas()
	{
		if (!isset($_POST['phone'])) {
			exit;
		}
		$phone = $_POST['phone'];
		define('BASE_URL', 'https://api.ucpaas.com/');
		define('SOFT_VERSION','2014-06-30');

		$accountSid = '2e24761cd9beb800c53e5bd5a2ffa7ca';
		$authorToken = 'be91ff76da2879a42a483802412343b6';
		//设置默认时区
		date_default_timezone_set('PRC');
		$timestamp = date('YmdHis');

		$sigParameter = strtoupper(md5($accountSid.$authorToken.$timestamp));

		$url = BASE_URL . SOFT_VERSION . '/Accounts/' . $accountSid . '/Messages/templateSMS?sig=' . $sigParameter;

		$authorization = base64_encode($accountSid.':'.$timestamp);
		//拼接header
		$header = [
			'Accept:application/json',
			'Content-Type:application/json;charset=utf-8',
			'Authorization:'.$authorization
		];

	$nums = '1234567890';
	$yzm = substr(str_shuffle($nums),0,4);
		$data['templateSMS'] = [
								'appId'=>'5e6cbb9fc7244006beac494b849111e5',
								'templateId'=>'153890',
								'to'=>$phone,
								'param'=>$yzm
							];

		$body = json_encode($data);

		//发送请求
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch,CURLOPT_POST,1);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$body);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		$result = curl_exec($ch);
		curl_close($ch);
		//var_dump($result);
		$result = json_decode($result, true);
		$respCode = $result['resp']['respCode'];

		if ($respCode == '000000') {
			$send = 'ok';
			//将验证码保存在session中
			Session::set('yzm',$yzm);
		} else {
			$send = 'err';
		}
		echo json_encode(['send' => $send]);
	}
}