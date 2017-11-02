<?php
namespace app\index\model;

use think\Model;
class Primessage extends Model
{
	public function getMessage($uid)
	{
		$data = $this->where('uid', $uid)->whereOr('ruid', $uid)->select();
		$user = new User;
		foreach ($data as $key => $value) {
			if ($value['uid']==$uid) {
				$data[$key]['uname'] = $user->selectSingle($value['ruid'])['uname'];
				$data[$key]['uimage'] = $user->selectSingle($value['ruid'])['uimage'];
			}else {
				$data[$key]['uname'] = $user->selectSingle($value['uid'])['uname'];
				$data[$key]['uimage'] = $user->selectSingle($value['uid'])['uimage'];
			}
		}
		return $data;
	}
	public function insertPm($data)
		{
			$result = $this->where('pid',$data['id'])->order('pmtime', 'desc')->find();
			if(empty($result)){
				$this->data([
					'uid'=>$data['uid'],
					'ruid'=>$data['ruid'],
					'pid'=>$data['id'],
					'pmcontent'=>$data['pmcontent']
				]);
				$this->save();
			}else{
				$data['id'] = $result['id'];
				$this->insertPm($data);
			}
		}
}