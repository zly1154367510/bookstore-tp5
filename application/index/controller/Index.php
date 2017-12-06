<?php
namespace app\index\controller;
use think\Db;

class Index  extends \think\Controller
{
    public function index()
    {
        $book = new \app\index\model\Book();
		$data= $book->field('book_id, book_name, book_newprice ,book_img')->where('book_issepprice=1')->find();
		$this->assign('tejiabook',$data);	
		return $this->fetch();	
    }
}
