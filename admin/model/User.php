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
}