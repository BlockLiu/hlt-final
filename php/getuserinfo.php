<?php

require "GstoreConnector.php";

// $uid = $_POST['uid'];
$uid = $_GET['uid'];
// echo $uid;

$username = "root";
$password = "123456";
$gc = new GstoreConnector("127.0.0.1", 9000);


$useridentity = "<user/" . $uid . ">";
$if_uid_exist = "select (COUNT(?uid) as ?uidcnt) where\n{\n?uid <exist> " . $useridentity . " .\n}";
echo $if_uid_exist . PHP_EOL;
$query = $gc->query($username, $password, "weibo", $if_uid_exist);
echo $query . PHP_EOL;
$obj = json_decode($query);
if(count($obj->results->bindings) == 0){
	$arr = array("status" => "no such uid");
	$ret = json_encode($arr);
	echo $ret;
	return;
}


$name = $useridentity . " <has_name> ?name .\n";
$sname = $useridentity . " <has_screen_name> ?sname .\n";
$loc = $useridentity . " <has_location> ?loc .\n";
$url = $useridentity . " <has_url> ?url .\n";
$gender = $useridentity . " <has_gender> ?gender .\n";
$followersnum = $useridentity . " <has_followersnum> ?n .\n";
$friendsnum = $useridentity . " <has_friendsnum> ?n .\n";
$statusesnum = $useridentity . " <has_statusesnum> ?n .\n";
$favouritesnum = $useridentity . " <has_favouritesnum> ?n .\n";
$time = $useridentity . " <created_at> ?time .\n";
$head = $useridentity . " <has_headimage_id> ?head .\n";


$arr = array();

$queryinfo = "select ?name where\n{\n" . $name . "}";
$info = $gc->query($username, $password, "weibo", $queryinfo);
$obj = json_decode($info);
$name = $obj->results->bindings[0]->name->value;

$queryinfo = "select ?sname where\n{\n" . $sname . "}";
$info = $gc->query($username, $password, "weibo", $queryinfo);
$obj = json_decode($info);
$sname = $obj->results->bindings[0]->sname->value;

$queryinfo = "select ?loc where\n{\n" . $loc . "}";
$info = $gc->query($username, $password, "weibo", $queryinfo);
$obj = json_decode($info);
$loc = $obj->results->bindings[0]->loc->value;

$queryinfo = "select ?url where\n{\n" . $url . "}";
$info = $gc->query($username, $password, "weibo", $queryinfo);
$obj = json_decode($info);
$url = $obj->results->bindings[0]->url->value;

$queryinfo = "select ?gender where\n{\n" . $gender . "}";
$info = $gc->query($username, $password, "weibo", $queryinfo);
$obj = json_decode($info);
$gender = $obj->results->bindings[0]->gender->value;

$queryinfo = "select ?n where\n{\n" . $followersnum . "}";
$info = $gc->query($username, $password, "weibo", $queryinfo);
$obj = json_decode($info);
$followersnum = $obj->results->bindings[0]->n->value;

$queryinfo = "select ?n where\n{\n" . $friendsnum . "}";
$info = $gc->query($username, $password, "weibo", $queryinfo);
$obj = json_decode($info);
$friendsnum = $obj->results->bindings[0]->n->value;

$queryinfo = "select ?n where\n{\n" . $statusesnum . "}";
$info = $gc->query($username, $password, "weibo", $queryinfo);
$obj = json_decode($info);
$statusesnum = $obj->results->bindings[0]->n->value;

$queryinfo = "select ?n where\n{\n" . $favouritesnum . "}";
$info = $gc->query($username, $password, "weibo", $queryinfo);
$obj = json_decode($info);
$favouritesnum = $obj->results->bindings[0]->n->value;

$queryinfo = "select ?time where\n{\n" . $time . "}";
$info = $gc->query($username, $password, "weibo", $queryinfo);
$obj = json_decode($info);
$time = $obj->results->bindings[0]->time->value;

$queryinfo = "select ?head where\n{\n" . $head . "}";
$info = $gc->query($username, $password, "weibo", $queryinfo);
$obj = json_decode($info);
$head = $obj->results->bindings[0]->head->value;



$arr = array();
$arr = array("name"=>$name, 
			 "sname"=>$sname, 
			 "location"=>$loc, 
			 "url"=>$url,
			 "gender"=>$gender, 
			 "followersnum"=>$followersnum, 
			 "friendsnum"=>$friendsnum, 
			 "statusesnum"=>$statusesnum, 
			 "favouritesnum"=>$favouritesnum, 
			 "createdat"=>$time, 
			 "headimageid"=>$head);
$ret = json_encode($arr);
echo $ret;
?>