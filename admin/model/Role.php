<?php
namespace app\admin\model;

use think\Model;
class Role extends Model
{
	public function roleTopermisssion()//多对多查询
	{
		return $this->belongsToMany('Permission','role_permission','pid','rid');
	}

	public function getAdminPer($adminrole)
	{
		$rid = [];
		foreach ($adminrole as $key => $value) {
				$rid[$key] = $value['rid'];
		}
		$rolePer = [];
		foreach ($rid as $key => $value) {
			$info = $this->get($value);
			$rolePer[] = $info->roleTopermisssion;
		}
		return $rolePer;
	}
}