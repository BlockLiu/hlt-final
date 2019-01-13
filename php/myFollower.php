<?php

require "GstoreConnector.php";

$uid = $_POST['uid'];
$startidx = $_POST['start'];
$len = $_POST['len'];
// $uid = $_GET['uid'];
// $startidx = $_GET['start'];
// $len = $_GET['len'];


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


/*
	select ?uid ?sname where
	{
		?uid <follow> $useridentity .
		?uid <has_screen_name> ?sname .
	}
 */
$querysql = "select ?uid ?sname where\n{\n?uid <follow> " . $useridentity . " .\n?uid <has_screen_name> ?sname .\n} ORDER BY (?uid) LIMIT " . $len . " OFFSET " . $startidx;
$query = $gc->query($username, $password, "weibo", $querysql);
// echo $querysql . PHP_EOL;
// echo $query . PHP_EOL;

$obj = json_decode($query);
$userset = $obj->results->bindings;
$arr = array();
for($i = 0; $i < count($userset); $i++){
	$a = array("uid"=>substr($userset[$i]->uid->value, 5), 
		"screen_name"=>$userset[$i]->sname->value);
	$arr[] = $a;
}

$retarr = array("user_num"=>count($userset), "content"=>$arr);
$ret = json_encode($retarr);
echo $ret;
?>







