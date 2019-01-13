<?php
	$image = $_FILES['myfile'];
    $imgname = $_FILES['myfile']['name'];
    $tmp = $_FILES['myfile']['tmp_name'];
    echo $imgname . PHP_EOL;
    echo $image['error'] . PHP_EOL;
    $filepath = '../uploads/';
    echo $filepath.$imgname . PHP_EOL;
    if(move_uploaded_file($tmp, $filepath.$imgname)){
        echo "上传成功";
    }else{
        echo "上传失败";
    }
?>
