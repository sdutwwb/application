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
	public function uploadImg($files)
	{
		$str = '';
		foreach($files as $file)
		{
		// 移动到框架应用根目录/public/uploads/ 目录下
			$info = $file->validate(['size'=>10000000,'ext'=>'jpg,png,gif,jpeg,mp4'])->move(ROOT_PATH . 'public' . DS . 'uploads');
				if($info){
				// 成功上传后 获取上传信息
				// 输出 jpg
				//echo $info->getExtension();
				// 输出 42a79759f284b767dfcb2a0197904287.jpg
				$str .= $info->getSavename().'&';
				}else{
				// 上传失败获取错误信息
				return  $file->getError();
				}
		}
		return $str;
	}
	public function selectSingle($uid)
	{
		$data = $this->where('uid', $uid)->select();
		return $data;
	}
}