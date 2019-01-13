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
//echo $if_uid_exist . PHP_EOL;
$query = $gc->query($username, $password, "weibo", $if_uid_exist);
$obj = json_decode($query);
if(count($obj->results->bindings) == 0){
	$arr = array("status" => "no such uid");
	$ret = json_encode($arr);
	echo $ret;
	return;
}

/*
 select ?wid ?time where
 {
	$uid <follow> ?tuid .
	?wid <from_uid> ?tuid .
	?wid <publish_date> ?time .
 } ORDER BY DESC(?time) LIMIT $len OFFSET $startidx
 */
$weibotimequery = "select ?wid ?time where\n{\n" . $useridentity . " <follow> ?tuid .\n?wid <from_uid> ?tuid .\n?wid <publish_date> ?time .\n} ORDER BY DESC(?time) LIMIT " . $len . " OFFSET " . $startidx;
$query = $gc->query($username, $password, "weibo", $weibotimequery);

$obj = json_decode($query);
$widset = $obj->results->bindings;
$arr = array();
for($i = 0; $i < count($widset); $i++)
{

	$widentity = "<" . $widset[$i]->wid->value . ">";
	$time = $widentity . " <publish_date> ?time .\n";
	$repostsnum = $widentity . " <repostsnum> ?repostsnum .\n";
	$commentsnum = $widentity . " <commentsnum> ?commentsnum .\n";
	$attitudesnum = $widentity . " <attitudesnum> ?attitudesnum .\n";
	$fromuid = $widentity . " <from_uid> ?fromuid .\n";
	$topic = $widentity . " <topic> ?topic .\n";
	$source = $widentity . " <source> ?source .\n";
	$text = $widentity . " <text> ?text .\n";
	$weiboquery = "select ?time ?repostsnum ?commentsnum ?attitudesnum ?fromuid ?topic ?source ?text where\n{\n" . $time . $repostsnum . $commentsnum . $attitudesnum . $fromuid . $topic . $source . $text . "}";
	// echo $weiboquery . PHP_EOL;
	$weiboinfo = $gc->query($username, $password, "weibo", $weiboquery);
	// echo $weiboinfo . PHP_EOL;
	$obj = json_decode($weiboinfo);


	$snamequery = "select ?sname where\n{\n<" . $obj->results->bindings[0]->fromuid->value . "> <has_screen_name> ?sname .\n}";
	$snameinfo = $gc->query($username, $password, "weibo", $snamequery);
	$obj2 = json_decode($snameinfo);
	$sname = $obj2->results->bindings[0]->sname->value;

	$a = array("wid"=>substr($widset[$i]->wid->value, 6),
			   "publish_time"=>$obj->results->bindings[0]->time->value,
			   "repostsnum"=>$obj->results->bindings[0]->repostsnum->value,
			   "commentsnum"=>$obj->results->bindings[0]->commentsnum->value,
			   "attitudesnum"=>$obj->results->bindings[0]->attitudesnum->value,
			   "fromuid"=>substr($obj->results->bindings[0]->fromuid->value, 5),
			   "topic"=>$obj->results->bindings[0]->topic->value,
			   "source"=>$obj->results->bindings[0]->source->value,
			   "text"=>$obj->results->bindings[0]->text->value,
			   "sname"=>$sname);
	$arr[] = $a;
}

$retarr = array("weibo_num"=>count($widset), "content"=>$arr);
$ret = json_encode($retarr);
echo $ret;

?>







