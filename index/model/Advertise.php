<?php
namespace app\index\model;

use think\Model;
class Advertise extends Model
{
	public function getSimpleImage($list)
	{
		
				$str1 = trim($list['adverimage'],'&');
				$str2 = trim($list['adverurl'],'&');
				$list['moreImage'] = explode('&', $str1);
				$list['moreUrl'] = explode('&', $str2);
				return $list;
	}
}