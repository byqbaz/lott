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

/**
 * Class Network
 * @package app\controller
 * 网络采集数据专用
 */
class Network extends BaseController
{
    protected $fc='fucai';
    protected $tc='ticai';

    public $title='互联网生成';

    public function initialize(){
        if(empty(session('admin'))||session('admin')==null||session('admin')==false){
            $url='/Login/index';
            header("Location: $url");
            exit;
        }
    }
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
        $title='采集接口测试';
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
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * 展示最新数据
     */
    public function newest(){
        $title=$this->title.'_展示最新数据';
        $where['is_deleted']=0;
        $where['type']=0;
        $year=date('y');
        $type=$this->request->get('type');
        if($type=='f') {
            $data = newest_data($type,false);
            $str='red1,red2,red3,red4,red5,red6';
            $red=Db::name($this->fc)->field($str)->where($where)->where('day_code','>',intval($year.'000'))->where('day_code','<',intval($year.'365'))->select();

            $blue=Db::name($this->fc)->field('blue')->where($where)->where('day_code','>',intval($year.'000'))->where('day_code','<',intval($year.'365'))->select();

            $count=count($red);
            $red=$this->newest_key_value($red,'red');
            $blue=$this->newest_key_value($blue,'blue');
//            halt($blue);
        }elseif($type=='t'){
            $data = newest_data($type,false);
            $str='red1,red2,red3,red4,red5';
            $red=Db::name($this->tc)->field($str)->where($where)->where('day_code','>',intval($year.'000'))->where('day_code','<',intval($year.'365'))->select();
            $blue=Db::name($this->tc)->field('blue1,blue2')->where($where)->where('day_code','>',intval($year.'000'))->where('day_code','<',intval($year.'365'))->select();
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
            $res=appear_most($res,false);
            return $res;
        }
    }

    /**
     * @return int|string
     * 采集号码表单
     */
    public function newest_form(){
        if($this->request->isPost()){
           $res=$this->request->post();
           return $this->newest_form_data($res);
        }
        $type=$this->request->get('type');
        View::assign('type',$type);
        return View::fetch();

    }

    /**
     * @param $res
     * @return int|string
     * 单个数据采集
     */
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
     * @return bool
     * 批量数据采集
     */
    public function batch_capture(){
        if($this->request->isPost()){
            $res=$this->request->post();
            //数据验证
            if(!is_numeric($res['start_code'])) exit;
            if(!is_numeric($res['end_code'])) exit;
            $res['start_code']<0||$res['start_code']>21365?$res['start_code']=date('y').'001':$res['start_code'];
            $res['end_code']<0?$res['end_code']=date('y').'001':$res['end_code'];
            if($res['end_code']>21365) exit;
            $arr=range($res['start_code'],$res['end_code']);

            if($res['type']=='f'){
                //开始采集
                foreach ($arr as $key=>$val){
                    $parameter='start='.$val.'&end='.$val;
                    $url='https://datachart.500.com/ssq/history/newinc/history.php?'.$parameter;
                    $data['red1'] = QueryList::get($url)->find('#tdata > tr:nth-child(1) > td:nth-child(2)')->text();
                    $data['red2'] = QueryList::get($url)->find('#tdata > tr:nth-child(1) > td:nth-child(3)')->text();
                    $data['red3'] = QueryList::get($url)->find('#tdata > tr:nth-child(1) > td:nth-child(4)')->text();
                    $data['red4'] = QueryList::get($url)->find('#tdata > tr:nth-child(1) > td:nth-child(5)')->text();
                    $data['red5'] = QueryList::get($url)->find('#tdata > tr:nth-child(1) > td:nth-child(6)')->text();
                    $data['red6'] = QueryList::get($url)->find('#tdata > tr:nth-child(1) > td:nth-child(7)')->text();
                    $data['blue'] = QueryList::get($url)->find('#tdata > tr:nth-child(1) > td:nth-child(8)')->text();
                    $data['day_code']= QueryList::get($url)->find('#tdata > tr > td:nth-child(1)')->text();
                    $data['create_time']= time();
                    $str=Db::name($this->fc)->insert($data);
                    if($str)
                        echo '第'.$val.'采集成功';
                    else
                        echo '第'.$val.'采集失败';
                    echo "<br>";
                }
                exit;
            }elseif ($res['type']=='t'){
                //开始采集
                foreach ($arr as $key=>$val){
                    $url='http://kaijiang.500.com/shtml/dlt/'.$val.'.shtml';
                    $data['red1'] = QueryList::get($url)->find('body > div.wrap > div.kj_main01 > div.kj_main01_right > div.kjxq_box02 > div:nth-child(2) > table:nth-child(1) > tr:nth-child(2) > td > table tr:nth-child(1) > td:nth-child(2) > div > ul > li:nth-child(1)')->text();
                    $data['red2'] = QueryList::get($url)->find('body > div.wrap > div.kj_main01 > div.kj_main01_right > div.kjxq_box02 > div:nth-child(2) > table:nth-child(1) > tr:nth-child(2) > td > table tr:nth-child(1) > td:nth-child(2) > div > ul > li:nth-child(2)')->text();
                    $data['red3'] = QueryList::get($url)->find('body > div.wrap > div.kj_main01 > div.kj_main01_right > div.kjxq_box02 > div:nth-child(2) > table:nth-child(1) > tr:nth-child(2) > td > table tr:nth-child(1) > td:nth-child(2) > div > ul > li:nth-child(3)')->text();
                    $data['red4'] = QueryList::get($url)->find('body > div.wrap > div.kj_main01 > div.kj_main01_right > div.kjxq_box02 > div:nth-child(2) > table:nth-child(1) > tr:nth-child(2) > td > table tr:nth-child(1) > td:nth-child(2) > div > ul > li:nth-child(4)')->text();
                    $data['red5'] = QueryList::get($url)->find('body > div.wrap > div.kj_main01 > div.kj_main01_right > div.kjxq_box02 > div:nth-child(2) > table:nth-child(1) > tr:nth-child(2) > td > table tr:nth-child(1) > td:nth-child(2) > div > ul > li:nth-child(5)')->text();
                    $data['blue1'] = QueryList::get($url)->find('body > div.wrap > div.kj_main01 > div.kj_main01_right > div.kjxq_box02 > div:nth-child(2) > table:nth-child(1) > tr:nth-child(2) > td > table tr:nth-child(1) > td:nth-child(2) > div > ul > li:nth-child(6)')->text();
                    $data['blue2'] = QueryList::get($url)->find('body > div.wrap > div.kj_main01 > div.kj_main01_right > div.kjxq_box02 > div:nth-child(2) > table:nth-child(1) > tr:nth-child(2) > td > table tr:nth-child(1) > td:nth-child(2) > div > ul > li:nth-child(7)')->text();
                    $day_code = QueryList::get($url)->find('body > div.wrap > div.kj_main01 > div.kj_main01_right > div.kjxq_box02 > div:nth-child(2) > table:nth-child(1) > tr:nth-child(1) > td > span > a')->text();
                    $data['day_code']= preg_replace('/\D/s', '', $day_code);
                    $data['create_time']= time();
                    $str=Db::name($this->tc)->insert($data);
                    if($str)
                        echo '第'.$val.'采集成功';
                    else
                        echo '第'.$val.'采集失败';
                    echo "<br>";
                }
                exit;
            }else{
                return false;
            }
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
        $data=more_past_data($data,false);

        return $data;
    }

}