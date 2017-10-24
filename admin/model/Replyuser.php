<?php
namespace app\admin\model;

use think\Model;
class Replyuser extends Model
{
	public function insertReplyuser($data,$adminid)
	{
		$data['adminid'] = $adminid;
		return $this->isupdate(false)->save($data);
	}

	public function getReplyuser($data)
	{
		$umid = $data['umid'];
		$uid = $data['uid'];
		$result = $this->where('uid',$uid)->where('umid',$umid)->order('replytime','desc')->select();
		return $result;
	}
}