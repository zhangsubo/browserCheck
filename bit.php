<?php 
header("Content-Type: application/json;charset=utf-8");
/**
 * 判断操作系统位数
 */
function is_64bit() {
    $int = "9223372036854775807";
    $int = intval($int);
    if ($int == 9223372036854775807) {
        /* 64bit */
        return true;
    }
    elseif ($int == 2147483647) {
        /* 32bit */
        return false;
    }
    else {
        /* error */
        return "error";
    }
}
if (is_64bit()==1) {
    $bit = array('bit' => 'x64');
} elseif (is_64bit()==0) {
    $bit = array('bit' => 'x86');
}else{
    $bit = array('bit' => 'error');
}
echo "var bit = ".json_encode($bit); //json_encode对变量进行 JSON 编码
?>