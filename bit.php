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
        return 'x64';
    }
    elseif ($int == 2147483647) {
        /* 32bit */
        return 'x86';
    }
    else {
        /* error */
        return "error";
    }
}


/**
 * 获取 ip 地址
 * get_client_ip 获取客户端 ip
 * get_ip_local 获取 ip 所属地址
 */
function get_client_ip() {
    $ip = $_SERVER['REMOTE_ADDR'];
    if (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR']) AND preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
        foreach ($matches[0] AS $xip) {
            if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
                $ip = $xip;
                break;
            }
        }
    }
    return $ip;
}
$ip = get_client_ip();
function get_ip_local($ip){
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://freeapi.ipip.net/{$ip}",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
      return "cURL Error #:" . $err;
    } else {
      return $response;
    }
}
$output_get_ip_local = json_decode(get_ip_local($ip),1) ;
/**
 * 输出信息
 */
$bit_output = array(
    'bit' => is_64bit(),
    'ip' => $ip, 
    'country' => $output_get_ip_local[0],
    'province' => $output_get_ip_local[1],
    'city' => $output_get_ip_local[2],
    'company' => $output_get_ip_local[3],
    'isp' => $output_get_ip_local[4]
);
echo 'var bit='.json_encode($bit_output);
?>