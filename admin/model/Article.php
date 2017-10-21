<?php
namespace app\admin\model;

use think\Model;
class Article extends Model
{
	//文章审核状态转换
	public function getStatusAttr($value) {
		$status = [1=>'未审核',0=>'通过',2=>'通过并推荐'];
		return $status[$value];
	}

	//对对关系
	public function touser()//在user控制器中反查询用户信息
	{
		return $this->belongsTo('user', 'uid');
	}
	public function getuser()//一对一去查询用的信息
	{
		return $this->hasOne('user','uid');
	}


	//封装的方法
	public function getallArticle()//得到所有未处理的文章
	{
		return $this->where('status', '1')->select();
	}
	public function getsimpleArticle($aid)
	{
		return $this->where('aid',"$aid")->find();
	}
	public function solveimage($allimage)
	{
		$str1 = trim($allimage,'&');
		$arr = explode('&', $str1);
		return $arr;
	}
}