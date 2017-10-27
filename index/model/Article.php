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
	//遍历出微博的详情
	public function getDetails($aid)
	{
		return $this->where('aid', $aid)->find();
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
	//微博阅读量自增
	public function addSelf($aid)
	{
		return $this->where('aid', $aid)->setInc('readcount');
	}
	//一对多关系(微博对多条评论)
	public function comment()
	{
		$list = $this->hasMany('Comment', 'aid')->order('comtime', 'desc')->paginate(6);
		$page = $list->render();
		return ['list'=>$list, 'page'=>$page];
	}
}