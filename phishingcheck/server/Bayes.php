<?php
header("Content-type:text/html;charset=utf-8");
require_once("mysql.php");
function delhead($url)//去掉url的http://或者https://头
{
    return preg_replace("/http:\/\/|https:\/\//","",$url);
}
function ipContained($url,$phishing)//返回是否包含ip 如果phishing 值为1则按钓鱼网站加入数据库  值为 0或>1 按可信网站加入数据库  值<0,只返回是否包含ip,不处理数据库
{
    $url = delhead($url);
    $regex = '/([1-9]|[1-9]\\d|1\\d{2}|2[0-4]\\d|25[0-5])(\\.(\\d|[1-9]\\d|1\\d{2}|2[0-4]\\d|25[0-5])){3}/';     
    if(preg_match($regex, $url))
    {
        if($phishing>=0)
        {
            if($phishing==1)$sql = "update Bayes set ip_contained=ip_contained+1 where lable!='trusted'";
            else 			$sql = "update Bayes set ip_contained=ip_contained+1 where lable!='phishing'";
            mysql_query($sql);
        }
        return true;
    }
    return false;
}
//ip_contained("http://12.168.1.1/id_ABCDEFG.html",1);
function abnormalSymbol($url,$phishing)//类似ipContained
{
    $url = delhead($url);
    $regex = '/[^\/]+/';
    preg_match($regex,$url,$match);
    $url=$match[0];
    $regex = '/@|-/';     
    if(preg_match($regex, $url))
    {
        if($phishing>=0)
        {
            if($phishing==1)$sql = "update Bayes set abnormal_symbol=abnormal_symbol+1 where lable!='trusted'";
            else 			$sql = "update Bayes set abnormal_symbol=abnormal_symbol+1 where lable!='phishing'";
            mysql_query($sql);
        }
        return true;
    }
    return false;
}
//abnormalSymbol("http://12.168.1.1/id_ABCDEFG.html@www.baidu.com",1);
function domainNum($url,$phishing)//类似ipContained
{
    $url = delhead($url);
    $regex = '/[^\/]+/';
    preg_match($regex,$url,$match);
    $url=$match[0];
    $regex = '/\w+\.\w+\.\w+\.\w+/';
    if(preg_match($regex,$url,$match))
    {
        if($phishing>=0)
        {
            if($phishing==1)$sql = "update Bayes set domain_num=domain_num+1 where lable!='trusted'";
            else 			$sql = "update Bayes set domain_num=domain_num+1 where lable!='phishing'";
            mysql_query($sql);
        }
        return true;
    }
    return false;
}
//domainNum("http://www.ppp.baidu.com.com/sd/sdf/sdf",1);
function portContained($url,$phishing)//类似ipContained
{
    $url = delhead($url);
    $regex = '/[^\/]+/';
    preg_match($regex,$url,$match);
    $url=$match[0];
    $regex = '/:\d+/';
    preg_match($regex,$url,$match);
    if(count($match[0]))
    {
        if($phishing>=0)
        {
            if($phishing==1)$sql = "update Bayes set port_contained=port_contained+1 where lable!='trusted'";
            else 			$sql = "update Bayes set port_contained=port_contained+1 where lable!='phishing'";
            mysql_query($sql);
        }
        return true;
    }
    return false;
}
//portContained("http://12.168.1.1:0090/sd/sdf/sdf",1);
function longLength($url,$phishing)//类似ipContained
{
    $url = delhead($url);
    $regex = '/[^\/]+/';
    preg_match($regex,$url,$match);
    $url=$match[0];
    if(strlen($url)>23)
    {
        if($phishing>=0)
        {
            if($phishing==1)$sql = "update Bayes set long_length=long_length+1 where lable!='trusted'";
            else 			$sql = "update Bayes set long_length=long_length+1 where lable!='phishing'";
            mysql_query($sql);
        }
        return true;
    }
    return false;
}
//longLength("http://12.168.1.1:0090/sd/sdffff",1);
function callFunc($url,$phishing)//学习函数辅助函数
{
    if($phishing==1)$sql = "update Bayes set total=total+1 where lable!='trusted'";
    else 			$sql = "update Bayes set total=total+1 where lable!='phishing'";
    mysql_query($sql);
    ipContained($url,$phishing);
    abnormalSymbol($url,$phishing);
    domainNum($url,$phishing);
    portContained($url,$phishing);
    longLength($url,$phishing);
}
//if(!empty($_POST["url"])&&!empty($_POST["phishing"]))
function bayesLearn($url,$phishing,$relean=false)//学习函数	返回url在数据库中是否已经存在
{
    if($phishing==1)
    {
        $sql = "select * from `phishingwebsite` where phishingwebsite='".$url."'";
        if($relean)callFunc($url,1);
        else if(mysql_empty($sql))//如果 url在数据库中已经存在,不再计算
        {
            $sql = "insert phishingwebsite (phishingwebsite) values('".$url."')";//存入钓鱼网站数据库
            mysql_query($sql);
            callFunc($url,1);
            return true;
        }
        return false;
    }
    else
    {
        $sql = "select * from `websitelist` where trustedwebsite='".$url."'";
        if($relean)callFunc($url,1);
        else if(mysql_empty($sql))//如果 url在数据库中已经存在,不再计算
        {
            $sql = "insert  websitelist (trustedwebsite) values('".$url."')";//存入可信网站数据库
            mysql_query($sql);
            callFunc($url,2);
            return true;
        }
        return false;
    }
}
function Bayes($url)
{
    $sql = "select ip_contained,abnormal_symbol,domain_num,port_contained,long_length,total from Bayes";
    $data= mysql_get($sql,MYSQL_NUM);
    $C[0]=1;//待测网站是钓鱼网站的概率
    $C[1]=1;//待测网站是可信网站的概率
    for($i=0;$i<5;++$i)
    {
        $P[0][$i]=$data[0][$i]/$data[0][5];//含有第i个特征的网页是钓鱼网站的概率
        $P[1][$i]=$data[1][$i]/$data[1][5];//含有第i个特征的网页是可信网站的概率
    }
    if(ipContained($url,-1))//如果包含第0个特征
    {
        $C[0]*=$P[0][0];		//待测网站是钓鱼网站的概率
        $C[1]*=$P[1][0];		//待测网站是可信网站的概率
    }
    else					//如果不包含第0个特征
    {
        $C[0]*=1-$P[0][0];		//待测网站是钓鱼网站的概率
        $C[1]*=1-$P[1][0];		//待测网站是可信网站的概率
    }
    if(abnormalSymbol($url,-1))//以下类似
    {
        $C[0]*=$P[0][1];
        $C[1]*=$P[1][1];
    }
    else
    {
        $C[0]*=1-$P[0][1];
        $C[1]*=1-$P[1][1];
    }
    if(domainNum($url,-1))
    {
        $C[0]*=$P[0][2];
        $C[1]*=$P[1][2];
    }
    else
    {
        $C[0]*=1-$P[0][2];
        $C[1]*=1-$P[1][2];
    }
    if(portContained($url,-1))
    {
        $C[0]*=$P[0][3];
        $C[1]*=$P[1][3];
    }
    else
    {
        $C[0]*=1-$P[0][3];
        $C[1]*=1-$P[1][3];
    }
    if(longLength($url,-1))
    {
        $C[0]*=$P[0][4];
        $C[1]*=$P[1][4];
    }
    else
    {
        $C[0]*=1-$P[0][4];
        $C[1]*=1-$P[1][4];
    }
    if($C[0]/$C[1]>1.5)return "钓鱼";
    else if($C[1]/$C[0]>2)return "正常";
    else return "待定";
}
?>