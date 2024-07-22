<?php
//引入方法文件
use \Untils\Markdown as Markdown;
require_once('General.php');
require_once('Parse.php');

function themeInit($self){
    if ($self->hidden){
    $self->response->setStatus(200);
header('HTTP/1.1 200 OK');
    }
     if (strpos($_SERVER['REQUEST_URI'], '/attachment/') !== false) {
   $self->response->setStatus(404);
}
    $options = Helper::options();
    $options->commentsOrder = 'DESC';
$options->commentsAntiSpam = false;
$options->commentsCheckReferer = false;
     //升级API
        if (strpos($_SERVER['REQUEST_URI'], 'bg-upgrade') !== false) {
            $self->response->setStatus(200);
            $self->setThemeFile("modules/Upgrade/Upgrade.php");
        }
    //搜索API
        if (strpos($_SERVER['REQUEST_URI'], 'bg-search') !== false) {
            $self->response->setStatus(200);
            $self->setThemeFile("core/Functions/getSearch.php");
        }
        
        if(@$_GET['action'] === 'ajax_avatar_get' && $_GET['email'] !== null) {
$host = 'https://cravatar.cn/avatar/';
$email = strtolower( $_GET['email']);
            $hash = md5($email);
 $avatar = $host . $hash;
        echo $avatar; 
        die();
    }
}

function themeFields(Typecho_Widget_Helper_Layout $layout)
{
    
    $excerpt = new Typecho_Widget_Helper_Form_Element_Textarea('excerpt', null, null, '文章摘要', '输入自定义摘要，留空自动从文章截取。');
    $layout->addItem($excerpt);
    
    
}