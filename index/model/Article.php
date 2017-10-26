<?php
namespace app\index\model;

use think\Model;
class Article extends Model
{
	//遍历自己的微博
	public function getAllart($uid)
	{
		$list = $this->where('uid', $uid)->order('pubtime', 'desc')->paginate(5);
		$page = $list->render();
		return ['list'=>$list, 'page'=>$page];	
	}
	//遍历所有的微博
	public function getAllarts($uid)
	{
		$list = $this->where('uid', $uid)->order('pubtime', 'desc')->paginate(1);
		$page = $list->render();
		return ['list'=>$list, 'page'=>$page];	
	}
	//删除数据
	public function deleteArt($aid)
	{
		return $this->where('aid', $aid)->delete();
	}
	//插入数据
	public function insertArt($data)
	{
		return $this->save($data);
	}
	//查询所有用户发表过的微博
	public function articleCount($uid)
	{
		return $this->where('uid', $uid)->count();
	}
}