<?php
namespace app\index\model;

use think\Model;
class Category extends Model
{
		function tree(&$list,$pid=0,$level=0,$html='--')
		{
	    	
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
}