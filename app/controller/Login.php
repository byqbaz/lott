<?php

namespace app\controller;

use app\BaseController;
use think\facade\View;

/**
 * Class Login
 * @package app\controller
 * 登录控制器
 */
class Login extends BaseController
{
    /**
     * @var string[]
     * 用户信息
     */
    protected $user=[
        'admin_name'=>'admin',
        'password'=>'5771d12b747001ea71bf41490a616eaceaf11034',
        'num_code'=>'se231',
        'token'=>'lott_admin'
    ];

    /**
     * @return string|\think\response\Redirect
     * 登录入口
     */
    public function index(){
        if ($this->request->isGet()) {
            if(!empty(session('admin'))||session('admin')!=null) $this->login_jump();
            return View::fetch();
        }else{
            $data=$this->request->post();
            $this->user['admin_name']=$data['admin_name'];
            if($this->user['admin_name']==$data['admin_name']){
                    $pass=$data['password'].$this->user['num_code'];
                    if($this->user['password']==sha1($pass)){
                        session('admin',$this->user['admin_name']);
//                        return View::fetch('Index/index');
                        if(!empty(session('admin'))||session('admin')!=null) return redirect('/Index/index',500);
//                        exit;
                    }else{
                        return redirect('/Login/index',500);
                    }
            }else{
                return redirect('/Login/index',500);
            }
        }
    }

    /**
     * 登录跳转
     */
    protected function login_jump(){
        $url='http://'.$_SERVER['SERVER_NAME'];
        header("Location: $url");
        exit;
    }

    /**
     * 退出登录
     */
    public function out()
    {
        session('admin',null);
        return redirect('/Login/index');
    }
}