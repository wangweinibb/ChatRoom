<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
class Message extends Login{
    //实现渲染聊天室界面
    public function message(){
        $data = ['name' => $this->getUsername()];
        $this->assign('data', $data);
        return $this->fetch('message/index');
    }
    //存入聊天记录
    public function chat(){
        $username=$this->getUsername();
        $message = $_POST['message'];
        $currentTime = $_POST['currentTime'];
        $color=$_POST['color'];
        Db::name('chat')->insert([
            'name'=>$username,
            'message' => $message,
            'time' => $currentTime,
            'color'=>$color
        ]);
    }

    /**
     * 上传图片
     */
    public function img()
    {
        $username=$this->getUsername();
        $currentTime = $_POST['currentTime'];
        $color=$_POST['color'];
        $file = request()->file('img');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
        if ($info) {
            $data = [
                'code' => 200,
                'data' =>  $info->getSaveName(),
            ];
            $imgSrc = '<img style="width:670px" src="/uploads/'.$info->getSaveName().'">';
            Db::name('chat')->insert([
                'name'=>$username,
                'message' => $imgSrc,
                'time' => $currentTime,
                'color'=>$color
            ]);
            return json($data);
        } else {
            $data = [
                'code' => 400,
                'msg' => $file->getError(),
            ];
            return json($data);
        }
    }


    //获取聊天记录
    public function getChat(){
        $chatData = Db::name('chat')->order('id desc')->limit(50)->select();
        $chatData = array_reverse($chatData);
        $name = array_column($chatData, 'name');
        $message = array_column($chatData, 'message');
        $time = array_column($chatData, 'time');
        $color = array_column($chatData, 'color');
        header('Content-Type: application/json');
        echo json_encode([$name, $message, $time, $color]);
    }
    //请求刷步数接口
    public function ShuaBuShu(){
        $user=$_POST['user'];
        $password=$_POST['password'];
        $step=$_POST['step'];
        $url = 'https://apis.jxcxin.cn/api/mi?user='.$user.'&password='.$password.'&step='.$step.'&ver=cxydzsv3';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $output = curl_exec($ch);
        curl_close($ch);
        echo $output;
    }
}
?>