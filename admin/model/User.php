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
	public function getMemberCount($member)
	{
		return $this->where('member',$member)->count();
	}
	public function getNewCount($member)
	{
		$start = date('Y-m-d 00:00:00');
		$end = date('Y-m-d H:i:s');
		$members = $this->where('member',$member)->select();
		$arr_mem = array();  
		foreach ($members as $k => $v) {  
   		 $datetime = substr($v['rtime'],0,10);//得到年月日  
    //得到每日新增用户数  
    	if(array_key_exists($datetime,$arr_mem)){  
        	$arr_mem[$datetime] +=1;  
    		}else{  
       		 $arr_mem[$datetime] =1;  
    		}  
		}
		$Monthmember = array_sum($arr_mem);
		$Daymember = array_pop($arr_mem);
		return ['Monthmember'=>$Monthmember,'Daymember'=>$Daymember];
	}
 }