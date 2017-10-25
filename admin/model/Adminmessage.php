<?php
namespace app\admin\model;

use think\Model;
class Adminmessage extends Model
{
	public function getAllAdminMessage($like)
	{
		if(empty($like['like'])){
			$list = $this->paginate(10);
		}else{
			$like = $like['like'];
			$where1['messcontent'] = ['like',"%"."$like"."%"]; 
			$where2['adminid'] = ['like',"$like"];
			$list = $this->where($where1)->whereOr($where2)->paginate(10);
		}
		 $page = $list->render();
		 if(is_null($page)){
		 	return ['list'=>$list];
		 }else{
		 	return ['list'=>$list,'page'=>$page];
		 } 
	}
	public function addmessage($mess,$adminid)
	{
		$this->data(['adminid'=>$adminid,'messcontent'=>$mess]);
		return $this->save();
	}
}