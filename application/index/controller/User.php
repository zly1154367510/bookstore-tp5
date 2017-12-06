<?php
namespace app\index\controller;
use \think\Session;

use think\Request;

class User extends \think\Controller
{
	//显示注册页面
	public function reg(){		
		return $this->fetch();
	}

	public function login(){
		return $this->fetch();
	}

	public function loginDo(){
        $u = new \app\index\model\User();
        $username = input("post.username");
        $password = input("post.password");
        $res = $u -> where("user_name",$username) ->find();
        if (!$res) {
        	 $this->success("用户名错误","User/login");
        }else{
        	$res1 = $u -> where("user_pwd",md5($password)) ->find();
        	if ($res1) {
        		Session::set("username",$username);
        	    $this->success("登录成功","index/index");
        	}else{
        		 $this->success("密码错误","User/login");
        	}
        }
	}

	//完善资料
	public function perfect(){
		return $this->fetch();
	}	

	public function perfectDo(){
		$file = request()->file("photo");
		$info = $file->move("/mbook/public/static/userImages/","");
		$qq = input("post.qq");
		$sex = input("post.genber");
		$phone = input("post.phone");
		$u = new \app\index\model\User();
		$username = Session::get("username");
		$res = $u -> where("user_name",$username) 
		->update([
			"user_sex"=>$sex,
			"user_qq"=>$qq,
			"user_tel"=>$phone,
			"user_photo"=>$info->getFilename(),
			]);
		if ($res) {
			$this->success("更新成功","index/index");
		}else{
			$this->error("更新失败","user/perfect");
		}

	}

    public function insert(){

		$u=new \app\index\model\User();
		$username=\think\Request::instance()->post('username'); // 获取某个post变量username
		$password=input('post.password');
		$password1=input('post.repass');
		$gender= input('post.gender'); //性别
		$email=input('post.email');
		if (md5($password)==md5($password1)){
			$sql=$u->where('user_name',$username)->find();
			if($sql){
				echo '<h1>该用户已存在. 点击此处 <a href="javascript:history.back(-1);">返回</a></h1>';
			}else{
				$data['user_name']=$username;
				$data['user_pwd']=md5($password);
				$data['user_sex']=$gender;
				$data['user_email']=$email;
				$u->insert($data); // 插入数据库
				$this->success("<h1>注册成功</h1>","index/index/index");
			}
		}else{
			echo '<h1>两次密码不一样. 点击此处 <a href="javascript:history.back(-1);">返回</a></h1>';
		}		
    }

    public function insert2(){
    	$data['user_name']=\think\Request::instance()->post('username'); // 获取某个post变量username
		$data['user_pwd']=input('post.password');
		$data['repass']=input('post.repass');
		$data['user_sex']=input('post.gender'); //性别
		$data['user_email']=input('post.email');
		$data['problem'] = input('post.problem');
		$data['answer'] = input('post.answer');

		$validate = \think\Loader::validate('User');
		if(!$validate->check($data)){
			//echo '<h1>'.$validate->getError().' 点击此处 <a href="javascript:history.back(-1);">返回</a></h1>';
			$this->error($validate->getError());
		}

		$u=new \app\index\model\User();
		$u->user_name=\think\Request::instance()->post('username');
		$u->user_pwd=md5(input('post.password'));
		$u->user_sex=input('post.gender'); //性别
		$u->user_email=input('post.email');
		$u->user_question=input('post.problem');
		$u->user_answer=input('post.answer');
		$u->save();
		$this->success("<h1>注册成功</h1>","index/index/index");
    }


		/*$u=new \app\index\model\User();
		$data['user_pwd']=md5(input('post.password'));
		$u->strict(false)->insert($data); // 插入数据库
		$this->success("<h1>注册成功</h1>","index/index/index");*/	
	//注销登陆
	public function cancellation(){
		Session::clear();
		$this->success("注销成功",'index/index/index');
	}
	//找回密码
	public function back(){
		return $this->fetch();
	}

	public function backDo(){
		$username = input('post.username');
		$problew = input('post.problew');
		$answer = input('post.answer');
		$u = new \app\index\model\User();
		$res = $u->where('user_name',$username)
		         ->where('user_question',$problew)
		         ->where('user_answer',$answer)
		         ->find();
		if ($res) {
			$this->redirect("User/rebuild",array("username"=>$username));
		}else{
			$this->error("信息错误无法找回","../User/login");
		}
	}

	public function rebuild(){
		$username = Request::instance()->param();
		$this->assign("username",$username["username"]);
		return $this->fetch();
	}

	public function rebulidDo(){
		$u = new \app\index\model\User();
		$date = Request::instance()->param();
		$password = $date['password'];
		$password1 = $date['password1'];
		$username = $date['username'];
		if ($password == $password1) {
			$res = $u->where("user_name",$username)->update(["user_pwd"=>md5($password)]);
			if ($res) {
				$this->success("修改成功，重新登录","User/login");
			}else{
				$this->error("修改失败","index/index");
			}
		}else{
			$this->error("两次密码不一致","User/login");
		}
	}

	


}