<?php
namespace TypechoPlugin\BgCore;
error_reporting(0);
use Utils\Helper;
use Typecho\Plugin\PluginInterface;
use Typecho\Widget\Helper\Form;
use Typecho\{Plugin\Exception, Widget, Db, Widget\Request as WidgetRequest};
use \CSF as CSF;
use bgOptions;
use Widget\Options;
use Typecho\Common;
use Typecho\Router;
use Widget\Archive;
use Widget\Contents\Post\Admin;
use Widget\Contents\Post\Edit;
use Widget\Feedback;
use Widget\Service;
use ReflectionClass;
use Utils\PasswordHash;
use Typecho\Widget\Response as WidgetResponse;
use Utils\Markdown as Markdown;
use Widget\Upload;
use CURLFile;
require_once 'modules/Tinypng/Tinify/Exception.php';
require_once 'modules/Tinypng/Tinify/ResultMeta.php';
require_once 'modules/Tinypng/Tinify/Result.php';
require_once 'modules/Tinypng/Tinify/Source.php';
require_once 'modules/Tinypng/Tinify/Client.php';
require_once 'modules/Tinypng/Tinify.php';
require_once 'modules/aliyun_oss/aliyun-oss-php-sdk-2.6.0.phar';
use OSS\OssClient;
use OSS\Core\OssException;
require_once 'modules/qiniu_kodo/autoload.php';
use Qiniu\Auth;
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;
require_once 'modules/upyun_uss/vendor/autoload.php';
use Upyun\Upyun;
use Upyun\Config;
require_once 'modules/huawei_obs/vendor/autoload.php';
require_once 'modules/huawei_obs/obs-autoloader.php';
use Obs\ObsClient;
require_once 'modules/baidu_bos/BaiduBce.phar';
use BaiduBce\Services\Bos\BosClient;
use BaiduBce\Services\Bos\BosOptions;

/**
 * BearGallery主题核心插件
 * <br>安装后无需进行其他设置
 * @package BgCore
 * @author BearNotion
 * @version v1.0.7-release
 * @link https://www.bearnotion.ru/
 *
 */



if (!defined('__TYPECHO_ROOT_DIR__')) exit;
if (!defined('pluginName')) {
  define('pluginName', 'BgCore');
}
require_once 'bgoptions-framework.php';

class Plugin implements PluginInterface
{
     const UPLOAD_DIR = 'usr/uploads';
  const PLUGIN_DIR = '/usr/plugins/BgCore/';
     public static function getNS(): string
    {
        return __NAMESPACE__;
    }
    
    public static function activate()
    {
        /* 
         *
         *  初始化路由和方法
         *
         */
        bgRouter::initRouter();
      \Typecho\Plugin::factory('index.php')->begin = [__CLASS__, 'initevent'];
        \Typecho\Plugin::factory('admin/header.php')->header_999 = [__CLASS__, 'enqueue_style'];
        \Typecho\Plugin::factory('admin/footer.php')->end = [__CLASS__, 'enqueue_script'];
        \Typecho\Plugin::factory('Widget_Archive')->footer_999 = [__CLASS__, 'footlink'];
        \Typecho\Plugin::factory('Widget_Abstract_Contents')->excerptEx_999 = [__CLASS__, 'shortcode_parse_content'];
        \Typecho\Plugin::factory('Widget_Abstract_Contents')->contentEx_999 = [__CLASS__, 'shortcode_parse_content'];
        \Typecho\Plugin::factory('admin/write-post.php')->bottom_999 = [__CLASS__, 'shortcode_button'];
        \Typecho\Plugin::factory('admin/write-page.php')->bottom_999 = [__CLASS__, 'shortcode_button'];
        \Typecho\Plugin::factory('admin/write-post.php')->richEditor_1000 = [__CLASS__, 'Editor'];
        \Typecho\Plugin::factory('admin/write-page.php')->richEditor_1000 = [__CLASS__, 'Editor'];
        \Typecho\Plugin::factory('Widget_Feedback')->comment_28 = [__CLASS__, 'filter'];
         //LskyPro
          if(get_option('beargallery')['bucket_choose'] == '8'){
\Typecho\Plugin::factory('Widget_Upload')->uploadHandle = [__CLASS__, 'lsky_uploadHandle'];
   \Typecho\Plugin::factory('Widget_Upload')->modifyHandle = [__CLASS__, 'lsky_modifyHandle'];
    \Typecho\Plugin::factory('Widget_Upload')->deleteHandle = [__CLASS__, 'lsky_deleteHandle'];
    \Typecho\Plugin::factory('Widget_Upload')->attachmentHandle = [__CLASS__, 'lsky_attachmentHandle'];
    \Typecho\Plugin::factory('Widget_Upload')->attachmentDataHandle = [__CLASS__, 'lsky_attachmentDataHandle'];
        }
        //腾讯云COS
        if(get_option('beargallery')['bucket_choose'] == '2'){
\Typecho\Plugin::factory('Widget_Upload')->uploadHandle = [__CLASS__, 'uploadHandle'];
   \Typecho\Plugin::factory('Widget_Upload')->modifyHandle = [__CLASS__, 'modifyHandle'];
    \Typecho\Plugin::factory('Widget_Upload')->deleteHandle = [__CLASS__, 'deleteHandle'];
    \Typecho\Plugin::factory('Widget_Upload')->attachmentHandle = [__CLASS__, 'attachmentHandle'];
    \Typecho\Plugin::factory('Widget_Upload')->attachmentDataHandle = [__CLASS__, 'attachmentDataHandle'];
    \Typecho\Plugin::factory('Widget_Archive')->beforeRender = [__CLASS__, 'Widget_Archive_beforeRender'];
        }
        //阿里云COS
        if(get_option('beargallery')['bucket_choose'] == '3'){
\Typecho\Plugin::factory('Widget_Upload')->uploadHandle = [__CLASS__, 'ali_uploadHandle'];
   \Typecho\Plugin::factory('Widget_Upload')->modifyHandle = [__CLASS__, 'ali_modifyHandle'];
    \Typecho\Plugin::factory('Widget_Upload')->deleteHandle = [__CLASS__, 'ali_deleteHandle'];
    \Typecho\Plugin::factory('Widget_Upload')->attachmentHandle = [__CLASS__, 'ali_attachmentHandle'];
    \Typecho\Plugin::factory('Widget_Upload')->attachmentDataHandle = [__CLASS__, 'ali_attachmentDataHandle'];
        }
        //七牛云Kodo
        if(get_option('beargallery')['bucket_choose'] == '4'){
\Typecho\Plugin::factory('Widget_Upload')->uploadHandle = [__CLASS__, 'qiniu_uploadHandle'];
   \Typecho\Plugin::factory('Widget_Upload')->modifyHandle = [__CLASS__, 'qiniu_modifyHandle'];
    \Typecho\Plugin::factory('Widget_Upload')->deleteHandle = [__CLASS__, 'qiniu_deleteHandle'];
    \Typecho\Plugin::factory('Widget_Upload')->attachmentHandle = [__CLASS__, 'qiniu_attachmentHandle'];
    \Typecho\Plugin::factory('Widget_Upload')->attachmentDataHandle = [__CLASS__, 'qiniu_attachmentDataHandle'];
        }
        //又拍云USS
        if(get_option('beargallery')['bucket_choose'] == '5'){
\Typecho\Plugin::factory('Widget_Upload')->uploadHandle = [__CLASS__, 'upyun_uploadHandle'];
   \Typecho\Plugin::factory('Widget_Upload')->modifyHandle = [__CLASS__, 'upyun_modifyHandle'];
    \Typecho\Plugin::factory('Widget_Upload')->deleteHandle = [__CLASS__, 'upyun_deleteHandle'];
    \Typecho\Plugin::factory('Widget_Upload')->attachmentHandle = [__CLASS__, 'upyun_attachmentHandle'];
    \Typecho\Plugin::factory('Widget_Upload')->attachmentDataHandle = [__CLASS__, 'upyun_attachmentDataHandle'];
        }
        //华为云OBS
        if(get_option('beargallery')['bucket_choose'] == '7'){
\Typecho\Plugin::factory('Widget_Upload')->uploadHandle = [__CLASS__, 'hw_uploadHandle'];
   \Typecho\Plugin::factory('Widget_Upload')->modifyHandle = [__CLASS__, 'hw_modifyHandle'];
    \Typecho\Plugin::factory('Widget_Upload')->deleteHandle = [__CLASS__, 'hw_deleteHandle'];
    \Typecho\Plugin::factory('Widget_Upload')->attachmentHandle = [__CLASS__, 'hw_attachmentHandle'];
    \Typecho\Plugin::factory('Widget_Upload')->attachmentDataHandle = [__CLASS__, 'hw_attachmentDataHandle'];
        }
        //百度云BOS
        if(get_option('beargallery')['bucket_choose'] == '6'){
\Typecho\Plugin::factory('Widget_Upload')->uploadHandle = [__CLASS__, 'bd_uploadHandle'];
   \Typecho\Plugin::factory('Widget_Upload')->modifyHandle = [__CLASS__, 'bd_modifyHandle'];
    \Typecho\Plugin::factory('Widget_Upload')->deleteHandle = [__CLASS__, 'bd_deleteHandle'];
    \Typecho\Plugin::factory('Widget_Upload')->attachmentHandle = [__CLASS__, 'bd_attachmentHandle'];
    \Typecho\Plugin::factory('Widget_Upload')->attachmentDataHandle = [__CLASS__, 'bd_attachmentDataHandle'];
        }
        \CSF::activateEvent();
        
        
        
         return _t('BgCore核心同步完成');
    }

    public static function deactivate()
    {
        bgRouter::removeRouter();
        return _t('BgCore核心清除完成');
    }
    
    
    public static function get_shortcode_atts_regex()
    {
        return '/([\w-]+)\s*=\s*"([^"]*)"(?:\s|$)|([\w-]+)\s*=\s*\'([^\']*)\'(?:\s|$)|([\w-]+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|\'([^\']*)\'(?:\s|$)|(\S+)(?:\s|$)/';
    }
    
    public static function shortcode_parse_atts($text)
    {
        $atts = array();
        $pattern = self::get_shortcode_atts_regex();
        $text = preg_replace("/[\x{00a0}\x{200b}]+/u", ' ', $text);
        if (preg_match_all($pattern, $text, $match, PREG_SET_ORDER)) {
            foreach ($match as $m) {
                if (!empty($m[1])) {
                    $atts[strtolower($m[1])] = stripcslashes($m[2]);
                } elseif (!empty($m[3])) {
                    $atts[strtolower($m[3])] = stripcslashes($m[4]);
                } elseif (!empty($m[5])) {
                    $atts[strtolower($m[5])] = stripcslashes($m[6]);
                } elseif (isset($m[7]) && strlen($m[7])) {
                    $atts[] = stripcslashes($m[7]);
                } elseif (isset($m[8]) && strlen($m[8])) {
                    $atts[] = stripcslashes($m[8]);
                } elseif (isset($m[9])) {
                    $atts[] = stripcslashes($m[9]);
                }
            }
            foreach ($atts as &$value) {
                if (false !== strpos($value, '<')) {
                    if (1 !== preg_match('/^[^<]*+(?:<[^>]*+>[^<]*+)*+$/', $value)) {
                        $value = '';
                    }
                }
            }
        } else {
            $atts = ltrim($text);
        }
        return $atts;
    }
    


    public static function get_shortcode_regex($tagnames = null)
    {
        global $shortcode_tags;
        if (empty($tagnames)) {
            $tagnames = array_keys($shortcode_tags);
        }
        $tagregexp = join('|', array_map('preg_quote', $tagnames));
        return
            '\\['                           
            . '(\\[?)'                        
            . "($tagregexp)"       
            . '(?![\\w-])'                    
            . '('                         
            . '[^\\]\\/]*'               
            . '(?:'
            . '\\/(?!\\])'     
            . '[^\\]\\/]*'          
            . ')*?'
            . ')'
            . '(?:'
            . '(\\/)'                    
            . '\\]'           
            . '|'
            . '\\]'              
            . '(?:'
            . '('       
            . '[^\\[]*+'  
            . '(?:'
            . '\\[(?!\\/\\2\\])'
            . '[^\\[]*+'
            . ')*+'
            . ')'
            . '\\[\\/\\2\\]'    
            . ')?'
            . ')'
            . '(\\]?)'; 
    }
    
    public static function excerpt_text($content)
    {
       if (strpos($content, '[player') !== false) {
            $pattern = self::get_shortcode_regex(array('player'));
            $content = preg_replace("/$pattern/", '', $content);
        }
        return $content;
    }
    
    
    /**
     * 智能过滤
     * 
     */
     
    public static function getLastCommentTimeByIp($ipAddress) {
    	$db = \Typecho\Db::get();
    $ipFind = $db->fetchRow($db->select()->from('table.comments')->where('table.comments.ip = ?', $ipAddress)->order('table.comments.created', \Typecho\Db::SORT_DESC));;
    if($ipFind){
        return $ipFind['created'];
    }
    return 0;
}

    public static function containsHiddenCharacters($str) {
    $hiddenChars = [
        "\x01", // SOH (Start of Heading)
        "\x02", // STX (Start of Text)
        "\x03", // ETX (End of Text)
        "\x04", // EOT (End of Transmission)
        "\x05", // ENQ (Enquiry)
        "\x06", // ACK (Acknowledge)
        "\x07", // BEL (Bell)
        "\x08", // BS (Backspace)
        "\x09", // HT (Horizontal Tab)
        "\x0A", // LF (Line Feed)
        "\x0B", // VT (Vertical Tab)
        "\x0C", // FF (Form Feed)
        "\x0D", // CR (Carriage Return)
        "\x0E", // SO (Shift Out)
        "\x0F", // SI (Shift In)
        "\x10", // DLE (Data Link Escape)
        "\x11", // DC1 (Device Control 1)
        "\x12", // DC2 (Device Control 2)
        "\x13", // DC3 (Device Control 3)
        "\x14", // DC4 (Device Control 4)
        "\x15", // NAK (Negative Acknowledge)
        "\x16", // SYN (Synchronous Idle)
        "\x17", // ETB (End of Transmission Block)
        "\x18", // CAN (Cancel)
        "\x19", // EM (End of Medium)
        "\x1A", // Substitute character
        "\x1B", // ESC (Escape)
        "\x1C", // File Separator
        "\x1D", // Group Separator
        "\x1E", // Record Separator
        "\x1F"  // Unit Separator
    ];

    foreach ($hiddenChars as $char) {
        if (strpos($str, $char) !== false) {
            return true;
        }
    }

    return false;
}
    public static function isUrl($text) {
    //$urlPattern = '/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/';
    //return preg_match($urlPattern, $text);
    $urlPattern = '/\b(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?\b/';
    return preg_match($urlPattern, $text);
}

	public static function isBotComment($comment, $timeSinceLastComment = 60){
	//一些常见的机器人用户代理字符串
    $botUserAgents = [
        'bot',
        'crawl',
        'spider',
        'robot',
        'crawling',
        'fetch',
        'indexing',
        'mediapartners'
    ];

    // 检查用户代理是否包含机器人关键词
    foreach ($botUserAgents as $botKeyword) {
        if (stripos($comment['agent'], $botKeyword) !== false) {
            return true;
        }
    }

    // 检查评论内容是否过于简单或重复
    if (strlen($comment['text']) < 10 || preg_match('/^\s*$/', $comment['text'])) {
        return true;
    }

    // 检查IP地址是否在短时间内发送了多个评论
    $lastCommentTime = self::getLastCommentTimeByIp($comment['ip']);
    if ($lastCommentTime && (time() - $lastCommentTime) < $timeSinceLastComment) {
        return true;
    }
    // 检查评论内容是否包含隐藏字符
    if (self::containsHiddenCharacters($comment['text'])) {
        return true;
    }
    // 检查评论昵称是否包含网址
    if (self::isUrl($comment['author'])) {
        return true;
    }
    // 如果以上条件都不满足，则认为不是机器人评论
    return false;
	}
	
	public static function xsscheck($text)
{
    $xsscheck = false;
    $list = array(
        '/onabort/is',
        '/onblur/is',
        '/onchange/is',
        '/onclick/is',
        '/ondblclick/is',
        '/onerror/is',
        '/onfocus/is',
        '/onkeydown/is',
        '/onkeypress/is',
        '/onkeyup/is',
        '/onload/is',
        '/onmousedown/is',
        '/onmousemove/is',
        '/onmouseout/is',
        '/onmouseover/is',
        '/onmouseup/is',
        '/onreset/is',
        '/onresize/is',
        '/onselect/is',
        '/onsubmit/is',
        '/onunload/is',
        '/eval/is',
        '/ascript:/is',
        '/style=/is',
        '/width=/is',
        '/width:/is',
        '/height=/is',
        '/height:/is',
        '/src=/is',
    );
    if (strip_tags($text)) {
        for ($i = 0; $i < count($list); $i++) {
            if (preg_match($list[$i], $text) > 0) {
                $xsscheck = true;
                break;
            }
        }
    } else {
        $xsscheck = true;
    };
    return $xsscheck;
}

	 public static function filter($comment,$post){
	   $options = bgOptions::getInstance()::get_option( 'beargallery' );  
	   $removeChar = ["https://", "http://"]; 
if (strpos($_SERVER['HTTP_REFERER'], str_replace($removeChar, "", Helper::options()->siteUrl)) == false ) { 
    throw new \Typecho\Widget\Exception("抱歉，检测到来源错误，请在正确的页面进行评论。",200);
}
if(Plugin::xsscheck($comment['text'])) {
			\Typecho\Cookie::set('__typecho_remember_text', $comment['text']);
			throw new \Typecho\Widget\Exception("抱歉，系统检测到您的评论包含危险内容，请返回修改后再试。",200);
        }
$comment['url'] =htmlspecialchars($comment['url'],ENT_QUOTES,'UTF-8');
        if ($comment['mail'] !== null && preg_match("/^[A-Za-z0-9\-]+([_\.][A-Za-z0-9\-]+)*@([A-Za-z0-9\-]+\.)+[A-Za-z]{2,}$/", $comment['mail']) !== 1) {
    throw new \Typecho\Widget\Exception("抱歉，系统检测到您的评论包含危险内容，请返回修改后再试。", 200);                      
}
if (strlen(trim(preg_replace('/\xc2\xa0/',' ',$comment['text']))) == 0) {
			\Typecho\Cookie::set('__typecho_remember_text', $comment['text']);
			throw new \Typecho\Widget\Exception("抱歉，系统检测到您的评论内容只有空格，请返回修改后再试。",200);
            }
            if($options['BearSpam_Auto'] == true){
        if (self::isBotComment($comment)) {
              throw new \Typecho\Widget\Exception("抱歉，本条评论被判定为机器人评论，已自动拦截。",200);  
            }
            }   
            if($options['BearSpam_Pro'] == true){
        if (!empty($options['BearSpam_IP'])) {
			if (Plugin::check_ip($options['BearSpam_IP'], $comment['ip'])) {
			throw new \Typecho\Widget\Exception("抱歉，系统检测到您的IP处于屏蔽范围内，已拦截本条评论。",200);
			  \Typecho\Cookie::set('__typecho_remember_text', $comment['text']);
			}

            
        }
		if (!empty($options['BearSpam_EMAIL'])) {
			if (Plugin::check_in($options['BearSpam_EMAIL'], $comment['mail'])) {
				throw new \Typecho\Widget\Exception("抱歉，系统检测到您的邮箱处于屏蔽范围内，已拦截本条评论。",200);
				  \Typecho\Cookie::set('__typecho_remember_text', $comment['text']);
			}
		}
		
			if (!empty($options['BearSpam_NAME'])) {
			if (Plugin::check_in($options['BearSpam_NAME'], $comment['author'])) {
			    
				throw new \Typecho\Widget\Exception("抱歉，系统检测到您的昵称存在敏感禁止词汇，已拦截本条评论。",200);
				  \Typecho\Cookie::set('__typecho_remember_text', $comment['text']);
			}
		}
		
		if (!empty($options['BearSpam_URL'])) {
			if (Plugin::check_in($options['BearSpam_URL'], $comment['url'])) {
			    			throw new \Typecho\Widget\Exception("抱歉，系统检测到您的网址处于屏蔽范围内，已拦截本条评论。",200);
			\Typecho\Cookie::set('__typecho_remember_text', $comment['text']);
			}
			}
			
			if (!empty($options['BearSpam_ArticleTitle'])&& $options['BearSpam_ArticleTitle'] == "1") {
			$db = \Typecho\Db::get();
            // 获取评论所在文章
            $pot = $db->fetchRow($db->select('title')->from('table.contents')->where('cid = ?', $comment['cid']));        
            if(strstr($comment['text'], $pot['title'])){
                		throw new \Typecho\Widget\Exception("抱歉，系统检测到您的评论内容疑似存在灌水内容，已自动拦截。",200);
\Typecho\Cookie::set('__typecho_remember_text', $comment['text']);
			}
			}
			
			if (!empty($options['BearSpam_NAMEMIN'])) {    
            if(Plugin::strLength($comment['author']) < $options['BearSpam_NAMEMIN']){

			throw new \Typecho\Widget\Exception("抱歉，系统检测到您的评论昵称过短，已自动拦截。",200);
			\Typecho\Cookie::set('__typecho_remember_text', $comment['text']);
			}
			}
			
			if (!empty($options['BearSpam_NAMEMAX'])) {    
            if (Plugin::strLength($comment['author']) > $options['BearSpam_NAMEMAX']) {
                throw new \Typecho\Widget\Exception("抱歉，系统检测到您的评论昵称过长，已自动拦截。",200);
			\Typecho\Cookie::set('__typecho_remember_text', $comment['text']);
			}}
			
			if (!empty($options['BearSpam_NAMEURL'])&& $options['BearSpam_NAMEURL'] == "1") {    
            if (preg_match(" /^((https?|ftp|news):\/\/)?([a-z]([a-z0-9\-]*[\.。])+([a-z]{2}|aero|arpa|biz|com|coop|edu|gov|info|int|jobs|mil|museum|name|nato|net|org|pro|travel)|(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]))(\/[a-z0-9_\-\.~]+)*(\/([a-z0-9_\-\.]*)(\?[a-z0-9+_\-\.%=&]*)?)?(#[a-z][a-z0-9_]*)?$/ ", $comment['author']) > 0) {
			                throw new \Typecho\Widget\Exception("抱歉，系统检测到您的评论昵称异常，已自动拦截。",200);
			\Typecho\Cookie::set('__typecho_remember_text', $comment['text']);
			}}
			
			if (!empty($options['BearSpam_Chinese'])&& $options['BearSpam_Chinese'] == "1") {    
            if (preg_match("/[\x{4e00}-\x{9fa5}]/u", $comment['text']) == 0) {
			throw new \Typecho\Widget\Exception("抱歉，系统检测到您的评论内容不包含中文，已自动拦截。",200);
			\Typecho\Cookie::set('__typecho_remember_text', $comment['text']);
			}}
			
			if (!empty($options['BearSpam_MIN'])) {    
            if(Plugin::strLength($comment['text']) < $options['BearSpam_MIN']){         
				throw new \Typecho\Widget\Exception("抱歉，系统检测到您的评论内容字数过少，已自动拦截。",200);
			\Typecho\Cookie::set('__typecho_remember_text', $comment['text']);
			}}
			
			if (!empty($options['BearSpam_MAX'])) {    
            if(Plugin::strLength($comment['text']) > $options['BearSpam_MAX']){         
				throw new \Typecho\Widget\Exception("抱歉，系统检测到您的评论内容字数过多，已自动拦截。",200);
			\Typecho\Cookie::set('__typecho_remember_text', $comment['text']);
			}}
			
			if (!empty($options['BearSpam_Words'])) {    
            if (Plugin::check_in($options['BearSpam_Words'], $comment['text'])) { 
	throw new \Typecho\Widget\Exception("抱歉，系统检测到您的评论内容包含敏感禁止词汇，已自动拦截。",200);
			\Typecho\Cookie::set('__typecho_remember_text', $comment['text']);
			}
					    
			}
            }
            \Typecho\Cookie::delete('__typecho_remember_text');
             return $comment;
	 }
	 
	 
    public static function shortcode_parse_content($content,$obj,$text)
    {
      $content = empty($text)?$content:$text;
      if(!$obj->is('single')){
       $content = self::excerpt_text($content);
       return $content;
      }
      
      

               return $content;
}

    public static function shortcode_button(){
        ?>
        <style>.wmd-button-row {
    height: auto;
}
</style>
<link rel="stylesheet" href="<?php echo Helper::options()->themeUrl;?>/assets/plugins/remixicon/remixicon.css" />
        <script>
        $(document).ready(function() {
shortCode_player();
});


//插入播放器
function shortCode_player(){
    	if ($("#wmd-button-row").length > 0) {
		$('#wmd-button-row').append('<li class="wmd-button" id="wmd-player-button" style="" title="插入视频"><span style="background: none;margin-top:7px;font-size: 15px;text-align: center;color: #999999;font-family: serif;"><i class="ri-video-chat-line"></i></span></li>');

		
		
		$(document).on('click', '#wmd-player-button',
		function() {
			$('body').append('<div id="player_Panel">' + '<div class="wmd-prompt-background" style="position: fixed; top: 0px; z-index: 1000; opacity: 0.5; height: 100%; left: 0px; width: 100%;"></div>' + '<div class="wmd-prompt-dialog">' + '<div>' + '<p><b>插入视频</b><br><br></p>' + '<p>视频文件链接：<input name="player-videourl" type="text" placeholder="填写视频文件链接"></input></p>' + '<p>视频封面图片链接：<input name="player-imgurl" type="text" placeholder="填写视频封面图片链接"></input></p>' + '</div>' + '<button type="button" class="btn btn-s primary" id="player_submit">确定</button>' + '<button type="button" class="btn btn-s" id="player_cancel">取消</button>' + '</div>' + '</div>');
		});
		

		
		$(document).on('click', '#player_cancel',
		function() {
			$('#player_Panel').remove();
			$('#text').focus();
		});
		
		$(document).on('click', '#player_submit',
		function() {
			var videourl = $('.wmd-prompt-dialog input[name="player-videourl"]').val();
			var imgurl = $('.wmd-prompt-dialog input[name="player-imgurl"]').val();
			textContent = '\r\n' + '[player url="' + videourl + '" image="' + imgurl + '"]' + '\r\n';
			myField = document.getElementById('text');
			insertButton(myField, textContent, '#player_Panel');
		});
    	};
}







function insertButton(myField, textContent, modelId = '') {
	if (modelId != '') {
		$(modelId).remove();
	}
	if (document.selection) {
		myField.focus();
		var sel = document.selection.createRange();
		sel.text = textContent;
		myField.focus();
	} else if (myField.selectionStart || myField.selectionStart == '0') {
		var startPos = myField.selectionStart;
		var endPos = myField.selectionEnd;
		var cursorPos = startPos;
		myField.value = myField.value.substring(0, startPos) + textContent + myField.value.substring(endPos, myField.value.length);
		cursorPos += textContent.length;
		myField.selectionStart = cursorPos;
		myField.selectionEnd = cursorPos;
		myField.focus();
	} else { //其他环境
		myField.value += textContent;
		myField.focus();
	}
}
</script>
        <?php
    }
    public static function footlink() {
      $options = Helper::options();
        $dir = $options->themeUrl.'/';

  echo <<<HTML
  <script src="{$dir}assets/plugins/player/hls.min.js"></script>
   <script src="{$dir}assets/plugins/player/flv.min.js"></script>
    <script src="{$dir}assets/plugins/player/player.min.js?v=2.3.4" type="text/javascript"></script>
    <script src="{$dir}assets/plugins/player/load-player.js" type="text/javascript"></script>
    <script type="text/javascript">
    $(document).on('pjax:complete', function () {
    loadDPlayer();
    });
    </script>
HTML;


    }
    
    
    
    public static function initevent()
    {
        if (!class_exists('bgOptions')){
            require_once \Utils\Helper::options()->pluginDir('BgCore').'/bgOptions.php';
        }
    }
    
    
    public static function getApiFunc($func){
         switch($func){
             case 'getAttachFile':
    $api = Helper::options()->siteUrl.'index.php/write';
    break;
         }
    echo $api;
}  

     public static function Editor()
    {
        $options = Helper::options();
    
         $dir = $options->pluginUrl.'/BgCore/assets/vendors/';
        $cssUrl = $dir.'easymde/easymde.min.css';
        $jsUrl = $dir.'easymde/easymde.min.js';
        
        
	if (strpos( $_SERVER['REQUEST_URI'],'write-post.php') !== false)
{
    $type = 'post';
\Typecho\Widget::widget('Widget_Contents_Post_Edit')->to($post);
}
if (strpos( $_SERVER['REQUEST_URI'],'write-page.php') !== false)
{
    $type = 'page';
\Typecho\Widget::widget('Widget_Contents_Page_Edit')->to($page);
}

if (isset($post) || isset($page)) {
    $cid = isset($post) ? $post->cid : $page->cid;
    
    if ($cid) {
        \Typecho\Widget::widget('Widget_Contents_Attachment_Related', 'parentId=' . $cid)->to($attachment);
    } else {
        \Typecho\Widget::widget('Widget_Contents_Attachment_Unattached')->to($attachment);
    }
}

        ?>
<?php $fontawesomeUrl = Helper::options()->pluginUrl.'/BgCore/assets/vendors/fontawesome/font-awesome.min.css';?>
<link rel="stylesheet" href="<?php echo $fontawesomeUrl;?>" />
        <link rel="stylesheet" href="<?php echo $cssUrl; ?>?v=1" />
        <script type="text/javascript" src="<?php echo $jsUrl; ?>?v=1"></script>
<link href="<?php echo $options->pluginUrl;?>/BgCore/assets/vendors/toastr.js/toastr.min.css" rel="stylesheet">
	<script src="<?php echo $options->pluginUrl;?>/BgCore/assets/vendors/toastr.js/toastr.min.js"></script>
<style>
    .editor-statusbar .lines:before{content:'行数: '}
    .editor-statusbar .words:before{content:'字数: '}
    .editor-statusbar .lines:after{content:' 行'}
    .editor-statusbar .words:after{content:' 个字'}
    .editor-toolbar{
        background:#2d333b;
    }
   .fullscreen{
       background:#2d333b;
   }
    .editor-toolbar i,
    .editor-toolbar button::after{
        color:#adbac7;
    }
    .editor-toolbar button:hover{
        background:#444c56;
    }
    .editor-toolbar button.active{
                background:#444c56;
                
    }
    .cm-s-easymde{
       background:#22272e;
        color:#adbac7;
    }

.CodeMirror {
  --selection-bg: #316dca;
  --selection-active-bg: #3d8ef7;
  --selection-text: white;
}

.CodeMirror-selected {
  background: var(--selection-bg) !important;
}

.CodeMirror-selectedtext {
  color: var(--selection-text) !important;
}

.CodeMirror-focused .CodeMirror-selected {
  background: var(--selection-active-bg) !important;
}

.CodeMirror-selection-secondary {
  background: #ffd33d !important;
}

.cm-s-easymde .CodeMirror-selected {
  background: #80cbc4 !important;
}

.CodeMirror-cursor {
  border-left: 2px solid #fff !important; 
}

.CodeMirror-focused .CodeMirror-cursor {
  border-left-color: #fff !important;
}


</style>

        <script>
        

            $(document).ready(function() {

                new EasyMDE({
        placeholder:'您现在可以开始撰写文章啦~~~',
        autoDownloadFontAwesome: false,
        spellChecker:false,
        nativeSpellcheck:false,
        sideBySideFullscreen:false,
        maxHeight:"500px",
        showIcons: ['strikethrough', 'code', 'table', 'redo', 'heading', 'undo', 'heading-bigger', 'heading-smaller', 'heading-1', 'heading-2', 'heading-3', 'clean-block', 'horizontal-rule','side-by-side'],
        <?php if(bgOptions::getInstance()::get_option( 'beargallery' )['markdownEditorAutosave'][0] == '1'):?>
        autosave: {
            enabled: true,
            delay: 1000,
            uniqueId: 'text',
            timeFormat:{locale:'zh-CN'},
            text:'自动保存时间：'
        },
        <?php endif; ?>
        toolbar: [{ name: "bold", action:EasyMDE.toggleBold, className: "fa fa-bold",title: "加粗"}, { name: "italic", action:EasyMDE.toggleItalic, className: "fa fa-italic",title: "字体倾斜"}, { name: "strikethrough", action:EasyMDE.toggleStrikethrough, className: "fa fa-strikethrough",title: "删除线"},{ name: "heading-smaller", action:EasyMDE.toggleHeadingSmaller, className: "fa fa-header",title: "字体缩小"}, { name: "heading-bigger", action:EasyMDE.toggleHeadingBigger, className: "fa fa-header",title: "字体放大"},{ name: "heading-1", action:EasyMDE.toggleHeading1, className: "fa fa-header header-1",title: "H1字号"},{ name: "heading-2", action:EasyMDE.toggleHeading2, className: "fa fa-header header-2",title: "H2字号"},{ name: "heading-3", action:EasyMDE.toggleHeading3, className: "fa fa-header header-3",title: "H3字号"}, { name: "code", action:EasyMDE.toggleCodeBlock, className: "fa fa-code",title: "代码"}, { name: "quote", action:EasyMDE.toggleBlockquote, className: "fa fa-quote-left",title: "引用"}, { name: "unordered-list", action:EasyMDE.toggleUnorderedList, className: "fa fa-list-ul",title: "无序列表"}, { name: "ordered-list", action:EasyMDE.toggleOrderedList, className: "fa fa-list-ol",title: "有序列表"},{ name: "clean-block", action:EasyMDE.cleanBlock, className: "fa fa-eraser",title: "擦除代码块"}, { name: "link", action:EasyMDE.drawLink, className: "fa fa-link",title: "插入链接"}, { name: "image", action:EasyMDE.drawImage, className: "fa fa-image",title: "插入图片"},{ name: "table", action:EasyMDE.drawTable, className: "fa fa-table",title: "插入表格"},{ name: "mindmap", className: "fa fa-percent", action:mathjax,title: "数学公式"},{ name: "autosaves", className: "fa fa-floppy-o", action:autosave,title: "自动保存"},{ name: "inserts", className: "fa fa-paperclip", action:attachInsertEvent,title: "插入所有附件"},{ name: "insertper", className: "fa fa-file-o", action:insertper,title: "插入单个附件"},{ name: "copyupload", className: "fa fa-clone", action:copyupload,title: "复制粘贴上传"}],
        promptURLs:true,
        promptTexts:{
            image:"请填写图片直链",
            link:"请填写网址链接",
        },
        element: document.getElementById('text'),
    });
    
    function mathjax(editor){
    editor.codemirror.doc.replaceSelection('[player url="视频文件直链" image="视频封面图片链接"]'); 
            }
    function autosave(editor){
          <?php if(bgOptions::getInstance()::get_option( 'beargallery' )['EditorsAutosave'][0] == '11'||bgOptions::getInstance()::get_option( 'beargallery' )['EditorsAutosave'][1] == '11'):?>
            //$("textarea:eq(1)").bind("input propertychange", autosaves); //废弃方法
        setInterval(function(){
         var submitted = false, form = $('form[name=write_post],form[name=write_page]').submit(function () {
        submitted = true;
    }), formAction = form.attr('action'),
        idInput = $('input[name=cid]'),
        cid = idInput.val(),
        draft = $('input[name=draft]'),
        draftId = draft.length > 0 ? draft.val() : 0,
        btnSave = $('#btn-save').removeAttr('name').removeAttr('value'),
        btnSubmit = $('#btn-submit').removeAttr('name').removeAttr('value'),
        btnPreview = $('#btn-preview'),
        //doAction = $('<input type="hidden" name="do" value="publish" />').appendTo(form),
        locked = false,
        changed = false,
        autoSave = $('<span id="auto-save-message" class="left"></span>').prependTo('.submit'),
        lastSaveTime = null;
        var data = new FormData(form.get(0));
        data.set("text",editor.value());
        var localcid = "<?php echo $cid;?>";
if(localcid == '' && localStorage.getItem('autosavecid') && localStorage.getItem('autosavecid') !== ''){
data.set("cid",localStorage.getItem('autosavecid'));
}
else{
    data.set("cid",localcid);
}
     
        function callback(o) {
toastr.success('已自动保存<br>保存时间:'+o.time);   
localStorage.setItem('autosavecid',o.cid);
$('input[name=cid]').val(o.cid);
        }
                data.append('do', 'save');

            
  $.ajax({
                url: formAction,
                processData: false,
                contentType: false,
                type: 'POST',
                data: data,
                success: callback
            });    
   },15000); 
   <?php endif; ?>
}
            
                setTimeout(function() {
                var buttons = $(".autosaves");
                buttons.click();
            }, 2000);
            

            setTimeout(function() {
                var button = $(".insertper");
                button.click();
            }, 2000);
        function insertper (editor) {
        
  $('#file-list').on('mousedown', '.insert', function(){
      var s=$(this).parents("li").data('url');
   if(s.match(/\.(jpg|jepg|png|ico|bmp|gif|tiff|webp)/i)){
        var fileurl="!["+$(this).text()+"]("+$(this).parents("li").data('url')+")\n";
   }
   else{
       var fileurl="["+$(this).text()+"]("+$(this).parents("li").data('url')+")\n";
   }
                 editor.codemirror.doc.replaceSelection(fileurl); 
});
    }

             function attachInsertEvent (editor) {
                 function getData() {
                    $.ajax({
                        type: "GET",
                        url: "<?php self::getApiFunc("getAttachFile"); ?>",
                        data: {
                            "cid": "<?php echo $cid; ?>",
                             "url": "<?php echo $type; ?>",
                        },
                        dateType: "json",
                        success: function(json) {
                            json = JSON.parse(json);
                            content(json.list);
                        },
                        complete: function() {
                        },
                        error: function() {
                            alert("数据获取错误");
                        }
                    });
                }
                getData();
                function content(list) {
                    var filename = " ";
                    var fileurl = " ";
                    for(var i in list) {
                        if(list[i]['type'] == 'img'){
                        fileurl += '![' + list[i]['title'] + ']('+ list[i]['url'] +')\n'
}
                        if(list[i]['type'] == 'other'){
                        fileurl += '[' + list[i]['title'] + ']('+ list[i]['url'] +')\n'
}
}
editor.codemirror.doc.replaceSelection(fileurl);
}
    }
    
    function copyupload(editor){
 $(document).on('paste',function(event){
                    let defaultValue = editor.codemirror.doc.getValue();
                        event = event.originalEvent;
                        var cbd = event.clipboardData;
                        var ua = window.navigator.userAgent;
                        var uploadURL = '<?php Helper::security()->index('/action/upload?cid=CID'); ?>';
                        
                        if (!(event.clipboardData && event.clipboardData.items)) {
                            return;
                        }

                        if (cbd.items && cbd.items.length === 2 && cbd.items[0].kind === "string" && cbd.items[1].kind === "file" &&
                            cbd.types && cbd.types.length === 2 && cbd.types[0] === "text/plain" && cbd.types[1] === "Files" &&
                            ua.match(/Macintosh/i) && Number(ua.match(/Chrome\/(\d{2})/i)[1]) < 49){
                            return;
                        }

                        var itemLength = cbd.items.length;
       
                        if (itemLength == 0) {
                            return;
                        }

                        if (itemLength == 1 && cbd.items[0].kind == 'string') {
                            return;
                        }

                        if ((itemLength == 1 && cbd.items[0].kind == 'file')
                                || itemLength > 1
                            ) {
                            for (var i = 0; i < cbd.items.length; i++) {
                                var item = cbd.items[i];

                                if(item.kind == "file") {
                                    var blob = item.getAsFile();
                                    if (blob.size === 0) {
                                        return;
                                    }
                                    var ext = blob.name.substring(blob.name.lastIndexOf(".")+1);
                                    var formData = new FormData();
                                    formData.append('blob', blob, Math.floor(new Date().getTime() / 1000) + '.' + ext);
                                    var uploadFailText = '文件上传失败(' + blob.name + ')'
                                    toastr.success('检测到正在复制粘贴图片，熊宝正在努力上传中~~');
                                    $.ajax({
                                        method: 'post',
                                        url: uploadURL.replace('CID', $('input[name="cid"]').val()),
                                        data: formData,
                                        contentType: false,
                                        processData: false,
                                        success: function(data) {
                                
                                            if (data[0]) {
                                       
                                                var filehz = data[0].substring(data[0].lastIndexOf(".")+1);
                                                switch(filehz){
                                                 case 'gif':
                                                 editor.codemirror.doc.replaceSelection('![](' + data[0] + ')\n');
                                                 break;
                                                 case 'jpg':
                                                 editor.codemirror.doc.replaceSelection('![](' + data[0] + ')\n');
                                                 break;
                                                 case 'jpeg':
                                                 editor.codemirror.doc.replaceSelection('![](' + data[0] + ')\n');
                                                 break;
                                                 case 'gif':
                                                 editor.codemirror.doc.replaceSelection('![](' + data[0] + ')\n');
                                                 break;
                                                 case 'png':
                                                 editor.codemirror.doc.replaceSelection('![](' + data[0] + ')\n');
                                                 break;
                                                 case 'tiff':
                                                 editor.codemirror.doc.replaceSelection('![](' + data[0] + ')\n');
                                                 break;
                                                 case 'bmp':
                                                 editor.codemirror.doc.replaceSelection('![](' + data[0] + ')\n');
                                                 break;
                                                 case 'svg':
                                                 editor.codemirror.doc.replaceSelection('![](' + data[0] + ')\n');
                                                 break;
                                                 case 'ico':
                                                 editor.codemirror.doc.replaceSelection('![](' + data[0] + ')\n');
                                                 break;
                                                 default:
                                                 editor.codemirror.doc.replaceSelection('['+data[1]['cid']+']['+data[1]['cid']+']\n['+data[1]['cid']+']: ' + data[0]+'\n');
                                                }
                                            
                                            } else {
                                               editor.codemirror.doc.replaceSelection(uploadFailText);
                                            }
                                         
                                        },
                                        error: function() {
                                            editor.codemirror.doc.replaceSelection(uploadFailText);
                                        }
                                    });
                                }
                            }

                        }

			});
       }
       
              setTimeout(function() {
                var button = $(".copyupload");
                button.click();
            }, 2000);
            
            
    
            });
            
            
            $(document).ready(function() {
           $(".copyupload").attr("style","display:none;");
               $(".insertper").attr("style","display:none;");
               $(".autosaves").attr("style","display:none;");
               localStorage.setItem('autosavecid','');
               $("#wmd-button-bar").attr("style","display:none;");
        
       });
     </script>
     <?php
     }
    public static function send_request($url, $postdata,$sendtype,$header = 'Content-type: application/x-www-form-urlencoded') {
     if($postdata){
         if(is_array($postdata)){
    $data = http_build_query($postdata);
         }
         else{
           $data = $postdata;
         }
    $options    = array(
        'http' => array(
            'method'  => $sendtype,
            'header'  => $header,
            'content' => $data,
            'timeout' => 5
        )
    );
     }
     else{
     $options    = array(
        'http' => array(
            'method'  => $sendtype,
            'header'  => "Content-type: application/x-www-form-urlencoded",
            'timeout' => 5
        )
    );    
     }
    $context = stream_context_create($options);
    $result    = file_get_contents($url, false, $context);
    if($http_response_header[0] !== 'HTTP/1.1 200 OK'){
        $result = array(
            "result" => "success",
            "reason" => "request fail"
        );
        return json_encode($result);
    }else{
        return $result;
    }
}

    public static function enqueue_style($header=null, $old=null)
    {
        if ($old!=null) $header = $old;
        return CSF::get_enqueue_style($header);
    }

    public static function enqueue_script($footer=null)
    {
        return CSF::get_enqueue_script($footer);
    }

    public static function config(Form $form)
    {

    }

    public static function personalConfig(Form $form)
    {
    }
    
    

    public static function configHandle($origin_config, $is_init)
    {
        return true;
    }
    
  
     //腾讯云COS
    
    /**
   * @description: 上传文件处理函数
   * @param {array} $file
   * @return {*}
   */
  public static function uploadHandle(array $file)
  {
   $boptions = get_option('beargallery');
        $opt = Helper::options();
        
        //文件名是否空
        if (empty($file['name'])) {
            return false;
        }
        //文件名安全性检测
        $ext = self::getSafeName($file['name']);
        if (!self::getTypechoMethod("checkFileType", $ext) || \Typecho\Common::isAppEngine()) {
            return false;
        }
        //判断格式
        $ext = strtolower($ext);
        
        if (empty($date)) {
            $date = new \Typecho\Date($opt->gmtTime);
        }
        $filePath = self::getUploadDir()  . $date->year . '/' . $date->month;

        if (!isset($file['tmp_name'])) {
            return false;
        }
        
        //创建上传目录
        if (!is_dir($filePath)) {

            if (!self::getTypechoMethod("makeUploadDir", $filePath)) {

                return false;
            }
        }

        $fileName = sprintf('%u', crc32(uniqid())) . '.' . $ext;
        $path = $filePath . '/' . $fileName;
        
        if (isset($file['tmp_name'])) {
            $content = file_get_contents($file['tmp_name']);
        } else if (isset($file['bytes'])) {
            $content = $file['bytes'];
        } else {
            return false;
        }
        
        
        //判断是否开启tinypng
        if($boptions['Tinypng_open'] == true){
        
        $isimage = in_array($ext, array("jpg", "png", "jpeg"));

        
        
        if ($isimage) {
            $apikey = trim(bgOptions::getInstance()::get_option( 'beargallery' )['Tinypng_apikey']);
            \Tinify\setKey($apikey);
            $source = \Tinify\fromFile($file['tmp_name']);
        }
        
        if (!$isimage) {
            if (isset($file['tmp_name'])) {
                if (!@move_uploaded_file($file['tmp_name'], $path)) {
                    return false;
                }
            } else if (isset($file['bytes'])) {
                if (!file_put_contents($path, $file['bytes'])) {
                    return false;
                }
            } else {
                return false;
            }
        }
        if ($isimage) {
            $source->toFile($path);
            $file['size'] = filesize($path);
        } else if (!isset($file['size'])) {
            $file['size'] = filesize($path);
    }         
    $file['tmp_name'] = $path;
    }
    $uploadfile = self::getUploadFile($file);

    /* 上传到COS */
    #初始化COS
    $cosClient = self::CosInit();
    try {
      #判断是否存在重名文件，重名则重新生成
      $times = 10;
      while ($times > 0 && self::doesObjectExist($path)) {
        $fileName = sprintf('%u', crc32(uniqid($times--))) . '.' . $ext;
        $path = $fileDir . '/' . $fileName;
      }

      $cosClient->upload(
        $bucket = $boptions['bucket_name'],
        $key = $path,
        $body = fopen($uploadfile, 'rb'),
        $options = array(
          "ACL" => 'public-read',
          'CacheControl' => 'private'
        )
      );
    } catch (Exception $e) {
      echo "$e\n";
      return false;
    }

    if (!isset($file['size'])) {
      $fileInfo = $cosClient->headObject(array('Bucket' => $boptions['bucket_name'], 'Key' => $path))->toArray();
      $file['size'] = $fileInfo['ContentLength'];
    }
    
    if ($boptions['bucket_localsave'] == true && self::makeUploadDir($filePath)) {
      #本地存储一份
      @move_uploaded_file($uploadfile, $path);
    }

    #返回相对存储路径
    return array(
      'name' => $file['name'],
      'path' => $path,
      'size' => $file['size'],
      'type' => $ext,
      'mime' => @\Typecho\Common::mimeContentType($path)
    );
  }

  /**
   * @description: 修改文件处理函数
   * @param {array} $content 旧文件
   * @param {array} $file 新文件
   * @return {*}
   */
  public static function modifyHandle(array $content, array $file)
  {
    if (empty($file['name'])) {
      return false;
    }
if ($opts['bucket_other'] !== '') {
      $bucket_other = $opts['bucket_other'];
    }
    #获取扩展名
    $ext = self::getSafeName($file['name']);
    #判定是否是允许的文件类型
    if ($content['attachment']->type != $ext || \Typecho\Common::isAppEngine()) {
      return false;
    }
    #获取设置参数
    $opt = get_option('beargallery');
    #获取文件路径
    $path = $content['attachment']->path;
    #获得上传文件
    $uploadfile = self::getUploadFile($file);
    #如果没有临时文件，则退出
    if (!isset($uploadfile)) {
      return false;
    }

    /* 上传到COS */
    $cosClient = self::CosInit();
    try {
      $cosClient->upload(
        $bucket = $opt['bucket'],
        $key = $path,
        $body = fopen($uploadfile, 'rb'),
        $options = array(
          "ACL" => 'public-read',
          'CacheControl' => 'private'
        )
      );
    } catch (Exception $e) {
      echo "$e\n";
      return false;
    }

    if (!isset($file['size'])) {
      $fileInfo = $cosClient->headObject(array('Bucket' => $opt['bucket_name'], 'Key' => $path))->toArray();
      $file['size'] = $fileInfo['ContentLength'];
    }

    if ($opt['bucket_localsave'] == true) {
      #本地存储一份
      @move_uploaded_file($uploadfile, $path);
    }


    #返回相对存储路径
    return array(
      'name' => $content['attachment']->name,
      'path' => $content['attachment']->path,
      'size' => $file['size'],
      'type' => $content['attachment']->type,
      'mime' => $content['attachment']->mime
    );
  }

  /**
   * @description: 删除文件
   * @param {array} $content
   * @return {*}
   */
  public static function deleteHandle(array $content)
  {
    #获取设置参数
    $opt = get_option('beargallery');

    #删除本地文件
    if ($opt['bucket_localsave'] == true && $opt['bucket_localdelete']== true) {
      @unlink($content['attachment']->path);
    }
    # 开启同步删除COS文件则执行
    if ($opt['bucket_sync']==true) {
      #初始化COS
      $cosClient = self::CosInit();
      try {
        $result = $cosClient->deleteObject(array(
          #bucket的命名规则为{name}-{appid} ，此处填写的存储桶名称必须为此格式
          'Bucket' => $opt['bucket_name'],
          'Key' => $content['attachment']->path
        ));
      } catch (Exception $e) {
        echo "$e\n";
        return false;
      }
    }
    return true;
  }

  /**
   * @description: 获取对象访问Url
   * @param {array} $content
   * @return {*}
   */
  public static function attachmentHandle(array $content)
  {
      $options = get_option('beargallery');
         if (preg_match('/\.(jpg|jepg|png|ico|bmp|gif|tiff|webp)/i', $content['attachment']->path)) {
        $diy_style  = $options['bucket_other'];
        }
        return \Typecho\Common::url($content['attachment']->path, self::tx_getDomain()).$diy_style;
        
  }

  /**
   * @description: 获取对象访问Url
   * @param array $content
   * @return {*}
   */
  public static function attachmentDataHandle(array $content)
  {
    return file_get_contents(self::attachmentHandle($content));
  }

  /**
   * @description: 更新正文中的对象网址钩子
   * @return {*}
   */
  public static function Widget_Archive_beforeRender()
  {
    ob_start();
    $html = ob_get_contents();
    ob_clean();
    echo self::beforeRender($html);
    ob_end_flush();
  }

  /**
   * @description: 更新正文中的对象网址（获得新的sign）
   * @param string $text 页面html
   * @return string
   */
  public static function beforeRender($text)
  {
    $opt = get_option('beargallery');
    $cosClient = self::CosInit();
    if ($opt['bucket_sign'] == 1) {
      return preg_replace_callback(
        '/https?:\/\/[-A-Za-z0-9+&@#\/\%?=~_|!:,.;]+[-A-Za-z0-9+&@#\/\%=~_|]/i',
        function ($matches) use ($opt, $cosClient) {
          $url = $matches[0];
          if (strpos($url, self::getDomain()) !== false) {
            $expTime = explode('q-key-time%3D', $url);
            if (sizeof($expTime) > 1) {
              @$expTime = explode('%26q', $expTime[1])[0];
              @$expTime = explode('%3B', $expTime)[1];
              #未过期，不更新
              if ($expTime > time()) {
                return $url;
              }
            }
            $path = str_replace(self::getDomain(), '', $url);
            $url = $cosClient->getObjectUrl($opt['bucket_name'], explode('?', $path)[0], '+10 minutes');
          }
          return $url;
        },
        $text
      );
    }
    return $text;
  }

  /**
   * @description: COS初始化
   * @param object $options 设置信息
   * @return {*}
   */
  public static function CosInit($options = '')
  {
    try {
      if (!$options) {
        $options = get_option('beargallery');
      }
    } catch (Exception $e) {
    }
    
      require_once 'phar://' . __DIR__ . '/modules/tencent_cos/phar/cos-sdk-v5-7.phar/vendor/autoload.php';
    
   if(empty($options['bucket_secret_id'])||empty($options['bucket_secret_key'])){
       return new \Qcloud\Cos\Client(array(
      'region' => 'ap-beijing',
      'schema' => 'https', #协议头部，默认为http
      'credentials' => array(
        'secretId' => 'fix',
        'secretKey' => 'fix'
      )
    ));
   }
   else{
    return new \Qcloud\Cos\Client(array(
      'region' => $options['bucket_region'],
      'schema' => 'https', #协议头部，默认为http
      'credentials' => array(
        'secretId' => $options['bucket_secret_id'],
        'secretKey' => $options['bucket_secret_key']
      )
    ));
   }
  }

  /**
   * @description: 判断存储桶是否存在
   * @param {*} $opt
   * @return {*}
   */
  public static function doesBucketExist($opt)
  {
    #初始化COS
    $cosClient = self::CosInit($opt);
    try {
      $result = $cosClient->doesBucketExist(
        $opt['bucket_name']
      );
      if (!$result) {
        return false;
      }
    } catch (Exception $e) {
      return false;
    }
    return true;
  }

  /**
   * @description: 判断对象是否已存在
   * @param {*} $key
   * @return {*}
   */
  public static function doesObjectExist($key)
  {
    #获取设置参数
    $opt = get_option('beargallery');
    #初始化COS
    $cosClient = self::CosInit();
    try {
      $result = $cosClient->doesObjectExist(
        $opt['bucket_name'],
        $key
      );
      if ($result) {
        return true;
      }
    } catch (Exception $e) {
      return true;
    }
    return false;
  }
  
  
  
  
  //阿里处理方法
  
  /**
     * 上传文件处理函数
     *
     * @access public
     * @param array $file 上传的文件
     * @return mixed
     */
    public static function ali_uploadHandle($file) {
         $options = get_option('beargallery');
        $opt = Helper::options();
        
        //文件名是否空
        if (empty($file['name'])) {
            return false;
        }
        //文件名安全性检测
        $ext = self::getSafeName($file['name']);
        if (!self::getTypechoMethod("checkFileType", $ext) || \Typecho\Common::isAppEngine()) {
            return false;
        }
        //判断格式
        $ext = strtolower($ext);
        
        if (empty($date)) {
            $date = new \Typecho\Date($opt->gmtTime);
        }
        $filePath = self::ali_getUploadDir()  . $date->year . '/' . $date->month;

        if (!isset($file['tmp_name'])) {
            return false;
        }
        
        //创建上传目录
        if (!is_dir($filePath)) {

            if (!self::getTypechoMethod("makeUploadDir", $filePath)) {

                return false;
            }
        }

        $fileName = sprintf('%u', crc32(uniqid())) . '.' . $ext;
        $path = $filePath . '/' . $fileName;
        
        if (isset($file['tmp_name'])) {
            $content = file_get_contents($file['tmp_name']);
        } else if (isset($file['bytes'])) {
            $content = $file['bytes'];
        } else {
            return false;
        }
        
        
        //判断是否开启tinypng
        if($options['Tinypng_open'] == true){
        
        $isimage = in_array($ext, array("jpg", "png", "jpeg"));

        
        
        if ($isimage) {
            $apikey = trim(bgOptions::getInstance()::get_option( 'beargallery' )['Tinypng_apikey']);
            \Tinify\setKey($apikey);
            $source = \Tinify\fromFile($file['tmp_name']);
        }
        
        if (!$isimage) {
            if (isset($file['tmp_name'])) {
                if (!@move_uploaded_file($file['tmp_name'], $path)) {
                    return false;
                }
            } else if (isset($file['bytes'])) {
                if (!file_put_contents($path, $file['bytes'])) {
                    return false;
                }
            } else {
                return false;
            }
        }
        if ($isimage) {
            $source->toFile($path);
            $file['size'] = filesize($path);
        } else if (!isset($file['size'])) {
            $file['size'] = filesize($path);
    }         
    $file['tmp_name'] = $path;
    }
    


      
        $uploadfile = self::getUploadFile($file);

        if (!isset($uploadfile)) {
            return false;
        }

        $oss_client = self::ossInit();
        try {
            $oss_client->doesBucketExist($options['ali_bucket_name']);
        } catch (Exception $e) {
            return false;
        }
       try {
            $oss_client->putObject($options['ali_bucket_name'], $path, $content);
            
        } catch (Exception $e) {
            return false;
        }

 if ($options['ali_bucket_localsave'] == true && self::makeUploadDir($filePath)) {
      #本地存储一份
      @move_uploaded_file($uploadfile, $path);
    }
    else{
        @unlink($path);
    }
    
             return array(
            'name' => $file['name'],
            'path' => $path,
            'size' => $file['size'],
            'type' => $ext,
            'mime' => @\Typecho\Common::mimeContentType($path)
        );
            
        
    }

    /**
     * 修改文件处理函数
     *
     * @access public
     * @param array $content 老文件
     * @param array $file 新上传的文件
     * @return mixed
     */
    public static function ali_modifyHandle($content, $file)
    {
        
         $options = get_option('beargallery');
       if (isset($file['tmp_name'])) {
            $contents = file_get_contents($file['tmp_name']);
        } else if (isset($file['bytes'])) {
            $contents = $file['bytes'];
        } else {
            return false;
        }
        if (empty($file['name'])) {
            return false;
        }

        $ext = self::getSafeName($file['name']);

       if (!\Widget\Upload::checkFileType($ext) || \Typecho\Common::isAppEngine()) {
            return false;
        }

       $uploadfile = self::getUploadFile($file);

        if (!isset($uploadfile)) {
            return false;
        }

        $oss_client = self::ossInit();
         
         try{
            $ali_response = $oss_client->putObject($options['ali_bucket_name'], $content['attachment']->path, $contents);
        } catch(OssException $e) {
             
            return false;
        }
        
      if ($options['ali_bucket_localsave'] == true) {
      #本地存储一份
      @move_uploaded_file($uploadfile, $content['attachment']->path);
    }
    

        return array(
            'name' => $content['attachment']->name,
            'path' => $content['attachment']->path,
            'size' => intval($ali_response['oss-requestheaders']['Content-Length']),
            'type' => $ext,
            'mime' => $ali_response['oss-requestheaders']['Content-Type']
        );
    }

    /**
     * 删除文件
     *
     * @access public
     * @param array $content 文件相关信息
     * @return string
     */
    public static function ali_deleteHandle(array $content)
    {
         $options = get_option('beargallery');
        $oss_client = self::ossInit();
#删除本地文件
    if ($options['ali_bucket_localsave'] == true && $options['ali_bucket_localdelete']== true) {
      @unlink($content['attachment']->path);
    }
    if ($options['ali_bucket_sync']==true) {
        try {
            $oss_client->deleteObject($options['bd_bucket_name'],$content['attachment']->path);
            return true;
        } catch (Exception $e) {
            return false;
        } 
    }
    return true;
    }

    /**
     * 获取实际文件绝对访问路径
     *
     * @access public
     * @param array $content 文件相关信息
     * @return string
     */
    public static function ali_attachmentHandle(array $content) {
         $options = get_option('beargallery');
         if (preg_match('/\.(jpg|jepg|png|ico|bmp|gif|tiff|webp)/i', $content['attachment']->path)) {
        $diy_style  = $options['ali_bucket_other'];
        }
        return \Typecho\Common::url($content['attachment']->path, self::ali_getDomain()).$diy_style;
    
       
    }

    /**
     * 获取实际文件数据
     *
     * @access public
     * @param array $content
     * @return string
     */
    public static function ali_attachmentDataHandle(array $content)
    {
        return file_get_contents(self::ali_attachmentHandle($content));
    }
    
    /**
   * @description: OSS初始化
   * @param object $options 设置信息
   * @return {*}
   */
  public static function ossInit($options = '')
  {
      
    try {
      if (!$options) {
        $options = get_option('beargallery');
      }
    } catch (Exception $e) {
    }
    $end_point   = 'http://' . (($options['ali_bucket_region'] === "other") ?
                $options['ali_bucket_other_region'] :
                $options['ali_bucket_region'] . $options['ali_bucket_connect_type']);
   if(empty($options['ali_bucket_accesskey_id'])||empty($options['ali_bucket_accesskey_secret'])){
       return new OssClient('fix', 'fix', $end_point);
   }
   else{
    return new OssClient($options['ali_bucket_accesskey_id'], $options['ali_bucket_accesskey_secret'], $end_point);
   }
  }
  
  
  //七牛云Kodo
  
  public static function qiniu_uploadHandle($file)
    {
        $options = get_option('beargallery');
        $opt = Helper::options();
        
        //文件名是否空
        if (empty($file['name'])) {
            return false;
        }
        //文件名安全性检测
        $ext = self::getSafeName($file['name']);
        if (!self::getTypechoMethod("checkFileType", $ext) || \Typecho\Common::isAppEngine()) {
            return false;
        }
        //判断格式
        $ext = strtolower($ext);
        
        if (empty($date)) {
            $date = new \Typecho\Date($opt->gmtTime);
        }
        $filePath = self::qiniu_getUploadDir()  . $date->year . '/' . $date->month;

        if (!isset($file['tmp_name'])) {
            return false;
        }
        
        //创建上传目录
        if (!is_dir($filePath)) {

            if (!self::getTypechoMethod("makeUploadDir", $filePath)) {

                return false;
            }
        }

        $fileName = sprintf('%u', crc32(uniqid())) . '.' . $ext;
        $path = $filePath . '/' . $fileName;
        
        if (isset($file['tmp_name'])) {
            $content = file_get_contents($file['tmp_name']);
        } else if (isset($file['bytes'])) {
            $content = $file['bytes'];
        } else {
            return false;
        }
        
        
        //判断是否开启tinypng
        if($options['Tinypng_open'] == true){
        
        $isimage = in_array($ext, array("jpg", "png", "jpeg"));

        
        
        if ($isimage) {
            $apikey = trim(bgOptions::getInstance()::get_option( 'beargallery' )['Tinypng_apikey']);
            \Tinify\setKey($apikey);
            $source = \Tinify\fromFile($file['tmp_name']);
        }
        
        if (!$isimage) {
            if (isset($file['tmp_name'])) {
                if (!@move_uploaded_file($file['tmp_name'], $path)) {
                    return false;
                }
            } else if (isset($file['bytes'])) {
                if (!file_put_contents($path, $file['bytes'])) {
                    return false;
                }
            } else {
                return false;
            }
        }
        if ($isimage) {
            $source->toFile($path);
            $file['size'] = filesize($path);
        } else if (!isset($file['size'])) {
            $file['size'] = filesize($path);
    }         
    $file['tmp_name'] = $path;
    }
        $uploadfile = self::getUploadFile($file);

        $uploadMgr = new UploadManager();
        list($ret, $err) = $uploadMgr->putFile(self::qiniu_getAuthToken(), ltrim($path, "/"), $uploadfile);
        if ($err !== null) {
            return false;
        }
        if (!isset($file['size'])) {
            $file['size'] = filesize($uploadfile);
        }
        
    if ($options['qiniu_bucket_localsave'] == true && self::makeUploadDir($filePath)) {
      #本地存储一份
      @move_uploaded_file($uploadfile, $path);
    }
    else{
        @unlink($path);
    }
        return array(
            'name' => $file['name'],
            'path' => $path,
            'size' => $file['size'],
            'type' => $ext,
            'mime' => @\Typecho\Common::mimeContentType($path)
        );
    }

    /**
     * 七牛修改文件
     *
     * @access public
     * @param array $content 老文件
     * @param array $file 新上传的文件
     * @return mixed
     */
    public static function qiniu_modifyHandle($content, $file)
    {
        if (empty($file['name'])) {
            return false;
        }

        //获取扩展名
        $ext = self::getSafeName($file['name']);
        //判定是否是允许的文件类型
        if ($content['attachment']->type != $ext || \Typecho\Common::isAppEngine()) {
            return false;
        }
        //获取文件路径
        $path = $content['attachment']->path;
        //获得上传文件
        $uploadfile = self::getUploadFile($file);
        //如果没有临时文件，则退出
        if (!isset($uploadfile)) {
            return false;
        }

        $uploadMgr = new UploadManager();
        $kodo_path = ltrim($path, "/");
        list($ret, $err) = $uploadMgr->putFile(self::qiniu_getAuthToken($kodo_path), $kodo_path, $uploadfile, null,
            'application/octet-stream',
            false,
            $resumeFile,
            $version,
            $partSize);
        if ($err !== null) {
            return false;
        }

        if (!isset($file['size'])) {
            $file['size'] = filesize($uploadfile);
        }

if ($options['qiniu_bucket_localsave'] == true) {
      #本地存储一份
      @move_uploaded_file($uploadfile, $content['attachment']->path);
    }
    
        //返回相对存储路径
        return array(
            'name' => $content['attachment']->name,
            'path' => $content['attachment']->path,
            'size' => $file['size'],
            'type' => $content['attachment']->type,
            'mime' => $content['attachment']->mime
        );
    }

    /**
     * 七牛删除文件
     *
     * @access public
     * @param array $content 文件相关信息
     * @return string
     */
    public static function qiniu_deleteHandle(array $content)
    {
        $options = get_option('beargallery');
        $bucket = $options['qiniu_bucket_name'];
        #删除本地文件
    if ($options['qiniu_bucket_localsave'] == true && $options['qiniu_bucket_localdelete']== true) {
      @unlink($content['attachment']->path);
    }
    if ($options['qiniu_bucket_sync']==true) {
        $bucketManager = new BucketManager(self::qiniu_getAuthToken());
        $err = $bucketManager->delete($bucket, ltrim($content['attachment']->path, "/"));
        if ($err != null) {
            return false;
        }
    }
        return true;
    }

     /**
     * 七牛获取域名
     *
     * @access public
     * @param array $content 文件相关信息
     * @return string
     */
    public static function qiniu_attachmentHandle(array $content)
    {
        $options = get_option('beargallery');
        if (preg_match('/\.(jpg|jepg|png|ico|bmp|gif|tiff|webp)/i', $content['attachment']->path)) {
        $diy_style  = $options['qiniu_bucket_other'];
        }
        return \Typecho\Common::url($content['attachment']->path, $options['qiniu_bucket_domain']).$diy_style;
    }

    /**
     * 七牛获取实际文件数据
     *
     * @access public
     * @param array $content
     * @return string
     */
    public static function qiniu_attachmentDataHandle($content)
    {
        $options = get_option('beargallery');
        $bucket = $options['qiniu_bucket_name'];
        $bucketManager = new BucketManager(self::qiniu_getAuthToken());
        list($fileInfo, $err) = $bucketManager->stat($bucket, ltrim($content['attachment']->path, "/"));
        if ($err != null) {
            var_dump($err);
            return false;
        }
        return $fileInfo;
    }
    
    public static function qiniu_getAuth()
    {
        $options = get_option('beargallery');
        $accessKey = trim($options['qiniu_bucket_accesskey']);
        $secretKey = trim($options['qiniu_bucket_secretKey']);
        return new Auth($accessKey, $secretKey);
    }
    public static function qiniu_getAuthToken($key = null)
    {
        $options = get_option('beargallery');
return self::qiniu_getAuth()->uploadToken($options['qiniu_bucket_name'], $key);
}
    
  
  //又拍云
  
  
  public static function upyun_uploadHandle($file)
    {
          $options = get_option('beargallery');
        $opt = Helper::options();
        
        //文件名是否空
        if (empty($file['name'])) {
            return false;
        }
        //文件名安全性检测
        $ext = self::getSafeName($file['name']);
        if (!self::getTypechoMethod("checkFileType", $ext) || \Typecho\Common::isAppEngine()) {
            return false;
        }
        //判断格式
        $ext = strtolower($ext);
        
        if (empty($date)) {
            $date = new \Typecho\Date($opt->gmtTime);
        }
        $filePath = self::upyun_getUploadDir()  . $date->year . '/' . $date->month;

        if (!isset($file['tmp_name'])) {
            return false;
        }
        
        //创建上传目录
        if (!is_dir($filePath)) {

            if (!self::getTypechoMethod("makeUploadDir", $filePath)) {

                return false;
            }
        }

        $fileName = sprintf('%u', crc32(uniqid())) . '.' . $ext;
        $path = $filePath . '/' . $fileName;
        
        if (isset($file['tmp_name'])) {
            $content = file_get_contents($file['tmp_name']);
        } else if (isset($file['bytes'])) {
            $content = $file['bytes'];
        } else {
            return false;
        }
        
        
        //判断是否开启tinypng
        if($options['Tinypng_open'] == true){
        
        $isimage = in_array($ext, array("jpg", "png", "jpeg"));

        
        
        if ($isimage) {
            $apikey = trim(bgOptions::getInstance()::get_option( 'beargallery' )['Tinypng_apikey']);
            \Tinify\setKey($apikey);
            $source = \Tinify\fromFile($file['tmp_name']);
        }
        
        if (!$isimage) {
            if (isset($file['tmp_name'])) {
                if (!@move_uploaded_file($file['tmp_name'], $path)) {
                    return false;
                }
            } else if (isset($file['bytes'])) {
                if (!file_put_contents($path, $file['bytes'])) {
                    return false;
                }
            } else {
                return false;
            }
        }
        if ($isimage) {
            $source->toFile($path);
            $file['size'] = filesize($path);
        } else if (!isset($file['size'])) {
            $file['size'] = filesize($path);
    }         
    $file['tmp_name'] = $path;
    }

        $uploadfile = self::getUploadFile($file);

        if (!isset($uploadfile)) {
            return false;
        } else {
            //上传文件
            $upyun = self::upyunInit();
            $fh = fopen($uploadfile, 'rb');
            $upyun->write($path, $fh, TRUE);
            if(is_resource($fh)) {
                fclose($fh);
            }
        }

        if (!isset($file['size'])) {
            $fileInfo = $upyun->getFileInfo($path);
            $file['size'] = $fileInfo['x-upyun-file-size'];
        }
if ($options['upyun_bucket_localsave'] == true && self::makeUploadDir($filePath)) {
      #本地存储一份
      @move_uploaded_file($uploadfile, $path);
    }
    else{
       @unlink($path);
    }
        //返回相对存储路径
        return array(
            'name' => $file['name'],
            'path' => $path,
            'size' => $file['size'],
            'type' => $ext,
            'mime' => @\Typecho\Common::mimeContentType($path)
        );
    }

    /**
     * 修改文件处理函数
     *
     * @access public
     * @param array $content 老文件
     * @param array $file 新上传的文件
     * @return mixed
     */
    public static function upyun_modifyHandle($content, $file)
    {
        $options = get_option('beargallery');
        if (empty($file['name'])) {
            return false;
        }

        //获取扩展名
        $ext = self::getSafeName($file['name']);

        if ($content['attachment']->type != $ext || \Typecho\Common::isAppEngine()) {
            return false;
        }

        //获取文件路径
        $path = $content['attachment']->path;

        $uploadfile = self::getUploadFile($file);

        if (!isset($uploadfile)) {
            return false;
        } else {
            //修改文件
            $upyun = self::upyunInit();
            $fh = fopen($uploadfile, 'rb');
            $upyun->writeFile($path, $fh, TRUE);
            fclose($fh);
        }

        if (!isset($file['size'])) {
            $fileInfo = $upyun->getFileInfo($path);
            $file['size'] = $fileInfo['x-upyun-file-size'];
        }
        
        if ($options['upyun_bucket_localsave'] == true) {
      #本地存储一份
      @move_uploaded_file($uploadfile, $content['attachment']->path);
    }
    
        //返回相对存储路径
        return array(
            'name' => $content['attachment']->name,
            'path' => $content['attachment']->path,
            'size' => $file['size'],
            'type' => $content['attachment']->type,
            'mime' => $content['attachment']->mime
        );
    }

    /**
     * 删除文件
     *
     * @access public
     * @param array $content 文件相关信息
     * @return string
     */
    public static function upyun_deleteHandle(array $content)
    {
        $options = get_option('beargallery');
        $upyun = self::upyunInit();
        $path = $content['attachment']->path;
        
        #删除本地文件
    if ($options['upyun_bucket_localsave'] == true && $options['upyun_bucket_localdelete']== true) {
      @unlink($content['attachment']->path);
    }
    if ($options['upyun_bucket_sync']==true) {
        $upyun->delete($path);
    }
    return true;
        
        
    }

    /**
     * 获取实际文件绝对访问路径
     *
     * @access public
     * @param array $content 文件相关信息
     * @return string
     */
    public static function upyun_attachmentHandle(array $content)
    {
        $options = get_option('beargallery');
        if (preg_match('/\.(jpg|jepg|png|ico|bmp|gif|tiff|webp)/i', $content['attachment']->path)) {
        $diy_style  = $options['upyun_bucket_other'];
        }
        return \Typecho\Common::url($content['attachment']->path, $options['upyun_bucket_domain']).$diy_style;
    }

    /**
     * 获取实际文件数据
     *
     * @access public
     * @param array $content
     * @return string
     */
    public static function upyun_attachmentDataHandle(array $content)
    {
        $upyun = self::upyunInit();
        return $upyun->getFileInfo($content['attachment']->path);
    }


   
    public static function upyunInit()
    {
        $options = get_option('beargallery');
        return new Upyun(new Config($options['upyun_bucket_name'], $options['upyun_bucket_actionUser'], $options['upyun_bucket_actionKey']));
    }
    
    
    //华为云OBS
    public static function hw_uploadHandle($file)
    {
        $options = get_option('beargallery');
        $opt = Helper::options();
        if (empty($file['name'])) {
            return false;
        }
        //文件名安全性检测
        $ext = self::getSafeName($file['name']);
        if (!self::getTypechoMethod("checkFileType", $ext) || \Typecho\Common::isAppEngine()) {
            return false;
        }
        //判断格式
        $ext = strtolower($ext);
        
        if (empty($date)) {
            $date = new \Typecho\Date($opt->gmtTime);
        }
        $filePath = self::hw_getUploadDir()  . $date->year . '/' . $date->month;

        if (!isset($file['tmp_name'])) {
            return false;
        }
        
        //创建上传目录
        if (!is_dir($filePath)) {

            if (!self::getTypechoMethod("makeUploadDir", $filePath)) {

                return false;
            }
        }

        $fileName = sprintf('%u', crc32(uniqid())) . '.' . $ext;
        $path = $filePath . '/' . $fileName;
        
        if (isset($file['tmp_name'])) {
            $content = file_get_contents($file['tmp_name']);
        } else if (isset($file['bytes'])) {
            $content = $file['bytes'];
        } else {
            return false;
        }
        
        
        //判断是否开启tinypng
        if($options['Tinypng_open'] == true){
        
        $isimage = in_array($ext, array("jpg", "png", "jpeg"));

        
        
        if ($isimage) {
            $apikey = trim(bgOptions::getInstance()::get_option( 'beargallery' )['Tinypng_apikey']);
            \Tinify\setKey($apikey);
            $source = \Tinify\fromFile($file['tmp_name']);
        }
        
        if (!$isimage) {
            if (isset($file['tmp_name'])) {
                if (!@move_uploaded_file($file['tmp_name'], $path)) {
                    return false;
                }
            } else if (isset($file['bytes'])) {
                if (!file_put_contents($path, $file['bytes'])) {
                    return false;
                }
            } else {
                return false;
            }
        }
        if ($isimage) {
            $source->toFile($path);
            $file['size'] = filesize($path);
        } else if (!isset($file['size'])) {
            $file['size'] = filesize($path);
    }         
    $file['tmp_name'] = $path;
    }
    
       

        $uploadfile = self::getUploadFile($file);

        if (!isset($uploadfile)) {
            return false;
        }

        $obsClient = self::obsInit();

        try {
            $obsClient->putObject([
                'Bucket' => $options['hw_bucket_name'],
                'Key' => $path,
                'SourceFile' => $uploadfile
            ]);
        } catch (Exception $e) {
            return false;
        } finally {
            $obsClient->close();
        }

if ($options['hw_bucket_localsave'] == true && self::makeUploadDir($filePath)) {
      #本地存储一份
      @move_uploaded_file($uploadfile, $path);
    }
    else{
        @unlink($path);
    }
    
        //返回相对存储路径
        return array(
            'name' => $file['name'],
            'path' => $path,
            'size' => $file['size'],
            'type' => $ext,
            'mime' => @\Typecho\Common::mimeContentType($path)
        );
    }


    public static function hw_modifyHandle($content, $file)
    {
        $options = get_option('beargallery');
        if (empty($file['name'])) {
            return false;
        }

        $ext = self::getSafeName($file['name']);

       if (!\Widget\Upload::checkFileType($ext) || \Typecho\Common::isAppEngine()) {
            return false;
        }

       $uploadfile = self::getUploadFile($file);

        if (!isset($uploadfile)) {
            return false;
        }

        $obsClient = self::obsInit();

        try {
            $obsClient->putObject([
                'Bucket' => $options['hw_bucket_name'],
                'Key' => $content['attachment']->path,
                'SourceFile' => $uploadfile
            ]);
        } catch (Exception $e) {
            return false;
        } finally {
            $obsClient->close();
        }
if ($options['hw_bucket_localsave'] == true) {
      #本地存储一份
      @move_uploaded_file($uploadfile, $content['attachment']->path);
    }
 
        return array(
            'name' => $content['attachment']->name,
            'path' => $content['attachment']->path,
            'size' => $file['size'],
            'type' => $content['attachment']->type,
            'mime' => $content['attachment']->mime
        );
    }


    public static function hw_deleteHandle(array $content)
    {
        $options = get_option('beargallery');
        $obsClient = self::obsInit();
if ($options['hw_bucket_localsave'] == true && $options['hw_bucket_localdelete']== true) {
      @unlink($content['attachment']->path);
    }
    if ($options['hw_bucket_sync']==true) {
        try {
            $obsClient->deleteObject([
                'Bucket' => $options['hw_bucket_name'],
                'Key' => $content['attachment']->path
            ]);
            return true;
        } catch (Exception $e) {
            return false;
        } finally {
            $obsClient->close();
        }
    }
    }

   
    public static function hw_attachmentHandle(array $content)
    {
        $options = get_option('beargallery');
         if (preg_match('/\.(jpg|jepg|png|ico|bmp|gif|tiff|webp)/i', $content['attachment']->path)) {
        $diy_style  = $options['hw_bucket_other'];
        }
        return \Typecho\Common::url($content['attachment']->path, self::hw_getDomain()).$diy_style;
    }


    public static function hw_attachmentDataHandle(array $content)
    {
        $options = get_option('beargallery');
        return file_get_contents(\Typecho\Common::url($content['attachment']->path, self::hw_getDomain()));
    }

    
    private static function obsInit()
    {
        $options = get_option('beargallery');
        return new ObsClient([
            'key' => $options['hw_bucket_accesskey_id'],
            'secret' => $options['hw_bucket_accesskey_secret'],
            'endpoint' => $options['hw_bucket_region'].'.myhuaweicloud.com',
            'socket_timeout' => 30,
            'connect_timeout' => 10
        ]);
    }
  
  
  
  public static function bd_uploadHandle($file) {
      $options = get_option('beargallery');
        $opt = Helper::options();
        
        //文件名是否空
        if (empty($file['name'])) {
            return false;
        }
        //文件名安全性检测
        $ext = self::getSafeName($file['name']);
        if (!self::getTypechoMethod("checkFileType", $ext) || \Typecho\Common::isAppEngine()) {
            return false;
        }
        //判断格式
        $ext = strtolower($ext);
        
        if (empty($date)) {
            $date = new \Typecho\Date($opt->gmtTime);
        }
        $filePath = self::bd_getUploadDir()  . $date->year . '/' . $date->month;

        if (!isset($file['tmp_name'])) {
            return false;
        }
        
        //创建上传目录
        if (!is_dir($filePath)) {

            if (!self::getTypechoMethod("makeUploadDir", $filePath)) {

                return false;
            }
        }

        $fileName = sprintf('%u', crc32(uniqid())) . '.' . $ext;
        $path = $filePath . '/' . $fileName;
        
        if (isset($file['tmp_name'])) {
            $content = file_get_contents($file['tmp_name']);
        } else if (isset($file['bytes'])) {
            $content = $file['bytes'];
        } else {
            return false;
        }
        
        
        //判断是否开启tinypng
        if($options['Tinypng_open'] == true){
        
        $isimage = in_array($ext, array("jpg", "png", "jpeg"));

        
        
        if ($isimage) {
            $apikey = trim(bgOptions::getInstance()::get_option( 'beargallery' )['Tinypng_apikey']);
            \Tinify\setKey($apikey);
            $source = \Tinify\fromFile($file['tmp_name']);
        }
        
        if (!$isimage) {
            if (isset($file['tmp_name'])) {
                if (!@move_uploaded_file($file['tmp_name'], $path)) {
                    return false;
                }
            } else if (isset($file['bytes'])) {
                if (!file_put_contents($path, $file['bytes'])) {
                    return false;
                }
            } else {
                return false;
            }
        }
        if ($isimage) {
            $source->toFile($path);
            $file['size'] = filesize($path);
        } else if (!isset($file['size'])) {
            $file['size'] = filesize($path);
    }         
    $file['tmp_name'] = $path;
    }
    


      
        $uploadfile = self::getUploadFile($file);

        if (!isset($uploadfile)) {
            return false;
        }

        $bosClient = self::bosInit();
        try {
            $bosClient->doesBucketExist($options['bd_bucket_name']);
        } catch (Exception $e) {
            return false;
        }
       try {
            $bosClient->putObjectFromString($options['bd_bucket_name'], $path, $content, array(BosOptions::CONTENT_TYPE => $file['type']));
            
        } catch (Exception $e) {
            return false;
        }

 if ($options['bd_bucket_localsave'] == true && self::makeUploadDir($filePath)) {
      #本地存储一份
      @move_uploaded_file($uploadfile, $path);
    }
    else{
        @unlink($path);
    }
    
    
    
        //返回相对存储路径
        return array(
            'name' => $file['name'],
            'path' => $path,
            'size' => $file['size'],
            'type' => $ext,
            'mime' => @\Typecho\Common::mimeContentType($path)
        );
    }


    public static function bd_modifyHandle($content, $file)
    {
       $options = get_option('beargallery');
       if (isset($file['tmp_name'])) {
            $contents = file_get_contents($file['tmp_name']);
        } else if (isset($file['bytes'])) {
            $contents = $file['bytes'];
        } else {
            return false;
        }
        if (empty($file['name'])) {
            return false;
        }

        $ext = self::getSafeName($file['name']);

       if (!\Widget\Upload::checkFileType($ext) || \Typecho\Common::isAppEngine()) {
            return false;
        }

       $uploadfile = self::getUploadFile($file);

        if (!isset($uploadfile)) {
            return false;
        }

        $bosClient = self::bosInit();

        try {
            $bosClient->putObjectFromString($options['bd_bucket_name'], $content['attachment']->path, $contents, array(BosOptions::CONTENT_TYPE => $file['type']));
        } catch (Exception $e) {
            return false;
        } 
      if ($options['bd_bucket_localsave'] == true) {
      #本地存储一份
      @move_uploaded_file($uploadfile, $content['attachment']->path);
    }
    
 
        return array(
            'name' => $content['attachment']->name,
            'path' => $content['attachment']->path,
            'size' => $file['size'],
            'type' => $content['attachment']->type,
            'mime' => $content['attachment']->mime
        );
    }


    public static function bd_deleteHandle(array $content)
    {
       $options = get_option('beargallery');
        $bosClient = self::bosInit();
#删除本地文件
    if ($options['bd_bucket_localsave'] == true && $options['bd_bucket_localdelete']== true) {
      @unlink($content['attachment']->path);
    }
    if ($options['bd_bucket_sync']==true) {
        try {
            $bosClient->deleteObject($options['bd_bucket_name'],$content['attachment']->path);
            return true;
        } catch (Exception $e) {
            return false;
        } 
    }
    return true;
    }


    public static function bd_attachmentHandle(array $content) {
       $options = get_option('beargallery');
         if (preg_match('/\.(jpg|jepg|png|ico|bmp|gif|tiff|webp)/i', $content['attachment']->path)) {
        $diy_style  = $options['bd_bucket_other'];
        }
        return \Typecho\Common::url($content['attachment']->path, self::bd_getDomain()).$diy_style;
    }


    public static function bd_attachmentDataHandle(array $content)
    {
        return file_get_contents(self::bd_attachmentHandle($content));
    }
    
    
  private static function bosInit()
    {
        $options = get_option('beargallery');
        return new BosClient(array(
                'credentials' => array(
                    'accessKeyId' => $options['bd_bucket_accesskey'],
                    'secretAccessKey' => $options['bd_bucket_secretkey'],
                ),
                'endpoint' => 'http://' .$options['bd_bucket_region'],
            ));
    }
  
  private static function tx_getDomain()
  {
    $options = get_option('beargallery');
        $domain = $options['bucket_domain'];
        if (empty($domain)) $domain = 'https://' .$options['bucket_name'] . '.cos.' . $options['bucket_region'].'.myqcloud.com';

        return $domain;
  }  
  
  private static function ali_getDomain()
  {
    $options = get_option('beargallery');
        $domain = $options['ali_bucket_domain'];
        $bucket_name = $options['ali_bucket_name'];
            $end_point   = ($options['ali_bucket_region'] === "other") ? 
                            $options['ali_bucket_other_region'] : 
                            $options['ali_bucket_region'];
        if (empty($domain)) $domain = 'https://' .$bucket_name . '.' . $end_point.'.aliyuncs.com';

        return $domain;
  }  
  
  private static function bd_getDomain()
  {
    $options = get_option('beargallery');
        $domain = $options['bd_bucket_domain'];
        if (empty($domain)) $domain = 'https://' .$options['bd_bucket_name'] . '.' . $options['bd_bucket_region'];

        return $domain;
  }  
  
  private static function hw_getDomain()
  {
    $options = get_option('beargallery');
        $domain = $options['hw_bucket_domain'];
        if (empty($domain)) $domain = 'https://' .$options['hw_bucket_name'] . '.' . $options['hw_bucket_region']. '.myhuaweicloud.com';

        return $domain;
  }  

  private static function getDomain()
  {
    $opt = get_option('beargallery');
    $domain = $opt['bucket_domain'];
    if (empty($domain))  $domain = 'https://' . $opt['bucket_name'] . '.cos.' . $opt['bucket_region'] . '.myqcloud.com';
    return $domain;
  }
  
  
  
 
  
private static function checkFileType($ext)
    {
        $options = \Typecho\Widget::widget('Widget_Options');
        return in_array($ext, $options->allowedAttachmentTypes);
    }
 
 
  private static function makeUploadDir(string $path)
  {
    $path = preg_replace("/\\\+/", '/', $path);
    $current = rtrim($path, '/');
    $last = $current;

    while (!is_dir($current) && false !== strpos($path, '/')) {
      $last = $current;
      $current = dirname($current);
    }

    if ($last == $current) {
      return true;
    }

    if (!@mkdir($last, 0755)) {
      return false;
    }

    return self::makeUploadDir($path);
  }
  
  private static function makeUploadaDir($path)
    {
        $path = preg_replace("/\\\+/", '/', $path);
        $current = rtrim($path, '/');
        $last = $current;

        while (!is_dir($current) && false !== strpos($path, '/')) {
            $last = $current;
            $current = dirname($current);
        }

        if ($last == $current) {
            return true;
        }

        if (!@mkdir($last)) {
            return false;
        }

        $stat = @stat($last);
        $perms = $stat['mode'] & 0007777;
        @chmod($last, $perms);

        return self::makeUploadaDir($path);
    }

  private static function getUploadDir()
  {
    $opt = get_option('beargallery');
    if ($opt['bucket_path']) {
      return $opt['bucket_path'];
    } else if (defined('__TYPECHO_UPLOAD_DIR__')) {
      return __TYPECHO_UPLOAD_DIR__;
    } else {
      return self::UPLOAD_DIR;
    }
  }
  
  private static function ali_getUploadDir()
  {
    $opt = get_option('beargallery');
    if ($opt['ali_bucket_path']) {
      return $opt['ali_bucket_path'];
    } else if (defined('__TYPECHO_UPLOAD_DIR__')) {
      return __TYPECHO_UPLOAD_DIR__;
    } else {
      return self::UPLOAD_DIR;
    }
  }
  
  private static function qiniu_getUploadDir()
  {
    $opt = get_option('beargallery');
    if ($opt['qiniu_bucket_path']) {
      return $opt['qiniu_bucket_path'];
    } else if (defined('__TYPECHO_UPLOAD_DIR__')) {
      return __TYPECHO_UPLOAD_DIR__;
    } else {
      return self::UPLOAD_DIR;
    }
  }
  
  private static function upyun_getUploadDir()
  {
    $opt = get_option('beargallery');
    if ($opt['upyun_bucket_path']) {
      return $opt['upyun_bucket_path'];
    } else if (defined('__TYPECHO_UPLOAD_DIR__')) {
      return __TYPECHO_UPLOAD_DIR__;
    } else {
      return self::UPLOAD_DIR;
    }
  }
  
  private static function hw_getUploadDir()
  {
    $opt = get_option('beargallery');
    if ($opt['hw_bucket_path']) {
      return $opt['hw_bucket_path'];
    } else if (defined('__TYPECHO_UPLOAD_DIR__')) {
      return __TYPECHO_UPLOAD_DIR__;
    } else {
      return self::UPLOAD_DIR;
    }
  }
  
  private static function bd_getUploadDir()
  {
    $opt = get_option('beargallery');
    if ($opt['bd_bucket_path']) {
      return $opt['bd_bucket_path'];
    } else if (defined('__TYPECHO_UPLOAD_DIR__')) {
      return __TYPECHO_UPLOAD_DIR__;
    } else {
      return self::UPLOAD_DIR;
    }
  }
  
  private static function getUploadFile($file)
  {
    return isset($file['tmp_name']) ? $file['tmp_name'] : (isset($file['bytes']) ? $file['bytes'] : (isset($file['bits']) ? $file['bits'] : ''));
  }

  
  private static function getSafeName(&$file)
  {
    $file = str_replace(array('"', '<', '>'), '', $file);
    $file = str_replace('\\', '/', $file);
    $file = false === strpos($file, '/') ? ('a' . $file) : str_replace('/', '/a', $file);
    $info = pathinfo($file);
    $file = substr($info['basename'], 1);
    return isset($info['extension']) ? strtolower($info['extension']) : '';
  }
  private static function getSafeaName(&$name) {
        $name = str_replace(array('"', '<', '>'), '', $name);
        $name = str_replace('\\', '/', $name);
        $name = false === strpos($name, '/') ? ('a' . $name) : str_replace('/', '/a', $name);
        $info = pathinfo($name);
        $name = substr($info['basename'], 1);
        return $name;
    }
  
  private static function getExtentionName(&$name) {
        $info = pathinfo($name);
        return isset($info['extension']) ? strtolower($info['extension']) : '';
    }
    

  private static function compareVersion($test)
  {
    $currentVersion = explode('.', PHP_VERSION);
    $testVersion = explode('.', $test);
    $ret = 0;
    for ($i = 0; $i < sizeof($currentVersion); $i++) {
      if ($currentVersion[$i] == $testVersion[$i]) {
        continue;
      }
      if ($currentVersion[$i] > $testVersion[$i]) {
        $ret = 1;
        break;
      } else {
        $ret = -1;
        break;
      }
    }
    return $ret;
  }
 /*

     * 文件上传

     * @param $file

     *

     */

    public static function tinypng_uploadHandle($file)
    {
        //文件名是否空
        if (empty($file['name'])) {
            return false;
        }
        //文件名安全性检测
        $ext = self::getTypechoMethod("getSafeName", $file['name']);
        if (!self::getTypechoMethod("checkFileType", $ext) || \Typecho\Common::isAppEngine()) {
            return false;
        }
        //判断格式
        $ext = strtolower($ext);
        $isimage = in_array($ext, array("jpg", "png", "jpeg"));

        $options = Helper::options();
        if (empty($date)) {
            $date = new \Typecho\Date($options->gmtTime);
        }
        $path = \Typecho\Common::url(defined('__TYPECHO_UPLOAD_DIR__') ? __TYPECHO_UPLOAD_DIR__ : \Widget\Upload::UPLOAD_DIR,

                defined('__TYPECHO_UPLOAD_ROOT_DIR__') ? __TYPECHO_UPLOAD_ROOT_DIR__ : __TYPECHO_ROOT_DIR__)

            . '/' . $date->year . '/' . $date->month;

        if (!isset($file['tmp_name'])) {
            return false;
        }
        if ($isimage) {
            $apikey = trim(bgOptions::getInstance()::get_option( 'beargallery' )['Tinypng_apikey']);
            \Tinify\setKey($apikey);
            $source = \Tinify\fromFile($file['tmp_name']);
        }
        //创建上传目录
        if (!is_dir($path)) {

            if (!self::getTypechoMethod("makeUploadDir", $path)) {

                return false;
            }
        }

        $fileName = sprintf('%u', crc32(uniqid())) . '.' . $ext;
        $path = $path . '/' . $fileName;
        if (!$isimage) {
            if (isset($file['tmp_name'])) {
                if (!@move_uploaded_file($file['tmp_name'], $path)) {
                    return false;
                }
            } else if (isset($file['bytes'])) {
                if (!file_put_contents($path, $file['bytes'])) {
                    return false;
                }
            } else {
                return false;
            }
        }
        if ($isimage) {
            $source->toFile($path);
            $file['size'] = filesize($path);
        } else if (!isset($file['size'])) {
            $file['size'] = filesize($path);
        }

        return array(
            'name' => $file['name'],
            'path' => (defined('__TYPECHO_UPLOAD_DIR__') ? __TYPECHO_UPLOAD_DIR__ : \Widget\Upload::UPLOAD_DIR)
                . '/' . $date->year . '/' . $date->month . '/' . $fileName,
            'size' => $file['size'],
            'type' => $ext,
            'mime' => \Typecho\Common::mimeContentType($path)
        );
    }
    
    
    public static function lsky_uploadHandle($file)
    {
        $options = bgOptions::getInstance()::get_option( 'beargallery' );
        if (empty($file['name'])) {

            return false;
        }

        $ext = self::_getSafeName($file['name']);

        if (!Upload::checkFileType($ext) || Common::isAppEngine()) {

            return false;
        }
        
        if (self::_isImage($ext)) {

            return self::_uploadImg($file, $ext);
        }
   
        
    
        return self::_uploadOtherFile($file, $ext);
    }

    public static function lsky_deleteHandle(array $content): bool
    {
    $ext = $content['attachment']->type;

        if (self::_isImage($ext)) {

            return self::_deleteImg($content);
        }

        return unlink($content['attachment']->path);
    }

    public static function lsky_modifyHandle($content, $file)
    {
        $options = bgOptions::getInstance()::get_option( 'beargallery' );
        if (empty($file['name'])) {

            return false;
        }
        $ext = self::_getSafeName($file['name']);
        if ($content['attachment']->type != $ext || Common::isAppEngine()) {

            return false;
        }

        if (!self::_getUploadFile($file)) {

            return false;
        }
       $uploadfile = self::_getUploadFile($file);
        if (self::_isImage($ext)) {
            self::_deleteImg($content);

            return self::_uploadImg($file, $ext);
        }
      
        return self::_uploadOtherFile($file, $ext);
    }

    public static function lsky_attachmentHandle(array $content): string
    {
		$arr = unserialize($content['text']);
		$text = strstr($content['text'],'.');
		$ext = substr($text,1,3);
        if (self::_isImage($ext)) {

            return $content['attachment']->path ?? '';
        }

        $ret = explode('/'.self::UPLOAD_DIR, $arr['path']);
        return Common::url('/'.self::UPLOAD_DIR . @$ret[1], Options::alloc()->siteUrl);
    }
   
   public static function lsky_attachmentDataHandle(array $content)
    {
        return file_get_contents(self::lsky_attachmentHandle($content));
    }
    
    private static function _getUploadDir($ext = ''): string
    {
        if (self::_isImage($ext)) {
            $url = parse_url(Options::alloc()->siteUrl);
            $DIR = str_replace('.', '_', $url['host']);
            return __TYPECHO_ROOT_DIR__ . '/'.self::UPLOAD_DIR;
        } elseif (defined('__TYPECHO_UPLOAD_DIR__')) {
            return __TYPECHO_UPLOAD_DIR__;
        } else {
            $path = Common::url('/'.self::UPLOAD_DIR, __TYPECHO_ROOT_DIR__);
            return $path;
        }
    }

    private static function _getUploadFile($file): string
    {
        return $file['tmp_name'] ?? ($file['bytes'] ?? ($file['bits'] ?? ''));
    }

    private static function _getSafeName(&$name): string
    {
        $name = str_replace(array('"', '<', '>'), '', $name);
        $name = str_replace('\\', '/', $name);
        $name = false === strpos($name, '/') ? ('a' . $name) : str_replace('/', '/a', $name);
        $info = pathinfo($name);
        $name = substr($info['basename'], 1);

        return isset($info['extension']) ? strtolower($info['extension']) : '';
    }

    private static function _makeUploadDir($path): bool
    {
        $path    = preg_replace("/\\\+/", '/', $path);
        $current = rtrim($path, '/');
        $last    = $current;

        while (!is_dir($current) && false !== strpos($path, '/')) {
            $last    = $current;
            $current = dirname($current);
        }

        if ($last == $current) {
            return true;
        }

        if (!@mkdir($last)) {
            return false;
        }

        $stat  = @stat($last);
        $perms = $stat['mode'] & 0007777;
        @chmod($last, $perms);

        return self::_makeUploadDir($path);
    }

    private static function _isImage($ext): bool
    {
        $img_ext_arr = array('gif','jpg','jpeg','png','tiff','bmp','ico','psd','webp','JPG','BMP','GIF','PNG','JPEG','ICO','PSD','TIFF','WEBP');
        return in_array($ext, $img_ext_arr);
    }

    private static function _uploadOtherFile($file, $ext)
    {
        $dir = self::_getUploadDir($ext) . '/' . date('Y') . '/' . date('m');
        if (!self::_makeUploadDir($dir)) {

            return false;
        }

        $path = sprintf('%s/%u.%s', $dir, crc32(uniqid()), $ext);
        if (!isset($file['tmp_name']) || !@move_uploaded_file($file['tmp_name'], $path)) {

            return false;
       }
   
 

        return [
            'name' => $file['name'],
            'path' => $path,
            'size' => $file['size'] ?? filesize($path),
            'type' => $ext,
            'mime' => @Common::mimeContentType($path)
        ];
    }


    private static function _uploadImg($file, $ext)
    {
       
    
        $options = bgOptions::getInstance()::get_option( 'beargallery' );
        $api     = $options['lskypro_api'] . '/api/v1/upload';
		$token   = 'Bearer '.$options['lskypro_token'];
        $strategyId = $options['lskypro_id'];
        
        $tmp     = self::_getUploadFile($file);
        if (empty($tmp)) {

            return false;
        }

		$img = $file['name'];
        if (!rename($tmp, $img)) {

            return false;
        }
        $params = ['file' => new CURLFile($img)];
        if ($strategyId) {
            $params['strategy_id'] = $strategyId;
        }

        $res = self::_curlPost($api, $params, $token);
          
          
        unlink($img);

        if (!$res) {

            return false;
        }
      

        $json = json_decode($res, true);
     
        
        if ($json['status'] === false) {
            return false;
        }
        
        $data = $json['data'];
         $data = $json['data'];
        return [
            'img_key' => $data['key'],
            'img_id' => $data['md5'],
            'name'   => $data['origin_name'],
            'path'   => $data['links']['url'],
            'size'   => $data['size']*1024,
            'type'   => $data['extension'],
            'mime'   => $data['mimetype'],
			'description'  => $data['mimetype'],
        ];
    }

    private static function _deleteImg(array $content): bool
    {
      
        $options = bgOptions::getInstance()::get_option( 'beargallery' );
        $api     = $options['lskypro_api'] . '/api/v1/upload';
		$token   = 'Bearer '.$options['lskypro_token'];
     

        $id = $content['attachment']->img_key;
        
        if (empty($id)) {
            return false;
        }
        
        $res  = self::_curlDelete($api . '/' . $id, ['key' => $id], $token);
        $json = json_decode($res, true);
    
        if (!is_array($json)) {

            return false;
        }

        return true;
    }

    private static function _curlDelete($api, $post, $token)
    {
        $headers = array(
            "Content-Type: multipart/form-data",
            "Accept: application/json",
            "Authorization: ".$token,
            );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.102 Safari/537.36');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $res = curl_exec($ch);
        curl_close($ch);

        return $res;
    }
    
    private static function _curlPost($api, $post, $token)
    {
        $headers = array(
            "Content-Type: multipart/form-data",
            "Accept: application/json",
            "Authorization: ".$token,
            );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.102 Safari/537.36');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $res = curl_exec($ch);
        curl_close($ch);

        return $res;
    }
    

    public static function getTypechoMethod($methodName, $inParams)

    {

        if (empty($objReflectClass)) {

            $objReflectClass = new ReflectionClass("Widget_Upload");

        }

        $method = $objReflectClass->getMethod($methodName);

        $method->setAccessible(true);

        if ("getSafeName" == $methodName) {

            $result = $method->invokeArgs(null, array(&$inParams));

        } else {

            $result = $method->invokeArgs(null, array($inParams));

        }

        return $result;

    }

    public static function getSecurity($typeName,$name,$value = null){
    if($typeName == 'get'){
        $value = \Typecho\Cookie::get($name);
        return $value;
    }
    elseif($typeName == 'set'){
        \Typecho\Cookie::set($name, $value);
    }
    else{
        \Typecho\Cookie::delete($name, $value);
    }
}


}