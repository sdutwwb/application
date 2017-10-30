<?php
namespace app\index\model;

use think\Model;
class Zan extends Model
{
	public function Zan($data)
	{
		return $this->where($data)->find();
	}
	public function insertZan($data)
	{
		$this->data($data);
		return $this->save();
	}
}