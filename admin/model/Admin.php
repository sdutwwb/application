<?php
namespace app\admin\model;


use think\Model;
class Admin extends Model
{
	
	protected $auto = ['lastip'];
	protected $update = [];
	protected function setLastipAttr()
	{
		return request()->ip();
	}
	/*public function setAdminpasswordAttr($value)//密码加密
	{
		return md5($value);
	} */
	public function updateLastTime($adminid)//跟新登录时间和ip
	{
		$ip = $_SERVER['REMOTE_ADDR'];
		if($ip == "::1"){
			$ip = '0.0.0.0';
		}
		return $this->where('adminid',$adminid)->update(['lasttime'=>time(),'lastip'=>$ip]);
	}

	
	public function getAlladmin()//得到所有的管理员 可以模糊查询  并分页
	{
		if(empty($like['like'])){
			$list = $this->paginate(10);
		}else{
			$like = $like['like'];
			$where1['adminname'] = ['like',"$like"."%"]; 
			$where2['admintel'] = ['like',"$like"."%"];
			$where3['adminid'] = ['like', "$like"]; 
			$list = $this->where($where1)->whereOr($where2)->whereOr($where3)->paginate(10);
		}
		 // 获取分页显示
		 $page = $list->render();
		 if(is_null($page)){
		 	return ['list'=>$list];
		 }else{
		 	return ['list'=>$list,'page'=>$page];
		 } 
	}
	public function adminTorole()//多对多查询
	{
		return $this->belongsToMany('Role','admin_role','rid','adminid');
	}
	public function updatePassword($data)
	{
		$result = $this->where('adminname',$data['adminname'])->where('adminemail',$data['adminemail'])
		->where('adminpassword',$data['adminpassword'])->find();
		if(is_null($result)){
			return false;
		}else{
			$password = $data['newpassword'];
			$this->where('adminname',$data['adminname'])->update(['adminpassword'=>$password]);
			return true;
		}
	}
}