<?php
require "GstoreConnector.php";
$uid = $_POST['uid'];
$topic = $_POST['topic'];
$source = $_POST['source'];
$text = $_POST['text'];
$picture = $_POST['picture'];

// echo $source . PHP_EOL . $text . PHP_EOL;
$source = str_replace('"', '\"', $source);
$text = str_replace('"', '\"', $text);
// echo $source . PHP_EOL . $text . PHP_EOL;

$username = "root";
$password = "123456";
$gc = new GstoreConnector("127.0.0.1", 9000);



$useridentity = "<user/" . $uid . ">";
$if_uid_exist = "select (COUNT(?uid) as ?uidcnt) where\n{\n?uid <exist> " . $useridentity . " .\n}";
$query = $gc->query($username, $password, "weibo", $if_uid_exist);
// echo $if_uid_exist . PHP_EOL . $query . PHP_EOL;
$obj = json_decode($query);
if(count($obj->results->bindings) == 0){
	$arr = array("status" => "no such uid");
	$ret = json_encode($arr);
	echo $ret;
	return;
}
// find a new wid
$flag = 1;
while($flag == 1){
	$wid = rand(1000000000000000, 9999999999999999);
	$if_wid_exist = "select (COUNT(?wid) as ?widcnt) where\n{\n?wid <exist> <weibo/" . $wid . "> .\n}";
	$query = $gc->query($username, $password, "weibo", $if_wid_exist);
	$obj = json_decode($query);
	// echo $query;
	if(count($obj->results->bindings) == 0)
		$flag = 0;
}




$weiboidentity = "<weibo/" . $wid . ">";
$insertdata = "insert data\n{\n<weiboidentity> <exist> " . $weiboidentity . " .\n}";
// echo $insertdata . PHP_EOL;
$res = $gc->query($username, $password, "weibo", $insertdata);
// echo $res . PHP_EOL;


$if_wid_exist = "select (COUNT(?wid) as ?widcnt) where\n{\n?wid <exist> <weibo/" . $wid . "> .\n}";
$query = $gc->query($username, $password, "weibo", $if_wid_exist);
$obj = json_decode($query);
// echo $query;















$publish_date = date('Y-m-d H:i:s');
$repostsnum = "0";
$commentsnum = "0";
$attitudesnum = "0";
$insertdate = $weiboidentity . " <publish_date> \"" . $publish_date . "\" .\n";
$insertrep = $weiboidentity . " <repostsnum> \"" . $repostsnum . "\" .\n";
$insertcom = $weiboidentity . " <commentsnum> \"" . $commentsnum . "\" .\n";
$insertatt = $weiboidentity . " <attitudesnum> \"" . $attitudesnum . "\" .\n";
$insertuid = $weiboidentity . " <from_uid> " . $useridentity . " .\n";
$inserttopic = $weiboidentity . " <topic> \"" . $topic . "\" .\n";
$insertsource = $weiboidentity . " <source> \"" . $source . "\" .\n";
$inserttext = $weiboidentity . " <text> \"" . $text . "\" .\n";
$insertpic = $weiboidentity . " <picture> \"" . $picture . "\" .\n";
$insertdata = "insert data\n{\n" . $insertdate . $insertrep . $insertcom . $insertatt . $insertuid . $inserttopic . $insertsource . $inserttext . $insertpic . "}";
// $insertdata = "insert data\n{\n" . $insertsource . "}";
$res = $gc->query($username, $password, "weibo", $insertdata);
// echo $insertdata . PHP_EOL . $res . PHP_EOL;
$obj = json_decode($res);
$arr = array("status" => "fail");
if($obj->StatusMsg == "update query returns true."){
	$check = "select ?x where\n{\n" . $weiboidentity . " ?y ?x .\n}";
	// echo $check . PHP_EOL;
	$checkres = $gc->query($username, $password, "weibo", $check);
	$checkobj = json_decode($checkres);
	echo $checkres . PHP_EOL;
	// echo count($checkobj->results->bindings) . PHP_EOL;
	if(count($checkobj->results->bindings) == 9){
		$arr = array('status' => 'success', 'wid' => $wid);
	}
}
$ret = json_encode($arr);
echo $ret;
?>