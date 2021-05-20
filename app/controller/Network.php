<?php
/**
 * Created by PhpStorm.
 * User: byq
 * Date: 2021/5/13
 * Time: 23:32
 */

namespace app\controller;

use app\BaseController;
use think\facade\View;
use think\facade\Db;
use QL\QueryList;
use think\cache\driver\Redis;
use think\response\Json;

class Network extends BaseController
{
    protected $fc='fucai';
    protected $tc='ticai';

    public $title='互联网生成';

    /**
     * @return string
     * 互联网生成首页
     */
     public function index(){
         View::assign('title',empty($title)?$this->title:$title);
         return View::fetch();
     }

    /**
     * @return string
     * 测试专用链接
     */
    public function test(){
        //采集某页面所有的图片
        $title='测试采集接口';
        $type=$this->request->get('type');
        if($type==1){
            $url='http://kaijiang.500.com/shtml/ssq/21053.shtml';
            $data = $this->GetCurl($url);
            $url_api='https://match.lottery.sina.com.cn/client/index/clientProxy/?format=json&__caller__=wap&__version__=1&__verno__=1&cat1=gameCurrentInfo&gameCode=101&issueNo=2021050&t=1621496272586';
//            $data = $this->GetCurl($url_api);
            $html = iconv('GBK','UTF-8',$data);
            $ql = QueryList::html($html)->rules('')->encoding('UTF-8','GB2312')->query()->getData();
            halt($ql);
//            $url='http://kaijiang.500.com/shtml/dlt/21056.shtml';
            $data['red1'] = QueryList::get($url_api)->find('body')->html();
//            $data['red1'] = QueryList::get($url)->find('#kj_opencode > li:nth-child(1)')->html();
//            $data['red2'] = QueryList::get($url)->find('#kj_opencode > li:nth-child(2)')->html();
//            $data['red3'] = QueryList::get($url)->find('#kj_opencode > li:nth-child(3)')->html();
//            $data['red4'] = QueryList::get($url)->find('#kj_opencode > li:nth-child(4)')->html();
//            $data['red5'] = QueryList::get($url)->find('#kj_opencode > li:nth-child(5)')->html();
//            $data['red6'] = QueryList::get($url)->find('#kj_opencode > li:nth-child(6)')->html();
//            $data['blue'] = QueryList::get($url)->find('#kj_opencode > li:nth-child(7)')->html();
//            $day_code = QueryList::get($url)->find('body > div.wrap > div.kj_main01 > div.kj_main01_right > div.kjxq_box02 > div:nth-child(2) > table:nth-child(1) > tr:nth-child(1) > td > span > a')->text();
//            $data['day_code']= preg_replace('/\D/s', '', $day_code);
//            $data['create_time']= time();
//            return Db::name($this->tc)->insert($data);
//            body > div.wrap > div.kj_main01 > div.kj_main01_right > div.kjxq_box02 > div:nth-child(2) > table:nth-child(1) > tbody > tr:nth-child(2) > td > table > tbody > tr:nth-child(1) > td:nth-child(2)
            halt($data);
        }elseif($type==2){
            $url='https://zx.500.com/ssq/';
            $url='https://datachart.500.com/ssq/history/newinc/history.php?start=21001&end=21054';
            $data['red1'] = QueryList::get($url)->find('#tdata > tr:nth-child(1) > td:nth-child(2)')->html();
            $data['red2'] = QueryList::get($url)->find('#tdata > tr:nth-child(1) > td:nth-child(3)')->html();
            $data['red3'] = QueryList::get($url)->find('#tdata > tr:nth-child(1) > td:nth-child(4)')->html();
            $data['red4'] = QueryList::get($url)->find('#tdata > tr:nth-child(1) > td:nth-child(5)')->html();
            $data['red5'] = QueryList::get($url)->find('#tdata > tr:nth-child(1) > td:nth-child(6)')->html();
            $data['red6'] = QueryList::get($url)->find('#tdata > tr:nth-child(1) > td:nth-child(7)')->html();
            $data['blue'] = QueryList::get($url)->find('#tdata > tr:nth-child(1) > td:nth-child(8)')->html();
            halt($data);

            echo "_";
//            echo $encode;#tdata > tr:nth-child(1) > td:nth-child(2)
            exit;
        }


        //body > li:nth-child(1)
        //.span_left> font > strong
        //大乐透$url='http://kaijiang.500.com/shtml/dlt/21053.shtml?0_ala_baidu';
        //双色球$url='http://kaijiang.500.com/shtml/ssq/21052.shtml?0_ala_baidu';
        //打印结果
        print_r($data);
        View::assign('title',empty($title)?$this->title:$title);
        return View::fetch();
    }

    /**
     * @return bool
     * 采集数据入口
     */
    public function collection(){
        if($this->request->isGet()){
            $type=$this->request->get('type');
            $is_verify_sql=is_verify_sql($type);
            if($is_verify_sql===true) return false;
            if($type=='f'){
                $url='http://kaijiang.500.com/shtml/';
                $result=$this->collection_result($url,$type);
                halt($result);
            }elseif($type=='t'){
                $url='http://kaijiang.500.com/shtml/';
//                $result=$this->collection_result($url,$type);
                $result=$this->collection_data($url,$type);
                halt($result);
            }
        }

    }

    /**
     * @param $url
     * @param $type
     * @return array|bool
     * 采集数据处理
     */
    protected function collection_result($url,$type){
         if(empty($url)) return false;
        if($type=='f'){
            $data=[];
        }elseif($type=='t'){
            $data['red1'] = QueryList::get($url)->find('body > div.wrap > div.kj_main01 > div.kj_main01_right > div.kjxq_box02 > div:nth-child(2) > table:nth-child(1) > tr:nth-child(2) > td > table tr:nth-child(1) > td:nth-child(2) > div > ul > li:nth-child(1)')->html();
            $data['red2'] = QueryList::get($url)->find('body > div.wrap > div.kj_main01 > div.kj_main01_right > div.kjxq_box02 > div:nth-child(2) > table:nth-child(1) > tr:nth-child(2) > td > table tr:nth-child(1) > td:nth-child(2) > div > ul > li:nth-child(2)')->html();
            $data['red3'] = QueryList::get($url)->find('body > div.wrap > div.kj_main01 > div.kj_main01_right > div.kjxq_box02 > div:nth-child(2) > table:nth-child(1) > tr:nth-child(2) > td > table tr:nth-child(1) > td:nth-child(2) > div > ul > li:nth-child(3)')->html();
            $data['red4'] = QueryList::get($url)->find('body > div.wrap > div.kj_main01 > div.kj_main01_right > div.kjxq_box02 > div:nth-child(2) > table:nth-child(1) > tr:nth-child(2) > td > table tr:nth-child(1) > td:nth-child(2) > div > ul > li:nth-child(4)')->html();
            $data['red5'] = QueryList::get($url)->find('body > div.wrap > div.kj_main01 > div.kj_main01_right > div.kjxq_box02 > div:nth-child(2) > table:nth-child(1) > tr:nth-child(2) > td > table tr:nth-child(1) > td:nth-child(2) > div > ul > li:nth-child(5)')->html();
            $data['blue1'] = QueryList::get($url)->find('body > div.wrap > div.kj_main01 > div.kj_main01_right > div.kjxq_box02 > div:nth-child(2) > table:nth-child(1) > tr:nth-child(2) > td > table tr:nth-child(1) > td:nth-child(2) > div > ul > li:nth-child(6)')->html();
            $data['blue2'] = QueryList::get($url)->find('body > div.wrap > div.kj_main01 > div.kj_main01_right > div.kjxq_box02 > div:nth-child(2) > table:nth-child(1) > tr:nth-child(2) > td > table tr:nth-child(1) > td:nth-child(2) > div > ul > li:nth-child(7)')->html();
            $day_code = QueryList::get($url)->find('body > div.wrap > div.kj_main01 > div.kj_main01_right > div.kjxq_box02 > div:nth-child(2) > table:nth-child(1) > tr:nth-child(1) > td > span > a')->text();
            $data['day_code']= preg_replace('/\D/s', '', $day_code);
        }else{
            $data=[];
        }
        return $data;
    }

    /**
     * @param $url
     * @param $type
     * @param int $year
     * @return array
     * 采集数据添加入库
     */
    protected function collection_data($url,$type,$year=21){
        $redis = new Redis();
        if($type=='f'){
            $res=[];
        }elseif($type=='t'){
            $num=$year.'0';
            for($i=1;$i<=53;$i++){
                if($i<10)$i='0'.$i;
                $url1=$url.'dlt/'.$num.$i.'.shtml';
                $data['red1'] = QueryList::get($url1)->find('body > div.wrap > div.kj_main01 > div.kj_main01_right > div.kjxq_box02 > div:nth-child(2) > table:nth-child(1) > tr:nth-child(2) > td > table tr:nth-child(1) > td:nth-child(2) > div > ul > li:nth-child(1)')->html();
                $data['red2'] = QueryList::get($url1)->find('body > div.wrap > div.kj_main01 > div.kj_main01_right > div.kjxq_box02 > div:nth-child(2) > table:nth-child(1) > tr:nth-child(2) > td > table tr:nth-child(1) > td:nth-child(2) > div > ul > li:nth-child(2)')->html();
                $data['red3'] = QueryList::get($url1)->find('body > div.wrap > div.kj_main01 > div.kj_main01_right > div.kjxq_box02 > div:nth-child(2) > table:nth-child(1) > tr:nth-child(2) > td > table tr:nth-child(1) > td:nth-child(2) > div > ul > li:nth-child(3)')->html();
                $data['red4'] = QueryList::get($url1)->find('body > div.wrap > div.kj_main01 > div.kj_main01_right > div.kjxq_box02 > div:nth-child(2) > table:nth-child(1) > tr:nth-child(2) > td > table tr:nth-child(1) > td:nth-child(2) > div > ul > li:nth-child(4)')->html();
                $data['red5'] = QueryList::get($url1)->find('body > div.wrap > div.kj_main01 > div.kj_main01_right > div.kjxq_box02 > div:nth-child(2) > table:nth-child(1) > tr:nth-child(2) > td > table tr:nth-child(1) > td:nth-child(2) > div > ul > li:nth-child(5)')->html();
                $data['blue1'] = QueryList::get($url1)->find('body > div.wrap > div.kj_main01 > div.kj_main01_right > div.kjxq_box02 > div:nth-child(2) > table:nth-child(1) > tr:nth-child(2) > td > table tr:nth-child(1) > td:nth-child(2) > div > ul > li:nth-child(6)')->html();
                $data['blue2'] = QueryList::get($url1)->find('body > div.wrap > div.kj_main01 > div.kj_main01_right > div.kjxq_box02 > div:nth-child(2) > table:nth-child(1) > tr:nth-child(2) > td > table tr:nth-child(1) > td:nth-child(2) > div > ul > li:nth-child(7)')->html();
                $day_code = QueryList::get($url1)->find('body > div.wrap > div.kj_main01 > div.kj_main01_right > div.kjxq_box02 > div:nth-child(2) > table:nth-child(1) > tr:nth-child(1) > td > span > a')->text();
                $data['day_code']= preg_replace('/\D/s', '', $day_code);
                $data['create_time']= time();
//                $redis->set($num.$i,json_encode($data));
                $res[]=$data;
            }
            $res[]=Db::name('ticai')->insertAll($res);
            $res=empty($res)?[]:$res;
        }else{
            $res=[];
        }
        return $res;
    }

    /**
     * @return string
     * 展示最新数据
     */
    public function newest(){
        $title=$this->title.'_展示最新数据';
        $where['is_deleted']=0;
        $where['type']=0;
        $year=date('y');
        if($this->request->get('type')=='f') {
            $data = Db::name($this->fc)->where($where)->order('day_code desc')->find();
            $str='red1,red2,red3,red4,red5,red6';
            $red=Db::name($this->fc)->field($str)->where('day_code','>',intval($year.'000'))->where('day_code','<',intval($year.'365'))->select();

            $blue=Db::name($this->fc)->field('blue')->where('day_code','>',intval($year.'000'))->where('day_code','<',intval($year.'365'))->select();

            $count=count($red);
            $red=$this->newest_key_value($red,'red');
            $blue=$this->newest_key_value($blue,'blue');
//            halt($blue);
        }elseif($this->request->get('type')=='t'){
            $data = Db::name($this->tc)->where($where)->order('day_code desc')->find();
            $str='red1,red2,red3,red4,red5';
            $red=Db::name($this->tc)->field($str)->where('day_code','>',intval($year.'000'))->where('day_code','<',intval($year.'365'))->select();
            $blue=Db::name($this->tc)->field('blue1,blue2')->where('day_code','>',intval($year.'000'))->where('day_code','<',intval($year.'365'))->select();
            $count=count($red);
            $red=$this->newest_key_value($red,'red');
            $blue=$this->newest_key_value($blue,'blue');
        }else{
            $data=[];
            $red=[];
            $blue=[];
            $count=0;
        }

//        if(empty($data)) exit;
//        if(empty($red)) exit;
//        halt(2);
        View::assign('data',$data);
//        View::assign('red',$red['red']);
//        View::assign('blue',$blue['blue']);
        View::assign('red_key',json_encode($red['red_key']));
        View::assign('red_val',json_encode($red['red_val']));
        View::assign('blue_key',json_encode($blue['blue_key']));
        View::assign('blue_val',json_encode($blue['blue_val']));
        View::assign('count',$count);
        View::assign('title',empty($title)?$this->title:$title);
        View::assign('type', $this->request->get('type'));
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
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * 最新数据查询
     */
    public function newest_data(){
        $where['is_deleted']=0;
        $where['type']=0;
        if($this->request->get('id')==='f') {
            $fc = Db::name($this->fc)->where($where)->order('fc_id desc')->limit(100)->select();
            return '{"code":0,"msg":"","count":1000,"data":' . json_encode($fc) . '}';
        }elseif($this->request->get('id')==='t'){
            $tc = Db::name($this->tc)->where($where)->order('tc_id desc')->limit(100)->select();
            return '{"code":0,"msg":"","count":1000,"data":' . json_encode($tc) . '}';
        }else{
            return '{"code":0,"msg":"","count":1000,"data":[{}]}';
        }

    }

    /**
     * @return false|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * 最新数据处理
     */
    public function newest_result(){
        if($this->request->isPost()){
            $where['is_deleted']=0;
            $where['type']=0;
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

                $arr['red1']=$this->newest_result_data($red1);
                $arr['red2']=$this->newest_result_data($red2);
                $arr['red3']=$this->newest_result_data($red3);
                $arr['red4']=$this->newest_result_data($red4);
                $arr['red5']=$this->newest_result_data($red5);
                $arr['red6']=$this->newest_result_data($red6);
                $arr['blue']=$this->newest_result_data($blue);
                $arr['type']=$this->request->post('res');
                return json_encode($arr);
            }elseif($this->request->post('res')=='t'){
                $tc = Db::name($this->tc)->where($where)->field('red1,red2,red3,red4,red5,blue1,blue2')->order('tc_id desc')->limit(100)->select();
                $arr=[];
                foreach($tc as $key=>$val){
                    $red1[]=$val['red1'];
                    $red2[]=$val['red2'];
                    $red3[]=$val['red3'];
                    $red4[]=$val['red4'];
                    $red5[]=$val['red5'];
                    $blue1[]=$val['blue1'];
                    $blue2[]=$val['blue2'];

                }

                $arr['red1']=$this->newest_result_data($red1);
                $arr['red2']=$this->newest_result_data($red2);
                $arr['red3']=$this->newest_result_data($red3);
                $arr['red4']=$this->newest_result_data($red4);
                $arr['red5']=$this->newest_result_data($red5);
                $arr['blue1']=$this->newest_result_data($blue1);
                $arr['blue2']=$this->newest_result_data($blue2);
                $arr['type']=$this->request->post('res');
                return json_encode($arr);
            }

        }else{
            echo 1;
        }
    }

    /**
     * @param $data
     * @param bool $type
     * @return bool|int|string|null
     * 提取数据
     */
    protected function newest_result_data($data,$type=false){
        if(!is_array($data)) return false;
        $data = array_count_values($data);
        arsort($data);
//        $data = key($data);
//        $data=each($data) ;
        if($type===true){
            $res['value '] = current($data);
            $res['key'] = key($data);
        }else{
            $res= key($data);
        }
        return $res;
    }

    public function newest_form(){
        if($this->request->isPost()){
           $res=$this->request->post();
           return $this->newest_form_data($res);
        }
        $type=$this->request->get('type');
        View::assign('type',$type);
        return View::fetch();

    }

    protected function newest_form_data(&$res){
        if(empty($res)) return '';
        if($res['type']=='f'){
            $start=$res['day_code']-1;
            $end=$res['day_code'];
            $parameter='start='.$start.'&end='.$end;
            $url='https://datachart.500.com/ssq/history/newinc/history.php?'.$parameter;
            $data['red1'] = QueryList::get($url)->find('#tdata > tr:nth-child(1) > td:nth-child(2)')->html();
            $data['red2'] = QueryList::get($url)->find('#tdata > tr:nth-child(1) > td:nth-child(3)')->html();
            $data['red3'] = QueryList::get($url)->find('#tdata > tr:nth-child(1) > td:nth-child(4)')->html();
            $data['red4'] = QueryList::get($url)->find('#tdata > tr:nth-child(1) > td:nth-child(5)')->html();
            $data['red5'] = QueryList::get($url)->find('#tdata > tr:nth-child(1) > td:nth-child(6)')->html();
            $data['red6'] = QueryList::get($url)->find('#tdata > tr:nth-child(1) > td:nth-child(7)')->html();
            $data['blue'] = QueryList::get($url)->find('#tdata > tr:nth-child(1) > td:nth-child(8)')->html();
            $data['day_code']= $res['day_code'];
            $data['create_time']= time();
            return Db::name($this->fc)->insert($data);
        }elseif($res['type']=='t'){
//            $res=$this->newest_form_data_collection($res);
            $dlt='dlt/';
            $url='http://kaijiang.500.com/shtml/'.$dlt.$res['day_code'].'.shtml';
            $data['red1'] = QueryList::get($url)->find('body > div.wrap > div.kj_main01 > div.kj_main01_right > div.kjxq_box02 > div:nth-child(2) > table:nth-child(1) > tr:nth-child(2) > td > table tr:nth-child(1) > td:nth-child(2) > div > ul > li:nth-child(1)')->html();
            $data['red2'] = QueryList::get($url)->find('body > div.wrap > div.kj_main01 > div.kj_main01_right > div.kjxq_box02 > div:nth-child(2) > table:nth-child(1) > tr:nth-child(2) > td > table tr:nth-child(1) > td:nth-child(2) > div > ul > li:nth-child(2)')->html();
            $data['red3'] = QueryList::get($url)->find('body > div.wrap > div.kj_main01 > div.kj_main01_right > div.kjxq_box02 > div:nth-child(2) > table:nth-child(1) > tr:nth-child(2) > td > table tr:nth-child(1) > td:nth-child(2) > div > ul > li:nth-child(3)')->html();
            $data['red4'] = QueryList::get($url)->find('body > div.wrap > div.kj_main01 > div.kj_main01_right > div.kjxq_box02 > div:nth-child(2) > table:nth-child(1) > tr:nth-child(2) > td > table tr:nth-child(1) > td:nth-child(2) > div > ul > li:nth-child(4)')->html();
            $data['red5'] = QueryList::get($url)->find('body > div.wrap > div.kj_main01 > div.kj_main01_right > div.kjxq_box02 > div:nth-child(2) > table:nth-child(1) > tr:nth-child(2) > td > table tr:nth-child(1) > td:nth-child(2) > div > ul > li:nth-child(5)')->html();
            $data['blue1'] = QueryList::get($url)->find('body > div.wrap > div.kj_main01 > div.kj_main01_right > div.kjxq_box02 > div:nth-child(2) > table:nth-child(1) > tr:nth-child(2) > td > table tr:nth-child(1) > td:nth-child(2) > div > ul > li:nth-child(6)')->html();
            $data['blue2'] = QueryList::get($url)->find('body > div.wrap > div.kj_main01 > div.kj_main01_right > div.kjxq_box02 > div:nth-child(2) > table:nth-child(1) > tr:nth-child(2) > td > table tr:nth-child(1) > td:nth-child(2) > div > ul > li:nth-child(7)')->html();
            $day_code = QueryList::get($url)->find('body > div.wrap > div.kj_main01 > div.kj_main01_right > div.kjxq_box02 > div:nth-child(2) > table:nth-child(1) > tr:nth-child(1) > td > span > a')->text();
            $data['day_code']= preg_replace('/\D/s', '', $day_code);
            $data['create_time']= time();
            return Db::name($this->tc)->insert($data);
        }else{
            return '';
        }
    }

    /**
     * @param $url
     * @return bool|string
     * 接口方式采集数据
     */
    protected function GetCurl($url) {
        $curl =curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.100 Safari/537.36");
        //Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.100 Safari/537.36
        //Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)
//        curl_setopt($curl, CURLOPT_REFERER, 'http://baidu.com/');
        curl_setopt($curl, CURLOPT_REFERER, $url);
        $resp =curl_exec($curl);
        curl_close($curl);
        return $resp;
    }

    /**
     * 往期更多数据
     */
    public function more_past(){
        $title=$this->title.'_往期更多数据';
        View::assign('title',empty($title)?$this->title:$title);
        return View::fetch();
    }

    public function count_all_numbers(){
//        $tc=Db::name($this->tc);
//        $data = $tc->where($where)->order('day_code desc')->find();
//        $res1=$tc->where('day_code','>',intval($year.'000'))->where('day_code','<',intval($year.'333'))->column('red1');
//        $res2=$tc->where('day_code','>',intval($year.'000'))->where('day_code','<',intval($year.'333'))->column('red2');
//        $res3=$tc->where('day_code','>',intval($year.'000'))->where('day_code','<',intval($year.'333'))->column('red3');
//        $res4=$tc->where('day_code','>',intval($year.'000'))->where('day_code','<',intval($year.'333'))->column('red4');
//        $res5=$tc->where('day_code','>',intval($year.'000'))->where('day_code','<',intval($year.'333'))->column('red5');
//        $res6=$tc->where('day_code','>',intval($year.'000'))->where('day_code','<',intval($year.'333'))->column('blue1');
//        $res7=$tc->where('day_code','>',intval($year.'000'))->where('day_code','<',intval($year.'333'))->column('blue2');
//        $res['red1']=$this->newest_result_data($res1,true);
//        $res['red2']=$this->newest_result_data($res2,true);
//        $res['red3']=$this->newest_result_data($res3,true);
//        $res['red4']=$this->newest_result_data($res4,true);
//        $res['red5']=$this->newest_result_data($res5,true);
//        $res['blue1']=$this->newest_result_data($res6,true);
//        $res['blue2']=$this->newest_result_data($res7,true);
    }

}