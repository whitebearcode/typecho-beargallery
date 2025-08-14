<?php
use Typecho\Db;
header("HTTP/1.1 200 OK");
    header("Access-Control-Allow-Origin: *");
    date_default_timezone_set('PRC');
    $options = Helper::options();
    $removeChar = ["https://", "http://"]; 
    Typecho_Widget::widget('Widget_User')->to($user);
    $db = \Typecho\Db::get();
$id = $this->user->uid;

function curl_getToken($url, $email = '', $password = '')
{
    $ch = curl_init();
    $postData = [
        'email' => $email,
        'password' => $password
    ];
    
    curl_setopt($ch, CURLOPT_URL, $url.'/api/v1/tokens');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.121 Safari/537.36');

    $output = curl_exec($ch);
    curl_close($ch);
    
    $response = json_decode($output, true);
    
    if ($response && isset($response['status'])) {
        if ($response['status'] === true && isset($response['data']['token'])) {
             exit(json_encode([
            'code'=> 1,
            'msg' => $response['data']['token'],
    ]));
        } elseif (isset($response['message'])) {
             exit(json_encode([
            'code'=> 0,
            'msg' => $response['message']
    ]));
        }
    }
    
    return false; // or return an appropriate default value
}


    if (strpos($_SERVER['HTTP_REFERER'], str_replace($removeChar, "", $options->siteUrl)) !== false  && $user->hasLogin()) {   
        if($_POST['type'] == 'getLskyProToken'){
            if(get_option('beargallery')["lskypro_api"] == null || get_option('beargallery')["lskypro_api"] == ''){
                exit(json_encode([
            'code'=> 0,
            'msg' => '您需要先填写LskyPro Api地址并保存选项后再进行获取Token操作！'
    ]));
            }
            if($_POST['lskypro_username'] == null || $_POST['lskypro_username'] == ''){
                exit(json_encode([
            'code'=> 0,
            'msg' => '账号为空，请重新填写'
    ]));
            }
            if($_POST['lskypro_password'] == null || $_POST['lskypro_password'] == ''){
                exit(json_encode([
            'code'=> 0,
            'msg' => '密码为空，请重新填写'
    ]));
            }
            curl_getToken(get_option('beargallery')["lskypro_api"], $_POST['lskypro_username'], $_POST['lskypro_password']);
        }
       

}