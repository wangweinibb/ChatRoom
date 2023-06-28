<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
class User extends Login{
    public function getUser(){
        //获取成员列表传给前端
        $time=Db::name('login')->column('time');
        $currentTimestamp = time();
        $account=Db::name('login')->column('account');
        $state=Db::name('login')->column('state');
        header('Content-Type: application/json');
        echo json_encode([$account,$state]);
    }
    public function forwordUser(){
        //转发请求
        $message = $_POST['message'];
        $response = array('message' => $message);
        echo json_encode($response);
    }
}
?>