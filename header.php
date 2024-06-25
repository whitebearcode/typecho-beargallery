<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="<?php $this->options->charset(); ?>">
    <meta name="renderer" content="webkit">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
     <?php if(!empty(General::Options('favicon'))): ?>
 <link rel="icon" href="<?php echo General::Options('favicon') ?>" />
 <?php endif; ?>
    <title><?php $this->archiveTitle([
            'category' => _t('分类 %s 下的文章'),
            'search'   => _t('包含关键字 %s 的文章'),
            'tag'      => _t('标签 %s 下的文章'),
            'author'   => _t('%s 发布的文章')
        ], '', ' - '); ?><?php $this->options->title(); ?></title>

    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Noto+Serif+SC:wght@200;300;400;500;600;700;900&display=swap" as="style" onload="this.rel='stylesheet'" crossorigin>
<script src="<?php $this->options->themeUrl('assets/js/jquery.min.js'); ?>"></script>
<link href="//lib.baomitu.com/remixicon/3.7.0/remixicon.min.css" rel="stylesheet">
<link rel="stylesheet" href="//lib.baomitu.com/element-ui/2.15.14/theme-chalk/index.min.css">
<link rel="stylesheet"  href="<?php $this->options->themeUrl('assets/css/skeleton.css'); ?>" />
<link rel="stylesheet"  href="<?php $this->options->themeUrl('assets/css/base.css'); ?>" />
<link rel="stylesheet"  href="<?php $this->options->themeUrl('assets/css/flexslider.css'); ?>" />
<link rel="stylesheet"  href="<?php $this->options->themeUrl('assets/css/style.css?v=2'); ?>" />
<link rel="stylesheet" href="<?php $this->options->themeUrl('assets/plugins/fancybox/fancybox.min.css'); ?>">   
<link rel="stylesheet" href="<?php $this->options->themeUrl('assets/plugins/nprogress/nprogress.min.css'); ?>">
<link rel="stylesheet" href="<?php $this->options->themeUrl('assets/plugins/lazyload/transition.min.css'); ?>">
 <script src="<?php $this->options->themeUrl('assets/plugins/qrious/qrious.min.js'); ?>"></script>
 <style>
        *{
            font-family: 'Noto Serif SC',serif!important;
        }
        </style>
<!-- 通过自有函数输出HTML头部信息 -->
    <?php $this->header('commentReply=1&description=&pingback=0&xmlrpc=0&wlw=0&generator=&template=&atom='); ?>
    <?php echo General::Options('CustomizationCode'); ?>
    
<script>
(function () {
    window.TypechoComment = {
        dom : function (id) {
            return document.getElementById(id);
        },
    
        create : function (tag, attr) {
            var el = document.createElement(tag);
        
            for (var key in attr) {
                el.setAttribute(key, attr[key]);
            }
        
            return el;
        },

        reply : function (cid, coid) {
            var comment = this.dom(cid), parent = comment.parentNode,
                response = this.dom('respond-post-<?php echo $this->cid;?>'), input = this.dom('comment-parent'),
                form = 'form' == response.tagName ? response : response.getElementsByTagName('form')[0],
                textarea = response.getElementsByTagName('textarea')[0];
            if (null == input) {
                input = this.create('input', {
                    'type' : 'hidden',
                    'name' : 'parent',
                    'id'   : 'comment-parent'
                });

                form.appendChild(input);
            }

            input.setAttribute('value', coid);

            if (null == this.dom('comment-form-place-holder')) {
                var holder = this.create('div', {
                    'id' : 'comment-form-place-holder'
                });

                response.parentNode.insertBefore(holder, response);
            }
            //comment.appendChild(response);
            comment.insertAdjacentElement("afterend",response);
            this.dom('cancel-comment-reply-link').style.display = '';

            if (null != textarea && 'text' == textarea.name) {
                textarea.focus();
            }

            return false;
        },

        cancelReply : function () {
            var response = this.dom('respond-post-<?php echo $this->cid;?>'),
            holder = this.dom('comment-form-place-holder'), input = this.dom('comment-parent');

            if (null != input) {
                input.parentNode.removeChild(input);
            }

            if (null == holder) {
                return true;
            }

            this.dom('cancel-comment-reply-link').style.display = 'none';
            holder.parentNode.insertBefore(response, holder);
            return false;
        }
    };
})();

    </script>
</head>
<body>
    <div id="pjax">
        <script>
        (function () {
    window.TypechoComment = {
        dom : function (id) {
            return document.getElementById(id);
        },
    
        create : function (tag, attr) {
            var el = document.createElement(tag);
        
            for (var key in attr) {
                el.setAttribute(key, attr[key]);
            }
        
            return el;
        },

        reply : function (cid, coid) {
            var comment = this.dom(cid), parent = comment.parentNode,
                response = this.dom('respond-post-<?php echo $this->cid;?>'), input = this.dom('comment-parent'),
                form = 'form' == response.tagName ? response : response.getElementsByTagName('form')[0],
                textarea = response.getElementsByTagName('textarea')[0];
            if (null == input) {
                input = this.create('input', {
                    'type' : 'hidden',
                    'name' : 'parent',
                    'id'   : 'comment-parent'
                });

                form.appendChild(input);
            }

            input.setAttribute('value', coid);

            if (null == this.dom('comment-form-place-holder')) {
                var holder = this.create('div', {
                    'id' : 'comment-form-place-holder'
                });

                response.parentNode.insertBefore(holder, response);
            }
            //comment.appendChild(response);
            comment.insertAdjacentElement("afterend",response);
            this.dom('cancel-comment-reply-link').style.display = '';

            if (null != textarea && 'text' == textarea.name) {
                textarea.focus();
            }

            return false;
        },

        cancelReply : function () {
            var response = this.dom('respond-post-<?php echo $this->cid;?>'),
            holder = this.dom('comment-form-place-holder'), input = this.dom('comment-parent');

            if (null != input) {
                input.parentNode.removeChild(input);
            }

            if (null == holder) {
                return true;
            }

            this.dom('cancel-comment-reply-link').style.display = 'none';
            holder.parentNode.insertBefore(response, holder);
            return false;
        }
    };
})();
</script>
<div class="bgallery_fl_wrapper_all">
    <header class="bgallery_fl_header">
    </header>
    
      <div class="bgallery_fl_content">
        <div class="bgallery_fl_vertical_menu">
        	<div class="bgallery_fl_vertical_menu_in scrollable">
            	<span class="vertical_menu_closer">
                	<a href="#"></a>
                    <span></span>
                    <span></span>
                </span>
                <div class="bgallery_fl_logo">
                     <?php if(General::Options('header_choose') == 'image'):?>
                        <img  src="<?php echo General::Options('imagelogo');?>"  height="75" loading="lazy">
                        <?php else:?>
<h3><?php echo General::Options('textlogo_text');?></h3>
<?php endif;?>
            
                </div>
                <div class="bgallery_fl_vertical_menu_nav_list">
                	<ul>
                    	<li>
                        	<a<?php if ($this->is('index')): ?> class="current"<?php endif; ?> href="<?php $this->options->siteUrl(); ?>"><span class="line">首页</span></a>
                        </li>
                         <?php \Widget\Contents\Page\Rows::alloc()->to($pages); ?>
                    <?php while ($pages->next()): ?>
                        <li> <a<?php if ($this->is('page', $pages->slug)): ?> class="current"<?php endif; ?>
                            href="<?php $pages->permalink(); ?>"
                            title="<?php $pages->title(); ?>"><span class="line"><?php $pages->title(); ?></span></a></li>
                    <?php endwhile; ?>
                       
                        	

                    </ul>
                </div>
              
                <div class="bgallery_fl_copyright">
                	<span class="cright">&copy; <?php echo date("Y");?> <span class="autor"><a class="autor" href="<?php $this->options->siteUrl();?>"><?php $this->options->title();?></a></span> All right Reserved.  <br><?php _e('Powered by <span class="autor"><a href="http://www.typecho.org">Typecho</a> & <a href="https://github.com/whitebearcode/typecho-beargallery"> BearGallery</a></span>  '); ?><br>
     <?php if (General::Options('PoliceBa')): ?><img src="<?php $this->options->themeUrl('assets/images/beian.png');?>"> <span class="autor"><a href="http://www.beian.gov.cn/portal/registerSystemInfo?recordcode=<?php echo General::parseNumber(General::Options('PoliceBa')); ?>"><?php echo General::Options('PoliceBa'); ?></a></span><?php endif; ?><?php if (General::Options('IcpBa') && General::Options('PoliceBa')): ?> <br> <?php endif; ?><?php if (General::Options('IcpBa')): ?><img src="<?php $this->options->themeUrl('assets/images/icp.png');?>"> <span class="autor"><a href="https://beian.miit.gov.cn/"><?php echo General::Options('IcpBa'); ?></a></span><?php endif; ?></span>
     <?php if(!empty(General::Options('CustomizationFooterCode'))): ?>
     <br><?php echo General::Options('CustomizationFooterCode');?>
     <?php endif;?>
                </div>
                
            </div>
        </div>
      
      
        <div class="bgallery_fl_content_in">
        	<div class="bgallery_fl_menu_trigger light">
            	<a href="#">
                	<span class="menu_on">
                    	<span class="menu_a"></span>
                        <span class="menu_b"></span>
                        <span class="menu_c"></span>
                    </span>
                </a>
            </div>