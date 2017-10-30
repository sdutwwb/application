<?php
namespace app\index\model;

use think\Model;
class Comment extends Model
{
	//相对的关联(多个评论对应一个微博)
	public function article()
	{
		return $this->belongsTo('article');
	}
	//一对多关系(一条评论对应多条回复)
	public function reply()
	{
		return $this->hasMany('Reply', 'pid')->order('replytime', 'desc');
	}
	//查询微博的所有评论数
	public function commentCount($aid)
	{
		return $this->where('aid', $aid)->count();
	}
	//删除评论
	public function deleteP($pid)
	{
		return $this->where('pid', $pid)->delete();
	}
}