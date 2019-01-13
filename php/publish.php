<?php
require "GstoreConnector.php";
$uid = $_POST['uid'];
$topic = $_POST['topic'];
$source = $_POST['source'];
$text = $_POST['text'];
$picture = $_POST['picture'];
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
$publish_date = date('Y-m-d H:i:s');
$repostsnum = "0";
$commentsnum = "0";
$attitudesnum = "0";
$insertdate = $weiboidentity . " <publish_date> \"" . $publish_date . "\" ;\n";
$insertrep = "<repostsnum> \"" . $repostsnum . "\" ;\n";
$insertcom = "<commentsnum> \"" . $commentsnum . "\" ;\n";
$insertatt = "<attitudesnum> \"" . $attitudesnum . "\" ;\n";
$insertuid = "<from_uid> " . $useridentity . " ;\n";
$inserttopic = "<topic> \"" . $topic . "\" ;\n";
$insertsource = "<source> \"" . $source . "\" ;\n";
$inserttext = "<text> \"" . $text . "\" ;\n";
$insertpic = "<picture> \"" . $picture . "\" .\n";
$insertdata = "insert data\n{\n" . $insertdate . $insertrep . $insertcom . $insertatt . $insertuid . $inserttopic . $insertsource . $inserttext . $insertpic . "}";
$res = $gc->query($username, $password, "weibo", $insertdata);
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