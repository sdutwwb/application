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
	//跟新登录时间和ip
	public function updateDateTime($uid)
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
	//将用户头像和用户名放到用户信息中去
	public function getUserAll($list)
	{
		if (!empty($list)) {
			foreach ($list as $key => $value) {

				$data = $this->where('uid', $value['uid'])->find();
				$list[$key]['uname'] = $data['uname'];
				$list[$key]['uimage'] = $data['uimage'];
				$list[$key]['uid'] = $data['uid'];
			}
		}
		return $list;
	}
	public function getLoad($files)
	{
		$str = '';
		foreach($files as $file)
		{
		// 移动到框架应用根目录/public/uploads/ 目录下
			$info = $file->validate(['size'=>1000000,'ext'=>'jpg,png,gif,jpeg'])->move(ROOT_PATH . 'public' . DS . 'uploads');
				if($info){
				// 成功上传后 获取上传信息
				// 输出 jpg
				//echo $info->getExtension();
				// 输出 42a79759f284b767dfcb2a0197904287.jpg
				$str .= $info->getSavename();
				}else{
				// 上传失败获取错误信息
				return  $file->getError();
				}
		}
		return $str;
	}
}