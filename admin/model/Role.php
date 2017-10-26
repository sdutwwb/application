<?php
namespace app\admin\model;

use think\Model;
use \think\Db;
class Role extends Model
{
	public function roleTopermisssion()//多对多查询
	{
		return $this->belongsToMany('Permission','role_permission','pid','rid');
	}

	public function getAdminPer($adminrole)//得到有的权限
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
	public function getAdminNonePer($adminrole)//得到没有的权限
	{
		$rid = [];
		foreach ($adminrole as $key => $value) {
			$rid[$key] = $value['rid'];
		}
		//$data = $this->role->where('rid','<>','2')->where('rid','<>','1')->select();
		$arr = [];
		foreach ($rid as $key => $value) {
			$arr[] = $value; 
		}
		if(empty($arr)){
			$data = Db::query("select * from wb_role where rid <> 0");
		}else{
			$str = join(' and rid <>',$arr);
			$data = Db::query("select * from wb_role where (rid <> $str)");
		}
		return $data;
	}
}