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
//echo $if_uid_exist . PHP_EOL;
$query = $gc->query($username, $password, "weibo", $if_uid_exist);
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
$followersnum = $useridentity . " <has_followersnum> ?followersnum .\n";
$friendsnum = $useridentity . " <has_friendsnum> ?friendsnum .\n";
$statusesnum = $useridentity . " <has_statusesnum> ?statusesnum .\n";
$favouritesnum = $useridentity . " <has_favouritesnum> ?favouritesnum .\n";
$time = $useridentity . " <created_at> ?time .\n";
$head = $useridentity . " <has_headimage_id> ?head .\n";

$queryinfo = "select ?name ?sname ?loc ?url ?gender ?followersnum ?friendsnum ?statusesnum ?favouritesnum ?time ?head where\n{\n" . $name . $sname . $loc . $url . $gender . $followersnum . $friendsnum . $statusesnum . $favouritesnum . $time . $head . "}";
$info = $gc->query($username, $password, "weibo", $queryinfo);
$obj = json_decode($info);
// echo $info . PHP_EOL;
if(count($obj->results->bindings) == 0){
	$arr = array("status" => "no enough information");
	$ret = json_encode($arr);
	echo $ret;
	return;
}

$res = $obj->results->bindings[0];
$arr = array("name"=>$res->name->value, "sname"=>$res->sname->value,
			 "location"=>$res->loc->value, "url"=>$res->url->value,
			 "gender"=>$res->gender->value, "followersnum"=>$res->followersnum->value,
			 "friendsnum"=>$res->friendsnum->value, 
			 "statusesnum"=>$res->statusesnum->value,
			 "favouritesnum"=>$res->favouritesnum->value,
			 "createdat"=>$res->time->value, "headimageid"=>$res->head->value);
$ret = json_encode($arr);
echo $ret;
?>