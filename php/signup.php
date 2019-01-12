<?php

require "GstoreConnector.php";

$name = $_POST['name'];
$screenname = $_POST['screen_name'];
$pwd = $_POST['password'];
$province = $_POST['province'];
$city = $_POST['city'];
$gender = $_POST['gender'];
if($gender == 'male')	$gender = "m";
if($gender == 'female')	$gender = "f";

$username = "root";
$password = "123456";
$gc = new GstoreConnector("127.0.0.1", 9000);


// find a new uid
$flag = 1;
while($flag == 1){
	$uid = rand(1000000000, 9999999999);
	$if_uid_exist = "select (COUNT(?uid) as ?uidcnt) where\n{\n?uid <exist> <user/" . $uid . "> .\n}";
	$query = $gc->query($username, $password, "weibo", $if_uid_exist);
	$obj = json_decode($query);
	// echo $query;
	if(count($obj->results->bindings) == 0)
		$flag = 0;
}

// url
$url = "http://localhost/homepage.php?suid=" . $uid;
$createdat = date('Y-m-d H:i:s');
$friendsnum = 0;
$followersnum = 0;
$statusesnum = 0;
$favouritesnum = 0;
$location = $province . " " . $city;

$useridentity = "<user/" . $uid . ">";
$insertuid = "<useridentity> <exist> <user/" . $uid . "> .\n";
$insertname = $useridentity . " <has_name> \"" . $name . "\" .\n";
$insertsname = $useridentity . " <has_screen_name> \"" . $screenname . "\" .\n";
$insertloc = $useridentity . " <has_location> \"" . $location . "\" .\n";
$inserturl = $useridentity . " <has_url> \"" . $url . "\" .\n";
$insertgender = $useridentity . " <has_gender> \"" . $gender . "\" .\n";
$insertfollowersnum = $useridentity . " <has_followersnum> " . $followersnum . " .\n";
$insertfriendsnum = $useridentity . " <has_friendsnum> " . $friendsnum . " .\n";
$insertstatusesnum = $useridentity . " <has_statusesnum> " . $statusesnum . " .\n";
$insertfavouritesnum = $useridentity . " <has_favouritesnum> " . $favouritesnum . " .\n";
$inserttime = $useridentity . " <created_at> \"" . $createdat . "\" .\n";
$insertpwd = $useridentity . " <has_password> \"" . $pwd . "\" .\n";
$inserthead = $useridentity . " <has_headimage_id> \"default\" .\n";

$insertdata = "insert data\n{\n" . $insertuid . $insertname . $insertsname . $insertloc . $inserturl . $insertgender . $insertfollowersnum . $insertfriendsnum . $insertstatusesnum . $insertfavouritesnum . $inserttime . $insertpwd . $inserthead . "}";

// echo $insertdata . PHP_EOL;

$res = $gc->query($username, $password, "weibo", $insertdata);
// echo $res . PHP_EOL;
$obj = json_decode($res);

$arr = array("status" => "fail");
if($obj->StatusMsg == "update query returns true."){
	$check = "select ?x where\n{\n" . $useridentity . " ?y ?x .\n}";
	// echo $check . PHP_EOL;
	$checkres = $gc->query($username, $password, "weibo", $check);
	$checkobj = json_decode($checkres);
	// echo $checkres . PHP_EOL;
	// echo count($checkobj->results->bindings) . PHP_EOL;
	if(count($checkobj->results->bindings) == 12){
		$arr = array('status' => 'success', 'uid' => $uid);
	}
}
$ret = json_encode($arr);
echo $ret;

?>