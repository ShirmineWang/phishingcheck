<?php
header("Content-type:text/html;charset=utf-8");
require_once("mysql.php");
require_once("Bayes.php");
require_once("svm.php");
$url=$_POST["url"];
function addUrl($url,$phishing)
{
    if(bayesLearn($url,$phishing))
        svmLearn($url,$phishing);
    else echo "�Ѿ�����: ";
}
if(!empty($_GET["type"]))
{
    switch($_GET["type"])
    {
    case "addphishing":
        echo "����������վ����:<br/>";
        preg_match_all('/[\S]+/',$url,$match);
        for($i=0;$i<count($match[0]);++$i)
        {
            addUrl($match[0][$i],true);
            echo $match[0][$i]."<br/>";
        }
        break;
    case "addtrusted":
        echo "����������վ����:<br/>";
        preg_match_all('/[\S]+/',$url,$match);
        for($i=0;$i<count($match[0]);++$i)
        {
            addUrl($match[0][$i],false);
            echo $match[0][$i]."<br/>";
        }
        break;
    }
    exit;
}
//�������ݿ�
//$mysql=new SaeMysql();
//
$head=preg_replace("/http:\/\/|https:\/\//","",$url);
preg_match('/[^\/]+/',$head,$match);
$head=$match[0];
$sql = "select * from `websitelist` where trustedwebsite like '%".$head."%'";//���ﲻ�ǵ�����,������1ǰ��ķ���
if(!mysql_empty($sql))
{
    echo '3';
    exit;
}
$sql = "select * from `phishingwebsite` where phishingwebsite like '%".$head."%'";
if(!mysql_empty($sql))
{
    echo '4';
    exit;
}
$res = Bayes($url);
if($res=="����")
{
    echo '5';
}
else if($res=="����")
{
    echo '3';
}
else if($res=="����")
{
    if(!empty($_POST["exter_link"])&&!empty($_POST["empty_href"]))
    {
        $res = svm($_POST["empty_href"],$_POST["exter_link"]);
        //        if($res>0.75)echo '6';
        if($res>0.75)echo '3';
        //else if($res<0.25)echo '3';
        else if($res<0.25)echo '6';
        else echo '2';
    }
}
?>
