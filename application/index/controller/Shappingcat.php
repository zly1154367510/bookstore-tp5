<?php
namespace app\index\controller;
use \think\Session;
use think\Db;
use think\Request;


class Shappingcat extends Base
{
   public function addcat(){
	   	$name=$_GET["num"];
         $bookId = Db::table("tb_book")
         ->where("book_name",$name)
         ->find();
	   	$username = Session::get("username");
	   	$res = Db::table("tb_shappingcat")->insert([
		   	"book_id" => $bookId['book_id'],
		   	"user_id" => $username
		   	 ]);

   	}

   	public function index(){
   		$username = Session::get("username");
   		$res = Db::table("tb_shappingcat a")
         ->join("tb_book b","a.book_id=b.book_id")
         ->where("a.user_id",$username)
   		->select();
   		$this->assign("res",$res);
         return $this->fetch();
   	}

      public function removeBook(){
         $name=$_GET["num"];
         $bookId = Db::table("tb_book")
         ->where("book_name",$name)
         ->find();
         $username = Session::get("username");
         db("shappingcat")
         ->where("book_id",$bookId["book_id"])
         ->where("user_id",$username)
         ->delete();
        
      }
   	

}