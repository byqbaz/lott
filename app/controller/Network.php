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

class Network extends BaseController
{
    public $title='互联网生成';
     public function index(){
         View::assign('title',empty($title)?$this->title:$title);
         return View::fetch();
     }

    public function test(){
        //采集某页面所有的图片
//        $url='http://kaijiang.500.com/shtml/ssq/21052.shtml?0_ala_baidu';
        $url='http://kaijiang.500.com/shtml/dlt/21053.shtml?0_ala_baidu';
        $data[] = QueryList::get($url)->find('body > div.wrap > div.kj_main01 > div.kj_main01_right > div.kjxq_box02 > div:nth-child(2) > table:nth-child(1) > tr:nth-child(1) > td > span > a')->text();
        $data= preg_replace('/\D/s', '', $data[0]);
//        print_r($data[0]);
//        $data[] = QueryList::get($url)->find('body > div.wrap > div.kj_main01 > div.kj_main01_right > div.kjxq_box02 > div:nth-child(2) > table:nth-child(1) > tr:nth-child(2) > td > table tr:nth-child(1) > td:nth-child(2) > div > ul > li:nth-child(2)')->html();
//        $data[] = QueryList::get($url)->find('body > div.wrap > div.kj_main01 > div.kj_main01_right > div.kjxq_box02 > div:nth-child(2) > table:nth-child(1) > tr:nth-child(2) > td > table tr:nth-child(1) > td:nth-child(2) > div > ul > li:nth-child(3)')->html();
//        $data[] = QueryList::get($url)->find('body > div.wrap > div.kj_main01 > div.kj_main01_right > div.kjxq_box02 > div:nth-child(2) > table:nth-child(1) > tr:nth-child(2) > td > table tr:nth-child(1) > td:nth-child(2) > div > ul > li:nth-child(4)')->html();
//        $data[] = QueryList::get($url)->find('body > div.wrap > div.kj_main01 > div.kj_main01_right > div.kjxq_box02 > div:nth-child(2) > table:nth-child(1) > tr:nth-child(2) > td > table tr:nth-child(1) > td:nth-child(2) > div > ul > li:nth-child(5)')->html();
//        $data[] = QueryList::get($url)->find('body > div.wrap > div.kj_main01 > div.kj_main01_right > div.kjxq_box02 > div:nth-child(2) > table:nth-child(1) > tr:nth-child(2) > td > table tr:nth-child(1) > td:nth-child(2) > div > ul > li:nth-child(6)')->html();
//        $data[] = QueryList::get($url)->find('body > div.wrap > div.kj_main01 > div.kj_main01_right > div.kjxq_box02 > div:nth-child(2) > table:nth-child(1) > tr:nth-child(2) > td > table tr:nth-child(1) > td:nth-child(2) > div > ul > li:nth-child(7)')->html();

        //body > div.wrap > div.kj_main01 > div.kj_main01_right > div.kjxq_box02 > div:nth-child(2) > table:nth-child(1) > tbody > tr:nth-child(1) > td
        //.span_left> font > strong
        //打印结果
        print_r($data);
        View::assign('title',empty($title)?$this->title:$title);
        return View::fetch();
    }

    public function collection(){
        if($this->request->isGet()){
            $type=$this->request->get('type');
            $is_verify_sql=is_verify_sql($type);
            if($is_verify_sql===true) return false;
            if($type=='f'){
                $url='http://kaijiang.500.com/shtml/ssq/21052.shtml?0_ala_baidu';
                $result=$this->collection_result($url,$type);
                halt($result);
            }elseif($type=='t'){
                $url='http://kaijiang.500.com/shtml/dlt/21053.shtml?0_ala_baidu';
                $result=$this->collection_result($url,$type);
                halt($result);
            }
        }

    }

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


}