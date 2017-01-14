<?php
header("Content-type:text/html;charset=utf-8");
require_once("mysql.php");
function svm($href,$link)
{
    $sql = "select * from svm";
    $data= mysql_get($sql);
    $p_ave_href=$data[0]["empty_href"]/$data[0]["total"];//钓鱼网站平均空链接数
    $p_ave_link=$data[0]["exter_link"]/$data[0]["total"];//钓鱼网站平均外部链接数
    $t_ave_href=$data[1]["empty_href"]/$data[1]["total"];//可信网站
    $t_ave_link=$data[1]["exter_link"]/$data[1]["total"];//可信网站
    $weight[0]=abs($p_ave_href-$t_ave_href)/($p_ave_href+$t_ave_href);//空链接特征向量权重
    $weight[1]=abs($p_ave_link-$t_ave_link)/($p_ave_link+$t_ave_link);//外部链接
    $href=($href-$t_ave_href)/($p_ave_href-$t_ave_href);//待测网页 空链接特征值 越大越可能是钓鱼网站
    $link=($link-$t_ave_link)/($p_ave_link-$t_ave_link);//待测网页 外部链接
    return ($href*$weight[0]+$link*$weight[1])/($weight[0]+$weight[1]);//计算加上权重后的复合特征值
}
function svmLearnFunc($href,$link,$phishing)//将 空链接,外部链接 数存入数据库
{
    if($phishing==1)
    {
        $sql = "update svm set total=total+1,empty_href=empty_href+".$href.",exter_link=exter_link+".$link." where lable='phishing'";
        mysql_query($sql);
    }
    else
    {
        $sql = "update svm set total=total+1,empty_href=empty_href+".$href.",exter_link=exter_link+".$link." where lable='trusted'";
        mysql_query($sql);
    }
}
function svmLearn($url,$phishing)//获取指定url的外部链接,空链接数
{
    if(!preg_match("/^https?:/",$url))$url="http://".$url;
    $text= file_get_contents($url);//获取网页内容
    if(strlen($text)<700)return;
    preg_match_all('/href\s*=\s*"#?"/i',$text,$match);//通过正则匹配获得空链接
    $empty_href=count($match[0]);//空链接数
    preg_match_all('/https?:/i',$text,$match);
    $exter_link=count($match[0]);
    svmLearnFunc($empty_href,$exter_link,$phishing);
}
?>