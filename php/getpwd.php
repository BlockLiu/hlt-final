<?php

require "GstoreConnector.php";

$uid = $_POST['uid'];
// $uid = $_GET['uid'];
// echo $uid;

$username = "root";
$password = "123456";
$gc = new GstoreConnector("127.0.0.1", 9000);


$useridentity = "<user/" . $uid . ">";
$if_uid_exist = "select (COUNT(?uid) as ?uidcnt) where\n{\n?uid <exist> " . $useridentity . " .\n}";
// echo $if_uid_exist . PHP_EOL;
$query = $gc->query($username, $password, "weibo", $if_uid_exist);
// echo $query . PHP_EOL;
$obj = json_decode($query);
if(count($obj->results->bindings) == 0){
	$arr = array("status" => "no such uid");
	$ret = json_encode($arr);
	echo $ret;
	return;
}


$query_pwd = "select ?pwd where\n{\n" . $useridentity . " <has_password> ?pwd .\n}";
$query = $gc->query($username, $password, "weibo", $query_pwd);
$obj = json_decode($query);

$arr = array("status"=>"success", "pwd"=>$obj->results->bindings[0]->pwd->value);
$ret = json_encode($arr);
echo $ret;
?>