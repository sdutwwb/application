<?php
namespace app\index\model;

use think\Model;
class Topic extends Model
{
	public function getAlltopic()
	{
		return $this->where('display', 1)->select();
	}
}