<?php
namespace app\index\model;

use think\Model;
class Reply extends Model
{
	public function getReply($pid)
	{
		return $this->where('pid', $pid)->order('replytime', 'asc')->select();
	}
}