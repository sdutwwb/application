<?php
namespace app\index\model;

use think\Model;
class Article extends Model
{
	//遍历自己的微博
	public function getAllart($uid)
	{
		$list = $this->where('uid', $uid)->order('pubtime', 'desc')->paginate(5);
		$page = $list->render();
		return ['list'=>$list, 'page'=>$page];	
	}
	//遍历所有的微博
	public function getAllarts($uid)
	{
		$list = $this->where('uid', $uid)->order('pubtime', 'desc')->paginate(1);
		$page = $list->render();
		return ['list'=>$list, 'page'=>$page];	
	}
	//遍历出微博的详情
	public function getDetails($aid)
	{
		return $this->where('aid', $aid)->find();
	}
	//删除数据
	public function deleteArt($aid)
	{
		return $this->where('aid', $aid)->delete();
	}
	//插入数据
	public function insertArt($data)
	{
		return $this->save($data);
	}
	//查询所有用户发表过的微博
	public function articleCount($uid)
	{
		return $this->where('uid', $uid)->count();
	}
	//微博转发数自增
	public function addCopy($aid)
	{
		return $this->where('aid', $aid)->setInc('copy');
	}
	//微博阅读量自增
	public function addSelf($aid)
	{
		return $this->where('aid', $aid)->setInc('readcount');
	}
	//微博评论数自增
	public function addComment($aid)
	{
		return $this->where('aid', $aid)->setInc('reply');
	}
	//微博赞的数量自增
	public function addZan($aid)
	{
		return $this->where('aid', $aid)->setInc('favour');
	}
	//一对多关系(微博对多条评论)
	public function comment()
	{
		$list = $this->hasMany('Comment', 'aid')->order('comtime', 'desc')->paginate(3);
		$page = $list->render();
		return ['list'=>$list, 'page'=>$page];
	}
	public function getTopicArt($like)//得到所有的管理员 可以模糊查询  并分页
	{
		if(empty($like['like'])){
			$tid = $like['tid'];
			$result = $this->where('tid',$tid)->order('pubtime','desc')->select();
			//dump($this->getlastsql());
		}else{
			$like = $like['like'];
			$where1['atitle'] = ['like',"%"."$like"."%"];  
			$where2['acontent'] = ['like',"%"."$like"."%"];  
			$result = $this->where($where1)->select();
			if(empty($result)){
				$result =  $this->where($where2)->order('pubtime','desc')->select();
			}
		}
		return $result;
	}
	public function belongsToUname()
	{
		return $this->belongsTo('User','uid');
	}
	//数据更新
	public function updateReply($data)
	{
		return $this->update($data);
	}
}