<?php

require "GstoreConnector.php";

$uid = $_POST['ID'];
$pwd = $_POST['pwd'];
$uid = $_GET['ID'];
$pwd = $_GET['pwd'];
$arr = array();

$username = "root";
$password = "123456";
$gc = new GstoreConnector("127.0.0.1", 9000);


$useridentity = "<user/" . $uid . ">";
$if_uid_exist = "select (COUNT(?uid) as ?uidcnt) where\n{\n?uid <exist> " . $useridentity . " .\n}";
//echo $if_uid_exist . PHP_EOL;
$query = $gc->query($username, $password, "weibo", $if_uid_exist);
$obj = json_decode($query);
if(count($obj->results->bindings) == 0){
	$arr = array("status" => "no such uid");
	$ret = json_encode($arr);
	echo $ret;
	return;
}

$if_match = "select ?pwd where\n{\n" . $useridentity . " <has_password> ?pwd .\n}";
$query = $gc->query($username, $password, "weibo", $if_match);
$obj = json_decode($query);
if($obj->results->bindings[0]->pwd->value != $pwd){
	$arr = array("status" => "wrong password");
	$ret = json_encode($arr);
	echo $ret;
	return;
}

$arr = array("status" => "success", "uid" => $uid);
$ret = json_encode($arr);
echo $ret;
?>