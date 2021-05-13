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
use think\db\BaseQuery;

class Lottery extends BaseController
{

    protected $fc='fucai';
    protected $tc='ticai';

    /**
     * @return string
     * 生成彩票页
     */
    public function index(){
        return View::fetch('index');
    }

    /**
     * @return string
     * 处理福彩API
     */
    public function generate_fc(){
        if($this->request->isPost()){
            $data=$this->request->post();
            $res=$this->generate_fc_data();
            if(!empty($data))
                $this->_generate_data($res,1);
            $type=1;
            $res='{"red":'.json_encode($res['red']).',"blue":'.json_encode($res['blue']).',"type":'.$type.'}';
            return $res;
        }
    }

    /**
     * @return mixed
     * 生成福彩API
     */
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

    /**
     * @return string
     * 处理体彩API
     */
    public function generate_tc(){
        if($this->request->isPost()){
            $data=$this->request->post();
            $res=$this->generate_tc_data();
            if(!empty($data))
                $this->_generate_data($res,2);
            $type=2;
            $res='{"red":'.json_encode($res['red']).',"blue":'.json_encode($res['blue']).',"type":'.$type.'}';
            return $res;
        }
    }

    /**
     * @return mixed
     * 生成体彩API
     */
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

    /**
     * @param $data
     * @param $num
     * @return int|string
     * 彩票数据存库
     */
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

    /**
     * @return string
     * 彩票浏览页面
     */
    public function trend_chart(){
         if($this->request->get('type')==='f'){

             View::assign('type', $this->request->get('type'));
         }elseif($this->request->get('type')==='t'){

             View::assign('type', $this->request->get('type'));
         }else{
             View::assign('type', $this->request->get('type'));
         }
        return View::fetch();
    }

    /**
     * @return string
     * 彩票数据传递
     */
    public function trend_chart_data(){
        $where['is_deleted']=0;
        if($this->request->get('id')==='f') {
            $fc = Db::name($this->fc)->where($where)->order('fc_id desc')->limit(100)->select();
            return '{"code":0,"msg":"","count":1000,"data":' . json_encode($fc,true) . '}';
        }elseif($this->request->get('id')==='t'){
            $tc = Db::name($this->tc)->where($where)->order('tc_id desc')->limit(100)->select();
            return '{"code":0,"msg":"","count":1000,"data":' . json_encode($tc) . '}';
        }else{
            return '{"code":0,"msg":"","count":1000,"data":[{"id":10000,"username":"user-0","sex":"女","city":"城市-0","sign":"签名-0","experience":255,"logins":24,"wealth":82830700,"classify":"作家","score":57},{"id":10001,"username":"user-1","sex":"男","city":"城市-1","sign":"签名-1","experience":884,"logins":58,"wealth":64928690,"classify":"词人","score":27},{"id":10002,"username":"user-2","sex":"女","city":"城市-2","sign":"签名-2","experience":650,"logins":77,"wealth":6298078,"classify":"酱油","score":31},{"id":10003,"username":"user-3","sex":"女","city":"城市-3","sign":"签名-3","experience":362,"logins":157,"wealth":37117017,"classify":"诗人","score":68},{"id":10004,"username":"user-4","sex":"男","city":"城市-4","sign":"签名-4","experience":807,"logins":51,"wealth":76263262,"classify":"作家","score":6},{"id":10005,"username":"user-5","sex":"女","city":"城市-5","sign":"签名-5","experience":173,"logins":68,"wealth":60344147,"classify":"作家","score":87},{"id":10006,"username":"user-6","sex":"女","city":"城市-6","sign":"签名-6","experience":982,"logins":37,"wealth":57768166,"classify":"作家","score":34},{"id":10007,"username":"user-7","sex":"男","city":"城市-7","sign":"签名-7","experience":727,"logins":150,"wealth":82030578,"classify":"作家","score":28},{"id":10008,"username":"user-8","sex":"男","city":"城市-8","sign":"签名-8","experience":951,"logins":133,"wealth":16503371,"classify":"词人","score":14},{"id":10009,"username":"user-9","sex":"女","city":"城市-9","sign":"签名-9","experience":484,"logins":25,"wealth":86801934,"classify":"词人","score":75}]}';
        }

    }

    /**
     * @return string
     * 获取出现次数最多的号码
     */
    public function trend_chart_result(){
        if($this->request->isPost()){
            $where['is_deleted']=0;
             if($this->request->post('res')=='f'){
//                 $fc = Db::name($this->fc)->where($where)->order('fc_id desc')->limit(100)->column('red2');
                 $fc = Db::name($this->fc)->where($where)->field('red1,red2,red3,red4,red5,red6,blue')->order('fc_id desc')->limit(100)->select();
                 $arr=[];
                 foreach($fc as $key=>$val){
                     $red1[]=$val['red1'];
                     $red2[]=$val['red2'];
                     $red3[]=$val['red3'];
                     $red4[]=$val['red4'];
                     $red5[]=$val['red5'];
                     $red6[]=$val['red6'];
                     $blue[]=$val['blue'];

                 }

                 $arr['red1']=$this->trend_chart_result_data($red1);
                 $arr['red2']=$this->trend_chart_result_data($red2);
                 $arr['red3']=$this->trend_chart_result_data($red3);
                 $arr['red4']=$this->trend_chart_result_data($red4);
                 $arr['red5']=$this->trend_chart_result_data($red5);
                 $arr['red6']=$this->trend_chart_result_data($red6);
                 $arr['blue']=$this->trend_chart_result_data($blue);
                 $arr['type']=$this->request->post('res');
                 return json_encode($arr);
             }elseif($this->request->post('res')=='t'){
                 $fc = Db::name($this->tc)->where($where)->field('red1,red2,red3,red4,red5,blue1,blue2')->order('tc_id desc')->limit(100)->select();
                 $arr=[];
                 foreach($fc as $key=>$val){
                     $red1[]=$val['red1'];
                     $red2[]=$val['red2'];
                     $red3[]=$val['red3'];
                     $red4[]=$val['red4'];
                     $red5[]=$val['red5'];
                     $blue1[]=$val['blue1'];
                     $blue2[]=$val['blue2'];

                 }

                 $arr['red1']=$this->trend_chart_result_data($red1);
                 $arr['red2']=$this->trend_chart_result_data($red2);
                 $arr['red3']=$this->trend_chart_result_data($red3);
                 $arr['red4']=$this->trend_chart_result_data($red4);
                 $arr['red5']=$this->trend_chart_result_data($red5);
                 $arr['blue1']=$this->trend_chart_result_data($blue1);
                 $arr['blue2']=$this->trend_chart_result_data($blue2);
                 $arr['type']=$this->request->post('res');
                 return json_encode($arr);
             }

        }
    }

    /**
     * @param $data
     * @param bool $type
     * @return array|bool
     * 获取
     */
    protected function trend_chart_result_data($data,$type=false){
        if(!is_array($data)) return false;
        $data = array_count_values($data);
        arsort($data);
        $data=each($data) ;
        if($type===true){
            return $data;
        }else{
            return $data['key'];
        }
    }

    public function trend_chart_res(){

    }


}