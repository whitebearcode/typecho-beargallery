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


/**
 * BearGallery主题核心插件
 * <br>安装后无需进行其他设置
 * @package BgCore
 * @author BearNotion
 * @version v1.0.6-release
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