<?php
namespace app\index\controller;
use app\index\model\Bigtype;
use think\Request;
use think\Db;

class Book extends \think\Controller
{
    public function book(){
        $date = Request::instance()->param();
        $res = Db::table("tb_book")
        ->where("book_name",$date["bookname"])
        ->find();
        $res1 = Db::table("tb_comment")
        ->where("book_id",$date["bookId"])
        ->select();
        $this->assign("res",$res);
        $this->assign("res1",$res1);
        return $this->fetch();
    }



    public function booklist(){
		$date = Request::instance()->param();
        $booktype = $date['booktype'];
        $res = Db::table("tb_smalltype a","tb_book b")
        ->join("tb_book b","a.s_id=b.s_id")
        ->where("a.s_name",$date["booktype"])
        ->paginate(3,false,[
            'query'=>request()->param(),
            ]);
        //$page = $res -> render();
        // $this->assign("page",$page);
        $this->assign("res",$res);
        return $this->fetch();

	}

    public function googleBookList(){
        $date = Request::instance()->param();
        $date1 = $date['bookName'];
        $res = Db::table("tb_book")
        ->where("book_name","LIKE","%$date1%")
        ->paginate(3,false,[
            'query'=>request()->param(),
            ]);
        $this->assign("res",$res);
        return $this->fetch();
    }

    

    public function booktype()
    {
        $bigtype = new Bigtype();
        $data1 = $bigtype->field('b_id,b_name')->select();
        $this->assign('bb',$data1);

        $data2=$bigtype->alias('a')->join('tb_smalltype b','a.b_id = b.b_id')->select();
        $this->assign('ss',$data2);

        return $this->fetch();
    }

}