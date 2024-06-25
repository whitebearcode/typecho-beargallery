<?php
namespace TypechoPlugin\BgCore;
use BgCore;
use bgOptions;
use \Utils\Helper;
use Typecho\{Exception, Widget, Db};
use Typecho\Cookie;
use Widget\Options;


if(!class_exists('CSF')){
    require_once Helper::options()->pluginDir('BgCore').'/bgoptions-framework.php';
}

if (!class_exists('bgOptions')){
    require_once \Utils\Helper::options()->pluginDir('BgCore').'/bgOptions.php';
}

class Action extends Widget implements \Widget\ActionInterface
{

 

    /**
     * 初始化
     * @return $this
     */
    
}