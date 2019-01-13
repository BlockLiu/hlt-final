<?php

require "GstoreConnector.php";

// $uid = $_POST['uid'];
// $attr = $_POST['attribute'];
// $attrVal = $_POST['newVal'];
$uid = $_GET['uid'];
$attr = $_GET['attribute'];
$attrVal = $_GET['newVal'];

echo $attribute . PHP_EOL;

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


if($attr != "name" && $attr != "screen_name" 
	&& $attr != "location" && $attr != "url" 
	&& $attr != "gender" && $attr != "password" && $attr != "headimage_id"){
	$arr = array("status" => "no such attribute");
	$ret = json_encode($arr);
	echo $ret;
	return;
}


$deletesql = "delete where\n{\n" . $useridentity . " <has_" . $attr . "> ?x .\n}";
$query = $gc->query($username, $password, "weibo", $deletesql);

$insertsql = "insert data\n{\n" . $useridentity . " <has_" . $attr . "> \"" . $attrVal . "\" .\n}";
$query = $gc->query($username, $password, "weibo", $insertsql);


$arr = array("status" => "success");
$ret = json_encode($arr);
echo $ret;

?>