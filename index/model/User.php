<?php
namespace app\index\model;
use think\Model;

class User extends Model
{
	protected $auto = ['ip'];
	protected $update = [];
	public function user()
	{
		
	}
	protected function setIpAttr()
	{
		return request()->ip();
	}
}