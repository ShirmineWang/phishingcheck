<?php
header("Content-type:text/html;charset=utf-8");
require_once("mysql.php");
function delhead($url)//ȥ��url��http://����https://ͷ
{
    return preg_replace("/http:\/\/|https:\/\//","",$url);
}
function ipContained($url,$phishing)//�����Ƿ����ip ���phishing ֵΪ1�򰴵�����վ�������ݿ�  ֵΪ 0��>1 ��������վ�������ݿ�  ֵ<0,ֻ�����Ƿ����ip,���������ݿ�
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
function abnormalSymbol($url,$phishing)//����ipContained
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
function domainNum($url,$phishing)//����ipContained
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
function portContained($url,$phishing)//����ipContained
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
function longLength($url,$phishing)//����ipContained
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
function callFunc($url,$phishing)//ѧϰ������������
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
function bayesLearn($url,$phishing,$relean=false)//ѧϰ����	����url�����ݿ����Ƿ��Ѿ�����
{
    if($phishing==1)
    {
        $sql = "select * from `phishingwebsite` where phishingwebsite='".$url."'";
        if($relean)callFunc($url,1);
        else if(mysql_empty($sql))//��� url�����ݿ����Ѿ�����,���ټ���
        {
            $sql = "insert phishingwebsite (phishingwebsite) values('".$url."')";//���������վ���ݿ�
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
        else if(mysql_empty($sql))//��� url�����ݿ����Ѿ�����,���ټ���
        {
            $sql = "insert  websitelist (trustedwebsite) values('".$url."')";//���������վ���ݿ�
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
    $C[0]=1;//������վ�ǵ�����վ�ĸ���
    $C[1]=1;//������վ�ǿ�����վ�ĸ���
    for($i=0;$i<5;++$i)
    {
        $P[0][$i]=$data[0][$i]/$data[0][5];//���е�i����������ҳ�ǵ�����վ�ĸ���
        $P[1][$i]=$data[1][$i]/$data[1][5];//���е�i����������ҳ�ǿ�����վ�ĸ���
    }
    if(ipContained($url,-1))//���������0������
    {
        $C[0]*=$P[0][0];		//������վ�ǵ�����վ�ĸ���
        $C[1]*=$P[1][0];		//������վ�ǿ�����վ�ĸ���
    }
    else					//�����������0������
    {
        $C[0]*=1-$P[0][0];		//������վ�ǵ�����վ�ĸ���
        $C[1]*=1-$P[1][0];		//������վ�ǿ�����վ�ĸ���
    }
    if(abnormalSymbol($url,-1))//��������
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
    if($C[0]/$C[1]>1.5)return "����";
    else if($C[1]/$C[0]>2)return "����";
    else return "����";
}
?>