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
}