<?php
/*
�û����� : SAE_MYSQL_USER
�ܡ����� : SAE_MYSQL_PASS
�������� : SAE_MYSQL_HOST_M
�ӿ����� : SAE_MYSQL_HOST_S
�ˡ����� : SAE_MYSQL_PORT
���ݿ��� : SAE_MYSQL_DB
*/
$link=mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);//�������ݿ�
if($link)
{
    mysql_select_db(SAE_MYSQL_DB,$link);//ѡ��database
}
else  //�������ʧ��
{
	die('Could not connect: ' . mysql_error());
}
function mysql_run($sql)//����sql��� ��Ч��mysql_query
{
	return mysql_query($sql);
}
function mysql_get($sql,$type=MYSQL_BOTH)//��ȡ���� �����ڶ�ά������
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
function mysql_clo()//�Ͽ����ݿ�
{
	global $link;
	mysql_close($link);
}
function mysql_empty($sql)//�жϲ�ѯ�����Ƿ�Ϊ��
{
	$data=mysql_query($sql);
	if(mysql_num_rows($data)<1)return true;
	return false;
}
?>