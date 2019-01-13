<?php

require "GstoreConnector.php";

$suid = $_POST['suid'];
$tuid = $_POST['tuid'];
// $swid = $_GET['swid'];
// $twid = $_GET['twid'];

$username = "root";
$password = "123456";
$gc = new GstoreConnector("127.0.0.1", 9000);

$sweiboidentity = "<weibo/" . $swid . ">";
$tweiboidentity = "<weibo/" . $twid . ">";

$if_swid_exist = "select (COUNT(?wid) as ?widcnt) where\n{\n?wid <exist> " . $sweiboidentity . " .\n}";
$query = $gc->query($username, $password, "weibo", $if_swid_exist);
$obj = json_decode($query);
if(count($obj->results->bindings) == 0){
	$arr = array("status" => "no such swid");
	$ret = json_encode($arr);
	echo $ret;
	return;
}


$if_twid_exist = "select (COUNT(?wid) as ?widcnt) where\n{\n?wid <exist> " . $tweiboidentity . " .\n}";
$query = $gc->query($username, $password, "weibo", $if_twid_exist);
$obj = json_decode($query);
if(count($obj->results->bindings) == 0){
	$arr = array("status" => "no such twid");
	$ret = json_encode($arr);
	echo $ret;
	return;
}


$if_rela_exist = "select ?y where\n{\n" . $sweiboidentity . " ?y " . $tweiboidentity . " .\n}";
$query = $gc->query($username, $password, "weibo", $if_rela_exist);
$obj = json_decode($query);
if(count($obj->results->bindings) != 0){
	$arr = array("status" => "repost relation already exist");
	$ret = json_encode($arr);
	echo $ret;
	return;
}


$insertData = "insert data\n{\n" . $sweiboidentity . " <repost> " . $tweiboidentity . " .\n}";
// echo $insertData . PHP_EOL;
$query = $gc->query($username, $password, "weibo", $insertData);
// echo $query . PHP_EOL;


$getdata = "select ?n where\n{\n" . $tweiboidentity . " <repostsnum> ?n .\n}";
$query = $gc->query($username, $password, "weibo", $getdata);
// echo $query . PHP_EOL;
$obj = json_decode($query);
$repostsnum = intval($obj->results->bindings[0]->n->value) + 1;

$deletedata = "delete where\n{\n" . $tweiboidentity . " <repostsnum> \"" . $obj->results->bindings[0]->n->value . "\" .\n}";
$query = $gc->query($username, $password, "weibo", $deletedata);

$insertdata = "insert data\n{\n" . $tweiboidentity . " <repostsnum> \"" . $repostsnum . "\" .\n}";
$query = $gc->query($username, $password, "weibo", $insertdata);



$arr = array("status" => "success");
$ret = json_encode($arr);
echo $ret;

?>







