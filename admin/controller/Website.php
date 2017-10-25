<?php
namespace app\admin\controller;

use  \think\Controller;
use app\admin\model\User as UserModel;
use app\admin\model\Article;
use app\admin\model\Usermessage;
use app\admin\model\Admin;
use \think\Validate;
use \think\Session;
class Website extends Auth
{
	protected $is_login = ['*'];
	protected $user;
	protected $usermessage;
	protected $admin;
	public function _initialize()
	{
		$this->user = new UserModel;
		$this->usermessage = new Usermessage;
		$this->admin = new Admin;
	}
	public function basic_settings()
	{
		return $this->fetch();
	}
	public function change_admin_ajaxname()//检测管理员nameajax处理
	{
		$adminname = $this->request->param('adminname');
		if(empty($adminname)){
			echo json_encode(['code'=>0,'message'=>'昵称不能为空']);
		}else{
			$nameresult = $this->admin->where('adminname',$adminname)->find();
			if($nameresult){
				echo json_encode(['code'=>1,'message'=>'管理员昵存在请继续']);
			}else{
				echo json_encode(['code'=>2,'message'=>'管理员昵称不存在请更换']);
			}
		}
		
	}
	public function change_admin_ajaxemail()//检测管理员emailajax处理
	{
		$email = $this->request->param('adminemail');
		$validate = new Validate([
			'email' => 'email'
			]);
		$data = [
			'email' => $email,
		];
		if (!$validate->check($data)) {
			echo json_encode(['code'=>1 , 'message' => '亲，你输入的邮箱格式不正确哦']);
		}else{
			$result = $this->admin->where('adminemail',$email)->find();
			if($result){
				echo json_encode(['code'=>2,'message'=>'邮箱存在请继续']);
			}else{
				echo json_encode(['code'=>3,'message'=>'邮箱不存在请更换']);
			}
		}
	}
	public function revise_password()//修改管理员页面
	{
		$data = $this->request->param();
		if(!empty($data)){
			$result =  $this->admin->updatePassword($data);
			if($result){
				$this->success('修改成功');
			}else{
				$this->error('修改失败');
			}
		}else{
			return $this->fetch();
		}
		
	}
}