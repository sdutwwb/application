<?php
namespace app\index\model;

use think\Model;
class Topic extends Model
{
	public function getAlltopic()
	{
		return $this->where('display', 1)->select();
	}
	//得到所属话题名字
	public function topicname($tid)
	{
		return $this->where('tid', $tid)->find()['tname'];
	}
	public function addSelf($tid)
	{
		return $this->where('tid', $tid)->setInc('artcount');
	}
}