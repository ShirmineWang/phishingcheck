<?php
/*
用户名　 : SAE_MYSQL_USER
密　　码 : SAE_MYSQL_PASS
主库域名 : SAE_MYSQL_HOST_M
从库域名 : SAE_MYSQL_HOST_S
端　　口 : SAE_MYSQL_PORT
数据库名 : SAE_MYSQL_DB
*/
$link=mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);//连接数据库
if($link)
{
    mysql_select_db(SAE_MYSQL_DB,$link);//选择database
}
else  //如果连接失败
{
	die('Could not connect: ' . mysql_error());
}
function mysql_run($sql)//运行sql语句 等效于mysql_query
{
	return mysql_query($sql);
}
function mysql_get($sql,$type=MYSQL_BOTH)//获取数据 并存在二维数组里
{
	$data=mysql_query($sql);
	$row=mysql_fetch_array($data,$type);
    if(!$row)return null;
	$i=0;
	do{
		$rows[$i++]=$row;
	}while($row=mysql_fetch_array($data));
	return $rows;
}
function mysql_clo()//断开数据库
{
	global $link;
	mysql_close($link);
}
function mysql_empty($sql)//判断查询数据是否为空
{
	$data=mysql_query($sql);
	if(mysql_num_rows($data)<1)return true;
	return false;
}
?>