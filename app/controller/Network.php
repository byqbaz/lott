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
        $url='http://kaijiang.500.com/shtml/dlt/21053.shtml?0_ala_baidu';
        $data[] = QueryList::get($url)->find('body > div.wrap > div.kj_main01 > div.kj_main01_right > div.kjxq_box02 > div:nth-child(2) > table:nth-child(1) > tr:nth-child(2) > td > table tr:nth-child(1) > td:nth-child(2) > div > ul > li:nth-child(1)')->html();
        $data[] = QueryList::get($url)->find('body > div.wrap > div.kj_main01 > div.kj_main01_right > div.kjxq_box02 > div:nth-child(2) > table:nth-child(1) > tr:nth-child(2) > td > table tr:nth-child(1) > td:nth-child(2) > div > ul > li:nth-child(2)')->html();
        $data[] = QueryList::get($url)->find('body > div.wrap > div.kj_main01 > div.kj_main01_right > div.kjxq_box02 > div:nth-child(2) > table:nth-child(1) > tr:nth-child(2) > td > table tr:nth-child(1) > td:nth-child(2) > div > ul > li:nth-child(3)')->html();
        $data[] = QueryList::get($url)->find('body > div.wrap > div.kj_main01 > div.kj_main01_right > div.kjxq_box02 > div:nth-child(2) > table:nth-child(1) > tr:nth-child(2) > td > table tr:nth-child(1) > td:nth-child(2) > div > ul > li:nth-child(4)')->html();
        $data[] = QueryList::get($url)->find('body > div.wrap > div.kj_main01 > div.kj_main01_right > div.kjxq_box02 > div:nth-child(2) > table:nth-child(1) > tr:nth-child(2) > td > table tr:nth-child(1) > td:nth-child(2) > div > ul > li:nth-child(5)')->html();
        $data[] = QueryList::get($url)->find('body > div.wrap > div.kj_main01 > div.kj_main01_right > div.kjxq_box02 > div:nth-child(2) > table:nth-child(1) > tr:nth-child(2) > td > table tr:nth-child(1) > td:nth-child(2) > div > ul > li:nth-child(6)')->html();
        $data[] = QueryList::get($url)->find('body > div.wrap > div.kj_main01 > div.kj_main01_right > div.kjxq_box02 > div:nth-child(2) > table:nth-child(1) > tr:nth-child(2) > td > table tr:nth-child(1) > td:nth-child(2) > div > ul > li:nth-child(7)')->html();

        //body > div.wrap > div.kj_main01 > div.kj_main01_right > div.kjxq_box02 > div:nth-child(2) > table:nth-child(1) > tbody > tr:nth-child(2) > td > table > tbody > tr:nth-child(1) > td:nth-child(2) > div > ul > li:nth-child(1)
        //打印结果
        print_r($data);
        View::assign('title',empty($title)?$this->title:$title);
        return View::fetch();
    }

    public function collection(){
        if($this->request->isPost('type')=='f'){
            $url='http://kaijiang.500.com/shtml/ssq/21052.shtml?0_ala_baidu';
        }elseif($this->request->isPost('type')=='t'){
            $url='http://kaijiang.500.com/shtml/dlt/21053.shtml?0_ala_baidu';
        }
    }
}