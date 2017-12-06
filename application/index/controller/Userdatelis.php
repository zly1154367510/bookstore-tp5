<?php
namespace app\index\controller;
use think\Controller;
use \app\index\model;
use \think\Session;
class Userdatelis extends Base
{
	public function index(){
		$u = new \app\index\model\User();
		$username = Session::get("username");
		$res = $u ->where("user_name",$username) -> find();
		$this->assign("res",$res);
		return $this->fetch();
	}
}