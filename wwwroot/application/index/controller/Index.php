<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
class Index extends Controller
{
    public function index(){    
        $count = Db::table('chat')->count(); 
        if($count>120){
            Db::table('chat')->where('1=1')->limit($count-120)->delete();
        }
        return $this->fetch('index/index');
    }
}
?>