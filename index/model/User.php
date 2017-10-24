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

}