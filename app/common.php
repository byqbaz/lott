<?php
// 应用公共文件
use think\facade\Db;
use app\BaseController;

global $fc,$tc;

$fc='fucai';
$tc='ticai';
/**
 * @param $str
 * @return bool
 * 防止SQL注入
 */
function is_verify_sql($str){
    if(empty($str)) return false;
    $pregs = '/select|insert|update|CR|document|LF|eval|delete|script|alert|\'|\/\*|\#|\--|\ --|\/|\*|\-|\+|\=|\~|\*@|\*!|\$|\%|\^|\&|\(|\)|\/|\/\/|\.\.\/|\.\/|union|into|load_file|outfile/';
    $check= preg_match($pregs,$str);
    if($check==1||$check==true) return true;
    else return false;
}

/**
 * @param $data
 * @param bool $type
 * @return string
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\DbException
 * @throws \think\db\exception\ModelNotFoundException
 * 查询往期数据
 */
function more_past_data($data,$type=true){
    global $fc,$tc;
    $where=where_query($type);
    if(!is_numeric($data['number'])||$data['number']<=0) $data['number']=100;
    $order=order_where($data['type'],$type);
    if($data['type']=='f') {
        $fc = Db::name($fc)->where($where)->order($order)->limit($data['number'])->select();
        return '{"code":0,"msg":"","count":1000,"data":' . json_encode($fc,true) . '}';
    }elseif($data['type']=='t'){
        $tc = Db::name($tc)->where($where)->order($order)->limit($data['number'])->select();
        return '{"code":0,"msg":"","count":1000,"data":' . json_encode($tc) . '}';
    }else{
        return '{"code":0,"msg":"","count":1000,"data":[{}]}';
    }
}

/**
 * @param $res
 * @param bool $type
 * @return false|string
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\DbException
 * @throws \think\db\exception\ModelNotFoundException
 * 出现次数最多的号码
 */
function appear_most($res,$type=true,$all=0){
    global $fc,$tc;
    $where=where_query($type);
    $order=order_where($res,$type);
        if($res=='f'){
            $fc = Db::name($fc)->where($where)->field('red1,red2,red3,red4,red5,red6,blue')->order($order)->limit(100)->select()->toArray();
            if(empty($fc)) return [];
            foreach($fc as $key=>$val){
                $red1[]=$val['red1'];
                $red2[]=$val['red2'];
                $red3[]=$val['red3'];
                $red4[]=$val['red4'];
                $red5[]=$val['red5'];
                $red6[]=$val['red6'];
                $blue[]=$val['blue'];

            }

            $arr['red1']=appear_most_data($red1);
            $arr['red2']=appear_most_data($red2);
            $arr['red3']=appear_most_data($red3);
            $arr['red4']=appear_most_data($red4);
            $arr['red5']=appear_most_data($red5);
            $arr['red6']=appear_most_data($red6);
            $arr['blue']=appear_most_data($blue);
            $arr['type']=$res;

            return json_encode($arr);
        }elseif($res=='t'){
            $tc = Db::name($tc)->where($where)->field('red1,red2,red3,red4,red5,blue1,blue2')->order($order)->limit(100)->select()->toArray();
            if(empty($fc)) return [];
            foreach($tc as $key=>$val){
                $red1[]=$val['red1'];
                $red2[]=$val['red2'];
                $red3[]=$val['red3'];
                $red4[]=$val['red4'];
                $red5[]=$val['red5'];
                $blue1[]=$val['blue1'];
                $blue2[]=$val['blue2'];

            }

            $arr['red1']=appear_most_data($red1);
            $arr['red2']=appear_most_data($red2);
            $arr['red3']=appear_most_data($red3);
            $arr['red4']=appear_most_data($red4);
            $arr['red5']=appear_most_data($red5);
            $arr['blue1']=appear_most_data($blue1);
            $arr['blue2']=appear_most_data($blue2);
            $arr['type']=$res;

            return json_encode($arr);
        }elseif($res=='all'){
            if($all=='f'){
                $fc = Db::name($fc)->where('is_deleted',0)->field('red1,red2,red3,red4,red5,red6,blue')->order('fc_id desc')->limit(200)->select()->toArray();
                if(empty($fc)) return [];
                foreach($fc as $key=>$val){
                    $red1[]=$val['red1'];
                    $red2[]=$val['red2'];
                    $red3[]=$val['red3'];
                    $red4[]=$val['red4'];
                    $red5[]=$val['red5'];
                    $red6[]=$val['red6'];
                    $blue[]=$val['blue'];

                }

                $arr['red1']=appear_most_data($red1);
                $arr['red2']=appear_most_data($red2);
                $arr['red3']=appear_most_data($red3);
                $arr['red4']=appear_most_data($red4);
                $arr['red5']=appear_most_data($red5);
                $arr['red6']=appear_most_data($red6);
                $arr['blue']=appear_most_data($blue);
                $arr['type']=$res;

                return json_encode($arr);
            }elseif($all=='t'){
                $tc = Db::name($tc)->where('is_deleted',0)->field('red1,red2,red3,red4,red5,blue1,blue2')->order('tc_id desc')->limit(200)->select()->toArray();

                if(empty($fc)) return [];
                foreach($tc as $key=>$val){
                    $red1[]=$val['red1'];
                    $red2[]=$val['red2'];
                    $red3[]=$val['red3'];
                    $red4[]=$val['red4'];
                    $red5[]=$val['red5'];
                    $blue1[]=$val['blue1'];
                    $blue2[]=$val['blue2'];

                }

                $arr['red1']=appear_most_data($red1);
                $arr['red2']=appear_most_data($red2);
                $arr['red3']=appear_most_data($red3);
                $arr['red4']=appear_most_data($red4);
                $arr['red5']=appear_most_data($red5);
                $arr['blue1']=appear_most_data($blue1);
                $arr['blue2']=appear_most_data($blue2);
                $arr['type']=$res;

                return json_encode($arr);
            }else{
                return [];
            }

        }else{
            return [];
        }

}

/**
 * @param $data
 * @param bool $type
 * @return int|string|null
 * 处理出现次数最多号码的数据
 */
function appear_most_data($data,$type=false){
    if(!is_array($data)) return 0;
    $data = array_count_values($data);
    arsort($data);
    if($type===true){
        $res['value '] = current($data);
        $res['key'] = key($data);
    }else{
        $res= key($data);
    }
    return $res;
}

/**
 * @param $res
 * @param bool $type
 * @return array|\think\Model|null
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\DbException
 * @throws \think\db\exception\ModelNotFoundException
 * 获取最新一期号码
 */
function newest_data($res,$type=true){
    global $fc,$tc;
    $where=where_query($type);
    $order=order_where($res,$type);
    if($res=='f'){
        $data = Db::name($fc)->where($where)->order($order)->find();
    }elseif($res=='t'){
        $data = Db::name($tc)->where($where)->order($order)->find();
    }else{
        $data=[];
    }
    return $data;

}

/**
 * @param $type
 * @param int $del
 * @return mixed
 * 生成判断条件
 */
function where_query($type,$del=0){
        $del==0?$where['is_deleted']=0:$where['is_deleted']=1;
        $type==true?$where['type']=1:$where['type']=0;
        return $where;
}

/**
 * @param $res
 * @param $type
 * @return string
 * 排序
 */
function order_where($res,$type){
        if($res=='f')$type==true? $order='fc_id desc': $order='day_code desc';
        elseif($res=='t')$type==true? $order='tc_id desc': $order='day_code desc';
        else $order='';
        return $order;
}