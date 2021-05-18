<?php
namespace app\controller;

use app\BaseController;
use think\facade\View;
use think\facade\Db;

class Index extends BaseController
{
    /**
     * @return string
     * 首页
     */
    public function index()
    {

        return View::fetch('index');
    }

    /**
     * 测试专用方法
     */
    protected function _index_res(){
        $num=rand(1,2);
        switch($num) {
            case 1;
                $cp=$this->generate_fc();
                break;
            case 2;
                $cp=$this->generate_tc();
                break;
            default;
                $cp=4;
        }
        if($cp==4)
            $sId=0;
        else
            $sId=$this->_index_data($cp,$num);
        View::assign('id',$sId);
    }

    /**
     * @param $data
     * @param $num
     * @return int|string
     * 处理数据存库
     */
    protected function _index_data($data,$num){
        if($num==1){
            $res['day_code']='10'.$data['type'].rand(1000,9999);
            $res['red1']=$data['red'][0];
            $res['red2']=$data['red'][1];
            $res['red3']=$data['red'][2];
            $res['red4']=$data['red'][3];
            $res['red5']=$data['red'][4];
            $res['red6']=$data['red'][5];
            $res['blue']=$data['blue'];
            $res['type']=$data['type'];
            $res['create_time']=time();
            $sId=Db::name('fucai')->insertGetId($res);

        }else{
            $res['day_code']='10'.$data['type'].rand(1000,9999);
            $res['red1']=$data['red'][0];
            $res['red2']=$data['red'][1];
            $res['red3']=$data['red'][2];
            $res['red4']=$data['red'][3];
            $res['red5']=$data['red'][4];
            $res['blue1']=$data['blue'][0];
            $res['blue2']=$data['blue'][1];
            $res['type']=$data['type'];
            $res['create_time']=time();
            $sId=Db::name('ticai')->insertGetId($res);
        }
        return $sId;
    }

    /**
     * @return array
     * 福彩双色球
     */
    protected function generate_fc(){
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
     * @param $num
     * @return mixed
     * 体彩大乐透
     */

    protected function generate_tc(){
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
     * @return array
     * 可以用但是先不用
     */
    protected function generate_fc_y($num=4){
        $red = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33);
        $blue = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16);
        for ($i=0;$i<6;$i++){
            $index = rand(0,32-$i);
            $redBall[]= $red[$index];
            unset($red[$index]);
            for($k=$index;$k<count($red)-1;$k++){
                //新增判断变量是否存在
                if(isset($red[$k+1]))
                    $red[$k]=$red[$k+1];
            }
        }
        asort($redBall);
        $data=[];
        foreach($redBall as $v){
            $data['red'][] = $v;
        }
        $data['blue']=$blue[rand(0,15)];
        $data['type']=$num;
        return $data;
    }
    public function hello($name = 'ThinkPHP6')
    {
        return 'hello,' . $name;
    }

    public function ceshi(){
        return '<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:) </h1><p> ThinkPHP V' . \think\facade\App::version() . '<br/><span style="font-size:30px;">14载初心不改 - 你值得信赖的PHP框架</span></p><span style="font-size:25px;">[ V6.0 版本由 <a href="https://www.yisu.com/" target="yisu">亿速云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=64890268" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="ee9b1aa918103c4fc"></think>';
    }
}
