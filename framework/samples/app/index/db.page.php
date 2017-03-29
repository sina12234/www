<?php
/*
drop table if exists test;
CREATE TABLE `test` (
  `id` int not null primary key auto_increment,
  `name` varchar(30) default NULL,
  `password` varchar(30) default NULL,
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
*/
$db = SDb::getDbEngine("mysql");
$db = SDb::getDbEngine("pdo_mysql");
if(!$db){
	die("DbEngine not exits");
}
$db->init(array(
	"host"=>"localhost",
	"user"=>"root",
	"password"=>"",
	"database"=>"test")
);
//�����¼
print_r($db->insert($table = "test",$items=array("name"=>"testName","password"=>"testPassword")));
//����һ��
print_r($db->selectOne($table = "test",$condition=array(),$items=array("name")));
//����������һ��
print_r($db->selectOne($table = "test",$condition=array("id"=>1),$items=array("*")));
//����ȫ��
$db->setLimit(-1);
print_r($db->select($table = "test",$condition=array(),$items=array("*")));
//��ҳ����
$db->setPage(2);
$db->setLimit(5);
//�Ƿ�������
$db->setCount(true);
print_r($db->select($table = "test",$condition=array(),$items=array("*")));

?>
