<?php
namespace app\index\controller;
use think\Request;
use think\Session;
use think\Db;
/**
* 
*/
class Order extends Base
{
	public function checkout(){
		$username = Session::get("username");
		$res = Db::table("tb_shappingcat a")
		->join("tb_book b","a.book_id=b.book_id")
		->where("a.user_id",$username)
		->select();
		
		$tatol = 0;
		foreach ($res as $key => $value) {
			$tatol += $value["book_newprice"];
		}
		$res1 = Db::table("tb_shappingcat a")
        ->join("tb_book b","a.book_id=b.book_id")
        ->where("a.user_id",$username)
   		->select();
   	    $str = "";
   		foreach ($res1 as $key => $value) {
   			$str1 = strval($value["book_id"]);	
   			$str = $str.$str1."/";
   		}
   		$this->assign("res",$res);
		$this->assign("tatol",$tatol);
		$this->assign("str",$str);
		return $this->fetch();

	}

	public function checkoutDo(){
		$username = Session::get("username");
		$date = Request::instance()->param();
        $res = Db::table("tb_order")
         ->insert([
        	"b_id" => $date["shuID"],
        	"user_name" => $username,
        	"user_address" => $date["address"],
        	]);
        if ($res) {
        	$this->success("购买成功","index/index");
        }else{
        	$this->error("购买失败","index/index");
        }
	}

	public function che(){
		$username = Session::get("username");
		$arr = array();
		$res = Db::table("tb_order")
		->where("user_name",$username)
		->select();
		$i = 0;
		$j = 0;
		foreach ($res as $key => $value) {
			$arr1 = explode("/", $value["b_id"]);
			foreach ($arr1 as $key => $value) {
				$res1 = Db::table("tb_book")
				->where("book_id",$value)
				->select();
				foreach ($res1 as $key => $value) {
					$arr[$i][] = $value["book_name"];
				}

			}
			$i++;
		}
		$this->assign("res1",$res);
		$this->assign("res",$arr);
		return $this->fetch();
	}
	public function removeCheckOut(){
		$orderId = $_GET['num'];
		Db::table("tb_order")
		->where("order_id",$orderId)
		->delete();
	}

	public function comment(){
		$shuming = $_GET["shuming"];
		$res = Db::table("tb_book")
		->where("book_name",$shuming)
		->find();
		$this->assign("bookId",$res["book_id"]);
		$this->assign("bookName",$res["book_name"]);
		return $this->fetch();
	}

	public function commentDo(){
		$date = Request::instance()->param();
		$username = Session::get("username");
		$res = Db::table("tb_comment")
		->insert([
			'content'=>$date['content'],
			'user_id'=>$username,
			'book_id'=>$date['bookId']
			]);
		if ($res) {
			$this->success("评论成功","index/index");
		}else{
			$this->error("评论失败","index/index");
		}
	}
}