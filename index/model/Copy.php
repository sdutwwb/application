<?php
namespace app\index\model;

use think\Model;
class Copy extends Model
{
	public function Copy($data)
	{
		return $this->where($data)->find();
	}
	public function insertCopy($data)
	{
		$this->data($data);
		return $this->save();
	}
}