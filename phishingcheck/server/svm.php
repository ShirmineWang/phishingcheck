<?php
header("Content-type:text/html;charset=utf-8");
require_once("mysql.php");
function svm($href,$link)
{
    $sql = "select * from svm";
    $data= mysql_get($sql);
    $p_ave_href=$data[0]["empty_href"]/$data[0]["total"];//������վƽ����������
    $p_ave_link=$data[0]["exter_link"]/$data[0]["total"];//������վƽ���ⲿ������
    $t_ave_href=$data[1]["empty_href"]/$data[1]["total"];//������վ
    $t_ave_link=$data[1]["exter_link"]/$data[1]["total"];//������վ
    $weight[0]=abs($p_ave_href-$t_ave_href)/($p_ave_href+$t_ave_href);//��������������Ȩ��
    $weight[1]=abs($p_ave_link-$t_ave_link)/($p_ave_link+$t_ave_link);//�ⲿ����
    $href=($href-$t_ave_href)/($p_ave_href-$t_ave_href);//������ҳ ����������ֵ Խ��Խ�����ǵ�����վ
    $link=($link-$t_ave_link)/($p_ave_link-$t_ave_link);//������ҳ �ⲿ����
    return ($href*$weight[0]+$link*$weight[1])/($weight[0]+$weight[1]);//�������Ȩ�غ�ĸ�������ֵ
}
function svmLearnFunc($href,$link,$phishing)//�� ������,�ⲿ���� ���������ݿ�
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
function svmLearn($url,$phishing)//��ȡָ��url���ⲿ����,��������
{
    if(!preg_match("/^https?:/",$url))$url="http://".$url;
    $text= file_get_contents($url);//��ȡ��ҳ����
    if(strlen($text)<700)return;
    preg_match_all('/href\s*=\s*"#?"/i',$text,$match);//ͨ������ƥ���ÿ�����
    $empty_href=count($match[0]);//��������
    preg_match_all('/https?:/i',$text,$match);
    $exter_link=count($match[0]);
    svmLearnFunc($empty_href,$exter_link,$phishing);
}
?>