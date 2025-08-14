<?php
error_reporting(0);
if(!class_exists('CSF')){
    require_once Helper::options()->pluginDir('BgCore').'/bgoptions-framework.php';
}

if (!class_exists('bgOptions')){
    require_once \Utils\Helper::options()->pluginDir('BgCore').'/bgOptions.php';
}
use Typecho\Common;
use Typecho\Exception;
use Typecho\Router;
use Utils\Helper;
use Widget\Options;
use bgOptions;
require_once('core/Core.php');
if(Helper::options()->version >= '1.3.0'){
require_once('modules/Comments/Comments_n.php');
}
else{
require_once('modules/Comments/Comments.php');
}
function themeVersion()
        {
            return '1.0.7.20250814';
        }


function themeVersionOnly()
        {
            return '1.0.7';
        }

$options = bgOptions::getInstance()::get_option( 'beargallery' );

if( class_exists( 'CSF' ) ) {
    $Tyoptions = Helper::options();
    $db = \Typecho\Db::get();
  $prefix = 'beargallery'; 
  CSF::createOptions( $prefix, array(
    'menu_title' => 'Beargallery',
    'menu_slug'  => 'my-beargallery',
  ) );
  CSF::createSection( $prefix, array(
    'title'       => '使用说明',
    'icon'        => 'fas fa-question-circle',
    'description' => '欢迎使用BearGallery主题，本主题为 <strong>摄影类相册主题</strong>，以下是本主题的使用说明。',
    'fields'      => array(

        array(
            'type'    => 'heading',
            'content' => '使用说明',
        ),

        array(
            'type'    => 'content',
            'content' => '1、主题用户交流QQ群:561848356<br><font color=red>2、若主题配置保存失败请检查是否开启了防火墙，且防火墙是否拦截了请求</font><br>3、不懂的问题或本主题存在的问题可加群或在BearTalk社区中进行反馈<br>
    4、
        BearTalk社区是为使用者提供的一个讨论交流社区，传送门：<a href="https://www.beartalk.ru" target="_blank">戳这里</a>
  
   <center>  <br>
 <div class="csf-submessage csf-submessage-info">当前版本: v'.themeVersion().' / 最新版本: v<font id="version"></font>
<div id="versiontips" style="margin-top:10px"></div></center>


<script src="'.Helper::options()->themeUrl.'/assets/js/jquery.min.js"></script>
  <script src="'.Helper::options()->themeUrl.'/assets/plugins/layer/layer.min.js?v=1" type="application/javascript"></script>
<script>

$(function() {


$.post("https://upgrade.typecho.co.uk/Beargallery/version.php",function(data,status){
switch(status)
{
case "success":
json = JSON.parse(data);
nowversion = "'.themeVersion().'";
$("#version").html(json.version);
if(json.version > nowversion){
if (/(iPhone|iPad|iPod|iOS|Android)/i.test(navigator.userAgent)) {
    $("#versiontips").html(\'<div class="csf-submessage csf-submessage-warning">检测到有新版本可以更新，请及时完成更新!<br><a href="#check" class="ui warning label">前往更新<a></div>\');
} else {
    $("#versiontips").html(\'<div class="csf-submessage csf-submessage-warning">检测到有新版本可以更新，请及时完成更新!<br><a href="#tab=在线升级" class="ui warning label">前往更新<a></div>\'); 
};

}
break;
case "error":
$("#version").html("最新版本获取失败");
break;
case "timeout":
$("#version").html("最新版本获取超时");
break;
default: $("#version").html("'.themeVersion().'");
}
    });
});
</script>',
        ),

        array(
            'type'    => 'submessage',
            'style'   => 'info',
            'content' => '在反馈本主题相关的问题时，请务必将以下内容放入您要反馈的内容中',
        ),
array(
                'type'    => 'notice',
            'style'   => 'info',
                'content' => '<ul style="margin:0 auto;"><li>BearGallery主题版本：v'.themeVersion().'[<a href="https://docs.whitebear.dev/index.php/archives/17/" target="_blank">更新日志</a>]</li><li>PHP版本：'.PHP_VERSION.'</li><li>网站服务器：'.$_SERVER['SERVER_SOFTWARE'].'</li><li>数据库：'.$db->getAdapterName().'[Version：'.$db->getVersion().']</li><li>Typecho版本：'.$Tyoptions->version.'</li><li>User Agent信息：'.$_SERVER['HTTP_USER_AGENT'].'</li></ul>',
        ),

    )
) );
  CSF::createSection( $prefix, array(
    'title'  => '基础设置',
    'icon'   => 'fas fa-cog',
    'fields' => array(
      
     array(
            'id'      => 'header_choose',
            'type'    => 'radio',
            'title'   => '站点LOGO类型',
            'inline'  => true,
            'options' => array(
                'text'    => '文字LOGO',
                'image'   => '图片LOGO',
            ),
            'default' => 'text'
        ),
      array(
        'id'    => 'textlogo_text',
        'type'  => 'text',
        'title' => '站点文字LOGO',
        'default' => $Tyoptions->title,
        'after' => '请填入站点文字LOGO',
        'dependency' => array( 'header_choose', '==', 'text' ),
      ),

      array(
            'id'      => 'imagelogo',
            'title'   => '站点图片LOGO',
            'after' => '请上传站点图片LOGO',
            'type'    => 'upload',
            'dependency' => array( 'header_choose', '==', 'image' ),
        ),
     
     
      array(
            'id'      => 'favicon',
            'type'    => 'upload',
            'title'   => '站点Favicon图标',
        ),
        array(
            'id'      => 'avatarChoose',
            'type'    => 'radio',
            'title'   => '头像服务选择',
            'inline'  => true,
            'options' => array(
                'cravatar'    => 'Cravatar服务(cravatar.cn)',
                'cravatar2'    => 'Cravatar服务(cravatar.com)',
                'weavatar'   => 'Weavatar服务',
                'gravatar'   => 'Gravatar服务',
                'diyavatar'   => '自定义头像',
            ),
            'default' => 'cravatar',
            'after' => 'Cravatar服务：Cravatar适合在国内使用，优先级:Cravatar头像->Gravatar头像->QQ头像<br>Weavatar服务：Weavatar也适合在国内使用，优先级:Weavatar头像->Gravatar头像->QQ头像<br>Gravatar服务：在国内官方源可能访问不了头像，支持了多个镜像源并支持您填写自定义镜像源<br>自定义头像：您可以通过自定义头像在前台显示随机头像，在自定义头像栏为空时默认为Cravatar<br><font color=red>目前QQ头像调用的是Weavatar，若注册过Weavatar，则不会显示QQ头像</font>',
        ),
        array(
            'id'      => 'Cravatar_default',
            'type'    => 'upload',
            'title'   => 'Cravatar默认头像',
            'after' => '考虑到Cravatar默认头像略丑的问题，这里支持自定义Cravatar默认头像。',
            'dependency' => array( 'avatarChoose', '==', 'cravatar' ),
        ),
        array(
            'id'      => 'Cravatar2_default',
            'type'    => 'upload',
            'title'   => 'Cravatar默认头像',
            'after' => '考虑到Cravatar默认头像略丑的问题，这里支持自定义Cravatar默认头像。',
            'dependency' => array( 'avatarChoose', '==', 'cravatar2' ),
        ),
        array(
            'id'      => 'Weavatar_default',
            'type'    => 'upload',
            'title'   => 'Weavatar默认头像',
            'after' => '考虑到Weavatar默认头像略丑的问题，这里支持自定义Weavatar默认头像。',
            'dependency' => array( 'avatarChoose', '==', 'weavatar' ),
        ),
        array(
        'id'    => 'DiyAvatar',
        'type'  => 'textarea',
        'title' => '自定义头像',
        'after' => '您可以自定义前台随机头像，格式为a.png,b.png，保证每个头像图片都为直链可直接访问，多个头像图片使用英文逗号分隔，当本栏为空时默认使用Cravatar服务。',
        'dependency' => array( 'avatarChoose', '==', 'diyavatar' ),
      ),
      array(
            'id'          => 'Gravatar',
            'type'        => 'select',
            'title'       => 'Gravatar源选择',
            'after' => '因Gravatar官方在中国大陆地区被Q，导致在中国大陆访问使用Gravatar的站点时头像不显示,这里支持您自主选择合适的源,若为自定义，举例:cdn.v2ex.com/gravatar/<br>本功能适配QQ,当填写的邮箱为QQ邮箱时则显示QQ头像',
            'options'     => array('1' => 'Gravatar官方源', '3' => 'V2EX*Gravatar镜像源','4' => 'LOLI.NET*Gravatar镜像源','7' => '自定义Gravatar镜像源'),
            'default' => '5',
            'dependency' => array( 'avatarChoose', '==', 'gravatar' ),
        ),
      array(
        'id'    => 'GravatarUrl',
        'type'  => 'text',
        'title' => '自定义Gravatar源地址',
        'after' => '请填入自定义Gravatar镜像源地址,格式:cdn.v2ex.com/gravatar/<br>Tips:当您所填写的自定义地址访问时能够显示图片时则您所填写的地址是正常的',
        'dependency' => array( 'Gravatar|avatarChoose', '==|==', '7|gravatar' ),
      ), 
        array(
            'id'      => 'EditorsAutosave',
            'type'    => 'checkbox',
            'title'   => '编辑器自动保存方式选择',
            'after' => '自动保存支持两种方式，两种方式可同时开启不受影响，全都不选代表不开启自动保存<br>第一种即为浏览器保存，文章在还没点击提交的情况下均保存在浏览器中，点击其他文章，先前文章的内容仍会直接覆盖到当前文章。第二种是保存为草稿，编辑器在每隔十五秒会将文章保存为草稿。',
            'inline'  => true,
            'options' => array(
                '1' => '浏览器自动保存',
                '11' => '自动保存为草稿',
            ),
        ),
        
        
      
    )
  ) );
CSF::createSection( $prefix, array(
    'title'  => '顶部设置',
    'icon'   => 'fas fa-chevron-up',
    'fields' => array(
     
        array(
            'id'       => 'CustomizationCode',
            'type'     => 'code_editor',
            'title'    => '顶部自定义代码',
            'subtitle' => '如百度Meta验证代码，均可以放在这里',
        ),
        
        
    )
  ) );
  
  
  CSF::createSection( $prefix, array(
    'title'  => '存储设置',
    'icon'   => 'fas fa-database',
    'fields' => array(
        array(
            'id'          => 'bucket_choose',
            'type'        => 'select',
            'title'       => '文件存储方式',
            'after' => '选择您所上传文件的存储方式，默认为本地存储',
            'options'     => array('1' => '本地存储',  '2' => '腾讯云COS',  '3' => '阿里云OSS',  '4' => '七牛云Kodo',  '5' => '又拍云USS',  '6' => '百度云BOS', '7' => '华为云OBS', '8' => 'LskyPro'),
            'default' => '1',
        ), 
        //LskyPro
         array(
            'type'    => 'submessage',
            'style'   => 'warning',
            'content' => '<font color=red>该存储方式仅作用于图片类型(支持gif,jpg,jpeg,png,tiff,bmp,ico,psd,webp)，其他类型会自动变成本地上传！！</font><br>仅在 LskyPro v2.1 开源版本中测试通过，其他版本需自行测试',
            'dependency' => array( 'bucket_choose', '==', '8' )
        ),
        array(
            'id'          => 'lskypro_api',
            'type'        => 'text',
            'title'       => 'LskyPro Api地址',
            'after' => '必填* 只需填写域名且包含 http(s)://，无需 / 结尾，如https://xxx.com<br><font color=red>Token需填写此项并保存后才可获取！</font>',
            'dependency' => array( 'bucket_choose', '==', '8' ),
        ), 
        array(
            'id'          => 'lskypro_token',
            'type'        => 'text',
            'title'       => 'LskyPro Token',
            'after' => '必填* 因v2.1版本无法直接获取到Token，可 <div id="getLskyToken" style="color:red;cursor:pointer;">点击此处获取Token</div>',
            'dependency' => array( 'bucket_choose', '==', '8' ),
        ), 
        array(
            'id'          => 'lskypro_id',
            'type'        => 'text',
            'title'       => 'LskyPro 储存策略ID',
            'after' => '非必填* 若本项为空则为默认策略',
            'dependency' => array( 'bucket_choose', '==', '8' ),
        ), 
        //腾讯云
        array(
            'id'          => 'bucket_secret_id',
            'type'        => 'text',
            'title'       => '腾讯云SecretId',
            'after' => '必填* 请前往<a target="_blank" href="https://console.cloud.tencent.com/capi">腾讯云控制台-个人API密钥</a> 获取您的SecretId',
            'dependency' => array( 'bucket_choose', '==', '2' ),
        ), 
        array(
            'id'          => 'bucket_secret_key',
            'type'        => 'text',
            'title'       => '腾讯云SecretKey',
            'after' => '必填* 请前往<a target="_blank" href="https://console.cloud.tencent.com/capi">腾讯云控制台-个人API密钥</a> 获取您的SecretKey',
            'dependency' => array( 'bucket_choose', '==', '2' ),
        ), 
        array(
            'id'          => 'bucket_region',
            'type'        => 'select',
            'title'       => 'COS所属地域',
            'after' => '必选* 选择您的腾讯云COS所属地域，请务必选择正确',
            'options'     => array(
          'ap-beijing' => _t('北京'),
          'ap-beijing-1' => _t('北京一区'),
          'ap-guangzhou' => _t('广州'),
          'ap-nanjing' => _t('南京'),
          'ap-shanghai' => _t('上海'),
          'ap-chengdu' => _t('成都'),
          'ap-chongqing' => _t('重庆'),
          'ap-shenzhen-fsi' => _t('深圳金融'),
          'ap-shanghai-fsi' => _t('上海金融'),
          'ap-beijing-fsi' => _t('北京金融'),
          'ap-hongkong' => _t('香港'),
          'ap-singapore' => _t('新加坡'),
          'ap-mumbai' => _t('孟买'),
          'ap-jakarta' => _t('雅加达'),
          'ap-seoul' => _t('首尔'),
          'ap-bangkok' => _t('曼谷'),
          'ap-tokyo' => _t('东京'),
          'na-toronto' => _t('多伦多'),
          'na-siliconvalley' => _t('硅谷'),
          'na-ashburn' => _t('弗吉尼亚'),
          'na-toronto' => _t('多伦多'),
          'sa-saopaulo' => _t('圣保罗'),
          'eu-frankfurt' => _t('法兰克福'),
          'eu-moscow' => _t('莫斯科'),
        ),
            'dependency' => array( 'bucket_choose', '==', '2' ),
        ), 
        array(
            'id'          => 'bucket_name',
            'type'        => 'text',
            'title'       => '腾讯云COS存储桶名称',
            'after' => '必填* 格式为 xxxxx-xxxxxx  请前往<a target="_blank" href="https://console.cloud.tencent.com/cos/bucket">腾讯云COS页面</a> 获取，<font color=red>请务必设置为公有读私有写!</font>',
            'dependency' => array( 'bucket_choose', '==', '2' ),
        ), 
         array(
            'id'          => 'bucket_path',
            'type'        => 'text',
            'title'       => '腾讯云COS存储路径',
            'after' => '必填* 默认为 uploads/，若非必要可不修改，路径最前面不加/',
            'default' =>'uploads/',
            'dependency' => array( 'bucket_choose', '==', '2' ),
        ), 
        array(
            'id'          => 'bucket_domain',
            'type'        => 'text',
            'title'       => '腾讯云COS访问域名',
            'after' => '留空则为腾讯云默认域名，如：https://xxxxx.cos.ap-beijing.myqcloud.com<br>若填写的为自定义CDN域名，请参考<a target="_blank" href="https://cloud.tencent.com/document/product/436/36637">文档</a><br>若填写的为自定义源站域名，请参考<a target="_blank" href="https://cloud.tencent.com/document/product/436/36638">文档</a><br><font color="red">要注意的一点是若填写的为自定义CDN域名或自定义源站域名，域名需以 https:// 或 https:// 开头</font>',
            'dependency' => array( 'bucket_choose', '==', '2' ),
        ), 
        array(
            'id'      => 'bucket_sync',
            'type'    => 'switcher',
            'title'       => '本地云端同步删除',
            'subtitle' => '在文件管理删除文件时，是否同步删除COS上的对应文件',
            'default' => false,
            'dependency' => array( 'bucket_choose', '==', '2' ),
        ),
        array(
            'id'      => 'bucket_localsave',
            'type'    => 'switcher',
            'title'       => '双重储存',
            'subtitle' => '在本地保存一份文件副本，这样的话会占用本地存储空间',
            'default' => false,
            'dependency' => array( 'bucket_choose', '==', '2' ),
        ),
        array(
            'id'      => 'bucket_localdelete',
            'type'    => 'switcher',
            'title'       => '删除时同步删除本地备份',
            'subtitle' => '在文件管理删除文件时，是否同步删除本地备份的对应文件',
            'default' => false,
            'dependency' => array( 'bucket_choose|bucket_localsave', '==|==', '2|true' ),
        ),
         array(
            'id'          => 'bucket_other',
            'type'        => 'text',
            'title'       => '对象链接附带后缀',
            'after' => '[针对图片处理]该项针对有需要在对象链接后面添加一些参数的用户，若不需要请留空<br>填写示例: ?v=1&type=webp',
            'dependency' => array( 'bucket_choose', '==', '2' ),
        ), 
        //阿里云
        array(
            'id'          => 'ali_bucket_accesskey_id',
            'type'        => 'text',
            'title'       => '阿里云ACCESSKEY ID',
            'after' => '必填* 请前往<a target="_blank" href="https://ram.console.aliyun.com/manage/ak">阿里云控制台-RAM访问控制</a> 获取您的ACCESSKEY ID',
            'dependency' => array( 'bucket_choose', '==', '3' ),
        ), 
        array(
            'id'          => 'ali_bucket_accesskey_secret',
            'type'        => 'text',
            'title'       => '阿里云ACCESSKEY SECRET',
            'after' => '必填* 请前往<a target="_blank" href="https://ram.console.aliyun.com/manage/ak">阿里云控制台-RAM访问控制</a> 获取您的ACCESSKEY SECRET',
            'dependency' => array( 'bucket_choose', '==', '3' ),
        ), 
        array(
            'id'          => 'ali_bucket_region',
            'type'        => 'select',
            'title'       => 'OSS所属地域',
            'after' => '必选* 选择您的阿里云OSS所属地域，请务必选择正确',
            'options'     => array(
                "oss-cn-hangzhou"   =>  "华东1-杭州",
                "oss-cn-shanghai"   =>  "华东2-上海",
                "oss-cn-nanjing"   =>  "华东5-南京（本地地域）",
                "oss-cn-fuzhou"   =>  "华东6-福州（本地地域）",
                "oss-cn-qingdao"    =>  "华北1-青岛",
                "oss-cn-beijing"    =>  "华北2-北京",
                "oss-cn-zhangjiakou"    =>  "华北3-张家口",
                "oss-cn-huhehaote"  =>  "华北5-呼和浩特",
                "oss-cn-wulanchabu" =>  "华北6-乌兰察布",
                "oss-cn-shenzhen"   =>  "华南1-深圳",
                "oss-cn-heyuan"     =>  "华南2-河源",
                "oss-cn-chengdu"    =>  "西南1-成都",
                "oss-cn-hongkong"   =>  "中国-香港",
                "oss-us-west-1"     =>  "美国-硅谷",
                "oss-us-east-1"     =>  "美国-弗吉尼亚",
                "oss-ap-southeast-1"    =>"新加坡",
                "oss-ap-southeast-2"    =>"澳大利亚-悉尼",
                "oss-ap-southeast-3"    =>"马来西亚-吉隆坡",
                "oss-ap-southeast-5"    =>"印度尼西亚-雅加达",
                "oss-ap-northeast-1"    =>"日本-东京",
                "oss-ap-south-1"    =>  "印度-孟买",
                "oss-eu-central-1"  =>  "德国-法兰克福",
                "oss-eu-west-1"     =>  "英国-伦敦",
                "oss-me-east-1"     =>  "阿联酋-迪拜",
                "oss-ap-southeast-7"     =>  "泰国-曼谷",
                "oss-ap-northeast-2"     =>  "韩国-首尔",
                "oss-ap-southeast-6"     =>  "菲律宾-马尼拉",
                "other"             => '无地域属性'
            ),
            'dependency' => array( 'bucket_choose', '==', '3' ),
        ), 
        array(
            'id'          => 'ali_bucket_other_region',
            'type'        => 'text',
            'title'       => 'Endpoint地址',
            'after' => '当非上述所可选地域时此项必填，填写完整的Endpoint地址，如oss-xx-china-xx.aliyuncs.com',
            'dependency' => array( 'bucket_choose|ali_bucket_region', '==|==', '3|other' ),
        ),
        array(
            'id'          => 'ali_bucket_name',
            'type'        => 'text',
            'title'       => '阿里云OSS存储桶名称',
            'after' => '必填* 请填写阿里云OSS存储桶名称，即Bucket名称，<font color=red>请务必设置为公有读私有写!</font>',
            'dependency' => array( 'bucket_choose', '==', '3' ),
        ),
        array(
            'id'          => 'ali_bucket_connect_type',
            'type'        => 'radio',
            'title'       => '阿里云OSS存储桶连接方式',
            'after' => '选择阿里云OSS存储桶连接方式',
            'options'     => array('.aliyuncs.com' => '外网','-internal.aliyuncs.com' => '内网'),
            'default' => '.aliyuncs.com',
            'dependency' => array( 'bucket_choose', '==', '3' ),
        ),
        array(
            'id'          => 'ali_bucket_path',
            'type'        => 'text',
            'title'       => '阿里云OSS存储路径',
            'after' => '必填* 默认为 uploads/，若非必要可不修改，路径最前面不加/',
            'default' => 'uploads/',
            'dependency' => array( 'bucket_choose', '==', '3' ),
        ), 
        array(
            'id'          => 'ali_bucket_domain',
            'type'        => 'text',
            'title'       => '阿里云OSS访问域名',
            'after' => '留空则为阿里云默认域名，如：https://xxxxx.oss-cn-beijing.aliyuncs.com<br>若填写的为自定义CDN域名，请参考<a target="_blank" href="https://help.aliyun.com/document_detail/97687.html">文档</a><br>若填写的为自定义源站域名，请参考<a target="_blank" href="https://help.aliyun.com/document_detail/31836.html">文档</a><br><font color="red">要注意的一点是若填写的为自定义CDN域名或自定义源站域名，域名需以 http:// 或 https:// 开头</font>',
            'dependency' => array( 'bucket_choose', '==', '3' ),
        ), 
        array(
            'id'      => 'ali_bucket_sync',
            'type'    => 'switcher',
            'title'       => '本地云端同步删除',
            'subtitle' => '在文件管理删除文件时，是否同步删除OSS上的对应文件',
            'default' => false,
            'dependency' => array( 'bucket_choose', '==', '3' ),
        ),
        array(
            'id'      => 'ali_bucket_localsave',
            'type'    => 'switcher',
            'title'       => '双重储存',
            'subtitle' => '在本地保存一份文件副本，这样的话会占用本地存储空间',
            'default' => false,
            'dependency' => array( 'bucket_choose', '==', '3' ),
        ),
        array(
            'id'      => 'ali_bucket_localdelete',
            'type'    => 'switcher',
            'title'       => '删除时同步删除本地备份',
            'subtitle' => '在文件管理删除文件时，是否同步删除本地备份的对应文件',
            'default' => false,
            'dependency' => array( 'bucket_choose|ali_bucket_localsave', '==|==', '3|true' ),
        ),
         array(
            'id'          => 'ali_bucket_other',
            'type'        => 'text',
            'title'       => '对象链接附带后缀',
            'after' => '[针对图片处理]该项针对有需要在对象链接后面添加一些参数的用户，若不需要请留空<br>填写示例: ?v=1&type=webp',
            'dependency' => array( 'bucket_choose', '==', '3' ),
        ),
        
        //七牛云
        array(
            'id'          => 'qiniu_bucket_accesskey',
            'type'        => 'text',
            'title'       => '七牛云AccessKey',
            'after' => '必填* 请前往<a target="_blank" href="https://portal.qiniu.com/user/key">七牛云个人中心-密钥管理</a> 获取您的AccessKey',
            'dependency' => array( 'bucket_choose', '==', '4' ),
        ), 
        array(
            'id'          => 'qiniu_bucket_secretKey',
            'type'        => 'text',
            'title'       => '七牛云SecretKey',
            'after' => '必填* 请前往<a target="_blank" href="https://portal.qiniu.com/user/key">七牛云个人中心-密钥管理</a> 获取您的SecretKey',
            'dependency' => array( 'bucket_choose', '==', '4' ),
        ), 
        array(
            'id'          => 'qiniu_bucket_name',
            'type'        => 'text',
            'title'       => '七牛云Kodo空间名称',
            'after' => '必填* 请填写七牛云Kodo空间名称,<font color=red>空间应设置为公开</font>',
            'dependency' => array( 'bucket_choose', '==', '4' ),
        ), 
        array(
            'id'          => 'qiniu_bucket_path',
            'type'        => 'text',
            'title'       => '七牛云Kodo存储路径',
            'after' => '必填* 默认为 uploads/，若非必要可不修改，路径最前面不加/',
            'default' => 'uploads/',
            'dependency' => array( 'bucket_choose', '==', '4' ),
        ), 
        array(
            'id'          => 'qiniu_bucket_domain',
            'type'        => 'text',
            'title'       => '七牛云Kodo访问域名',
            'after' => '切勿留空！可填写七牛测试域名/自定义CDN域名/自定义源站域名<br>若填写的为自定义CDN域名、自定义源站域名，请参考<a target="_blank" href="https://developer.qiniu.com/kodo/8527/kodo-domain-name-management">文档</a><br><font color="red">要注意的一点是若填写的是自定义CDN域名或自定义源站域名，域名需以 http:// 或 https:// 开头，而测试域名则必须为http://开头（七牛的测试域名不允许https访问）</font>',
            'dependency' => array( 'bucket_choose', '==', '4' ),
        ), 
        array(
            'id'      => 'qiniu_bucket_sync',
            'type'    => 'switcher',
            'title'       => '本地云端同步删除',
            'subtitle' => '在文件管理删除文件时，是否同步删除Kodo上的对应文件',
            'default' => false,
            'dependency' => array( 'bucket_choose', '==', '4' ),
        ),
        array(
            'id'      => 'qiniu_bucket_localsave',
            'type'    => 'switcher',
            'title'       => '双重储存',
            'subtitle' => '在本地保存一份文件副本，这样的话会占用本地存储空间',
            'default' => false,
            'dependency' => array( 'bucket_choose', '==', '4' ),
        ),
        array(
            'id'      => 'qiniu_bucket_localdelete',
            'type'    => 'switcher',
            'title'       => '删除时同步删除本地备份',
            'subtitle' => '在文件管理删除文件时，是否同步删除本地备份的对应文件',
            'default' => false,
            'dependency' => array( 'bucket_choose|qiniu_bucket_localsave', '==|==', '4|true' ),
        ),
         array(
            'id'          => 'qiniu_bucket_other',
            'type'        => 'text',
            'title'       => '对象链接附带后缀',
            'after' => '[针对图片处理]该项针对有需要在对象链接后面添加一些参数的用户，若不需要请留空<br>填写示例: ?v=1&type=webp',
            'dependency' => array( 'bucket_choose', '==', '4' ),
        ),
        
        //又拍云
        array(
            'id'          => 'upyun_bucket_actionUser',
            'type'        => 'text',
            'title'       => '又拍云操作员账号',
            'after' => '必填* 请前往<a target="_blank" href="https://console.upyun.com/services/file/">又拍云存储控制台</a>，在云存储-进入相对应的云存储配置页面-存储管理-操作员授权中获取或新建操作员，权限为可读取、可写入、可删除',
            'dependency' => array( 'bucket_choose', '==', '5' ),
        ), 
        array(
            'id'          => 'upyun_bucket_actionKey',
            'type'        => 'text',
            'title'       => '又拍云操作员密码',
            'after' => '必填* 请前往<a target="_blank" href="https://console.upyun.com/services/file/">又拍云存储控制台</a>，在云存储-进入相对应的云存储配置页面-存储管理-操作员授权中获取或新建操作员，权限为可读取、可写入、可删除',
            'dependency' => array( 'bucket_choose', '==', '5' ),
        ), 
        array(
            'id'          => 'upyun_bucket_name',
            'type'        => 'text',
            'title'       => '又拍云存储服务名称',
            'after' => '必填* 请填写又拍云存储服务名称',
            'dependency' => array( 'bucket_choose', '==', '5' ),
        ), 
        array(
            'id'          => 'upyun_bucket_path',
            'type'        => 'text',
            'title'       => '又拍云存储路径',
            'after' => '必填* 默认为 uploads/，若非必要可不修改，路径最前面不加/',
            'default' => 'uploads/',
            'dependency' => array( 'bucket_choose', '==', '5' ),
        ), 
        array(
            'id'          => 'upyun_bucket_domain',
            'type'        => 'text',
            'title'       => '又拍云存储访问域名',
            'after' => '切勿留空！可填写又拍云测试域名/加速域名<br>，域名需以 http:// 或 https:// 开头，而测试域名则必须为http://开头（又拍云的测试域名不允许https访问）</font>',
            'dependency' => array( 'bucket_choose', '==', '5' ),
        ), 
        array(
            'id'      => 'upyun_bucket_sync',
            'type'    => 'switcher',
            'title'       => '本地云端同步删除',
            'subtitle' => '在文件管理删除文件时，是否同步删除云存储上的对应文件',
            'default' => false,
            'dependency' => array( 'bucket_choose', '==', '5' ),
        ),
        array(
            'id'      => 'upyun_bucket_localsave',
            'type'    => 'switcher',
            'title'       => '双重储存',
            'subtitle' => '在本地保存一份文件副本，这样的话会占用本地存储空间',
            'default' => false,
            'dependency' => array( 'bucket_choose', '==', '5' ),
        ),
        array(
            'id'      => 'upyun_bucket_localdelete',
            'type'    => 'switcher',
            'title'       => '删除时同步删除本地备份',
            'subtitle' => '在文件管理删除文件时，是否同步删除本地备份的对应文件',
            'default' => false,
            'dependency' => array( 'bucket_choose|upyun_bucket_localsave', '==|==', '5|true' ),
        ),
         array(
            'id'          => 'upyun_bucket_other',
            'type'        => 'text',
            'title'       => '对象链接附带后缀',
            'after' => '[针对图片处理]该项针对有需要在对象链接后面添加一些参数的用户，若不需要请留空<br>填写示例: ?v=1&type=webp',
            'dependency' => array( 'bucket_choose', '==', '5' ),
        ),
        
        
        //华为云
        array(
            'id'          => 'hw_bucket_accesskey_id',
            'type'        => 'text',
            'title'       => '华为云ACCESSKEY ID',
            'after' => '必填* 请前往<a target="_blank" href="https://console.huaweicloud.com/iam/?locale=zh-cn#/mine/accessKey">华为云控制台-我的凭证</a> 获取您的ACCESSKEY ID',
            'dependency' => array( 'bucket_choose', '==', '7' ),
        ), 
        array(
            'id'          => 'hw_bucket_accesskey_secret',
            'type'        => 'text',
            'title'       => '华为云Secret AccessKey',
            'after' => '必填* 请前往<a target="_blank" href="https://console.huaweicloud.com/iam/?locale=zh-cn#/mine/accessKey">华为云控制台-我的凭证</a> 获取您的SECRET ACCESS KEY',
            'dependency' => array( 'bucket_choose', '==', '7' ),
        ), 
        array(
            'id'          => 'hw_bucket_region',
            'type'        => 'select',
            'title'       => 'OBS所属地域',
            'after' => '必选* 选择您的华为云OBS所属地域，请务必选择正确',
            'options'     => array(
                "obs.cn-north-4"   =>  "华北-北京四",
                "obs.cn-north-1"   =>  "华北-北京一",
                "obs.cn-north-9"   =>  "华北-乌兰察布一",
                "obs.cn-east-2"   =>  "华东-上海二",
                "obs.cn-east-3"    =>  "华东-上海一",
                "obs.cn-south-1"    =>  "华南-广州",
                "obs.cn-south-4"    =>  "华南-广州-友好用户环境",
                "obs.cn-southwest-2"    =>  "西南-贵阳一",
                "obs.ap-southeast-1"  =>  "中国-香港",
                "obs.la-north-2" =>  "拉美-墨西哥城二",
                "obs.na-mexico-1"   =>  "拉美-墨西哥城一",
                "obs.sa-brazil-1"     =>  "拉美-圣保罗一",
                "obs.la-south-2"    =>  "拉美-圣地亚哥",
                "obs.tr-west-1"   =>  "土耳其-伊斯坦布尔",
                "obs.ap-southeast-2"     =>  "亚太-曼谷",
                "obs.ap-southeast-3"     =>  "亚太-新加坡",
                "obs.af-south-1"     =>  "非洲-约翰内斯堡"
            ),
            'dependency' => array( 'bucket_choose', '==', '7' ),
        ), 
        array(
            'id'          => 'hw_bucket_name',
            'type'        => 'text',
            'title'       => '华为云OBS存储桶名称',
            'after' => '必填* 请填写华为云OBS存储桶名称，即Bucket名称，<font color=red>请务必设置为公有读私有写!</font>',
            'dependency' => array( 'bucket_choose', '==', '7' ),
        ),
        array(
            'id'          => 'hw_bucket_path',
            'type'        => 'text',
            'title'       => '华为云OBS存储路径',
            'after' => '必填* 默认为 uploads/，若非必要可不修改，路径最前面不加/',
            'default' => 'uploads/',
            'dependency' => array( 'bucket_choose', '==', '7' ),
        ), 
        array(
            'id'          => 'hw_bucket_domain',
            'type'        => 'text',
            'title'       => '华为云OBS访问域名',
            'after' => '留空则为华为云默认域名，如：https://xxxxxx.obs.cn-north-4.myhuaweicloud.com<br>若填写的为自定义CDN域名，请参考<a target="_blank" href="https://support.huaweicloud.com/usermanual-obs/obs_03_0600.html">文档</a><br>若填写的为自定义源站域名，请参考<a target="_blank" href="https://support.huaweicloud.com/usermanual-obs/obs_03_0032.html">文档</a><br><font color="red">要注意的一点是若填写的为自定义CDN域名或自定义源站域名，域名需以 http:// 或 https:// 开头</font>',
            'dependency' => array( 'bucket_choose', '==', '7' ),
        ), 
        array(
            'id'      => 'hw_bucket_sync',
            'type'    => 'switcher',
            'title'       => '本地云端同步删除',
            'subtitle' => '在文件管理删除文件时，是否同步删除OSS上的对应文件',
            'default' => false,
            'dependency' => array( 'bucket_choose', '==', '7' ),
        ),
        array(
            'id'      => 'hw_bucket_localsave',
            'type'    => 'switcher',
            'title'       => '双重储存',
            'subtitle' => '在本地保存一份文件副本，这样的话会占用本地存储空间',
            'default' => false,
            'dependency' => array( 'bucket_choose', '==', '7' ),
        ),
        array(
            'id'      => 'hw_bucket_localdelete',
            'type'    => 'switcher',
            'title'       => '删除时同步删除本地备份',
            'subtitle' => '在文件管理删除文件时，是否同步删除本地备份的对应文件',
            'default' => false,
            'dependency' => array( 'bucket_choose|hw_bucket_localsave', '==|==', '7|true' ),
        ),
         array(
            'id'          => 'hw_bucket_other',
            'type'        => 'text',
            'title'       => '对象链接附带后缀',
            'after' => '[针对图片处理]该项针对有需要在对象链接后面添加一些参数的用户，若不需要请留空<br>填写示例: ?v=1&type=webp',
            'dependency' => array( 'bucket_choose', '==', '7' ),
        ),
        
        
        //百度云
        array(
            'id'          => 'bd_bucket_accesskey',
            'type'        => 'text',
            'title'       => '百度云Access Key',
            'after' => '必填* 请前往<a target="_blank" href="https://console.bce.baidu.com/iam/#/iam/accesslist">百度云控制台-安全认证/ Access Key</a> 获取您的ACCESSKEY',
            'dependency' => array( 'bucket_choose', '==', '6' ),
        ), 
        array(
            'id'          => 'bd_bucket_secretkey',
            'type'        => 'text',
            'title'       => '百度云Secret Key',
            'after' => '必填* 请前往<a target="_blank" href="https://console.bce.baidu.com/iam/#/iam/accesslist">百度云控制台-安全认证/ Access Key</a> 获取您的SECRETKEY',
            'dependency' => array( 'bucket_choose', '==', '6' ),
        ), 
        array(
            'id'          => 'bd_bucket_region',
            'type'        => 'select',
            'title'       => 'BOS所属地域',
            'after' => '必选* 选择您的百度云BOS所属地域，请务必选择正确',
            'options'     => array(
                "bj.bcebos.com"   =>  "华北-北京",
                "bd.bcebos.com"   =>  "华北-保定",
                "su.bcebos.com"   =>  "华东-苏州",
                "gz.bcebos.com"   =>  "华南-广州",
                "fwh.bcebos.com"    =>  "华中金融-武汉",
                "fsh.bcebos.com"    =>  "华东-上海",
                "hkg.bcebos.com"  =>  "中国-香港",
            ),
            'dependency' => array( 'bucket_choose', '==', '6' ),
        ), 
        array(
            'id'          => 'bd_bucket_name',
            'type'        => 'text',
            'title'       => '百度云BOS存储桶名称',
            'after' => '必填* 请填写百度云BOS存储桶名称，即Bucket名称，<font color=red>请务必设置为公共读</font>',
            'dependency' => array( 'bucket_choose', '==', '6' ),
        ),
        array(
            'id'          => 'bd_bucket_path',
            'type'        => 'text',
            'title'       => '百度云BOS存储路径',
            'after' => '必填* 默认为 uploads/，若非必要可不修改，路径最前面不加/',
            'default' => 'uploads/',
            'dependency' => array( 'bucket_choose', '==', '6' ),
        ), 
        array(
            'id'          => 'bd_bucket_domain',
            'type'        => 'text',
            'title'       => '百度云BOS访问域名',
            'after' => '留空则为百度云默认域名，如：https://xxxxxx.bj.bcebos.com<br>若填写的为自定义CDN域名，请参考<a target="_blank" href="https://cloud.baidu.com/doc/BOS/s/hkaqii59b">文档</a><br>若填写的为自定义源站域名，请参考<a target="_blank" href="https://cloud.baidu.com/doc/BOS/s/ckaqihkra">文档</a><br><font color="red">要注意的一点是若填写的为自定义CDN域名或自定义源站域名，域名需以 http:// 或 https:// 开头</font>',
            'dependency' => array( 'bucket_choose', '==', '6' ),
        ), 
        array(
            'id'      => 'bd_bucket_sync',
            'type'    => 'switcher',
            'title'       => '本地云端同步删除',
            'subtitle' => '在文件管理删除文件时，是否同步删除OSS上的对应文件',
            'default' => false,
            'dependency' => array( 'bucket_choose', '==', '6' ),
        ),
        array(
            'id'      => 'bd_bucket_localsave',
            'type'    => 'switcher',
            'title'       => '双重储存',
            'subtitle' => '在本地保存一份文件副本，这样的话会占用本地存储空间',
            'default' => false,
            'dependency' => array( 'bucket_choose', '==', '6' ),
        ),
        array(
            'id'      => 'bd_bucket_localdelete',
            'type'    => 'switcher',
            'title'       => '删除时同步删除本地备份',
            'subtitle' => '在文件管理删除文件时，是否同步删除本地备份的对应文件',
            'default' => false,
            'dependency' => array( 'bucket_choose|bd_bucket_localsave', '==|==', '6|true' ),
        ),
         array(
            'id'          => 'bd_bucket_other',
            'type'        => 'text',
            'title'       => '对象链接附带后缀',
            'after' => '[针对图片处理]该项针对有需要在对象链接后面添加一些参数的用户，若不需要请留空<br>填写示例: ?v=1&type=webp',
            'dependency' => array( 'bucket_choose', '==', '6' ),
        ),
        
        
        ),
        ));
        
        CSF::createSection( $prefix, array(
    'title'  => '评论设置',
    'icon'   => 'fas fa-comment',
    'fields' => array(
    array(
            'id'      => 'hiddenComment',
            'type'    => 'switcher',
            'title'       => '全局隐藏评论区',
            'after' => '<br><br>开启后全局隐藏评论区',
            'default' => false,
        ),
         array(
        'id'    => 'CommentText',
        'type'  => 'text',
        'title' => '站点评论提示',
        'after' => '请填入站点评论提示，默认为：嘿~ 大神，别默默的看了，快来点评一下吧',
         'dependency' => array( 'hiddenComment', '==', 'false')
      ),
      array(
            'type'    => 'heading',
            'content' => '评论过滤设置',
        ),
        array(
            'type'    => 'subheading',
            'content' => '默认强制性过滤全空格评论、包含XSS危险内容评论，其余需过滤内容您可以通过开启智能过滤或开启评论过滤高级设置进行设置。',
        ),
        array(
            'id'      => 'BearSpam_Auto',
            'type'    => 'switcher',
            'title'       => '智能过滤[Beta]',
            'default' => false,
            'subtitle'=>'您可以通过开启智能过滤来对机器人评论进行拦截，不过请注意:<font color=red>该功能还处于Beta测试阶段，可能出现误拦截的情况</font>',
        ),
        array(
            'id'      => 'BearSpam_Pro',
            'type'    => 'switcher',
            'title'       => '评论过滤高级设置',
            'default' => false,
            'subtitle'=>'您可以通过开启高级设置对评论区的评论提交做更深层次的过滤。'
        ),
        array(
            'id'       => 'BearSpam_IP',
            'type'     => 'textarea',
            'title'    => '过滤IP',
            'after' => '多条IP请用换行符隔开<br />支持用*号匹配IP段，如：192.168.*.*',
            'dependency' => array( 'BearSpam_Pro', '==', 'true' ),
        ),
        array(
            'id'       => 'BearSpam_EMAIL',
            'type'     => 'textarea',
            'title'    => '过滤邮箱',
            'after' => '多个邮箱请用换行符隔开<br />可以是邮箱的全部，或者邮箱部分关键词',
            'dependency' => array( 'BearSpam_Pro', '==', 'true' ),
        ),
        array(
            'id'       => 'BearSpam_URL',
            'type'     => 'textarea',
            'title'    => '过滤网址',
            'after' => '多个网址请用换行符隔开<br />可以是网址的全部，或者网址部分关键词。如果网址为空，该项不会起作用',
            'dependency' => array( 'BearSpam_Pro', '==', 'true' ),
        ),
        array(
            'id'       => 'BearSpam_ArticleTitle',
            'type'     => 'select',
            'title'    => '过滤含有文章标题的评论',
            'options'  => array('1' => '是',  '2' => '否'),
            'after' => '根据研究表明机器人发表的内容可能含有评论文章的标题',
            'dependency' => array( 'BearSpam_Pro', '==', 'true' ),
        ),
        array(
            'id'       => 'BearSpam_NAME',
            'type'     => 'textarea',
            'title'    => '过滤昵称',
            'after' => '如果评论发布者的昵称含有该关键词，将执行该操作，多个请直接换行',
            'dependency' => array( 'BearSpam_Pro', '==', 'true' ),
        ),
        array(
            'id'       => 'BearSpam_NAMEMIN',
            'type'     => 'number',
            'title'    => '昵称允许的最短长度',
            'unit'     => '个字',
            'subtitle' => '如果评论发布者的昵称小于该最短长度将拦截',
            'dependency' => array( 'BearSpam_Pro', '==', 'true' ),
        ),
        array(
            'id'       => 'BearSpam_NAMEMAX',
            'type'     => 'number',
            'title'    => '昵称允许的最长长度',
            'unit'     => '个字',
            'subtitle' => '如果评论发布者的昵称大于该最长长度将拦截',
            'dependency' => array( 'BearSpam_Pro', '==', 'true' ),
        ),
        array(
            'id'       => 'BearSpam_NAMEURL',
            'type'     => 'select',
            'title'    => '过滤昵称为网址的评论',
            'options'  => array('1' => '是',  '2' => '否'),
            'after' => '根据研究表明机器人发表的评论，昵称很有可能为网址',
            'dependency' => array( 'BearSpam_Pro', '==', 'true' ),
        ),
        array(
            'id'       => 'BearSpam_Chinese',
            'type'     => 'select',
            'title'    => '过滤不包含中文的评论',
            'options'  => array('1' => '是',  '2' => '否'),
            'after' => '当评论内容中没有中文时进行拦截',
            'dependency' => array( 'BearSpam_Pro', '==', 'true' ),
        ),
        array(
            'id'       => 'BearSpam_MIN',
            'type'     => 'number',
            'title'    => '评论内容允许的最短长度',
            'unit'     => '个字',
            'subtitle' => '如果评论发布者的评论内容小于该最短长度将拦截',
            'dependency' => array( 'BearSpam_Pro', '==', 'true' ),
        ),
        array(
            'id'       => 'BearSpam_MAX',
            'type'     => 'number',
            'title'    => '评论内容允许的最长长度',
            'unit'     => '个字',
            'subtitle' => '如果评论发布者的评论内容大于该最长长度将拦截',
            'dependency' => array( 'BearSpam_Pro', '==', 'true' ),
        ),
        array(
            'id'       => 'BearSpam_Words',
            'type'     => 'textarea',
            'title'    => '过滤敏感词',
            'after' => '多个词汇请用换行符隔开',
            'dependency' => array( 'BearSpam_Pro', '==', 'true' ),
        ),
    )
  ) );
  CSF::createSection( $prefix, array(
    'title'  => '底部设置',
    'icon'   => 'fas fa-chevron-down',
    'fields' => array(
    array(
        'id'    => 'IcpBa',
        'type'  => 'text',
        'title' => 'ICP备案号',
        'after' => '请填写您网站的ICP备案号,若无ICP备案请为空',
      ),
      array(
        'id'    => 'PoliceBa',
        'type'  => 'text',
        'title' => '公安备案号',
        'after' => '请填写您网站的公安备案号,若无公安备案请为空',
      ),
    array(
            'id'       => 'CustomizationFooterCode',
            'type'     => 'code_editor',
            'title'    => '底部自定义代码',
            'after' => '可放置网站统计代码等<br><font color=red>谨慎填写，仅需填写代码即可！</font>需注意语法，若语法错误可能会造成前台报错甚至组件动作失效！',
        ),
    )
  ) );

  
  
CSF::createSection( $prefix, array(
    'title'       => '数据备份',
    'icon'        => 'fas fa-cloud-download-alt',
    'description' => '本主题支持您通过以下功能对您所填写的配置信息进行备份导出，也可以对备份的数据进行导入。',
    'fields'      => array(

        array(
            'type' => 'backup',
        ),

    )
) );

?>

<?php

                $htmls = '
        <div class="ui three steps">
  <div class="step" id="check">
    <i class="tree icon"></i>
    <div class="content">
      <div class="title">检测</div>
    </div>
  </div>
  <div class="disabled step" id="upgrade">
    <i class="angle double right icon"></i>
    <div class="content">
      <div class="title">升级</div>
    </div>
  </div>
  <div class="disabled step" id="finished">
    <i class="info icon"></i>
    <div class="content">
      <div class="title">完成</div>
    </div>
  </div>
</div>
<div id="checkcon">
<div class="ui placeholder segment">
  <div class="ui icon header">
    <i class="cloud icon"></i>
    您可以通过点击以下按钮进行检测是否符合在线升级的条件
  </div>
  <div class="inline">
    <div class="ui button" id="checkbtn">检测版本</div>
  </div>

</div>
  <div id="versiontipss"></div>
</div>
<div id="upgradecon" style="display:none">
<div class="ui placeholder segment">
  <div class="ui icon header">
    <i class="cloud icon"></i>
    检测到最新版本为 v<font id="newversion"></font>，您可以通过点击以下按钮进行在线升级
  </div>
<div class="ui piled segment" style="margin-top:10px;">
  <h4 class="ui header">更新内容</h4>
  <p id="upgradelog"></p>
</div>
  <div class="inline">
    <div class="ui button" id="upgradebtn" style="margin-top:-10px;">立即更新</div>
    
  </div>
 <center><div id="pre-message" style="margin-top:20px;"></div></center>
 <center><div id="progress-message" style="margin-top:20px;"></div></center>
<progress id="progress" value="0" max="100" style="display:none;margin-top:10px;width:100%;max-width:100%;height:20px"></progress> 

</div>
</div>

<div id="finishcon" style="display:none">
<div class="ui placeholder segment">
  <div class="ui icon header">
    <i class="green check icon"></i>
    您已成功升级到最新版本，系统将在三秒后自动刷新~~~
  </div>

</div>
</div>

<script>
//检测
$("#checkbtn").on("click",function(){
$("#checkbtn").addClass("loading").attr("disabled","disabled");
    $.post("https://upgrade.typecho.co.uk/Beargallery/version.php",function(data,status){
switch(status)
{
case "success":
json = JSON.parse(data);
nowversion = "'.themeVersion().'";
if(json.version > nowversion){
$("#versiontipss").html(\'<div class="csf-submessage csf-submessage-warning">检测到有新版本可以更新，3秒钟后自动跳转下一步!</div>\');
$("#newversion").html(json.version);
$.post("https://upgrade.typecho.co.uk/Beargallery/upgrade_log.php",function(data,status){
$("#upgradelog").html(data);
});
setTimeout(function(){
$("#check").addClass("completed");
$("#upgrade").removeClass("disabled");
$("#checkcon").hide();
$("#upgradecon").fadeIn();
},3000);
}
if(json.version == nowversion){
$("#versiontipss").html(\'<div class="csf-submessage csf-submessage-success">当前版本为最新版本，无需更新~<a href="https://docs.whitebear.dev/index.php/archives/10/" target="_blank">查看版本更新日志</a></div>\');
$("#checkbtn").removeClass("loading").removeAttr("disabled","disabled");
}
break;
case "error":
toastr.warning("最新版本获取失败，请稍后重试");
$("#checkbtn").removeClass("loading").removeAttr("disabled","disabled");
break;
case "timeout":
toastr.warning("最新版本获取超时，请稍后重试");
$("#checkbtn").removeClass("loading").removeAttr("disabled","disabled");
break;
}
    });
});

//升级
$("#upgradebtn").on("click",function(){
$("#upgradebtn").addClass("loading").attr("disabled","disabled");

$.ajax({
                        type: "GET",
                        url: "/index.php/bg-upgrade",
                        data: {
                            "action": "prepare-download",
                        },
                        dateType: "json",
                        success: function(json) {
                            json = JSON.parse(json);
                            if(json.code == 0){
                             layer.msg(json.message, {icon: 2});  
                             $("#upgradebtn").removeClass("loading").removeAttr("disabled","disabled");
$("#upgradebtn").css("pointer-events","auto");
                            }
                            else{
                           $("#pre-message").html("升级包大小："+json.filesize+"，预计需要三十秒，请耐心等待~");
                           $("#progress-message").html("升级进度：0%");
                           $("#progress").fadeIn();
                        let x = document.getElementById("progress");   
                        x.setAttribute("value", "1");
                        intervaldown();
                        //设置定时器定时每5秒获取一次升级进度
                        let progressx = setInterval(function(){ 
                        intervalprogress();
                        }, 5000);
                        $("#progress").one("click",function(){
                            clearInterval(progressx);
                            finishUpgrade();
                        })
                            }
                        },
                        error: function() {
alert("升级准备检测失败，请稍后重试");
$("#upgradebtn").removeClass("loading").removeAttr("disabled","disabled");
                        }
                    });
                    
});
function intervaldown(){
$.ajax({
                        type: "GET",
                        url: "/index.php/bg-upgrade",
                        data: {
                            "action": "download",
                        },
                        dateType: "json",
                    });
}
function intervalprogress(){
$.ajax({
                        type: "GET",
                        url: "/index.php/bg-upgrade",
                        data: {
                            "action": "getsize",
                        },
                        dateType: "json",
                        success: function(json) {
                            json = JSON.parse(json);
                           $("#progress-message").html("升级进度："+json.filesize+"%");
let x = document.getElementById("progress");   
                        x.setAttribute("value", json.filesize);
                if(json.filesize == "100"){
                
                $("#progress-message").html("正在进行数据效验，请稍后...");
                setTimeout(function(){
                   $("#progress").click();
},3000);
                }
                        },
                        error: function() {
alert("获取升级进度失败");
                        }
                    });
}
function finishUpgrade(){
$.ajax({
                        type: "GET",
                        url: "/index.php/bg-upgrade",
                        data: {
                            "action": "finish",
                        },
                        dateType: "json",
                        success: function(json) {
$("#upgrade").addClass("completed");
$("#finished").removeClass("disabled");
$("#upgradecon").hide();
$("#finishcon").fadeIn();
setTimeout(function(){
window.parent.location.reload();
},3000);
                        },
                        error: function() {
alert("升级失败");
                        }
                    });
}
</script>
        ';
        
        CSF::createSection( $prefix, array(
    'title'       => '在线升级',
    'icon'        => 'fa fa-cloud-upload-alt',
    
    'fields'      => array(
        array(
            'type'    => 'notice',
            'style'   => 'info',
            'content' => '<strong>注意事项</strong><p> <ul><li>1、若您有开启缓存，那么在升级完成后需要清除一次缓存。</li><li>2、若出现在线升级卡顿无法升级，可能是您的服务器网络到升级节点之间存在异常，则需要手动覆盖升级或者稍等一会再试试。</li><li>3、若升级完毕后出现报错，可尝试通过手动覆盖新版本来进行修复，下载地址:<a href="https://files.bear.dance/Beargallery/Beargallery_v'.themeVersionOnly().'.release.zip">戳这里</a></ul></p>',
        ),
array(
                    'type' =>'content',
                    'content' => $htmls,
                ),
        

    )
) );
}
function themeConfig($form)
{

   ?>
       <?php $all = Typecho_Plugin::export();
       \Widget\Security::alloc()->to($security);?>
<?php if (!array_key_exists('BgCore', $all['activated'])) : ?>
   <div class="update-check message error"><p>检测到未开启BgCore，主题尚处于封印状态，您需要安装启用BgCore核心插件后方能解除封印！<br>当下方出现解除封印按钮时点击按钮后即可解除封印~~~
   <?php if(is_dir(Helper::options()->pluginDir('BgCore'))):?><br><br><button onclick="window.location.href='<?php $security->index('/action/plugins-edit?activate=BgCore'); ?>'"  class="btn">
解除封印</button></p>
</div>
<?php endif;?>
   <?php else:?>
<style>
.body.container{
    max-width:100%!important;
}
</style>
        <link href="<?php echo Helper::options()->themeUrl;?>/assets/plugins/toastr/toastr.min.css" rel="stylesheet">
	<script src="<?php echo Helper::options()->themeUrl;?>/assets/plugins/toastr/toastr.min.js"></script>
        
<?php

    $params = [
        'args'=> [
            'framework_title' => 'BearGallery v'.themeVersionOnly(),
            'footer_text' => '自豪的采用BearGallery主题!',
        ]
    ];
    CSF::setup('beargallery', $params);
    
    ?>
       <?php endif; ?>
       <?php
       CSF::setTypechoOptionForm($form);
       ?>
              <?php if (array_key_exists('BgCore', $all['activated'])) : ?>
          <style>

        .popup{
            all:unset;
            font-size:0; overflow:hidden;
        }
        .message{
            all:unset;
            font-size:0; overflow:hidden;
        }
        .message a{
            all:unset;
            font-size:0; overflow:hidden;
        }
        .success{
            all:unset;
            font-size:0; overflow:hidden;
        }
        .success a{
            all:unset;
            font-size:0; overflow:hidden;
        }
        .message.popup.success{
            all:unset;
            font-size:0; overflow:hidden;
        }
        </style>
           <script>
           window.deactivateURL = '<?php $security->index('/action/plugins-edit?deactivate=BgCore'); ?>';
           window.activateURL = '<?php $security->index('/action/plugins-edit?activate=BgCore'); ?>';
           window.siteUrl = '<?php echo Helper::options()->siteUrl; ?>';
           window.siteName = "<?php echo Helper::options()->title; ?>";
           window.siteDesc = "<?php echo Helper::options()->description; ?>";
           window.siteUrl = "<?php echo Helper::options()->siteUrl; ?>";
           window.useTheme = 'beargallery';
           window.siteToken = '<?php echo General::md5Encode(Helper::options()->siteUrl); ?>7bae2123bear';
        </script>
<?php endif; ?>

       <?php if (!array_key_exists('BgCore', $all['activated'])) : ?>
       
    <script src="<?php echo Helper::options()->themeUrl;?>/assets/js/jquery.min.js"></script>
    

   <script>
       $('#wpwrap').hide();
   </script>
   <?php endif; ?>
    <?php
} ?>