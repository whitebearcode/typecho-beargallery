<?php 

/**
 * 
 *   BearDocs 解析工具类
 *   Update at 2023/07/26
 * 
*/

class Parse{
    
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
    
    public static function parsePlayerCallback($matches)
    {
        $matches[3] = preg_replace("/<a href=.*?>(.*?)<\/a>/",'$1',$matches[3]);
        $matches[3] = preg_replace("/<br>/",'',$matches[3]);
        $attr = htmlspecialchars_decode($matches[3]);
        $attrs = self::shortcode_parse_atts($attr);

        $config = [
            'live' => false,
            'autoplay' => false,
            'theme' => '#FADFA3',
            'loop' => 'true',
            'screenshot' => false,
            'hotkey' => true,
            'preload' => 'metadata',
            'lang' => 'zh-cn',
            'logo' => null,
            'volume' => 0.7,
            'mutex' => true,
            'video' => [
                'url' => isset($attrs['url']) ? $attrs['url'] : null,
                'pic' => isset($attrs['image']) ? $attrs['image'] : Helper::options()->themeUrl.'/assets/images/placeholder.png',
                'type' => 'auto',
                'thumbnails' => null,
            ],
        ];
        $json = json_encode($config,JSON_UNESCAPED_SLASHES);
        return <<<EOF
        <div class="dplayer" data-config='{$json}' style="border-radius:5px"></div>
EOF;
    }

    public static function ParseContent($content, $obj) {
    $elements = [];

    $player_pattern = self::get_shortcode_regex(['player']);
    preg_match_all('/'.$player_pattern.'/s', $content, $player_matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);
    foreach ($player_matches as $match) {
        $start = $match[0][1];
        $length = strlen($match[0][0]);
        $elements[] = [
            'type' => 'player',
            'start' => $start,
            'end' => $start + $length,
            'raw' => $match[0][0]
        ];
    }

    // 匹配所有图片标签并记录位置
    $img_pattern = '/<img\s+([^>]*?)(?:src=("|\')(.*?)\2)([^>]*?)(?:alt=("|\')(.*?)\5)?([^>]*)>/is';
    preg_match_all($img_pattern, $content, $img_matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);
    foreach ($img_matches as $match) {
        $start = $match[0][1];
        $length = strlen($match[0][0]);
        $elements[] = [
            'type' => 'image',
            'start' => $start,
            'end' => $start + $length,
            'raw' => $match[0][0],
            'src' => $match[3][0] ?? '',
            'alt' => $match[6][0] ?? ''
        ];
    }

    // 按原始位置排序
    usort($elements, function($a, $b) {
        return $a['start'] - $b['start'];
    });

    // 处理并构建最终内容
    $output = [];
    foreach ($elements as $element) {
        if ($element['type'] === 'player') {
            // 处理player短代码
            preg_match('/'.$player_pattern.'/s', $element['raw'], $m);
            $output[] = call_user_func('self::parsePlayerCallback', $m);
        } elseif ($element['type'] === 'image') {
            // 处理图片
            $src = htmlspecialchars($element['src'], ENT_QUOTES);
            $alt = htmlspecialchars($element['alt'], ENT_QUOTES);
            
            $output[] = <<<HTML
<div class="img_list_nth">
    <img class="lazyload lazyload-style-2" 
         data-fancybox="post-{$obj->cid}" 
         data-caption="{$alt}"
         src="data:image/svg+xml;base64,PCEtLUFyZ29uTG9hZGluZy0tPgo8c3ZnIHdpZHRoPSIxIiBoZWlnaHQ9IjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgc3Ryb2tlPSIjZmZmZmZmMDAiPjxnPjwvZz4KPC9zdmc+" 
         data-src="{$src}"
         alt="{$alt}"
         style="border-radius:5px;cursor: zoom-in;width:100%;height:auto;">
    <div class="title" style="border-radius:0 5px 5px 5px;"><a href="#"><span>分享</span></a></div>
    <div class="share_social" style="border-radius:0 5px 5px 5px;">
        <ul>
            <li><a class="share_qzone" title="分享至QQ" data-img="{$src}" style="cursor:pointer;"><i class="ri-qq-line"></i></a></li>
            <li><a class="share_weibo" title="分享至微博" data-img="{$src}" style="cursor:pointer;"><i class="ri-weibo-line"></i></a></li>
            <li><a data-fancybox data-src="#dialog-content" title="分享至微信" style="cursor:pointer;"><i class="ri-wechat-line"></i></a></li>
        </ul>
    </div>
</div>
HTML;
        }
    }

    return implode("\n", $output);
}
    public static function ParsePageContent($content,$cid){
       
        //增加灯箱
        $pattern = '/<\s*img[\s\S]+?(?:src=[\'"]([\S\s]*?)[\'"]\s*|alt=[\'"]([\S\s]*?)[\'"]\s*|[a-z]+=[\'"][\S\s]*?[\'"]\s*)+[\s\S]*?>/i';

 $replacement = '
  <img class="lazyload lazyload-style-2" data-fancybox="post-'.$cid.'" data-caption="$2" src="data:image/svg+xml;base64,PCEtLUFyZ29uTG9hZGluZy0tPgo8c3ZnIHdpZHRoPSIxIiBoZWlnaHQ9IjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgc3Ryb2tlPSIjZmZmZmZmMDAiPjxnPjwvZz4KPC9zdmc+" data-src="$1" alt="$2" style="border-radius:8px;cursor: zoom-in;width:100%;height:auto;margin:10px 0 10px 0;">'; 
    $content = preg_replace($pattern, $replacement, $content);
    
    
        return $content;
    }
}