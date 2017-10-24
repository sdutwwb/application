<?php
namespace app\admin\model;

use think\Model;
class Usermessage extends Model
{
	public function getMstatusAttr($value)
	 {
		$mstatus = [1=>'未处理',0=>'已经处理'];
		return $mstatus[$value];
	}


	public function getAllusermess($like)
	{
		$member = $like['member'];
		if(empty($like['like'])){
			$list = $this->where('member',$member)->paginate(10);
		}else{
			$like = $like['like'];
			$where['uid'] = ['like', "$like"]; 
			$list = $this->where('member',$member)->where($where)->paginate(10);
		}
		 // 获取分页显示
		 $page = $list->render();
		 if(is_null($page)){
		 	return ['list'=>$list];
		 }else{
		 	return ['list'=>$list,'page'=>$page];
		 } 
	}
	public function getsimpleuser($data)
	{
		$user = $this->get($data['uid']);
		return $user->onetoUser;
		 
	}



	public function onetoUser()
	{
		return $this->hasOne('user', 'uid');
	} 
}