<?php
namespace app\index\model;

use think\Model;
class Attention extends Model
{
	//粉丝数量
	public function fans($attuid)
	{
		return $this->where('attuid', $attuid)->count();
	}
	//关注的人数
	public function attentions($uid)
	{
		return $this->where('uid', $uid)->count();
	}
}