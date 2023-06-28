<?php
namespace app\index\model;
use think\Model;
class User extends Model{
    public function fun(){
        echo "我是模型层";
    }
}
?>