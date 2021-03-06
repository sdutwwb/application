<?php
namespace app\admin\model;

use think\Model;
class Category extends Model
{
		function tree(&$list,$pid=0,$level=0,$html='--')
		{
	    	//$list = $this->select();
	    	static $tree = array();
	    	foreach($list as $v){
	        	if($v['pid'] == $pid){
	           	 	$v['sort'] = $level;
	             	$v['html'] = str_repeat($html,$level);
	            	$tree[] = $v;
	            	$this->tree($list,$v['id'],$level+1);
	       	 	} 
	   		 }
	    	return $tree;
		}
		public function diguiDelete($id)
		{
			$this->where('id',$id)->delete();
			$list = $this->where('pid','id')->select();
			if(isset($list['id'])){
				$this->diguiDelete($list['id']);
			}else{
				return true;
			}
		}

		/*public function demo($id)
		{
			$result = $this->category->where('pid',$id)->order()->find();
			if(empty($result)){
				return $id;
			}else{
				$this->demo($result['id']);
			}
		}*/
}