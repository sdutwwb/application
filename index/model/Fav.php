<?php
namespace app\index\model;

use think\Model;
class Fav extends Model
{
	//查询用户的收藏
	public function selectFav($uid)
	{
		$list = $this->where('uid', $uid)->order('favtime','desc')->paginate(1);
		$page = $list->render();
		return ['list'=>$list, 'page'=>$page];
	}
	public function user()
	{
		return $this->belongsTo('user', 'uid');
	}
	public function fav($data)
	{
		return $this->where($data)->find();
	}
	public function insertFav($data)
	{
		$this->data($data);
		return $this->save();
	}
}