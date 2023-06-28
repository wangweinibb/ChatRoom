<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
class Logout extends Login{
    public function logout(){
      //连接心跳刷新时间
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            Db::table('login')
                ->where('account', $this->getUsername())
                ->update(['time' => time(),'state'=>'在线']);     
            header('Content-Type: application/json');
            echo json_encode(array('success' => true));
            $inactiveUsers = Db::table('login')
            ->where('time', '<', time() - 20)
            ->update(['state' => '离线']);
          } else {
            header("HTTP/1.1 400 Bad Request");
            exit();
          }
    }
}
?>