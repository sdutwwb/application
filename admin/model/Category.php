<?php
namespace app\admin\model;

use think\Model;
class Category extends Model
{
		function tree(&$list,$pid=0,$level=0,$html='--')
		{
	    	$list = $this->select();
	    	static $tree = array();
	    	foreach($list as $v){
	        	if($v['pid'] == $pid){
	           	 	$v['sort'] = $level;
	             	$v['html'] = str_repeat($html,$level);
	            	$tree[] = $v;
	            	tree($list,$v['id'],$level+1);
	       	 	} 
	   		 }
	    	return $tree;
		}
}