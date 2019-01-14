<?php

require "GstoreConnector.php";

$suid = $_POST['suid'];
$tuid = $_POST['tuid'];
// $suid = $_GET['suid'];
// $tuid = $_GET['tuid'];
// echo $uid;

$username = "root";
$password = "123456";
$gc = new GstoreConnector("127.0.0.1", 9000);

/* ******************************************** */

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

/* ************************************************ */

$s_identity = "<user/" . $suid . ">";
$t_identity = "<user/" . $tuid . ">";


$one_edge_res = array("status"=>"0");
$one_edge_query = "select ?y where\n{\n" . $s_identity . " ?y " . $t_identity . " .\n}"; 
$query = $gc->query($username, $password, "weibo", $one_edge_query);
// echo $one_edge_query . PHP_EOL . $query . PHP_EOL;
$obj = json_decode($query);
if(count($obj->results->bindings) == 1){
	$one_edge_res = array("status"=>"1");
}

/* ************************************************ */

$two_edge_res = array("status"=>"0");
$two_edge_query = "select ?mid where\n{\n" . $s_identity . " <follow> ?mid .\n?mid <follow> " . $t_identity . " .\n}";
$query = $gc->query($username, $password, "weibo", $two_edge_query);
$obj = json_decode($query);
if(count($obj->results->bindings) > 0){
	$content = array();
	for($i = 0; $i < count($obj->results->bindings); $i++)
	{
		$content[] = substr($obj->results->bindings[$i]->mid->value, 5);
	}
	$two_edge_res = array("status"=>strval(count($obj->results->bindings)),
						  "content"=>$content);
}
// echo $two_edge_query . PHP_EOL . $query . PHP_EOL;

/* ************************************************ */

$three_edge_res = array("status"=>"0");
$three_edge_query = "select ?m1 ?m2 where\n{\n" . $s_identity . " <follow> ?m1 .\n?m1 <follow> ?m2 .\n?m2 <follow> " . $t_identity . " .\n}";
$query = $gc->query($username, $password, "weibo", $three_edge_query);
$obj = json_decode($query);
if(count($obj->results->bindings) > 0){
	$content = array();
	for($i = 0; $i < count($obj->results->bindings); $i++)
	{
		$content[] = array("m1"=>substr($obj->results->bindings[$i]->m1->value, 5),
						   "m2"=>substr($obj->results->bindings[$i]->m2->value, 5));
	}
	$three_edge_res = array("status"=>strval(count($obj->results->bindings)),
						  "content"=>$content);
}
// echo $three_edge_query . PHP_EOL . $query . PHP_EOL;

/* ************************************************ */

$four_edge_res = array("status"=>"0");
$four_edge_query = "select ?m1 ?m2 ?m3 where\n{\n" . $s_identity . " <follow> ?m1 .\n?m1 <follow> ?m2 .\n?m2 <follow> ?m3 .\n?m3 <follow> " . $t_identity . " .\n}";
$query = $gc->query($username, $password, "weibo", $four_edge_query);
$obj = json_decode($query);
if(count($obj->results->bindings) > 0){
	$content = array();
	for($i = 0; $i < count($obj->results->bindings); $i++)
	{
		$content[] = array("m1"=>substr($obj->results->bindings[$i]->m1->value, 5),
						   "m2"=>substr($obj->results->bindings[$i]->m2->value, 5),
						   "m3"=>substr($obj->results->bindings[$i]->m3->value, 5));
	}
	$four_edge_res = array("status"=>strval(count($obj->results->bindings)),
						  "content"=>$content);
}
// echo $four_edge_query . PHP_EOL . $query . PHP_EOL;

/* ************************************************ */

$retArr = array("one"=>$one_edge_res, 
				"two"=>$two_edge_res,
				"three"=>$three_edge_res,
				"four"=>$four_edge_res);
$ret = json_encode($retArr);
echo $ret;


?>