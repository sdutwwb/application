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
	//被关注人的详情
	public function attentioned($uid)
	{
		$list = $this->where('uid', $uid)->order('atttime', 'desc')->paginate(1);
		$page = $list->render();
		return ['list'=>$list, 'page'=>$page];	
	}
	//将用户人的头像名字压入到数组中
	public function attentioner($data)
	{

	}
}