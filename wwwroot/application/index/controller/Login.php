<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
class Login extends Controller{
    public $username="";
    //登陆账号
    public function doLogin(){     
        $this->username = $_POST['username'];
        $password = $_POST['password'];
        $account=Db::table('login')->where('account',$this->username)->select();
        $dbpassword=$account['0']['password'] ?? "null";
        if($account){
            if($password==$dbpassword){
            http_response_code(200);
            echo json_encode(['code'=>'200','message' => '您已成功登录！']);
            session_start();
            $_SESSION['username'] = $this->username;
            Db::table('login')
                ->where('account', $this->username)
                ->update(['state' => '在线']);
            }else{
                http_response_code(401);
                echo json_encode(['code'=>'401','message' => '密码错误!']);
            }
        }else{
            http_response_code(401);
            echo json_encode(['code'=>'401','message' => '用户名错误!']);
        }
    }   
    //获取会话的账号
    public function getUsername(){
        session_start();
        if (isset($_SESSION['username'])) {
            return $_SESSION['username'];
        } else {
            return null;
        }
    }
    //注册账号
    public function doRegister(){
        $username1 = $_POST['new-username'];
        $password1 = $_POST['new-password'];
        if($username1!=""){
            if($password1!=""){
                $existingAccount = Db::name('login')->where('account', $username1)->find();
                if ($existingAccount) {
                    http_response_code(401);
                    echo json_encode(['code'=>'401','message' => '账号已存在!']);
                } else {
                    Db::name('login')->insert([
                        'account' => $username1,
                        'password' => $password1,
                    ]);
                    http_response_code(200);
                    echo json_encode(['code'=>'200','message' => '注册成功!账号:'.$username1.'密码:'.$password1]);
                }
            }else{
                http_response_code(401);
                echo json_encode(['code'=>'401','message' => '密码不能为空!']);
            }
        }else{
            http_response_code(401);
            echo json_encode(['code'=>'401','message' => '账号不能为空!']);
        }
    }
    //检测客户端是否在线
    public function logout(){
        session_start();
        if (isset($_SESSION['username'])) {
            Db::table('login')
                ->where('account', $_SESSION['username'])
                ->update(['state' => '在线']);
            http_response_code(200);
            echo json_encode(['code'=>'200','message' => '客户端在线']);
        } else {
            // If the session variable is not set, return an error message
            http_response_code(401);
            echo json_encode(['code'=>'401','message' => '客户端不在线']);
        }
    }
}
?>
