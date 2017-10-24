<?php
namespace app\admin\model;

use think\Model;
class Topic extends Model
{
	//状态判断
	public function getDisplayAttr($value)
	{
		$display = ['0'=>'不显示','1'=>'显示'];
		return $display[$value];
	}
	public function getAllTopic()
	{
		$list = $this->paginate(3);
		 // 获取分页显示
		 $page = $list->render();
		 if(is_null($page)){
		 	return ['list'=>$list];
		 }else{
		 	return ['list'=>$list,'page'=>$page];
		 } 
	}
	public function addcate($data)//增加话题
	{
		return $this->save($data);
	}
	public function getSimplecate($tid)
	{
		return $this->where('tid',$tid)->find();
	}
	public function setUpdatecate($data)//修改话题
	{
		$this->isupdate(true)->save($data);
	}
}