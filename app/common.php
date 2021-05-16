<?php
// 应用公共文件
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
}