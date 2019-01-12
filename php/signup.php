<?php

require "GstoreConnector.php";

$name = $_POST['name'];
$screenname = $_POST['screen_name'];
$pwd = $_POST['password'];
$province = $_POST['province'];
$city = $_POST['city'];
$gender = $_POST['gender'];
if($gender == 'male')	$gender = 'm';
if($gender == 'female')	$gender = 'f';

$username = "root";
$password = "123456";
$gc = new GstoreConnector("127.0.0.1", 9000);


// find a new uid
$flag = 1;
while($flag == 1){
	$uid = rand(1000000000, 9999999999);
	$if_uid_exist = 'select (COUNT(?uid) as ?uidcnt) where{ ?uid <exist> <user:' . $uid . '> .}';
	$query = $gc->query($username, $password, "weibo", $if_uid_exist);
	$obj = json_decode($query);
	if(count($obj->results->bindings) == 0)
		$flag = 0;
}
// echo $uid . PHP_EOL;
// echo $name . PHP_EOL;
// echo $screenname . PHP_EOL;
// echo $pwd . PHP_EOL;
// echo $gender . PHP_EOL;

// url
$url = "http://localhost/homepage.php?suid=" . $uid;
// echo $url . PHP_EOL;

$createdat = date('Y-m-d H:i:s');
$friendsnum = 0;
$followersnum = 0;
$statusesnum = 0;
$favouritesnum = 0;
$location = $province . " " . $city;


// echo $createdat . PHP_EOL;
// echo $friendsnum . PHP_EOL;
// echo $followersnum . PHP_EOL;
// echo $statusesnum . PHP_EOL;
// echo $favouritesnum . PHP_EOL;
// echo $location . PHP_EOL;

$insertuid = '<useridentity> <exist> <user:' . $uid . '> .' . PHP_EOL;
$insertname = '<user:' . $uid . '> <has_name> "' . $name . '" .' . PHP_EOL;
$insertsname = '<user:' . $uid . '> <has_screen_name> "' . $screenname . '" .' . PHP_EOL;
$insertloc = '<user:' . $uid . '> <has_location> "' . $location . '" .' . PHP_EOL;
$inserturl = '<user:' . $uid . '> <has_url> "' . $url . '" .' . PHP_EOL;
$insertgender = '<user:' . $uid . '> <has_gender> "' . $gender . '" .' . PHP_EOL; 
$insertfollowersnum = '<user:' . $uid . '> <has_followersnum> ' . $followersnum . ' .' . PHP_EOL;
$insertfriendsnum = '<user:' . $uid . '> <has_friendsnum> ' . $friendsnum . ' .' . PHP_EOL;
$insertstatusesnum = '<user:' . $uid . '> <has_statusesnum> ' . $statusesnum . ' .' . PHP_EOL;
$insertfavouritesnum = '<user:' . $uid . '> <has_favouritesnum> ' . $favouritesnum . ' .' . PHP_EOL;
$inserttime = '<user:' . $uid . '> <created_at> "' . $createdat . '" .' . PHP_EOL;
$insertpwd = '<user:' . $uid . '> <has_password> "' . $pwd . '" .';

$insertdata = 'insert data { ' . PHP_EOL . $insertuid . $insertname . $insertsname . $insertloc . $inserturl . $insertgender . $insertfollowersnum . $insertfriendsnum . $insertstatusesnum . $insertfavouritesnum . $inserttime . $insertpwd . '}';

echo $insertdata . PHP_EOL;

$res = $gc->query($username, $password, "weibo", $insertdata);
$obj = json_decode($res);

$arr = array('status' => 'false');
$ret = json_encode($arr);
if($obj->StatusMsg == 'update query returns true.'){
	$arr = array('status' => 'true', 'uid' => $uid);
	$ret = json_encode($arr);
}
echo $ret;

?>