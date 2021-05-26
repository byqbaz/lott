<?php
/**
 * Created by PhpStorm.
 * User: 62576
 * Date: 2021/4/29
 * Time: 15:34
 */

namespace app\controller;


use app\BaseController;
use think\facade\Db;
use think\facade\View;

class Lottery extends BaseController
{

    protected $fc='fucai';
    protected $tc='ticai';

    public function index(){
        return View::fetch('index');
    }

    public function generate_fc(){
        if($this->request->isPost()){
            $data=$this->request->post();
            $res=$this->generate_fc_data();
            if(!empty($data))
                $this->_generate_data($res,1);
            $res='{"red":'.json_encode($res['red']).',"blue":'.json_encode($res['blue']).'}';
            return $res;
        }
    }

    protected function generate_fc_data(){
        $arr = array();
        $special = array();
        //1-32位号码
        for ($i = 1; $i <= 33 ; $i++){
            $arr[$i] = $i;
        }
        //1-15特别号码
        for ($i = 1; $i <= 16 ; $i++) {
            $special[$i] = $i;
        }
        //随机取数组
        $arr2 = array_rand($arr,6);
        $arr3 = array_rand($special,1);


        $data['red'][0]=$arr2[0];
        $data['red'][1]=$arr2[1];
        $data['red'][2]=$arr2[2];
        $data['red'][3]=$arr2[3];
        $data['red'][4]=$arr2[4];
        $data['red'][5]=$arr2[5];
        $data['blue']=$arr3;
        $data['type']=1;
        return $data;
    }

    public function generate_tc(){
        if($this->request->isPost()){
            $data=$this->request->post();
            $res=$this->generate_tc_data();
            return json_encode($res);
        }
    }

    protected function generate_tc_data(){
        $arr = array();
        $special = array();
        //1-32位号码
        for ($i = 1; $i <= 35 ; $i++){
            $arr[$i] = $i;
        }
        //1-15特别号码
        for ($i = 1; $i <= 12 ; $i++) {
            $special[$i] = $i;
        }
        //随机取数组
        $arr2 = array_rand($arr,5);
        $arr3 = array_rand($special,2);

        $data['red'][0]=$arr2[0];
        $data['red'][1]=$arr2[1];
        $data['red'][2]=$arr2[2];
        $data['red'][3]=$arr2[3];
        $data['red'][4]=$arr2[4];
        $data['blue'][0]=$arr3[0];
        $data['blue'][1]=$arr3[1];
        $data['type']=1;
        return $data;
    }

    protected function _generate_data($data,$num){
        if($num==1){
            $res['day_code']='10'.$num.rand(1000,9999);
            $res['red1']=$data['red'][0];
            $res['red2']=$data['red'][1];
            $res['red3']=$data['red'][2];
            $res['red4']=$data['red'][3];
            $res['red5']=$data['red'][4];
            $res['red6']=$data['red'][5];
            $res['blue']=$data['blue'];
            $res['type']=$data['type'];
            $res['create_time']=time();
            $sId=Db::name($this->fc)->insertGetId($res);

        }else{
            $res['day_code']='10'.$num.rand(1000,9999);
            $res['red1']=$data['red'][0];
            $res['red2']=$data['red'][1];
            $res['red3']=$data['red'][2];
            $res['red4']=$data['red'][3];
            $res['red5']=$data['red'][4];
            $res['blue1']=$data['blue'][0];
            $res['blue2']=$data['blue'][1];
            $res['type']=$data['type'];
            $res['create_time']=time();
            $sId=Db::name($this->tc)->insertGetId($res);
        }
        return $sId;
    }

}