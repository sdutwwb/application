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
	//插入私信
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
	//插入第一条私信
	public function insertspm($data)
	{
		$this->data($data);
		$this->save();
	}
	//查找与某个人的私信
		public function getSinglemess($inf)
		{
			$data = $this->where(['uid'=>$inf['uid'], 'ruid'=>$inf['ruid']])->whereOr(['uid'=>$inf['ruid'], 'ruid'=>$inf['uid']])->select();
			$user = new User;
			foreach ($data as $key => $value) {
				if ($value['uid']==$inf['uid']) {
					$data[$key]['uname'] = $user->selectSingle($value['ruid'])['uname'];
					$data[$key]['uimage'] = $user->selectSingle($value['ruid'])['uimage'];
				}else {
					$data[$key]['uname'] = $user->selectSingle($value['uid'])['uname'];
					$data[$key]['uimage'] = $user->selectSingle($value['uid'])['uimage'];
				}
			}
			return $data;
		}
}