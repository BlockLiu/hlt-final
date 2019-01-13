<?php

require "GstoreConnector.php";

$suid = $_POST['suid'];
$tuid = $_POST['tuid'];
// $suid = $_GET['suid'];
// $tuid = $_GET['tuid'];

$username = "root";
$password = "123456";
$gc = new GstoreConnector("127.0.0.1", 9000);

$suseridentity = "<user/" . $suid . ">";
$if_suid_exist = "select (COUNT(?uid) as ?uidcnt) where\n{\n?uid <exist> " . $suseridentity . " .\n}";
//echo $if_suid_exist . PHP_EOL;
$query = $gc->query($username, $password, "weibo", $if_suid_exist);
$obj = json_decode($query);
if(count($obj->results->bindings) == 0){
	$arr = array("status" => "no such suid");
	$ret = json_encode($arr);
	echo $ret;
	return;
}

$tuseridentity = "<user/" . $tuid . ">";
$if_tuid_exist = "select (COUNT(?uid) as ?uidcnt) where\n{\n?uid <exist> " . $tuseridentity . " .\n}";
//echo $if_tuid_exist . PHP_EOL;
$query = $gc->query($username, $password, "weibo", $if_tuid_exist);
$obj = json_decode($query);
if(count($obj->results->bindings) == 0){
	$arr = array("status" => "no such tuid");
	$ret = json_encode($arr);
	echo $ret;
	return;
}

$if_follow = "select ?y where\n{\n" . $suseridentity . " ?y " . $tuseridentity . " .\n}";
$query = $gc->query($username, $password, "weibo", $if_follow);
// echo  $if_follow . PHP_EOL . $query . PHP_EOL;
$obj = json_decode($query);


if(count($obj->results->bindings) == 0){
	$arr = array("status"=>"no such relation");
	$ret = json_encode($arr);
	echo $ret;
	return;
}


if(count($obj->results->bindings) == 1 && $obj->results->bindings[0]->y->value == "follow"){
	$deleterela = "delete where\n{\n" . $suseridentity . " <follow> " . $tuseridentity . " .\n}";
	$query = $gc->query($username, $password, "weibo", $deleterela);
	// echo  $deleterela . PHP_EOL . $query . PHP_EOL;
	$obj = json_decode($query);

	$arr = array("status"=>"fail");
	if($obj->StatusMsg == "update query returns true."){
		$arr = array("status"=>"success");
	}
	$ret = json_encode($arr);
	echo $ret;
	return;
}


$arr = array("status"=>"fail");
$ret = json_encode($arr);
echo $ret;
?>







