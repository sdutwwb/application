<?php
namespace app\admin\model;


use think\Model;
class Advertise extends Model
{
	public function getAdverstatusAttr($value)
	 {
		$member = [1=>'赞助商的广告',0=>'会员的广告'];
		return $member[$value];
	}
	public function uploadImg($files)
	{
		$str = '';
		foreach($files as $file)
		{
		// 移动到框架应用根目录/public/uploads/ 目录下
			$info = $file->validate(['size'=>1000000,'ext'=>'jpg,png,gif,jpeg,mp4'])->move(ROOT_PATH . 'public' . DS . 'uploads');
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
}

	