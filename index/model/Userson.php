<?php
namespace app\index\model;
use think\Model;

class Userson extends Model
{
	//通过用户id查询用户私人数据
	public function selectIntro($uid)
	{
		return $this->where('uid', $uid)->find();
	}
}