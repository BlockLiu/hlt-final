<?php

require "GstoreConnector.php";

$name = $_POST['name'];
$screenname = $_POST['screen_name'];
$password = $_POST['password'];
$province = $_POST['province'];
$city = $_POST['city'];
$gender = $_POST['gender'];

$username = "root";
$password = "123456";
$gc = new GstoreConnector("127.0.0.1", 9000);
//$ret = $gc->build("weibo", "data/weibo/weibo.nt", $username, $password); 
//$ret = $gc->load("weibo", $username, $password);


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
echo $uid;
?>