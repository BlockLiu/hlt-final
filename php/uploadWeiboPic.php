<?php
    $image = $_FILES['weiboPic'];
    $imageName = $_FILES['weiboPic']['name'];
    $tmpName = $_FILES['weiboPic']['tmp_name'];
    // echo $imageName . PHP_EOL . $tmpName . PHP_EOL;
    $wid = $_POST['number'];

    if ($image['error'] > 0) {
                $error = "upload fail, because ";

                switch ($image['error']) {
                    case 1:
                        $error .= "file size exceed system limited size";
                        break;
                    case 2:
                        $error .= "file size exceed form limited size";
                        break;
                    case 3:
                        $error .= "only part of file uploaded";
                        break;
                    case 4:
                        $error .= "no uploaded file";
                        break;
                    case 5:
                        $error .= "size of uploaded file is zero";
                        break;
                    case 6:
                        $error .= "no tmp upload directory exist";
                        break;
                    case 7:
                        $error .= "fail to write in";
                        break;
                    default:
                        $error .= "unkown error";
                        break;
                }
                $arr = array("status"=>"fail", "errInfo"=>$error);
                $ret = json_encode($arr);
                echo $ret;
                return;
            }

    $filepath = '../uploads/pictures/';

    if(move_uploaded_file($tmpName, $filepath . $wid . ".png")){
        $arr = array("status"=>"success");
        $ret = json_encode($arr);
        echo $ret;
    }else{
        $arr = array("status"=>"fail", "errInfo"=>"please check read and write permission");
        $ret = json_encode($arr);
        echo $ret;
    }
?>
