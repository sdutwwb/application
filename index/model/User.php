<?php
namespace app\index\model;
use think\Model;

class User extends Model
{
	protected $auto = ['ip'];
	protected $update = [];
	public function user()
	{
		
	}
	protected function setIpAttr()
	{
		return request()->ip();
	}
	//通过手机号更改密码
	public function updatePwd($data)
	{
		$password = md5($data['password']);
		$istrue = $this->where('phone', $data['phone'])->update(['password'=> $password]);
		if ($istrue) {
			return true;
		} else {
			return false;
		}
		
	}
	public function updateDateTime($uid)//跟新登录时间和ip
	{
		return $this->where('uid',$uid)->update(['datetime'=>time()]);
	}
	//通过用户id查询单条数据
	public function selectSingle($uid)
	{
		return $this->where('uid', $uid)->find();
	}
	//发博加积分
	public function scoreSelf($uid)
	{
		return $this->where('uid', $uid)->setInc('score', 2);
	}
}