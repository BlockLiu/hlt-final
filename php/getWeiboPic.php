<?php

require "GstoreConnector.php";

$wid = $_POST['wid'];
// $wid = $_GET['wid'];


$username = "root";
$password = "123456";
$gc = new GstoreConnector("127.0.0.1", 9000);


$weiboidentity = "<weibo/" . $wid . ">";
$if_wid_exist = "select (COUNT(?wid) as ?widcnt) where\n{\n?wid <exist> " . $weiboidentity . " .\n}";
// echo $if_wid_exist . PHP_EOL;
$query = $gc->query($username, $password, "weibo", $if_wid_exist);
// echo $query . PHP_EOL;
$obj = json_decode($query);
if(count($obj->results->bindings) == 0){
	$arr = array("status" => "no such wid");
	$ret = json_encode($arr);
	echo $ret;
	return;
}


$imageNameSql = "select ?image where\n{\n" . $weiboidentity . " <picture> ?image .\n}";
$query = $gc->query($username, $password, "weibo", $imageNameSql);
$obj = json_decode($query);
if($obj->results->bindings[0]->image->value == "none"){
	$arr = array("status"=>"no picture for this wid");
	$ret = json_encode($arr);
	echo $ret;
	return;
}
$imageName = "../uploads/pictures/" . $obj->results->bindings[0]->image->value;

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