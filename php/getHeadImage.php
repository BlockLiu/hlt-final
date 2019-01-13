<?php

require "GstoreConnector.php";

$uid = $_POST['uid'];
// $uid = $_GET['uid'];


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


$imageNameSql = "select ?image where\n{\n" . $useridentity . " <has_headimage_id> ?image .\n}";
$query = $gc->query($username, $password, "weibo", $imageNameSql);
$obj = json_decode($query);
$imageName = "../uploads/headimages/" . $obj->results->bindings[0]->image->value;


function imgtobase64($img='', $imgHtmlCode=true)
{
       $imageInfo = getimagesize($img);
       $base64 = "" . chunk_split(base64_encode(file_get_contents($img)));
       # file_get_contents可替换为 fread(fopen($img, 'r'), filesize($img));
       return $imgHtmlCode? '![]('.$base64.')' : $base64;
}

$res = imgtobase64($imageName);
$arr = array("status"=>"success", "content"=>$res);
$ret = json_encode($arr);
echo $ret;

?>