<?php
namespace app\index\model;

use think\Model;
class Reply extends Model
{
	//根据时间排序所有回复
	public function getReply($pid)
	{
		return $this->where('pid', $pid)->order('replytime', 'desc')->select();
	}
	public function deleteR($pid)
	{
		return $this->where('pid', $pid)->delete();
	}
}