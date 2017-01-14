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
    else echo "已经存在: ";
}
if(!empty($_GET["type"]))
{
    switch($_GET["type"])
    {
    case "addphishing":
        echo "新增钓鱼网站数据:<br/>";
        preg_match_all('/[\S]+/',$url,$match);
        for($i=0;$i<count($match[0]);++$i)
        {
            addUrl($match[0][$i],true);
            echo $match[0][$i]."<br/>";
        }
        break;
    case "addtrusted":
        echo "新增可信网站数据:<br/>";
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
//连接数据库
//$mysql=new SaeMysql();
//
$head=preg_replace("/http:\/\/|https:\/\//","",$url);
preg_match('/[^\/]+/',$head,$match);
$head=$match[0];
$sql = "select * from `websitelist` where trustedwebsite like '%".$head."%'";//这里不是单引号,是数字1前面的符号
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
if($res=="钓鱼")
{
    echo '5';
}
else if($res=="正常")
{
    echo '3';
}
else if($res=="待定")
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
