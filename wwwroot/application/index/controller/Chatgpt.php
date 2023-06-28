<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
class Chatgpt extends Controller{
    public function chatgpt(){     
        //调用chatgpt接口
        $message=$_POST['message'];
        $url = 'http://zhijianpark.top:8000/chatapi';
        $data = array(
            'key' => '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',
            'model' => 'gpt-3.5-turbo',
            'text' => $message
        );

        $options = array(
            'http' => array(
                'header' => "Content-Type: application/json\r\n",
                'method' => 'POST',
                'content' => json_encode($data),
                'timeout' => 300
            )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        //数据库处理（存入chatgpt的记录）
        $messages = json_decode($result);
        $name="chatgpt";
        $message2 = $messages->choices[0]->message->content;
        $message2 = htmlspecialchars($message2, ENT_QUOTES, 'UTF-8');
        $time=date('Y-m-d H:i:s');
        $color="white";
        Db::name('chat')->insert([
            'name'=>$name,
            'message' => $message2,
            'time' => $time,
            'color'=>$color
        ]);
        //返回给前端
        http_response_code(200);
        echo json_encode(['code'=>'200','message' => $result]);
        }
}
?>