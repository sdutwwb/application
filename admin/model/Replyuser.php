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
}