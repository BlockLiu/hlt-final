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
$useridentity = "<user/" . $uid . ">";
$insertdata = "insert data\n{\n<useridentity> <exist> " . $useridentity . " .\n}";
// echo $insertdata . PHP_EOL;
$res = $gc->query($username, $password, "weibo", $insertdata);
// echo $res . PHP_EOL;

// url
$url = "http://localhost/homepage.php?suid=" . $uid;
$createdat = date('Y-m-d H:i:s');
$friendsnum = "0";
$followersnum = "0";
$statusesnum = "0";
$favouritesnum = "0";
$location = $province . " " . $city;

//$insertuid = "<useridentity> <exist> " . $useridentity . " .\n";
$insertname = $useridentity . " <has_name> \"" . $name . "\" ;\n";
$insertsname = "<has_screen_name> \"" . $screenname . "\" ;\n";
$insertloc = "<has_location> \"" . $location . "\" ;\n";
$inserturl = "<has_url> \"" . $url . "\" ;\n";
$insertgender = "<has_gender> \"" . $gender . "\" ;\n";
$insertfollowersnum = "<has_followersnum> \"" . $followersnum . "\" ;\n";
$insertfriendsnum = "<has_friendsnum> \"" . $friendsnum . "\" ;\n";
$insertstatusesnum = "<has_statusesnum> \"" . $statusesnum . "\" ;\n";
$insertfavouritesnum = "<has_favouritesnum> \"" . $favouritesnum . "\" ;\n";
$inserttime = "<created_at> \"" . $createdat . "\" ;\n";
$insertpwd = "<has_password> \"" . $pwd . "\" ;\n";
$inserthead = "<has_headimage_id> \"default\" .\n";

$insertdata = "insert data\n{\n" . $insertname . $insertsname . $insertloc . $inserturl . $insertgender . $insertfollowersnum . $insertfriendsnum . $insertstatusesnum . $insertfavouritesnum . $inserttime . $insertpwd . $inserthead . "}";

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
	echo $checkres . PHP_EOL;
	// echo count($checkobj->results->bindings) . PHP_EOL;
	if(count($checkobj->results->bindings) == 12){
		$arr = array('status' => 'success', 'uid' => $uid);
	}
}
$ret = json_encode($arr);
echo $ret;

?>