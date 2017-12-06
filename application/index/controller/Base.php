<?php
namespace app\index\controller;
use think\Controller;
class Base extends Controller{

	public function _initialize(){
        if (!session('username')) {
        	# code...
        	$this->error("请先登录","User/login");
        }
	}
}