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

    public $title='随机生成';
    public function initialize(){
        if(empty(session('admin'))||session('admin')==null||session('admin')==false){
            $url='/Login/index';
            header("Location: $url");
            exit;
        }
    }
    /**
     * @return string
     * 生成彩票页
     */
    public function index(){
        View::assign('title',empty($title)?$this->title:$title);
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
        $y=date('y');
        if($num==1){
            $res['day_code']=$y.$num.rand(1000,9999);
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
            $res['day_code']=$y.$num.rand(1000,9999);
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
    public function newest(){
        $where['is_deleted']=0;
        $where['type']=1;
        $year=date('y');
        $type=$this->request->get('type');
        //图表数据
        if($type=='f') {
            $data = newest_data($type);
            $str='red1,red2,red3,red4,red5,red6';
            $red=Db::name($this->fc)->field($str)->where($where)->where('day_code','>',intval($year.'10000'))->where('day_code','<',intval($year.'100000'))->select();

            $blue=Db::name($this->fc)->field('blue')->where($where)->where('day_code','>',intval($year.'10000'))->where('day_code','<',intval($year.'100000'))->select();
            $count=count($red);
            $red=$this->newest_key_value($red,'red');
            $blue=$this->newest_key_value($blue,'blue');
//            halt($blue);
        }elseif($type=='t'){
            $data = newest_data($type);
            $str='red1,red2,red3,red4,red5';
            $red=Db::name($this->tc)->field($str)->where($where)->where('day_code','>',intval($year.'20000'))->where('day_code','<',intval($year.'200000'))->select();
            $blue=Db::name($this->tc)->field('blue1,blue2')->where($where)->where('day_code','>',intval($year.'20000'))->where('day_code','<',intval($year.'200000'))->select();
            $count=count($red);
            $red=$this->newest_key_value($red,'red');
            $blue=$this->newest_key_value($blue,'blue');
        }else{
            $data=[];
            $red['red_key']='';
            $red['red_val']='';
            $blue['blue_key']='';
            $blue['blue_val']='';
            $count=0;
        }

        View::assign('data',$data);
        View::assign('red_key',json_encode($red['red_key']));
        View::assign('red_val',json_encode($red['red_val']));
        View::assign('blue_key',json_encode($blue['blue_key']));
        View::assign('blue_val',json_encode($blue['blue_val']));
        View::assign('count',$count);
        View::assign('title',empty($title)?$this->title:$title);
        View::assign('type', $type);
        return View::fetch();
    }

    /**
     * @param $data
     * @param $type
     * @return mixed
     * 处理数据键值对分离
     */
    protected function newest_key_value($data,$type){
        $result = [];

        array_walk_recursive($data, function($value) use (&$result) {
            array_push($result, $value);
        });
        $arr=array_count_values($result);
        ksort($arr);
        $arr_key=array_keys($arr);
        $arr_val=array_values($arr);
        if($type=='red'){
            $res['red']=$arr;
            $res['red_key']=$arr_key;
            $res['red_val']=$arr_val;
        }else{
            $res['blue']=$arr;
            $res['blue_key']=$arr_key;
            $res['blue_val']=$arr_val;
        }

        return $res;
    }

    /**
     * @return false|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * 出现次数最多的号码
     */
    public function newest_result(){
        if($this->request->isPost()){
            $res=$this->request->post('res');
            $res=appear_most($res);
            return $res;
        }
    }


    /**
     * @return string
     * 往期更多数据
     */
    public function more_past(){
        $title=$this->title.'_往期更多数据';
        $type=$this->request->get('type');
        if($type==='f'||$type==='t') {
            View::assign('type', $type);
            View::assign('title', $title);
            return View::fetch();
        }else{
            exit;
        }

    }

    /**
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * 查询往期更多数据
     */
    public function more_past_data(){
        $data=$this->request->get();
        $data=more_past_data($data);
        return $data;
    }

}