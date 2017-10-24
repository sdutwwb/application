<?php
namespace app\admin\model;

use think\Model;
class Admin extends Model
{
	protected $autoWriteTimestamp = true;
	protected $auto = ['ip'];
	protected $update = [];
	protected $updateTime = 'lasttime';
	
	protected function setIpAttr()
	{
		return request()->ip();
	}

}