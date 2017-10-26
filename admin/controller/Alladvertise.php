<?php
namespace app\admin\controller;

use  \think\Controller;
use app\admin\model\User as UserModel;
use app\admin\model\Article;
use app\admin\model\Usermessage;
use app\admin\model\Advertise;
use \think\Validate;
use \think\Session;
use \think\Cache;
class Alladvertise extends Auth
{
	protected $is_login = ['*'];
	protected $advertise;
	
	public function _initialize()
	{
		$this->advertise = new Advertise;
	}
	public function advertising_list()//广告列表
	{
		$status = $this->request->param('status');
		if(empty($status)){
			$list = $this->advertise->paginate(10);
		}else{
			$list = $this->advertise->where('adverstatus',$status)->paginate(10);
		}
		
		//dump($this->article->getlastsql());
		 // 获取分页显示
		 $page = $list->render();
		 $this->assign('list', $list);
		 $this->assign('page', $page);
		return $this->fetch();
	}
	public function reduce_adver()
	{
		$adverid = $this->request->param('adverid');
		$this->advertise->where('adverid',$adverid)->delete();
		$this->redirect('alladvertise/advertising_list');
	}
	public function advertising()//增加广告
	{
		$data = $this->request->param();
		$files = request()->file('adverimage');
		if(!empty($data['advername'])&&!empty($data['advercontent'])){
			if(!empty($files)){
				$image = $this->advertise->uploadImg($files);
				$data['adverimage'] = $image;
			}
			$this->advertise->data($data);
			if($this->advertise->save()){
				$this->success('添加成功',url('alladvertise/advertising_list'));
			}
		}
		
		
		return $this->fetch();
	}
}