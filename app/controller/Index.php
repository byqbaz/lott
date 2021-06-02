<?php
namespace app\controller;

use app\BaseController;
use think\facade\View;
use think\facade\Db;
use think\response\Json;

class Index extends BaseController
{
    public function initialize(){
        if(empty(session('admin'))||session('admin')==null||session('admin')==false){
            $url='/Login/index';
            header("Location: $url");
            exit;
        }
    }

    /**
     * @return string
     * 首页
     */
    public function index()
    {
        $f='f';
        $t='t';
        View::assign('fc',newest_data($f,false));
        View::assign('fc1',newest_data($f,true));
        View::assign('tc',newest_data($t,false));
        View::assign('tc1',newest_data($t,true));
        return View::fetch();
    }


    /**
     * @param $data
     * @param $num
     * @return int|string
     * 处理数据存库
     */
    public function _index_data(){
        $f='f';
        $t='t';
        if($this->request->isPost()){
            $res=$this->request->post('res');
            if($res=='dlt'){
                $dlt=appear_most($t,false);
                return $dlt;
            }
            if($res=='ssq'){
                $ssq=appear_most($f,false);
                return $ssq;
            }
            if($res=='dlt1'){
                $dlt=appear_most($t,true);
                return $dlt;
            }
            if($res=='ssq1'){
                $ssq=appear_most($f,true);
                return $ssq;
            }

            if($res=='dlt2'){
                $dlt=appear_most('all',true,'t');
                return $dlt;
            }

            if($res=='ssq2'){
                $ssq=appear_most('all',true,'f');
                return $ssq;
            }

        }

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


}
