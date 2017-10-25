<?php
namespace app\admin\model;

use think\Model;
class User extends Model
{
	public function onetomanyarticle()//1对多关联article表用与查询用户的发帖信息
	{
		return $this->hasMany('article','uid');
	}
	public function getMemberAttr($value)
	 {
		$member = [1=>'会员',0=>'非会员'];
		return $member[$value];
	}
	public function getScoreAttr($value)
	 {
		$va = floor($value/50);
		$score = ['0'=>"$va"];
		return $score['0'];
	}
	public function getSexAttr($value)
	{
		$sex = [0 => '保密', 2=>'女',1=>'男'];
		return $sex[$value];
	}
	public function getIslockAttr($value)
	{
		$islock = ['1'=>'已锁定','0'=>'未锁定用户'];
		return $islock[$value];
	}





	//得到所有用户信息
	public function getAlluser($like)//模糊查询用户详情
	{
		$member = $like['member'];//区别是普通用户还是管理员
		if(empty($like['like'])){
			$list = $this->where('member',$member)->paginate(10);
		}else{
			$like = $like['like'];
			$where1['uname'] = ['like',"$like"."%"]; 
			$where2['phone'] = ['like',"$like"."%"];
			//$where3['uid'] = ['like', "$like"]; 
			$list = $this->where('member',$member)->where('uid',$like)->paginate(10);
			$arr = $list->toArray();
			if($arr['total']==0){
				$list = $this->where('member',$member)->where($where2)->paginate(10);
				$arr = $list->toArray();
				if($arr['total']==0){
					$list = $this->where('member',$member)->where($where1)->paginate(10);
				}
			}
		
		}
		//->whereOr($where2)->whereOr($where3)
		 // 获取分页显示
		 $page = $list->render();
		 if(is_null($page)){
		 	return ['list'=>$list];
		 }else{
		 	return ['list'=>$list,'page'=>$page];
		 } 
	}
}